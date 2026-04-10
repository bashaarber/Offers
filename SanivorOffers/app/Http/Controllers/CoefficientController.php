<?php

namespace App\Http\Controllers;

use App\Models\Coefficient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

    private function hasDefaultSignatureColumn(): bool
    {
        static $hasColumn = null;
        if ($hasColumn === null) {
            $hasColumn = Schema::hasColumn('coefficients', 'default_signature');
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
            'default_signature' => 'nullable|string|max:255',
        ]);

        $payload = [
            'validity' => $formFields['validity'],
            'labor_cost' => $formFields['labor_cost'],
            'labor_price' => $formFields['labor_price'],
            'service' => $formFields['service'],
            'material' => $formFields['material'],
            'difficulty' => $formFields['difficulty'],
            'payment_conditions' => $formFields['payment_conditions'],
        ];

        if ($this->hasDefaultRabattColumn()) {
            $payload['default_rabatt'] = $request->input('default_rabatt', 20);
        }

        if ($this->hasDefaultSignatureColumn()) {
            $payload['default_signature'] = $request->input('default_signature', 'Arber Basha');
        }

        $coefficient = Coefficient::findOrFail($id);

        try {
            $coefficient->update($payload);
        } catch (\Throwable $e) {
            // Production safety: if schema is behind, update core fields instead of 500.
            Log::error('Coefficient update failed, retrying with core fields only', [
                'coefficient_id' => $id,
                'message' => $e->getMessage(),
            ]);
            $coefficient->update([
                'validity' => $payload['validity'],
                'labor_cost' => $payload['labor_cost'],
                'labor_price' => $payload['labor_price'],
                'service' => $payload['service'],
                'material' => $payload['material'],
                'difficulty' => $payload['difficulty'],
                'payment_conditions' => $payload['payment_conditions'],
            ]);
        }

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
