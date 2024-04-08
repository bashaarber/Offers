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
}
