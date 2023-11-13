<?php

namespace App\Http\Controllers;

use App\Models\MaterialPiece;
use Illuminate\Http\Request;

class MaterialPieceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('query');

        $materials = MaterialPiece::where('name', 'like', '%' . $query . '%')->orderBy('id', 'DESC')->paginate(10);

        return view('material_piece.index', compact('materials', 'query'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('material_piece.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name' => 'required',
            'price_in' => 'required',
            'price_out' => 'required',
            'z_schlosserei' => 'required',
            'z_pe' => 'required',
            'z_montage' => 'required',
            'z_fermacell' => 'required',
        ]);
        $formFields['total'] = $request->input('z_schlosserei') + $request->input('z_pe') + $request->input('z_montage') + $request->input('z_fermacell');
        MaterialPiece::create($formFields);

        return redirect()->route('material_piece.index');
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
        $material = MaterialPiece::find($id);
        return view('material_piece.edit', compact('material'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $formFields = $request->validate([
            'name' => 'required',
            'price_in' => 'required',
            'price_out' => 'required',
            'z_schlosserei' => 'required',
            'z_pe' => 'required',
            'z_montage' => 'required',
            'z_fermacell' => 'required',
        ]);
        $material = MaterialPiece::find($id);
        $formFields['total'] = $request->input('z_schlosserei') + $request->input('z_pe') + $request->input('z_montage') + $request->input('z_fermacell');
        $material->update($formFields);

        return redirect()->route('material_piece.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $material = MaterialPiece::find($id);
        $material->delete();

        return redirect()->route('material_piece.index');
    }
}
