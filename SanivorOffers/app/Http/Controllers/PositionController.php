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
        })->get();

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
        $latestOffert = Offert::where('user_id', auth()->user()->id)->latest()->first();
        $totalProTypPrice = $request->input('totalProTypPrice');
        $discountedTotal = $request->input('discountedTotal');
        $percentage = $request->input('percentage');
        $elementIds = $request->input('selected_elements');

        $formFields = [
            'price_brutto' => $totalProTypPrice,
            'price_discount' => $discountedTotal,
            'discount' => $percentage,
            'costo' => '0',
            'profit' => '0',
            'total' => '0',
        ];

        $position = Position::create($formFields);
        $position->offerts()->attach($latestOffert);
        $position->elements()->attach($elementIds);

        return redirect()->route('position.create',compact('latestOffert'));
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
        $totalProTypPrice = $request->input('totalProTypPrice');
        $discountedTotal = $request->input('discountedTotal');
        $percentage = $request->input('percentage');
        $elementIds = $request->input('selected_elements');

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

        $position->elements()->sync($elementIds);

        return redirect()->route('offert.index');
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
}
