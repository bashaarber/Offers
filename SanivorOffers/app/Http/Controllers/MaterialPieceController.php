<?php

namespace App\Http\Controllers;

use App\Models\MaterialPiece;
use App\Support\ListFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MaterialPieceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MaterialPiece::query();

        ListFilter::apply($query, $request, [
            'id'        => 'id',
            'name'      => 'name',
            'price_in'  => 'price_in',
            'price_out' => 'price_out',
        ]);

        $materials = $query->orderBy('id', 'ASC')->paginate(50)->withQueryString();

        return view('material_piece.index', compact('materials'));
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
        ]);
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
        ]);
        $material = MaterialPiece::find($id);

        // Propagate price changes to every parent Material that uses this piece.
        // Material price_in/price_out is the plain sum of its attached pieces;
        // total mirrors price_out (see MaterialController). Wrapped in a
        // transaction so a piece edit can't leave parents half-updated.
        DB::transaction(function () use ($material, $formFields) {
            $material->update($formFields);

            foreach ($material->materials as $parent) {
                $parent->loadMissing('material_pieces');
                $price_in  = (float) $parent->material_pieces->sum('price_in');
                $price_out = (float) $parent->material_pieces->sum('price_out');
                $parent->update([
                    'price_in'  => $price_in,
                    'price_out' => $price_out,
                    'total'     => $price_out,
                ]);
            }
        });

        // New offers read element/material prices from this cache; clear it so a
        // price change is reflected immediately (existing offers keep their snapshot).
        Cache::forget('elements_with_materials');

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok', 'material' => $material]);
        }

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
