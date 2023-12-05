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
        $material_pieces = MaterialPiece::all();
        return view('material.create', compact('material_pieces'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name' => 'required',
            'unit' => 'required',
            'price_in' => 'required',
            'price_out' => 'required',
            'z_schlosserei' => 'required',
            'z_pe' => 'required',
            'z_montage' => 'required',
            'z_fermacell' => 'required',
        ]);
        $z_total = $request->input('z_schlosserei') + $request->input('z_pe') + $request->input('z_montage') + $request->input('z_fermacell');
        $materials = new Material();
        $materials->z_total = $z_total;
        
        $coefficient = Coefficient::first();
        $zeit_cost = $z_total * $coefficient->labor_price;
        $materials->zeit_cost = $zeit_cost;
        $formFields['total'] = $request->input('price_out') + $zeit_cost;
        $materials->fill($formFields);
        $materials->save();
        
        $materials->material_pieces()->attach($request->input('materials'));

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
        return view('material.edit', compact('material', 'material_pieces'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $formFields = $request->validate([
            'name' => 'required',
            'unit' => 'required',
            'price_in' => 'required',
            'price_out' => 'required',
            'z_schlosserei' => 'required',
            'z_pe' => 'required',
            'z_montage' => 'required',
            'z_fermacell' => 'required',
        ]);
        $material = Material::find($id);

        $z_total = $request->input('z_schlosserei') + $request->input('z_pe') + $request->input('z_montage') + $request->input('z_fermacell');
        $material->z_total = $z_total;
        
        $coefficient = Coefficient::first();
        $zeit_cost = $z_total * $coefficient->labor_price;
        $material->zeit_cost = $zeit_cost;
        $formFields['total'] = $request->input('price_out') + $zeit_cost;
        $material->fill($formFields);
        $material->update();

        $material->material_pieces()->sync($request->input('added-materials'));

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
