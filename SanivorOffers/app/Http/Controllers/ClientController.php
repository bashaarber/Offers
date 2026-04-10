<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $showArchived = $request->input('show_archived');

        $clients = Client::query();

        if ($showArchived) {
            $clients = $clients->where('archived', true);
        } else {
            $clients = $clients->where(function($q) {
                $q->where('archived', false)->orWhereNull('archived');
            });
        }

        $clients = $clients->orderBy('id', 'ASC')->paginate(50);

        return view('client.index', compact('clients', 'showArchived'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('client.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'number' => 'required',
            'address' => 'required',
        ]);

        Client::create($formFields);

        return redirect()->route('client.index');
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
        $client = Client::find($id);
        return view('client.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $formFields = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'number' => 'required',
            'address' => 'required',
        ]);

        $client = Client::find($id);

        $client->update($formFields);

        return redirect()->route('client.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client = Client::find($id);
        $client->delete();
        return redirect()->route('client.index');
    }

    /**
     * Archive a client.
     */
    public function archive(string $id)
    {
        $client = Client::find($id);
        $client->update(['archived' => true]);
        return redirect()->route('client.index');
    }

    /**
     * Unarchive a client.
     */
    public function unarchive(string $id)
    {
        $client = Client::find($id);
        $client->update(['archived' => false]);
        return redirect()->route('client.index');
    }
}
