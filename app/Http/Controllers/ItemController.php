<?php

namespace App\Http\Controllers;

use App\Models\DiamondRate;
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
        $tags = Tag::all();
        $diamondRates = DiamondRate::all();
        return view('admin.items.create', compact('mode', 'categories', 'tags', 'diamondRates'));
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_in_diamonds' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array|nullable',
            'tags.*' => 'exists:tags,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $item = Item::create($validatedData);

        if ($request->has('tags')) {
            $item->tags()->attach($request->tags);
        }

        if ($request->hasFile('image')) {
            $item->addMedia($request->file('image'))->toMediaCollection('images');
        }

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Item created successfully.']);
        }

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }


    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $categories = Category::all();
        $tags = Tag::all();
        $mode = 'edit';
        return view('admin.items.edit', compact('item', 'categories', 'tags', 'mode'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'price_in_diamonds' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array|exists:tags,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

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
