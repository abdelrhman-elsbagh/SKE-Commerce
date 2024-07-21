<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        return view('admin.tags.index', ['tags' => Tag::all()]);
    }

    public function show($id)
    {
        return view('admin.tags.show', ['tag' => Tag::findOrFail($id)]);
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $tag = Tag::create($request->only('name'));

        if ($request->hasFile('image')) {
            $tag->addMedia($request->file('image'))->toMediaCollection('images');
        }

        return response()->json(['status' => 'success', 'message' => 'Tag created successfully.']);
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $tag->update($request->only('name'));

        if ($request->hasFile('image')) {
            $tag->clearMediaCollection('images');
            $tag->addMedia($request->file('image'))->toMediaCollection('images');
        }

        return response()->json(['status' => 'success', 'message' => 'Tag updated successfully.']);
    }


    public function edit($id)
    {
        return view('admin.tags.edit', ['tag' => Tag::findOrFail($id)]);
    }

    public function destroy($id)
    {
        try {
            Tag::findOrFail($id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Tag deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while deleting the tag.']);
        }
    }
}
