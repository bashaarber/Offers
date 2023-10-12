<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materials = Material::all();
        return view('material.index', compact('materials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('material.create');
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
            'total' => 'required',
        ]);

        Material::create($formFields);

        return redirect()->route('material.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $material = Material::find($id);
        return view('material.show', compact('material'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $material = Material::find($id);
        return view('material.edit', compact('material'));
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
            'total' => 'required',
        ]);
        $material = Material::find($id);

        $material->update($formFields);

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
