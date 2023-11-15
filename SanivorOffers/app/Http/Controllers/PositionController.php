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
        $totalProTypPrice = $request->input('totalProTypPrice');
        $discountedTotal = $request->input('discountedTotal');
        $percentage = $request->input('percentage');
        $elementIds = $request->input('selected_elements');
        $groupElementIds = $request->input('selected_group_elements');
        $organigramIds = $request->input('selected_organigrams');

        $formFields = [
            'price_brutto' => $totalProTypPrice,
            'price_discount' => $discountedTotal,
            'discount' => $percentage,
            'costo' => '0',
            'profit' => '0',
            'total' => '0',
        ];

        // Increment position_number for the new Position
        $latestPosition = $latestOffert ? $latestOffert->positions()->latest()->first() : null;
        $formFields['position_number'] = $latestPosition ? $latestPosition->position_number + 1 : 1;

        $position = Position::create($formFields);
        if ($latestOffert) {
            $position->offerts()->attach($latestOffert);
        }
        $position->elements()->attach($elementIds);
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
        $elements = Element::get();

        return view('position.edit', compact('position', 'materials', 'organigrams', 'elements'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $position = Position::find($id);
        $totalProTypPrice = $request->input('totalProTypPrice');
        $discountedTotal = $request->input('discountedTotal');
        $percentage = $request->input('percentage');

        $formFields = [
            'price_brutto' => $totalProTypPrice,
            'price_discount' => $discountedTotal,
            'discount' => $percentage,
            'costo' => '0',
            'profit' => '0',
            'total' => '0',
        ];
        $position = Position::find($id);
        $position->update($formFields);

        $selectedOrganigramIds = $request->input('selected_organigrams', []);
        $selectedGroupElementIds = $request->input('selected_group_elements', []);
        $selectedElementIds = $request->input('selected_elements', []);

        $position->organigrams()->sync($selectedOrganigramIds);
        $position->group_elements()->sync($selectedGroupElementIds);
        $position->elements()->sync($selectedElementIds);
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
