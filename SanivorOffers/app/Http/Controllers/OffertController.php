<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Offert;
use App\Models\Coefficient;
use App\Models\Organigram;
use App\Models\Position;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PositionMaterial;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

class OffertController extends Controller
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
        return Cache::remember('schema_offerts_has_default_rabatt', 86400, function () {
            return Schema::hasColumn('offerts', 'default_rabatt');
        });
    }

    private function hasCoefficientDefaultRabattColumn(): bool
    {
        return Cache::remember('schema_coefficients_has_default_rabatt', 86400, function () {
            return Schema::hasColumn('coefficients', 'default_rabatt');
        });
    }

    /**
     * Recompute every position's stored price snapshot using the offert's CURRENT
     * Material-Koeffizient. Each position keeps its own Schw.Koeffizient (difficulty).
     * Mirrors the JS calc in resources/views/position/edit.blade.php.
     */
    private function recomputePositionsForMaterialCoefficient(Offert $offert): void
    {
        $materialCoeff = (float) ($offert->material ?: 1);
        $coefficient   = Coefficient::first();
        $inLaborPrice  = (float) ($coefficient->in_labor_price ?? 60);
        $offertDifficulty = (float) ($offert->difficulty ?: 1);

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

        foreach ($offert->positions as $position) {
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

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $offerts = Offert::with([
            'client:id,name',
            'user:id,username',
            'lockingUser:id,username',
        ])->orderBy('id', 'DESC')->paginate(50);

        return view('offert.index', compact('offerts'));
    }

    public function exportPdf($id){
        try {
            $offert = Offert::find($id);
            if (!$offert) {
                abort(404, 'Offert #' . $id . ' not found.');
            }

            $selectedOrganigramIds = collect(request()->query('organigrams', []))
                ->map(fn ($v) => (int) $v)
                ->filter()
                ->values()
                ->all();

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

            // When a custom organigram filter is active, recompute per-position prices
            // using only the elements that belong to the selected organigrams.
            $customPositionPrices = [];
            if (!empty($selectedOrganigramIds)) {
                $materialCoeff   = (float) ($offert->material ?: 1);
                $difficultyCoeff = max((float) ($offert->difficulty ?: 1), 0.001);

                $positionIds = $offert->positions->pluck('id')->toArray();

                // Single join query: all PositionMaterial rows + material price columns
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
                    // Collect element IDs that touch at least one selected organigram
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

            $pdf = Pdf::loadView('offert.offert-pdf-export', compact('offert', 'selectedOrganigramIds', 'customPositionPrices'))
                ->setOption(['isPhpEnabled' => true]);
            return $pdf->stream();
        } catch (\Throwable $e) {
            Log::error('External PDF generation failed', [
                'offert_id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response('PDF generation failed: ' . $e->getMessage(), 500);
        }
        // return $pdf->download('invoice.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nextOffertId = ((int) Offert::max('id')) + 1;
        $newOffertNumber = Offert::formatDisplayNumber($nextOffertId);
        $users = User::all();
        $clients = Client::all();
        $coefficients = Coefficient::get();

        return view('offert.create', compact('newOffertNumber', 'users', 'clients', 'coefficients'));
    }

    /**
     * Store a newly created resource in storage.
     */
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
        ]);
        $formFields['type'] = $request->input('type');
        $formFields['user_id'] = $user->id;
        if (empty($formFields['client_address']) && !empty($formFields['client_id'])) {
            $formFields['client_address'] = Client::where('id', $formFields['client_id'])->value('address');
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

        $offert = Offert::create($formFields);

        // Always create a real Pos. 1 so the offer is never left with zero positions.
        $defaultDiscount = (float) ($offert->default_rabatt ?? 0);
        $position = \App\Models\Position::create([
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
        $position->offerts()->attach($offert);

        return redirect()->route('position.edit', $position->id);
    }

    /**
     * Display the specified resource.
     */

    public function show(string $id)
    {
        $offert = Offert::findOrFail($id);
        $firstPosition = $offert->positions()
            ->orderBy('position_number', 'ASC')
            ->select('positions.id')
            ->first();

        if ($firstPosition) {
            return redirect()->route('position.edit', $firstPosition->id);
        }

        // No positions yet — create a real Pos. 1 instead of using the create form.
        $defaultDiscount = (float) ($offert->default_rabatt ?? 0);
        $position = \App\Models\Position::create([
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
        $position->offerts()->attach($offert);

        return redirect()->route('position.edit', $position->id);
    }

    public function copy($offert_id)
    {
        $user = auth()->user();
        $timestamp = time();
        $currentDate = gmdate('Y-m-d', $timestamp);
        $offert = Offert::findOrFail($offert_id);

        $new_offert = $offert->replicate()->fill([
            'user_id' => $user->id,
            'create_date' => $currentDate,
        ]);

        $new_offert->save();

    // Copy positions
    foreach ($offert->positions as $position) {
        $new_position = $position->replicate();
        $new_position->save();

        // Attach the new position to the new Offert
        $new_offert->positions()->attach($new_position->id);

         // Copy the values from PositionMaterial table based on the previous position IDs
         $positionMaterials = PositionMaterial::where('position_id', $position->id)->get();
         foreach ($positionMaterials as $material) {
             // Check if a record with the same element_id, material_id, and position_id already exists
             $existingRecord = PositionMaterial::where([
                 'element_id' => $material->element_id,
                 'material_id' => $material->material_id,
                 'position_id' => $new_position->id,
             ])->first();
 
             // If no record exists, create a new one
             if (!$existingRecord) {
                 PositionMaterial::create([
                     'position_id' => $new_position->id,
                     'element_id' => $material->element_id,
                     'material_id' => $material->material_id,
                     'quantity' => $material->quantity,
                 ]);
             }
         }

        // Attach the existing relationships to the new position
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
        return redirect()->route('offert.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $offert = Offert::find($id);
        $clients = Client::all();
        $fromPositionOverview = $request->boolean('from_position');
        $returnUrl = $this->resolveSafeReturnUrl($request->input('return_url'));
        $embeddedOverview = $request->boolean('embed');

        return view('offert.edit', compact('offert', 'clients', 'fromPositionOverview', 'returnUrl', 'embeddedOverview'));
    }

    /**
     * Update the specified resource in storage.
     */
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
        ]);

        $formFields['type'] = $request->input('type');
        $formFields['user_id'] = $user->id;
        if (empty($formFields['client_address']) && !empty($formFields['client_id'])) {
            $formFields['client_address'] = Client::where('id', $formFields['client_id'])->value('address');
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

        $offert = Offert::find($id);
        $oldRabatt = (float) ($offert->default_rabatt ?? 0);
        $oldMaterial = (float) ($offert->material ?? 0);
        $offert->update($formFields);

        if ($this->hasDefaultRabattColumn() && isset($formFields['default_rabatt'])) {
            $newRabatt = (float) $formFields['default_rabatt'];
            if ($oldRabatt !== $newRabatt) {
                foreach ($offert->positions as $position) {
                    $position->update([
                        'discount'       => $newRabatt,
                        'price_discount' => round($position->price_brutto * (1 - $newRabatt / 100), 2),
                    ]);
                }
            }
        }

        // Schw.Koeffizient (difficulty) intentionally does NOT propagate to existing
        // positions — each position keeps its own value; new positions inherit from the offert.
        if (isset($formFields['material'])) {
            $newMaterial = (float) $formFields['material'];
            if ($oldMaterial !== $newMaterial) {
                $this->recomputePositionsForMaterialCoefficient($offert->fresh('positions'));
            }
        }

        if ($fromPositionOverview && $returnUrl) {
            return redirect()->to($returnUrl);
        }

        return redirect()->route('offert.index');
    }

    /**
     * Auto-save offert header fields via AJAX (no redirect).
     */
    public function autoSave(Request $request, string $id)
    {
        $offert = Offert::find($id);

        if (!$offert) {
            return response()->json(['success' => false, 'message' => 'Offert not found'], 404);
        }

        $fields = $request->only([
            'user_sign', 'status', 'create_date', 'validity', 'client_sign',
            'finish_date', 'object', 'city', 'service', 'payment_conditions',
            'difficulty', 'material', 'labor_price', 'default_rabatt', 'client_id', 'client_address', 'type',
        ]);

        // Remove empty finish_date — fall back to create_date
        if (empty($fields['finish_date']) && !empty($fields['create_date'])) {
            $fields['finish_date'] = $fields['create_date'];
        }

        $oldRabatt = (float) ($offert->default_rabatt ?? 0);
        $oldMaterial = (float) ($offert->material ?? 0);

        if (!$this->hasDefaultRabattColumn()) {
            unset($fields['default_rabatt']);
        }

        if (empty($fields['client_address']) && !empty($fields['client_id'])) {
            $fields['client_address'] = Client::where('id', $fields['client_id'])->value('address');
        }

        $offert->update(array_filter($fields, fn($v) => $v !== null && $v !== ''));

        if ($this->hasDefaultRabattColumn() && isset($fields['default_rabatt']) && $fields['default_rabatt'] !== null) {
            $newRabatt = (float) $fields['default_rabatt'];
            if ($oldRabatt !== $newRabatt) {
                foreach ($offert->positions as $position) {
                    $position->update([
                        'discount'       => $newRabatt,
                        'price_discount' => round($position->price_brutto * (1 - $newRabatt / 100), 2),
                    ]);
                }
            }
        }

        // Schw.Koeffizient (difficulty) intentionally does NOT propagate to existing
        // positions — each position keeps its own value; new positions inherit from the offert.
        if (isset($fields['material']) && $fields['material'] !== null && $fields['material'] !== '') {
            $newMaterial = (float) $fields['material'];
            if ($oldMaterial !== $newMaterial) {
                $this->recomputePositionsForMaterialCoefficient($offert->fresh('positions'));
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Manual action triggered by the "Alle überschreiben" button next to
     * Schw. Koeffizient — copies the offert's current difficulty onto every
     * position and refreshes the stored price snapshots.
     */
    public function overrideDifficultyAll(string $id)
    {
        $offert = Offert::find($id);
        if (!$offert) {
            return response()->json(['success' => false, 'message' => 'Offert not found'], 404);
        }

        $newDifficulty = (float) ($offert->difficulty ?: 1);
        foreach ($offert->positions as $position) {
            $position->update(['difficulty' => $newDifficulty]);
        }

        $this->recomputePositionsForMaterialCoefficient($offert->fresh('positions'));

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $offert = Offert::find($id);
        $positions = $offert->positions;
        $offert->delete();

        foreach ($positions as $position) {
            $position->delete();
        }

        return redirect()->route('offert.index');
    }

    public function lock(string $id)
    {
        $offert = Offert::find($id);
        if (! $offert) {
            return response()->json(['success' => false], 404);
        }
        if ($offert->isLockedByOther()) {
            return response()->json([
                'success' => false,
                'locked_by' => $offert->lockingUser?->username ?? 'another user',
            ], 423);
        }
        $offert->acquireLock();

        return response()->json(['success' => true]);
    }

    public function unlock(string $id)
    {
        $offert = Offert::find($id);
        if ($offert) {
            $offert->releaseLock();
        }

        return response()->json(['success' => true]);
    }
}
