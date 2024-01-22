<?php

namespace App\Http\Controllers;

use App\Models\GroupElement;
use App\Models\Organigram;
use Illuminate\Http\Request;

class OrganigramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('query');

        $organigrams = Organigram::where('name', 'like', '%' . $query . '%')->orderBy('id', 'ASC')->paginate(10);

        return view('organigram.index', compact('organigrams', 'query'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $group_elements = GroupElement::all();
        return view('organigram.create', compact('group_elements'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $group_elements = $request->input('materials');

        $organigrams = new Organigram();
        $organigrams->name = $request->input('name');
        $organigrams->save();
        $organigrams->group_elements()->attach($group_elements);

        return redirect()->route('organigram.index');
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
        $organigram = Organigram::find($id);
        $group_elements = GroupElement::all();
        $selectedGroupElements = $organigram->group_elements->pluck('id')->toArray();

        return view('organigram.edit', compact('organigram', 'group_elements', 'selectedGroupElements'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $organigram = Organigram::find($id);
        $organigram->name = $request->input('name');
        $organigram->save();

        $organigram->group_elements()->sync($request->input('materials'));

        return redirect()->route('organigram.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $organigram = Organigram::find($id);
        $organigram->delete();
        return redirect()->route('organigram.index');
    }
}
