<?php

namespace App\Http\Controllers;

use App\Models\Coefficient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

class CoefficientController extends Controller
{
    private function hasDefaultRabattColumn(): bool
    {
        return Cache::remember('schema_coefficients_has_default_rabatt', 86400, function () {
            return Schema::hasColumn('coefficients', 'default_rabatt');
        });
    }

    private function hasDefaultUnsereReferenzColumn(): bool
    {
        return Cache::remember('schema_coefficients_has_default_unsere_referenz', 86400, function () {
            return Schema::hasColumn('coefficients', 'default_unsere_referenz');
        });
    }

    private function hasPdfExternalClosingTextColumn(): bool
    {
        return Cache::remember('schema_coefficients_has_pdf_closing_text', 86400, function () {
            return Schema::hasColumn('coefficients', 'pdf_external_closing_text');
        });
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
            'default_unsere_referenz' => 'nullable|string|max:255',
            'pdf_external_closing_text' => 'nullable|string|max:65000',
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

        if ($this->hasDefaultUnsereReferenzColumn()) {
            $payload['default_unsere_referenz'] = $request->input('default_unsere_referenz');
        }

        if ($this->hasPdfExternalClosingTextColumn()) {
            $payload['pdf_external_closing_text'] = $request->input('pdf_external_closing_text');
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
