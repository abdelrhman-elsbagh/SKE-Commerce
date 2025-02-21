<?php

namespace App\Http\Controllers;

use App\Models\TicketCategory;
use Illuminate\Http\Request;

class TicketCategoryController extends Controller
{
    public function index()
    {
        $categories = TicketCategory::all(); // Get all categories
        return view('admin.ticket_categories.index', compact('categories')); // Pass categories to the view
    }

    public function show($id)
    {
        $category = TicketCategory::findOrFail($id); // Find the category by ID
        return view('admin.ticket_categories.show', compact('category'));
    }

    public function create()
    {
        return view('admin.ticket_categories.create'); // Show the create form without passing $categories
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'ar_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'ar_desc' => 'nullable|string',
            'desc' => 'nullable|string',
        ]);

        // Create the new ticket category
        TicketCategory::create($request->all());

        // Redirect to the categories index with a success message
        return redirect()->route('ticket_categories.index')->with('success', 'Ticket category created successfully.');
    }

    public function edit($id)
    {
        $category = TicketCategory::findOrFail($id); // Find the category by ID
        return view('admin.ticket_categories.edit', compact('category')); // Pass individual category data to edit view
    }

    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'ar_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'ar_desc' => 'nullable|string',
            'desc' => 'nullable|string',
        ]);

        // Find and update the category
        $category = TicketCategory::findOrFail($id);
        $category->update($request->all());

        // Redirect to the categories index with a success message
        return redirect()->route('ticket_categories.index')->with('success', 'Ticket category updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $category = TicketCategory::findOrFail($id);
            $category->delete();
            return response()->json(['status' => 'success', 'message' => 'Ticket category deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while deleting the ticket category.']);
        }
    }
}
