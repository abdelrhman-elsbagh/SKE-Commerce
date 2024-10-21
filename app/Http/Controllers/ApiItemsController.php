<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ClientStore;
use App\Models\Item;
use App\Models\SubItem;
use App\Models\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class ApiItemsController extends Controller
{
    public function edit()
    {
        return view('admin.api-items.edit');
    }

    public function fetchItems(Request $request)
    {
        $validated = $request->validate([
            'destination_key' => 'required',
            'domain' => 'required|url'
        ]);

        // Check if the destination key matches a ClientStore's secret_key
        $clientStore = ClientStore::where('secret_key', $validated['destination_key'])->first();

        if (!$clientStore) {
            return response()->json(['message' => 'Invalid source key.'], 401);
        }

        // Check if the source key matches a User's secret_key
        $user = User::where('secret_key', $validated['destination_key'])->first();


        if (!$user) {
            return response()->json(['message' => 'Invalid destination key.'], 401);
        }

        // Fetch items related to the user identified by the destination key
        $items = Item::with('subItems')
            ->where('user_id', $user->id)
            ->get();
        return response()->json(['items' => $items]);
    }

    /**
     * Import selected items into the database under the authenticated user's account.
     */
    public function importItems(Request $request)
    {
        $validated = $request->validate([
            'sub_items' => 'required|array', // Assuming 'sub_items' contains detailed sub-item data from the client side
        ]);

        $user = Auth::user(); // Get the authenticated user
        if (!$user) {
            return response()->json(['message' => 'User must be authenticated to import items.'], 401);
        }

        $importedItems = [];

        $category = Category::firstOrCreate(
            ['name' => 'OUT-SOURCE'],
            ['user_id' => $user->id] // Associate the category with the authenticated user
        );

        foreach ($validated['sub_items'] as $subItemData) {
            $itemExternalId = $subItemData['item_external_id'];

            // Check if the parent item has already been imported in this session to avoid duplication
            if (!isset($importedItems[$itemExternalId])) {
                // Import the parent item only if not already imported
                $importedItems[$itemExternalId] = Item::create([
                    'user_id' => $user->id,
                    'name' => $subItemData['item_name'],
                    'description' => $subItemData['item_description'],
                    'external_id' => $itemExternalId,
                    'price_in_diamonds' => 0,
                    'category_id' => $category->id,
                    'status' => 'active',  // Default status to 'active'
                    'title' => $subItemData['item_title'] ?? null,
                    'title_type' => 'default' ?? null,
                    'is_outsourced' => true,
                    'source_domain' => $request->input('domain') // Use the domain provided in the request
                ]);
            }

            // Associate sub-item with the newly imported item
            SubItem::create([
                'item_id' => $importedItems[$itemExternalId]->id, // Reference the newly created parent item
                'name' => $subItemData['name'],
                'description' => $subItemData['description'],
                'amount' => $subItemData['amount'],
                'price' => $subItemData['price'],
                'external_id' => $subItemData['external_id'],
            ]);
        }

        return response()->json(['message' => 'Sub-items and their parent items imported successfully.']);
    }

    public function allItems(Request $request)
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Check if there's a logged in user
        if (!$user) {
            return response()->json(['message' => 'No authenticated user found.'], 401);
        }

        // Retrieve all items belonging to the authenticated user
        $items = $user->items;

        // Return the items as JSON
        return response()->json($items);
    }

    public function makeHttpRequest(Request $request)
    {
        $validated = $request->validate([
            'domain' => 'required|url',
            'destination_key' => 'required'
        ]);

        // Create a new HTTP client instance
        $client = new Client();

        // Define the URL to send the request to
        $url = $validated['domain'] . '/api/fetch-items'; // Adjust endpoint as needed

        try {
            // Send a POST request to the specified domain with the destination key in the JSON body
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'destination_key' => $validated['destination_key'],
                    'domain' => $validated['domain'] // Include the domain in the JSON payload
                ],
            ]);

            // Decode the JSON response
            $data = json_decode($response->getBody()->getContents(), true);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch data: ' . $e->getMessage()
            ], 500);
        }
    }
}
