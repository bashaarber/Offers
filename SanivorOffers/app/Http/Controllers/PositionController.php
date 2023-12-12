<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Element;
use App\Models\Material;
use App\Models\Offert;
use App\Models\Organigram;
use App\Models\Position;
use Illuminate\Http\Request;

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

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $materials = Material::get();
        $organigrams = Organigram::get();
        $elements = Element::get();

        return view('position.create', compact('materials', 'organigrams', 'elements'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $latestOffert = Offert::where('user_id', $user->id)->latest()->first();
        $description = $request->input('description');
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

        foreach ($elementIdsWithQuantities as $elementId => $quantity) {
            if (in_array($elementId, $selectedElementIds)) {
                $position->elements()->attach([$elementId => ['quantity' => $quantity]]);
            }
        }

        $position->offerts()->attach($latestOffert);
        $position->group_elements()->attach($groupElementIds);
        $position->organigrams()->attach($organigramIds);

        return redirect()->route('position.index', ['offert_id' => $latestOffert ? $latestOffert->id : null]);
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
    public function edit(string $id)
    {
        $position = Position::find($id);
        $materials = Material::get();
        $organigrams = Organigram::get();
        $elements = Element::with(['positions' => function ($query) use ($id) {
            $query->where('position_id', $id);
        }])->get();

        return view('position.edit', compact('position', 'materials', 'organigrams', 'elements'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $description = $request->input('description');
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
            }
        }

        $position->organigrams()->sync($selectedOrganigramIds);
        $position->group_elements()->sync($selectedGroupElementIds);

        $offertId = $position->offerts->first()->id;

        return redirect()->route('position.index', ['offert_id' => $offertId]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $position = Position::find($id);
        $position->delete();

        return redirect()->route('offert.index');
    }


    public function updateOrder(Request $request)
    {
        $positionId = $request->input('position_id');
        $newOrder = $request->input('order');

        // Update the position_number in the database
        Position::where('id', $positionId)->update(['position_number' => $newOrder]);

        return response()->json(['success' => true]);
    }
}
