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
        return Tag::findOrFail($id);
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:tags',
        ]);

        try {
            $tag = Tag::create($validatedData);
            return response()->json(['status' => 'success', 'message' => 'Tag created successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while creating the tag.']);
        }
    }

    public function edit($id)
    {
        return view('admin.tags.edit', ['tag' => Tag::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);
        $tag->update($request->all());

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Tag updated successfully.']);
        }

        return redirect()->route('tags.index')->with('success', 'Tag updated successfully.');
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
