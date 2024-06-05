<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories.index', ['categories' => Category::all()]);
    }

    public function show($id)
    {
        return view('admin.categories.show', ['category' => Category::findOrFail($id)]);
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $category = Category::create($request->all());

        if ($request->ajax()) {
            return response()->json(['success' => 'Category created successfully.']);
        }

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        return view('admin.categories.edit', ['category' => Category::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        try {
            Category::findOrFail($id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Category deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while deleting the category.']);
        }
    }
}
