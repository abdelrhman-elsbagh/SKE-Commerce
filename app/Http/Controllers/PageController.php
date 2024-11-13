<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::all();
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'data' => 'nullable|string',
            'slug' => 'nullable|string|unique:pages,slug',
            'title' => 'nullable|string|max:255',
        ]);

        $page = Page::create($validatedData);

        // If the request contains an image, save it using the media library.
        if ($request->hasFile('image')) {
            $page->addMedia($request->file('image'))->toMediaCollection('images');
        }

        return redirect()->route('pages.index')->with('success', 'Page created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        return view('admin.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $validatedData = $request->validate([
            'data' => 'nullable|string',
            'slug' => 'nullable|string|unique:pages,slug,' . $page->id,
            'title' => 'nullable|string|max:255',
        ]);

        $page->update($validatedData);

        // Update image if a new file is uploaded
        if ($request->hasFile('image')) {
            $page->clearMediaCollection('images');
            $page->addMedia($request->file('image'))->toMediaCollection('images');
        }

        return redirect()->route('pages.index')->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('pages.index')->with('success', 'Page deleted successfully.');
    }
}
