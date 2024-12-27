<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\SubItem; // Import SubItem model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    // Display a listing of the invoices
    public function index()
    {
        // Including sub_item relationship in the invoice index
        $invoices = Invoice::with('subItem')->get(); // Load the related sub_item for each invoice
        return view('admin.invoices.index', ['invoices' => $invoices]);
    }

    // Show the details of a specific invoice
    public function show($id)
    {
        $invoice = Invoice::with('subItem')->findOrFail($id); // Ensure the sub_item is loaded with the invoice
        return view('admin.invoices.show', ['invoice' => $invoice]);
    }

    // Show the form for creating a new invoice
    public function create()
    {
        $subItems = SubItem::all(); // Get all sub_items to display in the form
        return view('admin.invoices.create', compact('subItems'));
    }

    // Store a newly created invoice in the database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'issued_in' => 'required|date',
            'notes' => 'nullable|string',
            'amount' => 'required|numeric',
            'price' => 'required|numeric',
            'sub_item_id' => 'nullable|exists:sub_items,id', // Validate the sub_item_id
        ]);

        $invoice = Invoice::create($validated);
        $invoice->save();

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    // Show the form for editing an existing invoice
    public function edit($id)
    {
        $invoice = Invoice::with('subItem')->findOrFail($id); // Load sub_item relationship
        $subItems = SubItem::all(); // Get all sub_items for the edit form
        return view('admin.invoices.edit', compact('invoice', 'subItems'));
    }

    // Update the specified invoice in the database
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'issued_in' => 'required|date',
            'notes' => 'nullable|string',
            'amount' => 'required|numeric',
            'price' => 'required|numeric',
            'sub_item_id' => 'nullable|exists:sub_items,id',
        ]);

        $invoice = Invoice::findOrFail($id);
        $invoice->update($validated);

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    // Delete the specified invoice
    public function destroy($id)
    {
        try {
            Invoice::findOrFail($id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Invoice deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while deleting the invoice.']);
        }
    }
}
