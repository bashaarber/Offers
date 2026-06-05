<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\SubOffert;
use App\Models\Coefficient;
use App\Models\Organigram;
use App\Models\Position;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PositionMaterial;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

/**
 * Standalone Sub Offert module — parallel to OffertController but backed by the
 * sub_offerts table. Positions are shared with the offert editor and linked via
 * positions.sub_offert_id.
 */
class SubOffertController extends Controller
{
    private function resolveSafeReturnUrl(?string $returnUrl): ?string
    {
        if (empty($returnUrl)) {
            return null;
        }

        $appUrl = rtrim(url('/'), '/');
        $candidate = trim($returnUrl);

        if (str_starts_with($candidate, $appUrl . '/')) {
            return $candidate;
        }

        if (str_starts_with($candidate, '/')) {
            return url($candidate);
        }

        return null;
    }

    private function hasDefaultRabattColumn(): bool
    {
        return Cache::remember('schema_sub_offerts_has_default_rabatt', 86400, function () {
            return Schema::hasColumn('sub_offerts', 'default_rabatt');
        });
    }

    private function hasCoefficientDefaultRabattColumn(): bool
    {
        return Cache::remember('schema_coefficients_has_default_rabatt', 86400, function () {
            return Schema::hasColumn('coefficients', 'default_rabatt');
        });
    }

    /**
     * Recompute every position's stored price snapshot using the sub-offert's CURRENT
     * Material-Koeffizient. Mirrors the offert flow.
     */
    private function recomputePositionsForMaterialCoefficient(SubOffert $subOffert): void
    {
        $materialCoeff = (float) ($subOffert->material ?: 1);
        $coefficient   = Coefficient::first();
        $inLaborPrice  = (float) ($coefficient->in_labor_price ?? 60);
        $offertDifficulty = (float) ($subOffert->difficulty ?: 1);

        $organigrams = Cache::remember('organigrams_tree', 600, function () {
            return Organigram::with(['group_elements.elements'])->get();
        });
        $externeWasserIds = [];
        foreach ($organigrams as $org) {
            foreach ($org->group_elements as $ge) {
                if ($ge->name === 'Externe Wasser Anschl.') {
                    foreach ($ge->elements as $el) {
                        $externeWasserIds[$el->id] = true;
                    }
                }
            }
        }

        $supportsOptional = Schema::hasColumn('element_position', 'is_optional');

        foreach ($subOffert->positions as $position) {
            $diffCoeff = max((float) ($position->difficulty ?: $offertDifficulty), 0.001);

            $elements = $position->elements()->with('materials')->get();
            $pmMap = [];
            foreach (PositionMaterial::where('position_id', $position->id)->get() as $pm) {
                $pmMap[(int) $pm->element_id][(int) $pm->material_id] = (float) $pm->quantity;
            }

            $totalProTypPrice = 0.0;
            $totalPriceOut    = 0.0;
            $totalPriceIn     = 0.0;
            $totalZeitCost    = 0.0;
            $totalZHours      = 0.0;

            foreach ($elements as $element) {
                $isOptional = $supportsOptional
                    && Position::truthyElementOptionalPivot($element->pivot->is_optional ?? null);
                if ($isOptional) {
                    continue;
                }

                $elementQty = isset($externeWasserIds[$element->id])
                    ? 1.0
                    : (float) ($element->pivot->quantity ?? 1);

                foreach ($element->materials as $material) {
                    $matQty = $pmMap[(int) $element->id][(int) $material->id]
                        ?? (float) ($material->pivot->quantity ?? 1);
                    $priceOut    = (float) $material->price_out;
                    $priceIn     = (float) $material->price_in;
                    $zeitCost    = (float) $material->zeit_cost;
                    $zTotal      = (float) ($material->z_total ?? 0);
                    $totalArbeit = (float) ($material->total_arbeit ?? 0);

                    $totalPriceOut += $priceOut * $matQty * $elementQty;
                    $totalPriceIn  += $priceIn  * $matQty * $elementQty;
                    $totalZeitCost += $zeitCost * $matQty * $elementQty;
                    $totalZHours   += $zTotal   * $matQty * $elementQty;

                    $matCalc = $priceOut * $materialCoeff + $totalArbeit / $diffCoeff;
                    $totalProTypPrice += $matCalc * $matQty * $elementQty;
                }
            }

            $menge    = (float) ($position->quantity ?: 1);
            $discount = (float) ($position->discount ?? 0);

            $laborKosto      = $totalZHours * $inLaborPrice / $diffCoeff;
            $costoTotal      = $totalPriceIn * $materialCoeff + $laborKosto;
            $discountedTotal = $totalProTypPrice * (1 - $discount / 100) * $menge;
            $profitTotal     = $discountedTotal - $costoTotal;
            $priceBrutto     = $totalProTypPrice * $menge;

            $position->update([
                'price_brutto'    => round($priceBrutto, 2),
                'price_discount'  => round($discountedTotal, 2),
                'material_brutto' => round($totalPriceOut, 2),
                'material_costo'  => round($totalPriceIn, 2),
                'material_profit' => round($totalPriceOut - $totalPriceIn, 2),
                'zeit_brutto'     => round($totalZeitCost, 2),
                'ziet_costo'      => round($laborKosto, 2),
                'ziet_profit'     => round($totalZeitCost - $laborKosto, 2),
                'costo_total'     => round($costoTotal, 2),
                'profit_total'    => round($profitTotal, 2),
            ]);
        }
    }

    public function index(Request $request)
    {
        $query = SubOffert::query()
            ->whereNull('parent_id')
            ->with([
                'client:id,name',
                'user:id,username',
                'lockingUser:id,username',
                'subOfferts' => fn ($q) => $q->orderBy('id', 'ASC'),
                'subOfferts.client:id,name',
                'subOfferts.user:id,username',
                'subOfferts.lockingUser:id,username',
            ]);

        \App\Support\ListFilter::apply($query, $request, [
            'id'          => 'sub_offerts.id',
            'date'        => 'sub_offerts.create_date',
            'client'      => ['relation' => 'client', 'column' => 'name'],
            'client_sign' => 'sub_offerts.client_sign',
            'object'      => 'sub_offerts.object',
            'status'      => 'sub_offerts.status',
            'type'        => 'sub_offerts.type',
            'user'        => ['relation' => 'user', 'column' => 'username'],
        ]);

        $offerts = $query->orderBy('id', 'DESC')->paginate(20)->withQueryString();
        $expandAll = $request->boolean('expand');

        return view('sub_offert.index', compact('offerts', 'expandAll'));
    }

    public function exportPdf($id){
        try {
            $offert = SubOffert::find($id);
            if (!$offert) {
                abort(404, 'Sub Offert #' . $id . ' not found.');
            }

            $selectedOrganigramIds = collect(request()->query('organigrams', []))
                ->map(fn ($v) => (int) $v)
                ->filter()
                ->values()
                ->all();

            // GIS surcharge: when enabled every position price on the external PDF is raised by 20%.
            $gisFactor = request()->boolean('gis') ? 1.20 : 1.0;

            $offert->load([
                'client',
                'positions' => function ($query) use ($selectedOrganigramIds) {
                    $query->orderBy('position_number', 'ASC');
                    if (!empty($selectedOrganigramIds)) {
                        $query->whereHas('group_elements.organigrams', function ($q) use ($selectedOrganigramIds) {
                            $q->whereIn('organigrams.id', $selectedOrganigramIds);
                        });
                    }
                },
                'positions.elementsForPdf.group_elements.organigrams',
            ]);

            $customPositionPrices = [];
            if (!empty($selectedOrganigramIds)) {
                $materialCoeff   = (float) ($offert->material ?: 1);
                $difficultyCoeff = max((float) ($offert->difficulty ?: 1), 0.001);

                $positionIds = $offert->positions->pluck('id')->toArray();

                $pmRows = \App\Models\PositionMaterial::whereIn('position_id', $positionIds)
                    ->join('materials', 'materials.id', '=', 'position_materials.material_id')
                    ->select(
                        'position_materials.position_id',
                        'position_materials.element_id',
                        'position_materials.quantity',
                        'materials.price_out',
                        'materials.total_arbeit'
                    )
                    ->get()
                    ->groupBy(fn ($pm) => $pm->position_id . '_' . $pm->element_id);

                foreach ($offert->positions as $position) {
                    $filteredElementIds = $position->elementsForPdf
                        ->filter(function ($element) use ($selectedOrganigramIds) {
                            foreach ($element->group_elements as $ge) {
                                foreach ($ge->organigrams as $org) {
                                    if (in_array($org->id, $selectedOrganigramIds, true)) {
                                        return true;
                                    }
                                }
                            }
                            return false;
                        })
                        ->pluck('id');

                    $totalProTypPrice = 0.0;
                    foreach ($filteredElementIds as $elementId) {
                        $element = $position->elementsForPdf->firstWhere('id', $elementId);
                        if (!$element) {
                            continue;
                        }
                        if (\App\Models\Position::truthyElementOptionalPivot($element->pivot->is_optional ?? null)) {
                            continue;
                        }

                        $isExterneWasser = $element->group_elements->contains('name', 'Externe Wasser Anschl.');
                        $elementQuantity = $isExterneWasser ? 1 : max((float) ($element->pivot->quantity ?? 1), 0);
                        $key = $position->id . '_' . $elementId;
                        $pms = $pmRows->get($key, collect());

                        $elementTotal = 0.0;
                        foreach ($pms as $pm) {
                            $elementTotal += (
                                (float) $pm->price_out * $materialCoeff
                                + (float) $pm->total_arbeit / $difficultyCoeff
                            ) * (float) $pm->quantity;
                        }
                        $totalProTypPrice += $elementTotal * $elementQuantity;
                    }

                    $mengeValue = max((float) ($position->quantity ?? 1), 1);
                    $brutto     = $totalProTypPrice * $mengeValue;
                    $discount   = (float) ($position->discount ?? 0);
                    $netto      = $brutto * (1 - $discount / 100);

                    $customPositionPrices[$position->id] = [
                        'brutto' => $brutto,
                        'netto'  => $netto,
                    ];
                }
            }

            $organigrams = Cache::remember('organigrams_tree', 600, function () {
                return Organigram::with(['group_elements.elements'])->get();
            });

            $pdf = Pdf::loadView('sub_offert.pdf-export', compact('offert', 'selectedOrganigramIds', 'customPositionPrices', 'organigrams', 'gisFactor'))
                ->setOption(['isPhpEnabled' => true]);
            return $pdf->stream();
        } catch (\Throwable $e) {
            Log::error('Sub Offert external PDF generation failed', [
                'sub_offert_id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response('PDF generation failed: ' . $e->getMessage(), 500);
        }
    }

    public function create(Request $request)
    {
        // When creating a nested sub-offert the parent is passed through; the
        // child shares the parent's running number but keeps the -S suffix.
        $parent = null;
        if ($request->filled('parent_id')) {
            $parent = SubOffert::find($request->input('parent_id'));
        }

        if ($parent) {
            $newOffertNumber = SubOffert::formatDisplayNumber($parent->rootId());
        } else {
            $nextOffertId = ((int) SubOffert::max('id')) + 1;
            $newOffertNumber = SubOffert::formatDisplayNumber($nextOffertId);
        }

        $users = User::all();
        $clients = Client::all();
        $coefficients = Coefficient::get();

        return view('sub_offert.create', compact('newOffertNumber', 'users', 'clients', 'coefficients', 'parent'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $formFields = $request->validate([
            'user_sign' => 'required',
            'status' => 'required',
            'create_date' => 'required',
            'validity' => 'required',
            'client_sign' => 'required',
            'finish_date' => 'nullable',
            'object' => 'required',
            'city' => 'required',
            'service' => 'required',
            'payment_conditions' => 'required',
            'difficulty' => 'required',
            'material' => 'required',
            'labor_price' => 'required',
            'default_rabatt' => 'nullable|numeric|min:0|max:100',
            'client_id' => 'required|exists:clients,id',
            'client_address' => 'nullable|string',
            'client_address_2' => 'nullable|string',
            'client_address_3' => 'nullable|string',
        ]);
        $formFields['type'] = $request->input('type');
        $formFields['user_id'] = $user->id;
        if (!empty($formFields['client_id'])) {
            $client = Client::find($formFields['client_id']);
            if ($client) {
                if (empty($formFields['client_address'])) {
                    $formFields['client_address'] = $client->address;
                }
                if (empty($formFields['client_address_2'])) {
                    $formFields['client_address_2'] = $client->address_2;
                }
                if (empty($formFields['client_address_3'])) {
                    $formFields['client_address_3'] = $client->address_3;
                }
            }
        }
        if ($this->hasDefaultRabattColumn()) {
            $coefficientDefaultRabatt = $this->hasCoefficientDefaultRabattColumn()
                ? (Coefficient::value('default_rabatt') ?? 20)
                : 20;
            $formFields['default_rabatt'] = $request->filled('default_rabatt')
                ? $request->input('default_rabatt')
                : $coefficientDefaultRabatt;
        } else {
            unset($formFields['default_rabatt']);
        }

        if (empty($formFields['finish_date'])) {
            $formFields['finish_date'] = $formFields['create_date'];
        }

        // Nested sub-offert: attach to an existing sub-offert parent.
        if ($request->filled('parent_id')
            && SubOffert::whereKey($request->input('parent_id'))->exists()) {
            $formFields['parent_id'] = (int) $request->input('parent_id');
        }

        $subOffert = SubOffert::create($formFields);

        // Nested sub-offert: copy all positions (with materials/elements) from the parent
        // so it starts as a full clone the user can then adjust.
        if (!empty($formFields['parent_id'])) {
            $parent = SubOffert::find($formFields['parent_id']);
            $firstPosition = $parent ? $this->copyPositionsFromTo($parent, $subOffert) : null;
            if ($firstPosition) {
                return redirect()->route('position.edit', $firstPosition->id);
            }
        }

        // Otherwise create a real Pos. 1 so the sub-offert is never left with zero positions.
        $defaultDiscount = (float) ($subOffert->default_rabatt ?? 0);
        $position = \App\Models\Position::create([
            'sub_offert_id'   => $subOffert->id,
            'description'     => '',
            'position_number' => 1,
            'quantity'        => 1,
            'price_brutto'    => 0,
            'price_discount'  => 0,
            'discount'        => $defaultDiscount,
            'material_brutto' => 0,
            'zeit_brutto'     => 0,
            'material_costo'  => 0,
            'material_profit' => 0,
            'ziet_costo'      => 0,
            'ziet_profit'     => 0,
            'costo_total'     => 0,
            'profit_total'    => 0,
        ]);

        return redirect()->route('position.edit', $position->id);
    }

    /**
     * Replicate every position of $source (incl. materials, organigrams, group
     * elements and element pivots) onto $target. Returns the first new position.
     */
    private function copyPositionsFromTo(SubOffert $source, SubOffert $target): ?Position
    {
        $first = null;

        foreach ($source->positions()->orderBy('position_number', 'ASC')->get() as $position) {
            $new_position = $position->replicate();
            $new_position->sub_offert_id = $target->id;
            $new_position->save();

            if (!$first) {
                $first = $new_position;
            }

            $positionMaterials = PositionMaterial::where('position_id', $position->id)->get();
            foreach ($positionMaterials as $material) {
                $exists = PositionMaterial::where([
                    'element_id'  => $material->element_id,
                    'material_id' => $material->material_id,
                    'position_id' => $new_position->id,
                ])->exists();

                if (!$exists) {
                    PositionMaterial::create([
                        'position_id' => $new_position->id,
                        'element_id'  => $material->element_id,
                        'material_id' => $material->material_id,
                        'quantity'    => $material->quantity,
                    ]);
                }
            }

            foreach ($position->organigrams as $organigram) {
                $new_position->organigrams()->attach($organigram->id);
            }
            foreach ($position->group_elements as $group_element) {
                $new_position->group_elements()->attach($group_element->id);
            }
            foreach ($position->elements as $element) {
                $new_position->elements()->attach($element->id, ['quantity' => $element->pivot->quantity]);
            }
        }

        return $first;
    }

    public function show(string $id)
    {
        $subOffert = SubOffert::findOrFail($id);
        $firstPosition = $subOffert->positions()
            ->orderBy('position_number', 'ASC')
            ->select('positions.id')
            ->first();

        if ($firstPosition) {
            return redirect()->route('position.edit', $firstPosition->id);
        }

        $defaultDiscount = (float) ($subOffert->default_rabatt ?? 0);
        $position = \App\Models\Position::create([
            'sub_offert_id'   => $subOffert->id,
            'description'     => '',
            'position_number' => 1,
            'quantity'        => 1,
            'price_brutto'    => 0,
            'price_discount'  => 0,
            'discount'        => $defaultDiscount,
            'material_brutto' => 0,
            'zeit_brutto'     => 0,
            'material_costo'  => 0,
            'material_profit' => 0,
            'ziet_costo'      => 0,
            'ziet_profit'     => 0,
            'costo_total'     => 0,
            'profit_total'    => 0,
        ]);

        return redirect()->route('position.edit', $position->id);
    }

    public function copy($sub_offert_id)
    {
        $user = auth()->user();
        $timestamp = time();
        $currentDate = gmdate('Y-m-d', $timestamp);
        $subOffert = SubOffert::findOrFail($sub_offert_id);

        $new_offert = $subOffert->replicate()->fill([
            'user_id' => $user->id,
            'create_date' => $currentDate,
        ]);

        $new_offert->save();

        // Copy positions
        foreach ($subOffert->positions as $position) {
            $new_position = $position->replicate();
            $new_position->sub_offert_id = $new_offert->id;
            $new_position->save();

            $positionMaterials = PositionMaterial::where('position_id', $position->id)->get();
            foreach ($positionMaterials as $material) {
                $existingRecord = PositionMaterial::where([
                    'element_id' => $material->element_id,
                    'material_id' => $material->material_id,
                    'position_id' => $new_position->id,
                ])->first();

                if (!$existingRecord) {
                    PositionMaterial::create([
                        'position_id' => $new_position->id,
                        'element_id' => $material->element_id,
                        'material_id' => $material->material_id,
                        'quantity' => $material->quantity,
                    ]);
                }
            }

            foreach ($position->organigrams as $organigram) {
                $new_position->organigrams()->attach($organigram->id);
            }

            foreach ($position->group_elements as $group_element) {
                $new_position->group_elements()->attach($group_element->id);
            }

            foreach ($position->elements as $element) {
                $new_position->elements()->attach($element->id, ['quantity' => $element->pivot->quantity]);
            }
        }

        return redirect()->route('sub-offert.index');
    }

    public function edit(Request $request, string $id)
    {
        $offert = SubOffert::find($id);
        $clients = Client::all();
        $fromPositionOverview = $request->boolean('from_position');
        $returnUrl = $this->resolveSafeReturnUrl($request->input('return_url'));
        $embeddedOverview = $request->boolean('embed');

        return view('sub_offert.edit', compact('offert', 'clients', 'fromPositionOverview', 'returnUrl', 'embeddedOverview'));
    }

    public function update(Request $request, string $id)
    {
        $user = auth()->user();
        $fromPositionOverview = $request->boolean('from_position');
        $returnUrl = $this->resolveSafeReturnUrl($request->input('return_url'));

        $formFields = $request->validate([
            'user_sign' => 'required',
            'status' => 'required',
            'create_date' => 'required',
            'validity' => 'required',
            'client_sign' => 'required',
            'finish_date' => 'nullable',
            'object' => 'required',
            'city' => 'required',
            'service' => 'required',
            'payment_conditions' => 'required',
            'difficulty' => 'required',
            'material' => 'required',
            'labor_price' => 'required',
            'default_rabatt' => 'nullable|numeric|min:0|max:100',
            'client_id' => 'required|exists:clients,id',
            'client_address' => 'nullable|string',
            'client_address_2' => 'nullable|string',
            'client_address_3' => 'nullable|string',
        ]);

        $formFields['type'] = $request->input('type');
        $formFields['user_id'] = $user->id;
        if (!empty($formFields['client_id'])) {
            $client = Client::find($formFields['client_id']);
            if ($client) {
                if (empty($formFields['client_address'])) {
                    $formFields['client_address'] = $client->address;
                }
                if (empty($formFields['client_address_2'])) {
                    $formFields['client_address_2'] = $client->address_2;
                }
                if (empty($formFields['client_address_3'])) {
                    $formFields['client_address_3'] = $client->address_3;
                }
            }
        }
        if ($this->hasDefaultRabattColumn()) {
            $coefficientDefaultRabatt = $this->hasCoefficientDefaultRabattColumn()
                ? (Coefficient::value('default_rabatt') ?? 20)
                : 20;
            $formFields['default_rabatt'] = $request->filled('default_rabatt')
                ? $request->input('default_rabatt')
                : $coefficientDefaultRabatt;
        } else {
            unset($formFields['default_rabatt']);
        }

        if (empty($formFields['finish_date'])) {
            $formFields['finish_date'] = $formFields['create_date'];
        }

        $subOffert = SubOffert::find($id);
        $oldRabatt = (float) ($subOffert->default_rabatt ?? 0);
        $oldMaterial = (float) ($subOffert->material ?? 0);
        $subOffert->update($formFields);

        if ($this->hasDefaultRabattColumn() && isset($formFields['default_rabatt'])) {
            $newRabatt = (float) $formFields['default_rabatt'];
            if ($oldRabatt !== $newRabatt) {
                foreach ($subOffert->positions as $position) {
                    $position->update([
                        'discount'       => $newRabatt,
                        'price_discount' => round($position->price_brutto * (1 - $newRabatt / 100), 2),
                    ]);
                }
            }
        }

        if (isset($formFields['material'])) {
            $newMaterial = (float) $formFields['material'];
            if ($oldMaterial !== $newMaterial) {
                $this->recomputePositionsForMaterialCoefficient($subOffert->fresh('positions'));
            }
        }

        if ($fromPositionOverview && $returnUrl) {
            return redirect()->to($returnUrl);
        }

        return redirect()->route('sub-offert.index');
    }

    public function autoSave(Request $request, string $id)
    {
        $subOffert = SubOffert::find($id);

        if (!$subOffert) {
            return response()->json(['success' => false, 'message' => 'Sub Offert not found'], 404);
        }

        $fields = $request->only([
            'user_sign', 'status', 'create_date', 'validity', 'client_sign',
            'finish_date', 'object', 'city', 'service', 'payment_conditions',
            'difficulty', 'material', 'labor_price', 'default_rabatt', 'client_id', 'client_address',
            'client_address_2', 'client_address_3', 'type',
        ]);

        if (empty($fields['finish_date']) && !empty($fields['create_date'])) {
            $fields['finish_date'] = $fields['create_date'];
        }

        $oldRabatt = (float) ($subOffert->default_rabatt ?? 0);
        $oldMaterial = (float) ($subOffert->material ?? 0);

        if (!$this->hasDefaultRabattColumn()) {
            unset($fields['default_rabatt']);
        }

        if (!empty($fields['client_id'])) {
            $client = Client::find($fields['client_id']);
            if ($client) {
                if (empty($fields['client_address'])) {
                    $fields['client_address'] = $client->address;
                }
                if (empty($fields['client_address_2'])) {
                    $fields['client_address_2'] = $client->address_2;
                }
                if (empty($fields['client_address_3'])) {
                    $fields['client_address_3'] = $client->address_3;
                }
            }
        }

        $subOffert->update(array_filter($fields, fn($v) => $v !== null && $v !== ''));

        if ($this->hasDefaultRabattColumn() && isset($fields['default_rabatt']) && $fields['default_rabatt'] !== null) {
            $newRabatt = (float) $fields['default_rabatt'];
            if ($oldRabatt !== $newRabatt) {
                foreach ($subOffert->positions as $position) {
                    $position->update([
                        'discount'       => $newRabatt,
                        'price_discount' => round($position->price_brutto * (1 - $newRabatt / 100), 2),
                    ]);
                }
            }
        }

        if (isset($fields['material']) && $fields['material'] !== null && $fields['material'] !== '') {
            $newMaterial = (float) $fields['material'];
            if ($oldMaterial !== $newMaterial) {
                $this->recomputePositionsForMaterialCoefficient($subOffert->fresh('positions'));
            }
        }

        return response()->json(['success' => true]);
    }

    public function overrideDifficultyAll(string $id)
    {
        $subOffert = SubOffert::find($id);
        if (!$subOffert) {
            return response()->json(['success' => false, 'message' => 'Sub Offert not found'], 404);
        }

        $newDifficulty = (float) ($subOffert->difficulty ?: 1);
        foreach ($subOffert->positions as $position) {
            $position->update(['difficulty' => $newDifficulty]);
        }

        $this->recomputePositionsForMaterialCoefficient($subOffert->fresh('positions'));

        return response()->json(['success' => true]);
    }

    public function destroy(string $id)
    {
        $subOffert = SubOffert::find($id);

        // Deleting a parent sub-offert also removes its nested sub-offerts (and positions).
        foreach ($subOffert->subOfferts as $sub) {
            $this->deleteSubOffertWithPositions($sub);
        }

        $this->deleteSubOffertWithPositions($subOffert);

        return redirect()->route('sub-offert.index');
    }

    private function deleteSubOffertWithPositions(SubOffert $subOffert): void
    {
        $positions = $subOffert->positions;
        $subOffert->delete();

        foreach ($positions as $position) {
            $position->delete();
        }
    }

    public function lock(string $id)
    {
        $subOffert = SubOffert::find($id);
        if (! $subOffert) {
            return response()->json(['success' => false], 404);
        }
        if ($subOffert->isLockedByOther()) {
            return response()->json([
                'success' => false,
                'locked_by' => $subOffert->lockingUser?->username ?? 'another user',
            ], 423);
        }
        $subOffert->acquireLock();

        return response()->json(['success' => true]);
    }

    public function unlock(string $id)
    {
        $subOffert = SubOffert::find($id);
        if ($subOffert) {
            $subOffert->releaseLock();
        }

        return response()->json(['success' => true]);
    }
}
