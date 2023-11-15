<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Offert;
use App\Models\Coefficient;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OffertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('query');
        $offerts = Offert::where('id', 'like', '%' . $query . '%')->orderBy('id', 'DESC')->paginate(10);

        return view('offert.index', compact('offerts', 'query'));
    }

    public function exportPdf($id){
        $offert = Offert::find($id);

        $offert->load(['positions' => function ($query) {
            $query->orderBy('position_number', 'ASC');
        }]);

        $pdf = Pdf::loadView('offert.offert-pdf-export', compact('offert'));
        return $pdf->stream();
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

        Offert::create($formFields);

        return redirect()->route('position.create');
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

        return view('offert.show', compact('offert'));
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

        return redirect()->route('offert.index');
    }

    public function searchClients(Request $request)
    {
        $searchTerm = $request->input('searchTerm');
        $clients = Client::where('name', 'like', '%' . $searchTerm . '%')->get();

        return response()->json($clients);
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
