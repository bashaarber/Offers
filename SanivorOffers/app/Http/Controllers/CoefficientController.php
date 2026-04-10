<?php

namespace App\Http\Controllers;

use App\Models\Coefficient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CoefficientController extends Controller
{
    private function hasDefaultRabattColumn(): bool
    {
        static $hasColumn = null;
        if ($hasColumn === null) {
            $hasColumn = Schema::hasColumn('coefficients', 'default_rabatt');
        }

        return $hasColumn;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $coefficients = Coefficient::all();

        return view('coefficient.index', compact('coefficients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $formFields = $request->validate([
            'validity' => 'required',
            'labor_cost' => 'required',
            'labor_price' => 'required',
            'service' => 'required',
            'material' => 'required',
            'difficulty' => 'required',
            'payment_conditions' => 'required',
            'default_rabatt' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($this->hasDefaultRabattColumn()) {
            $formFields['default_rabatt'] = $request->input('default_rabatt', 20);
        } else {
            unset($formFields['default_rabatt']);
        }

        $coefficient = Coefficient::find($id);

        $coefficient->update($formFields);

        return redirect()->route('coefficient.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
