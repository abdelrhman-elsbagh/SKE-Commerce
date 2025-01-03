<?php

namespace App\Http\Controllers;

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

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with(['category', 'tags', 'subItems'])->get();

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

        return view('admin.items.index', compact('items'));
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
        return view('admin.items.create', compact('categories', 'tags'));
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
        return view('admin.items.edit', compact('item', 'categories', 'tags'));
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
        $apiUrl = 'https://api.ekostore.co/client/api/products';
        $apiToken = 'bc249492922515e340088aafc560ff67720ab65ef478ba33';

        $eko_category = Category::firstOrCreate(['name' => 'EkoStore'],
            ['description' => 'Imported from EkoStore API', 'status' => 'active', 'name' => 'EkoStore']);

        // Fetch data from the API
        $response = Http::withHeaders([
            'api-token' => $apiToken,
        ])->get($apiUrl);

        if ($response->failed()) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch data from the EkoStore API'], 500);
        }

        $products = $response->json();

        foreach ($products as $product) {
            // Handle the category (Item in your app)
            $categoryName = $product['category_name'] ?? 'Uncategorized';
            $categoryImage = $product['category_img'];

            // Find or create the category (Item)
            $item = Item::firstOrCreate(
                ['name' => $categoryName],
                [
                    'category_id' => $eko_category->id,
                    'description' => 'Imported from EkoStore API',
                    'status' => 'active',
                    'is_outsourced' => true,
                    'source_domain' => 'https://api.ekostore.co',
                ]
            );

            // Attach category image if available
            if ($categoryImage && !$item->getFirstMediaUrl('images')) {
                $item->addMediaFromUrl($categoryImage)->toMediaCollection('images');
            }

            if ($categoryImage && !$item->getFirstMediaUrl('front_image')) {
                $item->addMediaFromUrl($categoryImage)->toMediaCollection('front_image');
            }

            // Handle the sub-item
            SubItem::updateOrCreate(
                ['external_id' => $product['id']], // Match by external ID
                [
                    'item_id' => $item->id,
                    'name' => $product['name'],
                    'description' => implode(', ', $product['params']),
                    'amount' => $product['qty_values']['min'] ?? 0,
                    'price' => $product['price'],
                    'original_price' => $product['base_price'],
                    'status' => $product['available'] ? 'active' : 'inactive',
                    'is_custom' => $product['product_type'] === 'amount' ? 1 : 0,
                    'minimum_amount' => $product['qty_values']['min'] ?? null,
                    'max_amount' => $product['qty_values']['max'] ?? null,
                    'product_type' => $product['product_type']?? null,
                    'domain' => 'https://api.ekostore.co',
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'EkoStore products imported successfully']);
    }
}
