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
        $users = ClientStore::all();
        return view('admin.api-items.edit', compact('users'));
    }

    public function fetchSubItem(Request $request)
    {
        $validated = $request->validate([
            'external_id' => 'required',
            'destination_key' => 'required',
        ]);

        // Check if the destination key matches a ClientStore's secret_key
        $clientStore = User::where('secret_key', $validated['destination_key'])->first();

        if (!$clientStore) {
            return response()->json(['message' => 'Invalid destination key.'], 401);
        }

        // Fetch sub-item by external_id
        $subItem = SubItem::where('id', $validated['external_id'])
            ->select('id', 'price', 'amount', 'user_id', 'item_id')
            ->first();

        if (!$subItem) {
            return response()->json(['message' => 'Sub-item not found.'], 404);
        }

        // Add clientStore's fee to the sub-item price
        if ($clientStore->feeGroup) {
            $subItem->origin_price = floatval($subItem->price);
            $subItem->price += round($subItem->price * $clientStore->feeGroup->fee / 100, 2);
        }

        return response()->json(['sub_item' => $subItem]);
    }

    public function fetchItem(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'destination_key' => 'required',
            'source_key' => 'required',
            'domain' => 'required|url',
            'item_id' => 'required|integer',
        ]);

        // Check if the source key matches a User's secret_key (ClientStore)
        $clientStore = User::where('secret_key', $validated['source_key'])->first();

        if (!$clientStore) {
            return response()->json(['message' => 'Invalid source key.'], 401);
        }

        // Check if the destination key matches a User's secret_key
        $user = User::where('secret_key', $validated['destination_key'])->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid destination key.'], 401);
        }

        // Fetch the specific item for the user
        $item = Item::with(['subItems', 'category'])
            ->where('user_id', $user->id)
            ->where('id', $validated['item_id']) // Fetch the specific item based on ID
            ->first();

        if (!$item) {
            return response()->json(['message' => 'Item not found.'], 404);
        }

        return response()->json(['item' => $item]);
    }

    public function fetchItems(Request $request)
    {
        $validated = $request->validate([
            'source_key' => 'required',
            'domain' => 'required|url',
            'client_id' => 'required|integer',
        ]);

        $client_id = $validated['client_id'];
        // Check if the source key matches a ClientStore's secret_key
        $clientStore = User::where([
            'secret_key' => $validated['source_key'],
            'status' => 'active',
//            'id' => $client_id,
        ])->first();


        if (!$clientStore) {
            return response()->json(['message' => 'Invalid source key.'], 401);
        }

        // Check if the domain matches a User's domain
        $user = User::where('domain', $validated['domain'])->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid info.'], 401);
        }

//        $user = $clientStore;

        $items = Item::where('status', 'active')->with(['subItems', 'category'])->get();

        // Apply the clientStore's feeGroup percentage to each subItem price
        if ($clientStore->feeGroup) {
            $feePercentage = $clientStore->feeGroup->fee;
            foreach ($items as $item) {
                foreach ($item->subItems as $subItem) {
                    // Calculate the additional fee amount as a percentage of the original price
                    $feeAmount = round($subItem->price * $feePercentage / 10, 2);
                    // Add the fee amount to the original price
                    $subItem->fee = $feeAmount;
                    $subItem->price = round($subItem->price + $feeAmount, 2);
                    $subItem->user_id = $user->id;
                    $subItem->item_id = $item->id;
                    $subItem->client_store_id = $client_id;
                }
            }
        }

        return response()->json(['items' => $items]);
    }


    /**
     * Import selected items into the database under the authenticated user's account.
     */
    public function importItems(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'sub_items' => 'required|array',
            'sub_items.*.name' => 'required|string',
            'sub_items.*.description' => 'nullable|string',
            'sub_items.*.amount' => 'required|numeric',
            'sub_items.*.price' => 'required|numeric',
            'sub_items.*.external_id' => 'required|string',
            'sub_items.*.user_id' => 'required|integer',
            'sub_items.*.item_id' => 'required|string',
            'sub_items.*.item_name' => 'required|string',
            'sub_items.*.item_description' => 'nullable|string',
            'sub_items.*.item_title' => 'nullable|string',
            'domain' => 'nullable|string',
            'client_id' => 'nullable|integer',
        ]);

        // Ensure the user is authenticated
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User must be authenticated to import items.'], 401);
        }

        $importedItems = [];
        $client_id = $validated['client_id'];

        // Get or create the OUT-SOURCE category for the user
        $category = Category::firstOrCreate(
            ['name' => 'OUT-SOURCE', 'user_id' => $user->id]
        );

        foreach ($validated['sub_items'] as $subItemData) {
            $itemExternalId = $subItemData['external_id'];
            $itemName = $subItemData['item_name'];

            // Check if the parent item already exists for the user based on external_id
            $parentItem = Item::where('external_id', $itemExternalId)
                ->where('user_id', $user->id)
                ->first();

            // If the item does not exist, create it
            if (!$parentItem) {
                $parentItem = Item::create([
                    'user_id' => Auth::user()->id,
                    'name' => $itemName,
                    'description' => $subItemData['item_description'],
                    'external_id' => $itemExternalId,
                    'price_in_diamonds' => 0,
                    'category_id' => $category->id,
                    'status' => 'active',
                    'title' => $subItemData['item_title'] ?? null,
                    'title_type' => 'default',
                    'is_outsourced' => true,
                    'source_domain' => $request->input('domain'),
                ]);
            }

            // Check if the sub-item already exists for the current parent item based on external_id and item_id
            $subItem = SubItem::where('external_id', $subItemData['external_id'])
                ->where('item_id', $parentItem->id)
                ->first();

            // If the sub-item does not exist, create it
            if (!$subItem) {
                SubItem::create([
                    'item_id' => $parentItem->id,
                    'user_id' => $user->id,
                    'name' => $subItemData['name'],
                    'description' => $subItemData['description'],
                    'amount' => $subItemData['amount'],
                    'price' => $subItemData['price'],
                    'external_id' => $subItemData['external_id'],
                    'external_user_id' => $subItemData['user_id'],
                    'external_item_id' => $subItemData['item_id'],
                    'domain' => $request->input('domain'),
                    'client_store_id' => $client_id,
                ]);
            }
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
