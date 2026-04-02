<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Offert;
use App\Models\Coefficient;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PositionMaterial;
use Illuminate\Support\Facades\Log;

class OffertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('query');
        $status = $request->input('status');

    if ($status) {
        $offerts = Offert::where('status', $status)
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('offert.index', compact('offerts', 'query', 'status'));
    }
        $offerts = Offert::where('id', 'like', '%' . $query . '%')
        ->orWhere('client_sign', 'like', '%' . $query . '%')
        ->orderBy('id', 'DESC')
        ->paginate(10);

        return view('offert.index', compact('offerts', 'query'));
    }

    public function exportPdf($id){
        try {
            $offert = Offert::find($id);
            if (!$offert) {
                abort(404, 'Offert #' . $id . ' not found.');
            }

            $offert->load([
                'client',
                'positions' => function ($query) {
                    $query->orderBy('position_number', 'ASC');
                },
                'positions.elementsForPdf.group_elements.organigrams',
            ]);

            $pdf = Pdf::loadView('offert.offert-pdf-export', compact('offert'));
            return $pdf->stream();
        } catch (\Throwable $e) {
            Log::error('External PDF generation failed', [
                'offert_id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response('PDF generation failed: ' . $e->getMessage(), 500);
        }
        // return $pdf->download('invoice.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $latestOffert = Offert::latest()->first();
        $newOffertId = $latestOffert ? $latestOffert->id + 1 : 1;
        $users = User::all();
        $clients = Client::all();
        $coefficients = Coefficient::get();

        return view('offert.create', compact('newOffertId', 'users', 'clients', 'coefficients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $formFields = $request->validate([
            'user_sign' => 'required',
            'status' => 'required',
            'create_date' => 'required',
            'validity' => 'required',
            'client_sign' => 'required',
            'finish_date' => 'nullable',
            'object' => 'required',
            'city' => 'required',
            'service' => 'required',
            'payment_conditions' => 'required',
            'difficulty' => 'required',
            'material' => 'required',
            'labor_price' => 'required',
            'client_id' => 'required|exists:clients,id',
        ]);
        $formFields['type'] = $request->input('type');
        $formFields['user_id'] = $user->id;

        $offert = Offert::create($formFields);

        return redirect()->route('position.create', ['index' => 0,'offert_id' => $offert->id]);
    }

    /**
     * Display the specified resource.
     */

    public function show(string $id)
    {
        $offert = Offert::find($id);

        // Order positions by position_number
        $offert->load(['positions' => function ($query) {
            $query->orderBy('position_number', 'ASC');
        }]);

        // Redirect to first position edit if positions exist, otherwise to create
        $firstPosition = $offert->positions->first();
        if ($firstPosition) {
            return redirect()->route('position.edit', $firstPosition->id);
        } else {
            return redirect()->route('position.create', ['index' => 0, 'offert_id' => $offert->id]);
        }
    }

    public function copy($offert_id)
    {
        $user = auth()->user();
        $timestamp = time();
        $currentDate = gmdate('Y-m-d', $timestamp);
        $offert = Offert::findOrFail($offert_id);

        $new_offert = $offert->replicate()->fill([
            'user_id' => $user->id,
            'create_date' => $currentDate,
        ]);

        $new_offert->save();

    // Copy positions
    foreach ($offert->positions as $position) {
        $new_position = $position->replicate();
        $new_position->save();

        // Attach the new position to the new Offert
        $new_offert->positions()->attach($new_position->id);

         // Copy the values from PositionMaterial table based on the previous position IDs
         $positionMaterials = PositionMaterial::where('position_id', $position->id)->get();
         foreach ($positionMaterials as $material) {
             // Check if a record with the same element_id, material_id, and position_id already exists
             $existingRecord = PositionMaterial::where([
                 'element_id' => $material->element_id,
                 'material_id' => $material->material_id,
                 'position_id' => $new_position->id,
             ])->first();
 
             // If no record exists, create a new one
             if (!$existingRecord) {
                 PositionMaterial::create([
                     'position_id' => $new_position->id,
                     'element_id' => $material->element_id,
                     'material_id' => $material->material_id,
                     'quantity' => $material->quantity,
                 ]);
             }
         }

        // Attach the existing relationships to the new position
        foreach ($position->organigrams as $organigram) {
            $new_position->organigrams()->attach($organigram->id);
        }

        foreach ($position->group_elements as $group_element) {
            $new_position->group_elements()->attach($group_element->id);
        }

        foreach ($position->elements as $element) {
            $new_position->elements()->attach($element->id, ['quantity' => $element->pivot->quantity]);
        }
    }
        return redirect()->route('offert.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $offert = Offert::find($id);
        $clients = Client::all();

        return view('offert.edit', compact('offert', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = auth()->user();

        $formFields = $request->validate([
            'user_sign' => 'required',
            'status' => 'required',
            'create_date' => 'required',
            'validity' => 'required',
            'client_sign' => 'required',
            'finish_date' => 'nullable',
            'object' => 'required',
            'city' => 'required',
            'service' => 'required',
            'payment_conditions' => 'required',
            'difficulty' => 'required',
            'material' => 'required',
            'labor_price' => 'required',
            'client_id' => 'required|exists:clients,id',
        ]);

        $formFields['type'] = $request->input('type');
        $formFields['user_id'] = $user->id;
        $offert = Offert::find($id);
        $offert->update($formFields);

        return redirect()->route('offert.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $offert = Offert::find($id);
        $positions = $offert->positions;
        $offert->delete();

        foreach ($positions as $position) {
            $position->delete();
        }

        return redirect()->route('offert.index');
    }
}
