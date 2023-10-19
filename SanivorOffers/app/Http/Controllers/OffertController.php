<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Coefficient;
use App\Models\Offert;
use App\Models\User;
use Illuminate\Http\Request;

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $clients = Client::all();
        $coefficients = Coefficient::get();
        return view('offert.create', compact('users', 'clients', 'coefficients'));
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
            'validity' => 'required',
            'client_sign' => 'required',
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

        // dd($formFields);

        Offert::create($formFields);

        return redirect()->route('offert.index');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
