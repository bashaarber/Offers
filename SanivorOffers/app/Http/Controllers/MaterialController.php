<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Coefficient;
use Illuminate\Http\Request;
use App\Models\MaterialPiece;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $query = $request->input('query');

        $materials = Material::where('name', 'like', '%' . $query . '%')->orderBy('id', 'DESC')->paginate(10);

        return view('material.index', compact('materials', 'query'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $materialPieces = MaterialPiece::all();
        return view('material.create', compact('materialPieces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name' => 'required',
            'unit' => 'required',
            'z_schlosserei' => 'required',
            'z_pe' => 'required',
            'z_montage' => 'required',
            'z_fermacell' => 'required',
        ]);

        $z_total = $request->input('z_schlosserei') + $request->input('z_pe') + $request->input('z_montage') + $request->input('z_fermacell');
        $coefficient = Coefficient::first();
        $zeit_cost = $z_total * $coefficient->labor_price;

        $selectedMaterialPieces = $request->input('materials');
        $price_in = 0;
        $price_out = 0;

        foreach ($selectedMaterialPieces as $materialPieceId) {
            $materialPiece = MaterialPiece::find($materialPieceId);
            if ($materialPiece) {
                $price_in += $materialPiece->price_in;
                $price_out += $materialPiece->price_out;
            }
        }
        $total = $price_out + $zeit_cost;

        $materials = new Material();
        $materials->fill($formFields);
        $materials->z_total = $z_total;
        $materials->zeit_cost = $zeit_cost;
        $materials->price_in = $price_in;
        $materials->price_out = $price_out;
        $materials->total = $total;
        $materials->save();

        $materials->material_pieces()->attach($selectedMaterialPieces);

        return redirect()->route('material.index');
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
        $material = Material::find($id);
        $material_pieces = MaterialPiece::all();
        $selectedMaterials = $material->material_pieces->pluck('id')->toArray();

        return view('material.edit', compact('material', 'material_pieces', 'selectedMaterials'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $formFields = $request->validate([
            'name' => 'required',
            'unit' => 'required',
            'z_schlosserei' => 'required',
            'z_pe' => 'required',
            'z_montage' => 'required',
            'z_fermacell' => 'required',
        ]);

        $material = Material::find($id);

        $z_total = $request->input('z_schlosserei') + $request->input('z_pe') + $request->input('z_montage') + $request->input('z_fermacell');

        $coefficient = Coefficient::first();
        $zeit_cost = $z_total * $coefficient->labor_price;

        $selectedMaterialPieces = $request->input('materials');
        $price_in = 0;
        $price_out = 0;

        foreach ($selectedMaterialPieces as $materialPieceId) {
            $materialPiece = MaterialPiece::find($materialPieceId);
            if ($materialPiece) {
                $price_in += $materialPiece->price_in;
                $price_out += $materialPiece->price_out;
            }
        }
        $total = $price_out + $zeit_cost;

        // Update the existing material instance
        $material->update([
            'name' => $formFields['name'],
            'unit' => $formFields['unit'],
            'z_schlosserei' => $formFields['z_schlosserei'],
            'z_pe' => $formFields['z_pe'],
            'z_montage' => $formFields['z_montage'],
            'z_fermacell' => $formFields['z_fermacell'],
            'z_total' => $z_total,
            'zeit_cost' => $zeit_cost,
            'price_in' => $price_in,
            'price_out' => $price_out,
            'total' => $total,
        ]);

        // Attach the selected material_pieces without detaching existing ones
        $material->material_pieces()->sync($selectedMaterialPieces);

        return redirect()->route('material.index');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $material = Material::find($id);
        $material->delete();
        return redirect()->route('material.index');
    }
}
