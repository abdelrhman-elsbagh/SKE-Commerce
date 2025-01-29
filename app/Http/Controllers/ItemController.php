<?php

namespace App\Http\Controllers;

use App\Models\ClientStore;
use App\Models\DiamondRate;
use App\Models\Item;
use App\Models\Category;
use App\Models\OrderSubItem;
use App\Models\SubItem;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with(['category', 'tags', 'subItems'])->get();
        $subitems = SubItem::with('item')->get();

        foreach ($items as $item) {
            // Get all sub-item IDs for the current item
            $subItemIds = $item->subItems->pluck('id');

            // Calculate the count of active orders' sub-items
            $item->activeOrdersSum = OrderSubItem::whereIn('sub_item_id', $subItemIds)
                ->whereHas('order', function ($query) {
                    $query->where('status', 'active');
                })
                ->count();

            // Calculate the count of refunded orders' sub-items
            $item->refundedOrdersSum = OrderSubItem::whereIn('sub_item_id', $subItemIds)
                ->whereHas('order', function ($query) {
                    $query->where('status', 'refunded');
                })
                ->count();
        }

        return view('admin.items.index', compact('items', 'subitems'));
    }

    public function show($id)
    {
        $item = Item::with('category', 'tags', 'media')->findOrFail($id);

        return view('admin.items.show', compact('item'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        $itemCount = Item::where('status', 'active')->count();
        return view('admin.items.create', compact('categories', 'tags', 'itemCount'));
    }

    public function store(Request $request)
    {
        // Create the main item
        $item = Item::create($request->all());
        $user = Auth::user();
        $item->user_id = $user->id;
        $item->save();


        // Attach tags if provided
        if ($request->has('tags')) {
            $item->tags()->attach($request->tags);
        }

        // Handle the main item image if provided
        if ($request->hasFile('image')) {
            $item->addMedia($request->file('image'))->toMediaCollection('images');
        }

        if ($request->hasFile('front_image')) {
            $item->addMedia($request->file('front_image'))->toMediaCollection('front_image');
        }



        // Handle sub-items if provided
        if ($request->has('sub_items')) {
            foreach ($request->input('sub_items') as $index => $subItemData) {
                // Create the sub-item
                $subItem = new SubItem([
                    'name' => $subItemData['name'],
                    'description' => $subItemData['description'],
                    'amount' => $subItemData['amount'],
                    'price' => $subItemData['price'],
                    'max_amount' => $subItemData['max_amount'] ?? null,
                    'minimum_amount' => $subItemData['minimum_amount'] ?? null,
                    'is_custom' => $subItemData['is_custom'] ?? 0,
                    'status' => $subItemData['sub_status'] ?? 'active',
                ]);

                // Save the sub-item to the parent item
                $item->subItems()->save($subItem);

                // Handle the sub-item image if provided
                if ($request->hasFile("sub_items.{$index}.image")) {
                    $subItem->addMedia($request->file("sub_items.{$index}.image"))->toMediaCollection('images');
                }
            }
        }

        // Redirect to the items index with a success message
        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    public function edit($id)
    {
        $item = Item::with(['subItems', 'subItems.media', 'media'])->findOrFail($id);
        $categories = Category::all();
        $tags = Tag::all();
        $itemCount = Item::where('status', 'active')->count();
        return view('admin.items.edit', compact('item', 'categories', 'tags', 'itemCount'));
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $item->update($request->all());

        if ($request->hasFile('image')) {
            $item->clearMediaCollection('images');
            $item->addMedia($request->file('image'))->toMediaCollection('images');
        }

        if ($request->hasFile('front_image')) {
            $item->clearMediaCollection('front_image');
            $item->addMedia($request->file('front_image'))->toMediaCollection('front_image');
        }

        if ($request->has('tags')) {
            $item->tags()->sync($request->tags);
        }

        if ($request->has('sub_items_to_remove')) {
            foreach ($request->sub_items_to_remove as $subItemId) {
                $subItem = SubItem::find($subItemId);
                if ($subItem) {
                    $subItem->delete();
                }
            }
        }

        if ($request->has('sub_items')) {
            foreach ($request->sub_items as $subItemData) {
                if (isset($subItemData['id'])) {
                    $subItem = SubItem::findOrFail($subItemData['id']);
                    $origin_price =  $subItem->price;
                    $origin_amount =  $subItem->amount;

                    if (isset($subItemData['sub_status'])) {
                        $subItemData['status'] = $subItemData['sub_status'];
                        unset($subItemData['sub_status']);
                    }

                    $subItem->update($subItemData);

                    // Only override amount and price if external_id is null
                    if ($subItem->external_id) {
                        $subItem->amount = $origin_price;
                        $subItem->price = $origin_amount;
                        $subItem->save();
                    }

                    if (isset($subItemData['image']) && $subItemData['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $subItem->clearMediaCollection('images');
                        $subItem->addMedia($subItemData['image'])->toMediaCollection('images');
                    }
                } else {
                    $subItem = $item->subItems()->create($subItemData);

                    if (isset($subItemData['image']) && $subItemData['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $subItem->addMedia($subItemData['image'])->toMediaCollection('images');
                    }
                }
            }
        }

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }


    public function destroy($id)
    {
        try {
            Item::findOrFail($id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Item deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while deleting the item.']);
        }
    }

    ###############################
    public function fetchAndImportEkoStoreProducts()
    {
        $eko_domains = ClientStore::where('name', 'EkoStore')->where('status', 'active')->get();

        if ($eko_domains->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No active EkoStore domains found']);
        }

        try {
            foreach ($eko_domains as $eko_domain) {

                if ($eko_domain) {
                    $apiUrl = "{$eko_domain->domain}/client/api/products";
                    $apiToken = $eko_domain->secret_key;
                    // Fetch data from the API
                    $response = Http::withHeaders([
                        'api-token' => $apiToken,
                    ])->get($apiUrl);

                    if ($response->failed()) {
                        return response()->json(['success' => false, 'message' => 'Failed to fetch data from the EkoStore API'], 500);
                    }

                    $products = $response->json();

                    foreach ($products as $key => $product) {
                            $subItem = SubItem::where('external_id', $product['id'])->first();
                            if ($subItem) {
                                $subItem->update(
                                    [
                                        'amount' => $product['qty_values']['min'] ?? 1,
                                        'price' => $product['price'],
                                        'original_price' => $product['price'],
                                        'status' => $product['available'] ? 'active' : 'inactive',
                                        'is_custom' => $product['product_type'] == "amount" ? 1 : 0,
                                        'minimum_amount' => $product['qty_values']['min'] ?? 1,
                                        'max_amount' => $product['qty_values']['max'] ?? 1,
                                        'product_type' => $product['product_type']?? "package",
                                        'domain' => $eko_domain->domain,
                                        'client_store_id' => $eko_domain->id,
                                        'out_flag' => 1,
                                    ]
                                );
                                $subItem->save();
                            }
                    }

                }
            }
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error while Import Eko-Store products' . $e->getMessage()]);
        }

        return response()->json(['success' => true, 'message' => 'EkoStore products imported successfully']);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $subitems = SubItem::where('name', 'LIKE', "%{$query}%")->get(['id', 'name']);

        return response()->json(['subitems' => $subitems]);
    }

    public function move(Request $request)
    {
        $request->validate([
            'subitem_ids' => 'required|array',
            'subitem_ids.*' => 'exists:sub_items,id',
            'target_item_id' => 'required|exists:items,id',
        ]);

        SubItem::whereIn('id', $request->subitem_ids)->update(['item_id' => $request->target_item_id]);

        return response()->json(['success' => true, 'message' => 'SubItems moved successfully']);
    }

    public function deleteSelected(Request $request)
    {
        $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:items,id',
        ]);

        Item::whereIn('id', $request->item_ids)->delete();

        return response()->json(['success' => true, 'message' => 'Selected items deleted successfully.']);
    }


}
