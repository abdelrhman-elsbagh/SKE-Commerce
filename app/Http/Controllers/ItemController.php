<?php

namespace App\Http\Controllers;

use App\Models\DiamondRate;
use App\Models\Item;
use App\Models\Category;
use App\Models\SubItem;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('category', 'tags')->get();

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

        // Attach tags if provided
        if ($request->has('tags')) {
            $item->tags()->attach($request->tags);
        }

        // Handle the main item image if provided
        if ($request->hasFile('image')) {
            $item->addMedia($request->file('image'))->toMediaCollection('images');
        }

        // Handle sub-items if provided
        if ($request->has('sub_items')) {
            foreach ($request->input('sub_items') as $index => $subItemData) {
                // Create the sub-item
                $subItem = new SubItem([
                    'name' => $subItemData['name'],
                    'description' => $subItemData['description'],
                    'amount' => $subItemData['amount'],
                    'price' => $subItemData['price']
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
//        dd($item->subItems[3]);
        return view('admin.items.edit', compact('item', 'categories', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $item->update($request->all());

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
                    $subItem->update($subItemData);

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
