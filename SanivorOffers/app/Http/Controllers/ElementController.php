<?php

namespace App\Http\Controllers;

use App\Models\Element;
use App\Models\Material;
use Illuminate\Http\Request;

class ElementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('query');

        $elements = Element::where('name', 'like', '%' . $query . '%')->orderBy('id', 'DESC')->paginate(10);

        return view('element.index', compact('elements', 'query'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $materials = Material::all();
        return view('element.create', compact('materials'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $materials = $request->input('materials');

        $elements = new Element();
        $elements->name = $request->input('name');
        $elements->save();
        $elements->materials()->attach($materials);

        return redirect()->route('element.index');
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
        $element = Element::find($id);
        $materials = Material::all();
        return view('element.edit', compact('element', 'materials'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $element = Element::find($id);
        $element->name = $request->input('name');
        $element->save();

        $element->materials()->sync($request->input('materials'));

        return redirect()->route('element.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $element = Element::find($id);
        $element->delete();
        return redirect()->route('element.index');
    }
}
