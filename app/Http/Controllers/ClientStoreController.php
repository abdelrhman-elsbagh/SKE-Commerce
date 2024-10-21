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
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|url|unique:client_stores,domain',
            'secret_key' => 'required|string|max:255'
        ]);

        ClientStore::create($validatedData);

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
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|url|unique:client_stores,domain,' . $clientStore->id,
            'secret_key' => 'required|string|max:255'
        ]);

        $clientStore->update($validatedData);

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
