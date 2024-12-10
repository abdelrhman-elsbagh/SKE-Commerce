<?php

namespace App\Http\Controllers;

use App\Models\ClientStore;
use Illuminate\Http\Request;

class ClientStoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientStores = ClientStore::all();
        return view('admin.clientStores.index', compact('clientStores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.clientStores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Sanitize the domain to remove trailing slash if present
        $request->merge([
            'domain' => rtrim($request->input('domain'), '/')
        ]);

        // Validate the input data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'domain' => 'required|url|unique:client_stores,domain',
            'secret_key' => 'required|string|max:255'
        ]);

        // Create the client store
        ClientStore::create($validatedData);

        // Redirect with success message
        return redirect()->route('clientStores.index')->with('success', 'Client store created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(ClientStore $clientStore)
    {
        return view('admin.clientStores.show', compact('clientStore'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClientStore $clientStore)
    {
        return view('admin.clientStores.edit', compact('clientStore'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClientStore $clientStore)
    {
        // Sanitize the domain to remove trailing slash if present
        $request->merge([
            'domain' => rtrim($request->input('domain'), '/')
        ]);

        // Validate the input data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'domain' => 'required|url|unique:client_stores,domain,' . $clientStore->id,
            'secret_key' => 'required|string|max:255'
        ]);

        // Update the client store
        $clientStore->update($validatedData);

        // Redirect with success message
        return redirect()->route('clientStores.index')->with('success', 'Client store updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClientStore $clientStore)
    {
        $clientStore->delete();
        return redirect()->route('clientStores.index')->with('success', 'Client store deleted successfully.');
    }
}
