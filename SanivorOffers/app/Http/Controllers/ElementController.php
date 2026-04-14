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
    public function index()
    {
        $elements = Element::with('materials')->orderBy('id', 'ASC')->paginate(50);

        return view('element.index', compact('elements'));
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
            'materials' => 'array',
            'quantities' => 'array',
        ]);

        $element = new Element();
        $element->name = $request->input('name');
        $element->save();

        $materials = $request->input('materials', []);
        $quantities = $request->input('quantities', []);

        foreach ($materials as $key => $materialId) {
            if (empty($materialId)) {
                continue;
            }
            $quantity = $quantities[$key] ?? 1;
            $element->materials()->attach($materialId, ['quantity' => $quantity]);
        }

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

        $materials = $request->input('materials', []);
        $quantities = $request->input('quantities', []);

        $syncData = [];
        foreach ($materials as $key => $materialId) {
            if (empty($materialId)) {
                continue;
            }
            $quantity = $quantities[$key] ?? 1;
            $syncData[$materialId] = ['quantity' => $quantity];
        }

        $element->materials()->sync($syncData);

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
