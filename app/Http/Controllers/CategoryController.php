<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $categoryCount = Category::count(); // Retrieve only the count of categories
        return view('admin.categories.create', compact('categoryCount'));
    }

    public function store(Request $request)
    {
        $category = Category::create($request->all());
        $user = Auth::user();
        $category->user_id = $user->id;
        $category->save();

        if ($request->ajax()) {
            return response()->json(['success' => 'Category created successfully.']);
        }

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categoryCount = Category::count(); // Retrieve the total count of categories
        return view('admin.categories.edit', compact('category', 'categoryCount'));
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
