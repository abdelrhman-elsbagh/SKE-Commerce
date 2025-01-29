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
        // Get client stores with external orders count and total, and total SubItems count
        $clientStores = ClientStore::withCount([
            'subItems as external_orders_count' => function ($query) {
                $query->whereHas('orders', function ($orderQuery) {
                    $orderQuery->where('is_external', true);
                });
            },
            'subItems as total_sub_items_count' // Total count of SubItems
        ])->get();

        // Add a custom attribute for the total of external orders
        foreach ($clientStores as $store) {
            $store->external_orders_total = $store->subItems()
                ->whereHas('orders', function ($query) {
                    $query->where('is_external', true);
                })->withSum('orders', 'total')->get()->sum('orders_sum_total');
        }

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
        // Load related data: external orders count, total sum, and total sub-items count
        $clientStore->loadCount([
            'subItems as external_orders_count' => function ($query) {
                $query->whereHas('orders', function ($orderQuery) {
                    $orderQuery->where('is_external', true);
                });
            },
            'subItems as total_sub_items_count' // Total count of SubItems
        ])->load([
            'subItems' => function ($query) {
                $query->whereHas('orders', function ($orderQuery) {
                    $orderQuery->where('is_external', true);
                })->withSum('orders', 'total');
            }
        ]);

        // Calculate the total external orders sum
        $clientStore->external_orders_total = $clientStore->subItems->sum('orders_sum_total');

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
            'secret_key' => 'required|string|max:255',
            'status' => 'required|string'
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

    public function integrate(Request $request)
    {
        $request->validate([
            'api_key' => 'required|string',
            'email' => 'required|email',
            'domain' => 'required',
        ]);

        $clientStore = null;
        $domain = rtrim($request->domain, '/');
        if ($request->has('client_id') && $request->client_id) {
            $clientStore = ClientStore::where('domain', $domain)->where('status', 'active')->first();
        }

        if (!$clientStore) {
            // Create a new client store if it doesn't exist
            $clientStore = ClientStore::create([
                'name' => 'EkoStore', // Replace with a default name or from the request if provided
                'email' => $request->email,
                'domain' => $domain,
                'secret_key' => $request->api_key,
            ]);
        } else {
            // Update the existing client store
            $clientStore->update([
                'email' => $request->email,
                'secret_key' => $request->api_key,
                'domain' => $domain,
            ]);
        }

        return response()->json(['message' => 'Integration successful', 'client_store' => $clientStore]);
    }

    public function ekoIntegrate()
    {
        $clientStores = ClientStore::where('name', 'EkoStore')->where('status', 'active')->get(); // Fetch all client stores
        return view('admin.clientStores.eko_integrate', compact('clientStores'));
    }
}
