<?php

namespace App\Http\Controllers;

use App\Models\Offert;
use App\Models\Element;
use App\Models\Material;
use App\Models\Position;
use App\Models\Organigram;
use Illuminate\Http\Request;
use App\Models\PositionMaterial;

class PositionController extends Controller
{
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

        foreach ($position->elements()->withPivot('quantity')->get() as $element) {
            $newElement = $newPosition->elements()->attach($element->id, ['quantity' => $element->pivot->quantity]);
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
        $offert = Offert::find($offertId);
        $positions = Position::whereHas('offerts', function ($query) use ($offertId) {
            $query->where('id', $offertId);
        })->orderBy('position_number', 'ASC')->get();

        $materials = Material::get();
        $organigrams = Organigram::get();
        $elements = Element::get();

        return view('position.create', compact('positions', 'materials', 'organigrams', 'elements', 'index','offert'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $latestOffert = Offert::where('user_id', $user->id)->latest()->first();
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
        ];

        // Increment position_number for the new Position
        $latestPosition = $latestOffert ? $latestOffert->positions()->latest()->first() : null;
        $formFields['position_number'] = $latestPosition ? $latestPosition->position_number + 1 : 1;

        $position = Position::create($formFields);

        $groupElementIds = $request->input('selected_group_elements', []);
        $organigramIds = $request->input('selected_organigrams', []);
        $selectedElementIds = $request->input('selected_elements', []);
        $elementIdsWithQuantities = $request->input('element_quantity', []);

        $elements = Element::all();
        foreach ($elementIdsWithQuantities as $elementId => $quantity) {
            if (in_array($elementId, $selectedElementIds)) {
                $position->elements()->attach([$elementId => ['quantity' => $quantity]]);

                // Store material_id, element_id, and quantity in the new table
                foreach ($elements->find($elementId)->materials as $material) {
                    $materialQuantityKey = "material_quantity.{$elementId}.{$material->id}";
                    $materialQuantity = $request->input($materialQuantityKey, $material->pivot->quantity);
                    PositionMaterial::create([
                        'position_id' => $position->id,
                        'element_id' => $elementId,
                        'material_id' => $material->id,
                        'quantity' => $materialQuantity,
                    ]);
                }
            }
        }

        $position->offerts()->attach($latestOffert);
        $position->group_elements()->attach($groupElementIds);
        $position->organigrams()->attach($organigramIds);

        // return redirect()->route('position.create', ['offert_id' => $latestOffert ? $latestOffert->id : null]);
        return redirect()->route('position.create', ['index' => $request->input('index'), 'offert_id' => $latestOffert ? $latestOffert->id : null]);
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
        // $offertId = $request->input('offert_id');
        $position = Position::find($id);
        $offertId = $position->offerts()->first()->id;
        $offert = Offert::find($offertId);

        $positions = Position::whereHas('offerts', function ($query) use ($offertId) {
            $query->where('id', $offertId);
        })->orderBy('position_number', 'ASC')->get();

        $materials = Material::get();
        $organigrams = Organigram::get();
        $elements = Element::with(['positions' => function ($query) use ($id) {
            $query->where('position_id', $id);
        }])->get();

        $positionMaterials = PositionMaterial::where('position_id', $id)->get();

        return view('position.edit', compact('positions', 'offertId', 'position', 'materials', 'organigrams', 'elements', 'positionMaterials','offert'));
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
        ];
        $position = Position::find($id);
        $position->update($formFields);

        $selectedOrganigramIds = $request->input('selected_organigrams', []);
        $selectedGroupElementIds = $request->input('selected_group_elements', []);

        $selectedElementIds = $request->input('selected_elements', []);
        $elementIdsWithQuantities = $request->input('element_quantity', []);

        // Detach existing elements for the position
        $position->elements()->detach();

        foreach ($elementIdsWithQuantities as $elementId => $quantity) {
            if (in_array($elementId, $selectedElementIds)) {
                $position->elements()->attach([$elementId => ['quantity' => $quantity]]);

                // Store material_id, element_id, and quantity in the new table
                $elements = Element::find($elementId);
                foreach ($elements->materials as $material) {
                    $materialQuantityKey = "material_quantity.{$elementId}.{$material->id}";
                    $materialQuantity = $request->input($materialQuantityKey, $material->pivot->quantity);

                    // Check if the record exists
                    $existingRecord = PositionMaterial::where([
                        'position_id' => $position->id,
                        'element_id' => $elementId,
                        'material_id' => $material->id
                    ])->first();

                    if ($existingRecord) {
                        // Update the existing record
                        PositionMaterial::where([
                            'position_id' => $position->id,
                            'element_id' => $elementId,
                            'material_id' => $material->id
                        ])->update(['quantity' => $materialQuantity]);
                    } else {
                        // Create a new record
                        PositionMaterial::create([
                            'position_id' => $position->id,
                            'element_id' => $elementId,
                            'material_id' => $material->id,
                            'quantity' => $materialQuantity,
                        ]);
                    }
                }
            }
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
        $offertId = $position->offerts()->first()->id;
        $position->delete();

        // Redirect to the latest position related to the offert_id
        $latestPosition = Position::whereHas('offerts', function ($query) use ($offertId) {
            $query->where('id', $offertId);
        })->latest()->first();

        if ($latestPosition) {
            return redirect()->route('position.edit', $latestPosition->id);
        } else {
            return redirect()->route('offert.index');
        }
    }

    public function updateOrder(Request $request)
    {
        $positionId = $request->input('position_id');
        $newOrder = $request->input('order');

        Position::where('id', $positionId)->update(['position_number' => $newOrder]);

        return response()->json(['success' => true]);
    }

    /**
     * Auto-save position for specific type
     */
    public function autoSave(Request $request)
    {
        try {
            $data = $request->all();
            $offertId = $data['offert_id'];
            $offert = Offert::find($offertId);
            
            if (!$offert) {
                return response()->json(['success' => false, 'message' => 'Offert not found'], 404);
            }

            // Check if position already exists for this type
            $existingPosition = Position::whereHas('offerts', function ($query) use ($offertId) {
                $query->where('id', $offertId);
            })
            ->where('description', $data['description'] ?? '')
            ->where('blocktype', $data['blocktype'] ?? null)
            ->first();

            if ($existingPosition) {
                // Update existing position
                $position = $existingPosition;
                $position->update([
                    'description' => $data['description'] ?? '',
                    'description2' => $data['description2'] ?? '',
                    'blocktype' => $data['blocktype'] ?? null,
                    'b' => $data['b'] ?? null,
                    'h' => $data['h'] ?? null,
                    't' => $data['t'] ?? null,
                    'quantity' => $data['quantity'] ?? 1,
                ]);
            } else {
                // Create new position
                $latestPosition = $offert->positions()->latest()->first();
                $positionNumber = $latestPosition ? $latestPosition->position_number + 1 : 1;

                $position = Position::create([
                    'description' => $data['description'] ?? '',
                    'description2' => $data['description2'] ?? '',
                    'blocktype' => $data['blocktype'] ?? null,
                    'b' => $data['b'] ?? null,
                    'h' => $data['h'] ?? null,
                    't' => $data['t'] ?? null,
                    'quantity' => $data['quantity'] ?? 1,
                    'position_number' => $positionNumber,
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
                ]);

                $position->offerts()->attach($offert);
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
                foreach ($data['selected_elements'] as $elementId) {
                    $quantity = $data['element_quantity'][$elementId] ?? 1;
                    $elementsToAttach[$elementId] = ['quantity' => $quantity];
                }
                $position->elements()->sync($elementsToAttach);

                // Update PositionMaterial records
                if (isset($data['material_quantity'])) {
                    foreach ($data['selected_elements'] as $elementId) {
                        if (isset($data['material_quantity'][$elementId])) {
                            $element = Element::find($elementId);
                            if ($element) {
                                foreach ($element->materials as $material) {
                                    $materialQuantity = $data['material_quantity'][$elementId][$material->id] ?? $material->pivot->quantity;
                                    
                                    PositionMaterial::updateOrCreate(
                                        [
                                            'position_id' => $position->id,
                                            'element_id' => $elementId,
                                            'material_id' => $material->id,
                                        ],
                                        ['quantity' => $materialQuantity]
                                    );
                                }
                            }
                        }
                    }
                }
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
