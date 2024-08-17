<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Currency;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return view('admin.clients.index', ['clients' => Client::all()]);
    }

    public function show($id)
    {
        return view('admin.clients.show', ['client' => Client::findOrFail($id)]);
    }

    public function create()
    {
        $currencies = Currency::all();
        return view('admin.clients.create', compact('currencies'));
    }

    public function store(Request $request)
    {
        $client = Client::create($request->except('currency_ids')); // Create client without currency_ids

        if ($request->has('currency_ids')) {
            $client->currencies()->sync($request->currency_ids); // Sync currencies
        }

        if ($request->ajax()) {
            return response()->json(['success' => 'Client created successfully.']);
        }

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }

    public function edit($id)
    {
        $client = Client::findOrFail($id);
        $currencies = Currency::all();
        return view('admin.clients.edit', compact('client', 'currencies'));
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        $client->update($request->except('currency_ids')); // Update client without currency_ids

        if ($request->has('currency_ids')) {
            $client->currencies()->sync($request->currency_ids); // Sync currencies
        }

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy($id)
    {
        try {
            Client::findOrFail($id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Client deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while deleting the client.']);
        }
    }

    public function getCurrencies(Request $request)
    {
        $clientId = $request->client_id;
        $client = Client::with('currencies')->find($clientId);

        if ($client) {
            $currencies = $client->currencies->pluck('currency', 'id');
            return response()->json(['currencies' => $currencies]);
        }

        return response()->json(['currencies' => []], 404); // Return empty if client not found
    }

}
