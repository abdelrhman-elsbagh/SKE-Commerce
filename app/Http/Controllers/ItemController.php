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
}
