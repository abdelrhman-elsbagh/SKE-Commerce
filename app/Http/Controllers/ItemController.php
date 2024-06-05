<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

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
        $mode = 'create'; // Set the mode to 'create' for creating a new item
        $categories = Category::all();
        return view('admin.items.create', compact('mode', 'categories'));
    }

    public function store(Request $request)
    {
        $item = Item::create($request->all());

        if ($request->has('tags')) {
            $item->tags()->attach($request->tags);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $item->addMedia($file)->toMediaCollection('images');
            }
        }

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    public function edit($id)
    {
        $mode = 'edit'; // Set the mode to 'edit' for editing an existing item
        $item = Item::findOrFail($id);
        $categories = Category::all();
        return view('admin.items.edit', compact('mode', 'item', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $item->update($request->all());

        if ($request->has('tags')) {
            $item->tags()->sync($request->tags);
        }

        if ($request->hasFile('images')) {
            $item->clearMediaCollection('images');
            foreach ($request->file('images') as $file) {
                $item->addMedia($file)->toMediaCollection('images');
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
