<?php

namespace App\Http\Controllers;

use App\Models\Element;
use App\Models\GroupElement;
use Illuminate\Http\Request;

class GroupElementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('query');

        $group_elements = GroupElement::where('name', 'like', '%' . $query . '%')->orderBy('id', 'DESC')->paginate(10);

        return view('group_element.index', compact('group_elements', 'query'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $elements = Element::all();
        return view('group_element.create', compact('elements'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $elements = $request->input('elements');

        $group_elements = new GroupElement();
        $group_elements->name = $request->input('name');
        $group_elements->save();
        $group_elements->elements()->attach($elements);

        return redirect()->route('group_element.index');
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
    public function edit(Request $request,string $id)
    {
        $group_element = GroupElement::find($id);
        $elements = Element::all();
        return view('group_element.edit', compact('group_element', 'elements'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $group_element = GroupElement::find($id);
        $group_element->name = $request->input('name');
        $group_element->save();

        $group_element->elements()->sync($request->input('elements'));

        return redirect()->route('group_element.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $group_element = GroupElement::find($id);
        $group_element->delete();
        return redirect()->route('group_element.index');
    }
}
