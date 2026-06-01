<?php

namespace App\Http\Controllers;

use App\Models\Element;
use App\Models\Material;
use App\Support\ListFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class ElementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Element::with('materials');

        ListFilter::apply($query, $request, [
            'id'        => 'elements.id',
            'name'      => 'elements.name',
            'materials' => ['relation' => 'materials', 'column' => 'name'],
        ]);

        $elements = $query->orderBy('id', 'ASC')->paginate(50)->withQueryString();

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

        $hasSortOrder = Schema::hasColumn('element_material', 'sort_order');
        $order = 0;
        foreach ($materials as $key => $materialId) {
            if (empty($materialId)) {
                continue;
            }
            $pivot = ['quantity' => $quantities[$key] ?? 1];
            if ($hasSortOrder) {
                $pivot['sort_order'] = $order;
            }
            $element->materials()->attach($materialId, $pivot);
            $order++;
        }

        Cache::forget('elements_with_materials');
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

        $hasSortOrder = Schema::hasColumn('element_material', 'sort_order');
        $syncData = [];
        $order = 0;
        foreach ($materials as $key => $materialId) {
            if (empty($materialId)) {
                continue;
            }
            $pivot = ['quantity' => $quantities[$key] ?? 1];
            if ($hasSortOrder) {
                $pivot['sort_order'] = $order;
            }
            $syncData[$materialId] = $pivot;
            $order++;
        }

        $element->materials()->sync($syncData);

        Cache::forget('elements_with_materials');
        return redirect()->route('element.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $element = Element::find($id);
        $element->delete();
        Cache::forget('elements_with_materials');
        return redirect()->route('element.index');
    }
}
