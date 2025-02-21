<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('category', 'media') // Include category and media
        ->get();

        return view('admin.tickets.index', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = Ticket::with('category', 'media') // Include category and media
        ->findOrFail($id);

        return view('admin.tickets.show', compact('ticket'));
    }

    public function create()
    {
        $categories = TicketCategory::all(); // Get all ticket categories for the form
        return view('admin.tickets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'ticket_category_id' => 'required|exists:ticket_categories,id',
            'message' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Create the new ticket
        $ticket = Ticket::create($request->all());

        // Handle the image media if provided
        if ($request->hasFile('image')) {
            $ticket->addMedia($request->file('image'))->toMediaCollection('images');
        }

        // Redirect to the tickets index with a success message
        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully.');
    }

    public function edit($id)
    {
        $ticket = Ticket::with('category', 'media')->findOrFail($id);
        $categories = TicketCategory::all(); // Get all ticket categories for the form
        return view('admin.tickets.edit', compact('ticket', 'categories'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'ticket_category_id' => 'required|exists:ticket_categories,id',
            'message' => 'required|string',
            'status' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Find and update the ticket
        $ticket = Ticket::findOrFail($id);
        $ticket->update($request->all());

        // Handle the image media if provided
        if ($request->hasFile('image')) {
            $ticket->clearMediaCollection('images'); // Clear the existing media
            $ticket->addMedia($request->file('image'))->toMediaCollection('images');
        }

        // Redirect to the tickets index with a success message
        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $ticket = Ticket::findOrFail($id);
            $ticket->delete();
            return response()->json(['status' => 'success', 'message' => 'Ticket deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while deleting the ticket.']);
        }
    }

    // Additional method for handling media collection (e.g., image upload)
    public function uploadMedia(Request $request, $ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);

        if ($request->hasFile('image')) {
            $ticket->addMedia($request->file('image'))->toMediaCollection('images');
            return response()->json(['status' => 'success', 'message' => 'Media uploaded successfully.']);
        }

        return response()->json(['status' => 'error', 'message' => 'No image file provided.']);
    }

    // Method for changing the status of a ticket
    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->update([
            'status' => $request->status,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Ticket status updated successfully.']);
    }
}
