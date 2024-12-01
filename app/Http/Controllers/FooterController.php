<?php

namespace App\Http\Controllers;

use App\Models\Footer;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    public function index()
    {
        return view('admin.footers.index', ['footers' => Footer::all()]);
    }

    public function show($id)
    {
        return view('admin.footers.show', ['footer' => Footer::findOrFail($id)]);
    }

    public function create()
    {
        return view('admin.footers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tag' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'link' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $footer = Footer::create($request->only('tag', 'title', 'ar_tag', 'ar_title', 'link'));

        if ($request->hasFile('image')) {
            $footer->addMedia($request->file('image'))->toMediaCollection('images');
        }

        return response()->json(['status' => 'success', 'message' => 'Footer item created successfully.']);
    }

    public function update(Request $request, Footer $footer)
    {
        $request->validate([
            'tag' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'link' => 'nullable|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $footer->update($request->only('tag', 'title', 'ar_tag', 'ar_title', 'link'));

        if ($request->hasFile('image')) {
            $footer->clearMediaCollection('images');
            $footer->addMedia($request->file('image'))->toMediaCollection('images');
        }

        return response()->json(['status' => 'success', 'message' => 'Footer item updated successfully.']);
    }

    public function edit($id)
    {
        return view('admin.footers.edit', ['footer' => Footer::findOrFail($id)]);
    }

    public function destroy($id)
    {
        try {
            Footer::findOrFail($id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Footer item deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while deleting the footer item.']);
        }
    }
}
