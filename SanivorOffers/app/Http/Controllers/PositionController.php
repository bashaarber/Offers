<?php

namespace App\Http\Controllers;

use App\Models\Coefficient;
use App\Models\Offert;
use App\Models\Element;
use App\Models\Position;
use App\Models\Organigram;
use Illuminate\Http\Request;
use App\Models\PositionMaterial;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PositionController extends Controller
{
    private function isRetryableCreateEmptyException(\Throwable $e): bool
    {
        $sqlState = (string) ($e->getCode() ?? '');
        $message = strtolower($e->getMessage());

        if (in_array($sqlState, ['40001', '40P01', '55P03', 'HY000'], true)) {
            return true;
        }

        return str_contains($message, 'deadlock')
            || str_contains($message, 'lock wait timeout')
            || str_contains($message, 'could not obtain lock');
    }

    private function normalizeDecimal(mixed $value, float $default = 0.0): float
    {
        if ($value === null || $value === '') {
            return $default;
        }

        $normalized = str_replace(',', '.', (string) $value);
        return is_numeric($normalized) ? (float) $normalized : $default;
    }

    /**
     * Build a lookup set of element IDs that belong to Rahme organigram groups
     * (Grundrahme, Aufstock, Nische). Uses the already-loaded organigrams collection
     * so zero extra DB queries are needed.
     *
     * @param  \Illuminate\Support\Collection  $organigrams  (with group_elements.elements eager-loaded)
     * @return \Illuminate\Support\Collection  keyed by element_id for O(1) has() lookups
     */
    private function computeRahmeElementIds(\Illuminate\Support\Collection $organigrams): \Illuminate\Support\Collection
    {
        $ids = [];
        foreach ($organigrams as $organigram) {
            if ($organigram->name !== 'Rahme') {
                continue;
            }
            foreach ($organigram->group_elements as $ge) {
                if (in_array($ge->name, ['Grundrahme', 'Aufstock', 'Nische'], true)) {
                    foreach ($ge->elements as $el) {
                        $ids[$el->id] = true;
                    }
                }
            }
        }
        return collect($ids);
    }

    private function hasElementPivotOptionalColumn(): bool
    {
        // Cache for 24 hours — this column never changes after the migration runs.
        return Cache::remember('schema_element_position_has_is_optional', 86400, function () {
            return Schema::hasColumn('element_position', 'is_optional');
        });
    }

    /**
     * Whether the element is marked optional in request data (handles int/string key mismatches).
     */
    private function elementOptionalFromRequestMap(array $elementOptionalMap, mixed $elementId): bool
    {
        if (! $this->hasElementPivotOptionalColumn()) {
            return false;
        }

        $keys = [$elementId, (string) $elementId];
        if (is_numeric($elementId)) {
            $keys[] = (int) $elementId;
        }

        foreach ($keys as $key) {
            if (! array_key_exists($key, $elementOptionalMap)) {
                continue;
            }

            return Position::truthyElementOptionalPivot($elementOptionalMap[$key]);
        }

        return false;
    }

    /**
     * Order elements the same way as the sidebar: organigram → group → element.
     * First tree occurrence wins; any element not linked in the tree is appended at the end.
     */
    private function orderElementsByOrganigramTree(Collection $elements, Collection $organigrams): Collection
    {
        $byId = $elements->keyBy('id');
        $ordered = collect();

        foreach ($organigrams as $organigram) {
            foreach ($organigram->group_elements as $groupElement) {
                foreach ($groupElement->elements as $el) {
                    if ($byId->has($el->id) && ! $ordered->has($el->id)) {
                        $ordered->put($el->id, $byId->get($el->id));
                    }
                }
            }
        }

        foreach ($byId as $id => $el) {
            if (! $ordered->has($id)) {
                $ordered->put($id, $el);
            }
        }

        return $ordered->values();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $offertId = $request->input('offert_id');

        $positions = Position::whereHas('offerts', function ($query) use ($offertId) {
            $query->where('id', $offertId);
        })->orderBy('position_number', 'ASC')->get();

        return view('position.index', compact('positions', 'offertId'));
    }

    public function copy($id)
    {
        $position = Position::findOrFail($id);
        $latestOffert = Offert::where('user_id', auth()->id())->latest()->first();

        $latestPositionNumber = Position::max('position_number');

        $newPosition = $position->replicate()->fill([
            'offert_id' => $latestOffert->id,
            'position_number' => $latestPositionNumber + 1,
        ]);
        $newPosition->save();

        $newPosition->group_elements()->sync($position->group_elements->pluck('id')->toArray());
        $newPosition->organigrams()->sync($position->organigrams->pluck('id')->toArray());
        $newPosition->offerts()->attach($latestOffert);

        $supportsElementOptionalPivot = $this->hasElementPivotOptionalColumn();
        foreach ($position->elements()->withPivot('quantity')->get() as $element) {
            $pivotData = ['quantity' => $element->pivot->quantity];
            if ($supportsElementOptionalPivot) {
                $pivotData['is_optional'] = (bool) ($element->pivot->is_optional ?? false);
            }
            $newPosition->elements()->attach($element->id, $pivotData);
            foreach ($element->materials as $material) {
                $materialQuantity = PositionMaterial::where([
                    'position_id' => $position->id,
                    'element_id' => $element->id,
                    'material_id' => $material->id
                ])->first()->quantity;
                PositionMaterial::create([
                    'position_id' => $newPosition->id,
                    'element_id' => $element->id,
                    'material_id' => $material->id,
                    'quantity' => $materialQuantity
                ]);
            }
        }
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, $index)
    {
        $offertId = $request->input('offert_id');
        $offert = Offert::with('lockingUser')->find($offertId);

        if (! $offert) {
            return redirect()->route('offert.index')
                ->with('error', 'Offer not found. Please open the position from a valid offer.');
        }

        if ($offert && $offert->isLockedByOther()) {
            $who = $offert->lockingUser?->username ?? 'another user';

            return redirect()->route('offert.index')
                ->with('lock_error', "Offer #{$offertId} is currently being edited by \"{$who}\". Please try again later.");
        }

        // Create a real empty position immediately when requested from "+ New Position".
        // This removes the temporary "(new)" placeholder flow and guarantees one-click-one-position.
        if ($request->boolean('add_new')) {
            try {
                $newPosition = DB::transaction(function () use ($offert) {
                    Offert::whereKey($offert->id)->lockForUpdate()->first();

                    $nextPositionNumber = (int) Position::whereHas('offerts', function ($query) use ($offert) {
                        $query->where('id', $offert->id);
                    })->max('position_number') + 1;

                    $payload = [
                        'description' => '',
                        'description2' => '',
                        'blocktype' => null,
                        'b' => null,
                        'h' => null,
                        't' => null,
                        'quantity' => 1,
                        'price_brutto' => 0,
                        'price_discount' => 0,
                        'discount' => 0,
                        'material_brutto' => 0,
                        'zeit_brutto' => 0,
                        'material_costo' => 0,
                        'material_profit' => 0,
                        'ziet_costo' => 0,
                        'ziet_profit' => 0,
                        'costo_total' => 0,
                        'profit_total' => 0,
                        'position_number' => $nextPositionNumber,
                    ];
                    if (Schema::hasColumn('positions', 'is_optional')) {
                        $payload['is_optional'] = false;
                    }

                    $position = Position::create($payload);

                    $position->offerts()->syncWithoutDetaching([$offert->id]);

                    return Position::findOrFail($position->id);
                });

                return redirect(url('/position/' . (int) $newPosition->id . '/edit'));
            } catch (\Throwable $e) {
                return redirect(url('/position/create/0?offert_id=' . (int) $offert->id))
                    ->with('error', 'Could not create a new empty position. Please try again.');
            }
        }

        $positions = Position::whereHas('offerts', function ($query) use ($offertId) {
            $query->where('id', $offertId);
        })->orderBy('position_number', 'ASC')->get();

        $organigrams = Cache::remember('organigrams_tree', 600, function () {
            return Organigram::with(['group_elements.elements'])->get();
        });
        $elements = $this->orderElementsByOrganigramTree(
            Cache::remember('elements_with_materials', 600, function () {
                return Element::with('materials')->get();
            }),
            $organigrams
        );
        $maxPositionNumber = (int) $positions->max('position_number');
        $nextPositionNumber = $maxPositionNumber + 1;
        $rahmeElementIds    = $this->computeRahmeElementIds($organigrams);

        $difficultyCoeff = max((float) ($offert->difficulty ?: 1), 0.001);
        $materialCoeff   = (float) ($offert->material ?: 1);
        $coefficient     = Coefficient::first();
        $inLaborPrice    = (float) ($coefficient->in_labor_price ?? 60);

        // On create ALL elements are unselected — build full JSON, render zero HTML tables.
        $allElementsData = [];
        foreach ($elements as $element) {
            $mats = [];
            foreach ($element->materials as $material) {
                $qty      = (float) ($material->pivot->quantity ?? 1);
                $calcTotal = ((float) $material->price_out * $materialCoeff)
                            + ((float) $material->total_arbeit / $difficultyCoeff);
                $mats[] = [
                    'id'        => $material->id,
                    'name'      => $material->name,
                    'unit'      => $material->unit ?? '',
                    'price_out' => (float) $material->price_out,
                    'price_in'  => (float) $material->price_in,
                    'zeit_cost' => (float) $material->zeit_cost,
                    'z_total'   => (float) $material->z_total,
                    'calc'      => round($calcTotal, 4),
                    'qty'       => $qty,
                ];
            }
            $allElementsData[$element->id] = [
                'id'      => $element->id,
                'name'    => $element->name,
                'isRahme' => $rahmeElementIds->has($element->id),
                'qty'     => 1,
                'mats'    => $mats,
            ];
        }

        return view('position.create', compact(
            'positions', 'organigrams', 'elements', 'index', 'offert',
            'nextPositionNumber', 'rahmeElementIds', 'allElementsData',
            'materialCoeff', 'difficultyCoeff', 'inLaborPrice'
        ));
    }

    public function createEmpty(Request $request)
    {
        $requestId = (string) Str::uuid();
        $offertId = (int) $request->input('offert_id');
        $supportsOptionalColumn = false;
        $maxAttempts = 3;

        Log::info('position.create-empty.input', [
            'request_id' => $requestId,
            'offert_id' => $offertId,
            'user_id' => auth()->id(),
        ]);

        try {
            $supportsOptionalColumn = Schema::hasColumn('positions', 'is_optional');
        } catch (\Throwable $e) {
            report($e);
            Log::warning('position.create-empty.schema-check-failed', [
                'request_id' => $requestId,
                'offert_id' => $offertId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);
        }

        if ($offertId <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid offert_id',
                'request_id' => $requestId,
            ], 422);
        }

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                $position = DB::transaction(function () use ($offertId, $supportsOptionalColumn, $requestId, $attempt) {
                    $offert = Offert::whereKey($offertId)->lockForUpdate()->first();
                    if (! $offert) {
                        return null;
                    }

                    if ($offert->isLockedByOther()) {
                        return [
                            'locked' => true,
                            'who' => $offert->lockingUser?->username ?? 'another user',
                        ];
                    }

                    Log::info('position.create-empty.lock-acquired', [
                        'request_id' => $requestId,
                        'offert_id' => $offertId,
                        'attempt' => $attempt,
                    ]);

                    $nextPositionNumber = (int) Position::whereHas('offerts', function ($query) use ($offertId) {
                        $query->where('id', $offertId);
                    })->max('position_number') + 1;

                    $payload = [
                        'description' => '',
                        'description2' => '',
                        'blocktype' => null,
                        'b' => null,
                        'h' => null,
                        't' => null,
                        'quantity' => 1,
                        'price_brutto' => 0,
                        'price_discount' => 0,
                        'discount' => 0,
                        'material_brutto' => 0,
                        'zeit_brutto' => 0,
                        'material_costo' => 0,
                        'material_profit' => 0,
                        'ziet_costo' => 0,
                        'ziet_profit' => 0,
                        'costo_total' => 0,
                        'profit_total' => 0,
                        'position_number' => $nextPositionNumber,
                    ];

                    if ($supportsOptionalColumn) {
                        $payload['is_optional'] = false;
                    }

                    $position = Position::create($payload);
                    Log::info('position.create-empty.position-inserted', [
                        'request_id' => $requestId,
                        'offert_id' => $offertId,
                        'position_id' => $position->id,
                        'position_number' => $position->position_number,
                    ]);

                    $position->offerts()->syncWithoutDetaching([$offertId]);
                    Log::info('position.create-empty.pivot-attached', [
                        'request_id' => $requestId,
                        'offert_id' => $offertId,
                        'position_id' => $position->id,
                    ]);

                    return $position;
                }, 1);

                if (! $position) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Offer not found',
                        'request_id' => $requestId,
                    ], 404);
                }

                if (is_array($position) && ($position['locked'] ?? false)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Offer is currently locked by ' . $position['who'] . '. Please try again shortly.',
                        'request_id' => $requestId,
                    ], 423);
                }

                Log::info('position.create-empty.response-sent', [
                    'request_id' => $requestId,
                    'offert_id' => $offertId,
                    'position_id' => $position->id,
                    'position_number' => (int) $position->position_number,
                ]);

                return response()->json([
                    'success' => true,
                    'position_id' => $position->id,
                    'position_number' => (int) $position->position_number,
                    'edit_url' => route('position.edit', $position->id),
                    'request_id' => $requestId,
                ]);
            } catch (\Throwable $e) {
                $retryable = $this->isRetryableCreateEmptyException($e);

                Log::error('position.create-empty.failed-attempt', [
                    'request_id' => $requestId,
                    'offert_id' => $offertId,
                    'user_id' => auth()->id(),
                    'attempt' => $attempt,
                    'retryable' => $retryable,
                    'sql_state' => (string) ($e->getCode() ?? ''),
                    'error' => $e->getMessage(),
                ]);

                if (! $retryable || $attempt >= $maxAttempts) {
                    report($e);
                    return response()->json([
                        'success' => false,
                        'message' => 'Could not create a new empty position.',
                        'request_id' => $requestId,
                    ], 500);
                }

                usleep(150000 * $attempt);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Could not create a new empty position.',
            'request_id' => $requestId,
        ], 500);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $offertId = $request->input('offert_id');
        $latestOffert = $offertId
            ? Offert::find($offertId)
            : Offert::where('user_id', $user->id)->latest()->first();
        $description = $request->input('description');
        $description2 = $request->input('description2');
        $blocktype = $request->input('blocktype');
        $b = $request->input('b');
        $h = $request->input('h');
        $t = $request->input('t');
        $totalProTypPrice = $request->input('totalProTypPrice');
        $discountedTotal = $request->input('discountedTotal');
        $percentage = $request->input('percentage');
        $material_brutto = $request->input('price-out-input');
        $zeit_brutto = $request->input('zeit-cost-input');
        $material_costo = $request->input('material-costo');
        $material_profit = $request->input('material-profit');
        $ziet_costo = $request->input('zeit-costo');
        $ziet_profit = $request->input('zeit-profit');
        $costo_total = $request->input('costo-total');
        $profit_total = $request->input('profit-total');
        $quantity = $request->input('quantity');

        $is_optional = $request->has('is_optional') ? true : false;

        $formFields = [
            'description' => $description,
            'description2' => $description2,
            'blocktype' => $blocktype,
            'b' => $b,
            'h' => $h,
            't' => $t,
            'price_brutto' => $totalProTypPrice,
            'price_discount' => $discountedTotal,
            'discount' => $percentage,
            'quantity' => $quantity,
            'material_brutto' => $material_brutto,
            'zeit_brutto' => $zeit_brutto,
            'material_costo' => $material_costo,
            'material_profit' => $material_profit,
            'ziet_costo' => $ziet_costo,
            'ziet_profit' => $ziet_profit,
            'costo_total' => $costo_total,
            'profit_total' => $profit_total,
            'is_optional' => $is_optional,
        ];

        // Keep position numbering sequential inside the current offert.
        $maxPositionNumber = $latestOffert
            ? (int) $latestOffert->positions()->max('position_number')
            : 0;
        $formFields['position_number'] = $maxPositionNumber + 1;

        $position = Position::create($formFields);

        $groupElementIds = $request->input('selected_group_elements', []);
        $organigramIds = $request->input('selected_organigrams', []);
        $selectedElementIds = $request->input('selected_elements', []);
        $elementIdsWithQuantities = $request->input('element_quantity', []);
        $elementOptionalMap = $request->input('element_optional', []);

        if (empty($groupElementIds) || empty($organigramIds)) {
            $selectedElementsCollection = Element::with('group_elements.organigrams')
                ->whereIn('id', $selectedElementIds)
                ->get();

            if (empty($groupElementIds)) {
                $groupElementIds = $selectedElementsCollection
                    ->flatMap(function ($element) {
                        return $element->group_elements->pluck('id');
                    })
                    ->unique()
                    ->values()
                    ->toArray();
            }

            if (empty($organigramIds)) {
                $organigramIds = $selectedElementsCollection
                    ->flatMap(function ($element) {
                        return $element->group_elements->flatMap(function ($group) {
                            return $group->organigrams->pluck('id');
                        });
                    })
                    ->unique()
                    ->values()
                    ->toArray();
            }
        }

        $elements = Element::all();
        $supportsElementOptionalPivot = $this->hasElementPivotOptionalColumn();
        foreach ($elementIdsWithQuantities as $elementId => $quantity) {
            if (in_array($elementId, $selectedElementIds)) {
                $pivotData = ['quantity' => $this->normalizeDecimal($quantity, 1.0)];
                if ($supportsElementOptionalPivot) {
                    $pivotData['is_optional'] = $this->elementOptionalFromRequestMap($elementOptionalMap, $elementId);
                }
                $position->elements()->attach([
                    $elementId => $pivotData,
                ]);

                // Store material_id, element_id, and quantity in the new table
                foreach ($elements->find($elementId)->materials as $material) {
                    $materialQuantityKey = "material_quantity.{$elementId}.{$material->id}";
                    $materialQuantity = $request->input($materialQuantityKey, $material->pivot->quantity);
                    PositionMaterial::create([
                        'position_id' => $position->id,
                        'element_id' => $elementId,
                        'material_id' => $material->id,
                        'quantity' => $this->normalizeDecimal($materialQuantity, (float) $material->pivot->quantity),
                    ]);
                }
            }
        }

        $position->offerts()->attach($latestOffert);
        $position->group_elements()->attach($groupElementIds);
        $position->organigrams()->attach($organigramIds);

        return redirect()->route('position.edit', $position->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        // Single JOIN query: load position + offert_id in one roundtrip instead of two
        $position = Position::find($id);
        if (! $position) {
            return redirect()->route('offert.index')
                ->with('error', 'Position not found. Please try opening it again from the offer list.');
        }

        $offertId = DB::table('offert_position')
            ->where('position_id', $id)
            ->value('offert_id');
        if (! $offertId) {
            return redirect()->route('offert.index')
                ->with('error', 'Position is not linked to an offer. Please create a new position again.');
        }

        $offert = Offert::with('lockingUser')->find($offertId);
        if (! $offert) {
            return redirect()->route('offert.index')
                ->with('error', 'Offer not found for this position.');
        }

        if ($offert && $offert->isLockedByOther()) {
            $who = $offert->lockingUser?->username ?? 'another user';

            return redirect()->route('offert.index')
                ->with('lock_error', "Offer #{$offertId} is currently being edited by \"{$who}\". Please try again later.");
        }

        // Replace whereHas (subquery) with a direct JOIN — faster on PostgreSQL
        $positions = Position::join('offert_position', 'positions.id', '=', 'offert_position.position_id')
            ->where('offert_position.offert_id', $offertId)
            ->orderBy('positions.position_number', 'ASC')
            ->select('positions.*')
            ->get();

        // Cache the heavy tree queries (organigrams + all elements with materials).
        // These are admin-managed data that rarely change — safe to cache for 10 minutes.
        $organigrams = Cache::remember('organigrams_tree', 600, function () {
            return Organigram::with(['group_elements.elements'])->get();
        });

        $allElements = Cache::remember('elements_with_materials', 600, function () {
            return Element::with('materials')->get();
        });

        // Lightweight single-query: pivot data for this position only.
        // Replaces the position-specific eager-load that prevented caching elements.
        $supportsOptional = $this->hasElementPivotOptionalColumn();
        $pivotColumns = $supportsOptional
            ? ['element_id', 'quantity', 'is_optional']
            : ['element_id', 'quantity'];
        $elementPivots = DB::table('element_position')
            ->where('position_id', $id)
            ->get($pivotColumns)
            ->keyBy('element_id');

        $elements = $this->orderElementsByOrganigramTree($allElements, $organigrams);

        $positionMaterials = PositionMaterial::where('position_id', $id)->get();

        $rahmeElementIds = $this->computeRahmeElementIds($organigrams);

        // Pre-index saved material quantities for O(1) lookup in the blade.
        // Replaces $positionMaterials->where(element)->where(material)->first() scan per row.
        $positionMaterialsMap = [];
        foreach ($positionMaterials as $pm) {
            $positionMaterialsMap[(int) $pm->element_id][(int) $pm->material_id] = (float) $pm->quantity;
        }

        // Pre-compute offert coefficients once (same for every element/material row).
        $difficultyCoeff = max((float) ($offert->difficulty ?: 1), 0.001);
        $materialCoeff   = (float) ($offert->material ?: 1);
        $coefficient     = Coefficient::first();
        $inLaborPrice    = (float) ($coefficient->in_labor_price ?? 60);

        // Build JSON data for UNSELECTED elements only.
        // The blade will skip rendering HTML tables for these — JS renders them on demand
        // the first time the user checks one. This is the main reason the page was slow:
        // PHP was generating 110+ hidden tables on every load even though most were invisible.
        $unselectedElementsData = [];
        foreach ($elements as $element) {
            if ($elementPivots->has($element->id)) {
                continue; // selected elements render as HTML in the blade as normal
            }
            $mats = [];
            foreach ($element->materials as $material) {
                $qty      = $positionMaterialsMap[(int) $element->id][(int) $material->id]
                            ?? (float) ($material->pivot->quantity ?? 1);
                $calcTotal = ((float) $material->price_out * $materialCoeff)
                            + ((float) $material->total_arbeit / $difficultyCoeff);
                $mats[] = [
                    'id'        => $material->id,
                    'name'      => $material->name,
                    'unit'      => $material->unit ?? '',
                    'price_out' => (float) $material->price_out,
                    'price_in'  => (float) $material->price_in,
                    'zeit_cost' => (float) $material->zeit_cost,
                    'z_total'   => (float) $material->z_total,
                    'calc'      => round($calcTotal, 4),
                    'qty'       => $qty,
                ];
            }
            $unselectedElementsData[$element->id] = [
                'id'      => $element->id,
                'name'    => $element->name,
                'isRahme' => $rahmeElementIds->has($element->id),
                'qty'     => 1,
                'mats'    => $mats,
            ];
        }

        return view('position.edit', compact(
            'positions', 'offertId', 'position', 'organigrams',
            'elements', 'offert', 'elementPivots', 'rahmeElementIds',
            'positionMaterialsMap', 'difficultyCoeff', 'materialCoeff',
            'unselectedElementsData', 'inLaborPrice'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $description = $request->input('description');
        $description2 = $request->input('description2');
        $blocktype = $request->input('blocktype');
        $b = $request->input('b');
        $h = $request->input('h');
        $t = $request->input('t');
        $totalProTypPrice = $request->input('totalProTypPrice');
        $discountedTotal = $request->input('discountedTotal');
        $percentage = $request->input('percentage');
        $material_brutto = $request->input('price-out-input');
        $zeit_brutto = $request->input('zeit-cost-input');
        $material_costo = $request->input('material-costo');
        $material_profit = $request->input('material-profit');
        $ziet_costo = $request->input('zeit-costo');
        $ziet_profit = $request->input('zeit-profit');
        $costo_total = $request->input('costo-total');
        $profit_total = $request->input('profit-total');
        $quantity = $request->input('quantity');

        $is_optional = $request->has('is_optional') ? true : false;

        $formFields = [
            'description' => $description,
            'description2' => $description2,
            'blocktype' => $blocktype,
            'b' => $b,
            'h' => $h,
            't' => $t,
            'price_brutto' => $totalProTypPrice,
            'price_discount' => $discountedTotal,
            'discount' => $percentage,
            'quantity' => $quantity,
            'material_brutto' => $material_brutto,
            'zeit_brutto' => $zeit_brutto,
            'material_costo' => $material_costo,
            'material_profit' => $material_profit,
            'ziet_costo' => $ziet_costo,
            'ziet_profit' => $ziet_profit,
            'costo_total' => $costo_total,
            'profit_total' => $profit_total,
            'is_optional' => $is_optional,
        ];
        $position = Position::find($id);
        $position->update($formFields);

        $selectedOrganigramIds = $request->input('selected_organigrams', []);
        $selectedGroupElementIds = $request->input('selected_group_elements', []);

        $selectedElementIds = $request->input('selected_elements', []);
        $elementIdsWithQuantities = $request->input('element_quantity', []);
        $elementOptionalMap = $request->input('element_optional', []);

        if (empty($selectedGroupElementIds) || empty($selectedOrganigramIds)) {
            $selectedElementsCollection = Element::with('group_elements.organigrams')
                ->whereIn('id', $selectedElementIds)
                ->get();

            if (empty($selectedGroupElementIds)) {
                $selectedGroupElementIds = $selectedElementsCollection
                    ->flatMap(function ($element) {
                        return $element->group_elements->pluck('id');
                    })
                    ->unique()
                    ->values()
                    ->toArray();
            }

            if (empty($selectedOrganigramIds)) {
                $selectedOrganigramIds = $selectedElementsCollection
                    ->flatMap(function ($element) {
                        return $element->group_elements->flatMap(function ($group) {
                            return $group->organigrams->pluck('id');
                        });
                    })
                    ->unique()
                    ->values()
                    ->toArray();
            }
        }

        // Detach existing elements for the position
        $position->elements()->detach();

        // Pre-load ALL needed elements with their materials in ONE query (fixes N+1)
        $elementsWithMaterials = Element::with('materials')
            ->whereIn('id', array_keys($elementIdsWithQuantities))
            ->get()
            ->keyBy('id');

        $supportsElementOptionalPivot = $this->hasElementPivotOptionalColumn();
        $elementsPivot    = [];  // batched attach data
        $materialsToUpsert = []; // batched upsert data
        $now = now();

        foreach ($elementIdsWithQuantities as $elementId => $quantity) {
            if (in_array($elementId, $selectedElementIds)) {
                $pivotData = ['quantity' => $this->normalizeDecimal($quantity, 1.0)];
                if ($supportsElementOptionalPivot) {
                    $pivotData['is_optional'] = $this->elementOptionalFromRequestMap($elementOptionalMap, $elementId);
                }
                // Collect pivot data — attach all at once after the loop
                $elementsPivot[$elementId] = $pivotData;

                $element = $elementsWithMaterials->get($elementId);
                if ($element) {
                    foreach ($element->materials as $material) {
                        $materialQuantityKey = "material_quantity.{$elementId}.{$material->id}";
                        $materialQuantity = $request->input($materialQuantityKey, $material->pivot->quantity);
                        $materialsToUpsert[] = [
                            'position_id' => $position->id,
                            'element_id'  => (int) $elementId,
                            'material_id' => $material->id,
                            'quantity'    => $this->normalizeDecimal($materialQuantity, (float) $material->pivot->quantity),
                            'created_at'  => $now,
                            'updated_at'  => $now,
                        ];
                    }
                }
            }
        }

        // Single attach call for all elements (instead of one per element)
        if (!empty($elementsPivot)) {
            $position->elements()->attach($elementsPivot);
        }

        // Single upsert for all materials (instead of find+update/create per material)
        if (!empty($materialsToUpsert)) {
            DB::table('position_materials')->upsert(
                $materialsToUpsert,
                ['position_id', 'element_id', 'material_id'],
                ['quantity', 'updated_at']
            );
        }

        $position->organigrams()->sync($selectedOrganigramIds);
        $position->group_elements()->sync($selectedGroupElementIds);
        
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $position = Position::find($id);
        if (! $position) {
            return redirect()->route('offert.index');
        }

        $offertPivot = $position->offerts()->first();
        if (! $offertPivot) {
            $position->delete();
            return redirect()->route('offert.index');
        }

        $offertId = $offertPivot->id;

        $latestPosition = DB::transaction(function () use ($position, $offertId) {
            Offert::whereKey($offertId)->lockForUpdate()->first();

            $positionCount = Position::whereHas('offerts', function ($query) use ($offertId) {
                $query->where('id', $offertId);
            })->count();

            if ($positionCount <= 1) {
                return false;
            }

            $position->delete();

            // Re-number remaining positions for this offert sequentially (1, 2, 3, …)
            $remaining = Position::whereHas('offerts', function ($query) use ($offertId) {
                $query->where('id', $offertId);
            })->orderBy('position_number', 'ASC')->get();

            foreach ($remaining as $i => $pos) {
                $pos->update(['position_number' => $i + 1]);
            }

            return Position::whereHas('offerts', function ($query) use ($offertId) {
                $query->where('id', $offertId);
            })->orderBy('position_number', 'ASC')->first();
        });

        if ($latestPosition === false) {
            return redirect()->back()->with('error', 'Cannot delete the only remaining position.');
        }

        if ($latestPosition) {
            return redirect()->route('position.edit', $latestPosition->id);
        } else {
            return redirect()->route('offert.index');
        }
    }

    public function updateOrder(Request $request)
    {
        $orders = $request->input('orders', []);

        // Backward compatibility for older clients sending a single row update.
        if (empty($orders) && $request->filled('position_id') && $request->filled('order')) {
            $orders = [[
                'position_id' => (int) $request->input('position_id'),
                'order' => (int) $request->input('order'),
            ]];
        }

        if (! is_array($orders) || empty($orders)) {
            return response()->json(['success' => false, 'message' => 'No position orders provided'], 422);
        }

        DB::transaction(function () use ($orders) {
            foreach ($orders as $item) {
                $positionId = (int) ($item['position_id'] ?? 0);
                $newOrder = (int) ($item['order'] ?? 0);
                if ($positionId <= 0 || $newOrder <= 0) {
                    continue;
                }
                Position::where('id', $positionId)->update(['position_number' => $newOrder]);
            }
        });

        return response()->json(['success' => true]);
    }

    /**
     * Auto-save position for specific type
     */
    public function autoSave(Request $request)
    {
        try {
            $data = $request->all();
            $offertId = (int) ($data['offert_id'] ?? 0);

            if ($offertId <= 0) {
                return response()->json(['success' => false, 'message' => 'Offert not found'], 404);
            }

            $position = DB::transaction(function () use ($data, $offertId) {
                $offert = Offert::whereKey($offertId)->lockForUpdate()->first();
                if (! $offert) {
                    return null;
                }

                $requestedIndex = (int) ($data['index'] ?? 0);
                $requestedPositionNumber = $requestedIndex + 1;
                $requestedPositionId = isset($data['position_id']) ? (int) $data['position_id'] : null;

                // Prefer explicit position ID from the client to avoid cross-position overwrites.
                $existingPosition = null;
                if ($requestedPositionId) {
                    $existingPosition = Position::where('id', $requestedPositionId)
                        ->whereHas('offerts', function ($query) use ($offertId) {
                            $query->where('id', $offertId);
                        })
                        ->first();
                }

                // Fallback for older clients that don't send position_id yet.
                if (! $existingPosition) {
                    $existingPosition = Position::whereHas('offerts', function ($query) use ($offertId) {
                        $query->where('id', $offertId);
                    })->where('position_number', $requestedPositionNumber)->first();
                }

                if ($existingPosition) {
                    $position = $existingPosition;
                    $position->update([
                        'description' => $data['description'] ?? '',
                        'description2' => $data['description2'] ?? '',
                        'blocktype' => $data['blocktype'] ?? null,
                        'b' => $data['b'] ?? null,
                        'h' => $data['h'] ?? null,
                        't' => $data['t'] ?? null,
                        'quantity' => $data['quantity'] ?? 1,
                        'is_optional' => $data['is_optional'] ?? false,
                        'price_brutto' => $data['totalProTypPrice'] ?? $data['price_brutto'] ?? 0,
                        'price_discount' => $data['discountedTotal'] ?? $data['price_discount'] ?? 0,
                        'discount' => $data['percentage'] ?? $data['discount'] ?? 0,
                        'material_brutto' => $data['price_out'] ?? $data['material_brutto'] ?? 0,
                        'zeit_brutto' => $data['zeit_cost'] ?? $data['zeit_brutto'] ?? 0,
                        'material_costo' => $data['material_costo'] ?? 0,
                        'material_profit' => $data['material_profit'] ?? 0,
                        'ziet_costo' => $data['zeit_costo'] ?? 0,
                        'ziet_profit' => $data['zeit_profit'] ?? 0,
                        'costo_total' => $data['costo_total'] ?? 0,
                        'profit_total' => $data['profit_total'] ?? 0,
                    ]);
                } else {
                    // Create new position with the next sequential number for this offert.
                    $maxPositionNumber = (int) Position::whereHas('offerts', function ($query) use ($offertId) {
                        $query->where('id', $offertId);
                    })->max('position_number');
                    $positionNumber = $maxPositionNumber + 1;

                    $position = Position::create([
                        'description' => $data['description'] ?? '',
                        'description2' => $data['description2'] ?? '',
                        'blocktype' => $data['blocktype'] ?? null,
                        'b' => $data['b'] ?? null,
                        'h' => $data['h'] ?? null,
                        't' => $data['t'] ?? null,
                        'quantity' => $data['quantity'] ?? 1,
                        'is_optional' => $data['is_optional'] ?? false,
                        'position_number' => $positionNumber,
                        'price_brutto' => $data['totalProTypPrice'] ?? $data['price_brutto'] ?? 0,
                        'price_discount' => $data['discountedTotal'] ?? $data['price_discount'] ?? 0,
                        'discount' => $data['percentage'] ?? $data['discount'] ?? 0,
                        'material_brutto' => $data['price_out'] ?? $data['material_brutto'] ?? 0,
                        'zeit_brutto' => $data['zeit_cost'] ?? $data['zeit_brutto'] ?? 0,
                        'material_costo' => $data['material_costo'] ?? 0,
                        'material_profit' => $data['material_profit'] ?? 0,
                        'ziet_costo' => $data['zeit_costo'] ?? 0,
                        'ziet_profit' => $data['zeit_profit'] ?? 0,
                        'costo_total' => $data['costo_total'] ?? 0,
                        'profit_total' => $data['profit_total'] ?? 0,
                    ]);

                    $position->offerts()->syncWithoutDetaching([$offert->id]);
                }

                // Sync relationships
                if (isset($data['selected_organigrams'])) {
                    $position->organigrams()->sync($data['selected_organigrams']);
                }
                if (isset($data['selected_group_elements'])) {
                    $position->group_elements()->sync($data['selected_group_elements']);
                }
                if (isset($data['selected_elements']) && isset($data['element_quantity'])) {
                    $elementsToAttach = [];
                    $elementOptionalMap = $data['element_optional'] ?? [];
                    $supportsElementOptionalPivot = $this->hasElementPivotOptionalColumn();
                    foreach ($data['selected_elements'] as $elementId) {
                        $quantity = $data['element_quantity'][$elementId] ?? 1;
                        $elementsToAttach[$elementId] = ['quantity' => $this->normalizeDecimal($quantity, 1.0)];
                        if ($supportsElementOptionalPivot) {
                            $elementsToAttach[$elementId]['is_optional'] = $this->elementOptionalFromRequestMap($elementOptionalMap, $elementId);
                        }
                    }
                    $position->elements()->sync($elementsToAttach);
                }

                // Always persist posted material quantities (including decimals),
                // independent from selected_elements payload to avoid stale rollbacks.
                if (isset($data['material_quantity']) && is_array($data['material_quantity'])) {
                    $rows = [];
                    $now = now()->toDateTimeString();
                    foreach ($data['material_quantity'] as $elementId => $materials) {
                        if (! is_array($materials)) {
                            continue;
                        }
                        foreach ($materials as $materialId => $materialQuantity) {
                            $rows[] = [
                                'position_id' => $position->id,
                                'element_id'  => (int) $elementId,
                                'material_id' => (int) $materialId,
                                'quantity'    => $this->normalizeDecimal($materialQuantity, 0.0),
                                'created_at'  => $now,
                                'updated_at'  => $now,
                            ];
                        }
                    }
                    DB::table('position_materials')->where('position_id', $position->id)->delete();
                    if (! empty($rows)) {
                        DB::table('position_materials')->insert($rows);
                    }
                }

                return $position;
            });

            if (! $position) {
                return response()->json(['success' => false, 'message' => 'Offert not found'], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Position auto-saved successfully',
                'position_id' => $position->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error auto-saving: ' . $e->getMessage()
            ], 500);
        }
    }
}
