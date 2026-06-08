<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Support\ListFilter;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Validate a return URL so it can only point back into this app
     * (used to send the user back to the same paginated/filtered list).
     */
    private function safeReturnUrl(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }
        $appUrl = rtrim(url('/'), '/');
        $candidate = trim($url);
        if (str_starts_with($candidate, $appUrl . '/') || $candidate === $appUrl) {
            return $candidate;
        }
        if (str_starts_with($candidate, '/')) {
            return url($candidate);
        }
        return null;
    }

    private function redirectBack(Request $request)
    {
        return redirect()->to(
            $this->safeReturnUrl($request->input('return_url')) ?? route('client.index')
        );
    }

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

        ListFilter::apply($clients, $request, [
            'id'      => 'clients.id',
            'name'    => 'clients.name',
            'email'   => 'clients.email',
            'number'  => 'clients.number',
            'address' => 'clients.address',
        ]);

        $clients = $clients->orderBy('id', 'ASC')->paginate(20)->withQueryString();

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
            'number' => 'nullable',
            'address' => 'required',
            'address_2' => 'nullable|string',
            'address_3' => 'nullable|string',
        ]);

        // 'number' is optional; the ConvertEmptyStringsToNull middleware turns a
        // blank field into null, but the column is NOT NULL — store '' instead.
        $formFields['number'] = $formFields['number'] ?? '';

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
            'number' => 'nullable',
            'address' => 'required',
            'address_2' => 'nullable|string',
            'address_3' => 'nullable|string',
        ]);

        $formFields['number'] = $formFields['number'] ?? '';

        $client = Client::find($id);

        $client->update($formFields);

        return $this->redirectBack($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $client = Client::find($id);
        $client->delete();
        return $this->redirectBack($request);
    }

    /**
     * Archive a client.
     */
    public function archive(Request $request, string $id)
    {
        $client = Client::find($id);
        $client->update(['archived' => true]);
        return $this->redirectBack($request);
    }

    /**
     * Unarchive a client.
     */
    public function unarchive(Request $request, string $id)
    {
        $client = Client::find($id);
        $client->update(['archived' => false]);
        return $this->redirectBack($request);
    }
}
