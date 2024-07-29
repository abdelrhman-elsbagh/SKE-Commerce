<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationRead;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('media')->get();

        return view('admin.notifications.index', compact('notifications'));
    }

    public function show($id)
    {
        $notification = Notification::with('media')->findOrFail($id);

        return view('admin.notifications.show', compact('notification'));
    }

    public function create()
    {
        return view('admin.notifications.create');
    }

    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|max:2048',
        ]);

        // Create the notification
        $notification = Notification::create($request->all());

        // Handle the notification attachment if provided
        if ($request->hasFile('attachment')) {
            $notification->addMedia($request->file('attachment'))->toMediaCollection('attachments');
        }

        // Redirect to the notifications index with a success message
        return redirect()->route('notifications.index')->with('success', 'Notification created successfully.');
    }

    public function edit($id)
    {
        $notification = Notification::with('media')->findOrFail($id);

        return view('admin.notifications.edit', compact('notification'));
    }

    public function update(Request $request, $id)
    {
        // Validate request
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|max:2048',
        ]);

        // Find and update the notification
        $notification = Notification::findOrFail($id);
        $notification->update($request->all());

        // Handle the notification attachment if provided
        if ($request->hasFile('attachment')) {
            $notification->clearMediaCollection('attachments');
            $notification->addMedia($request->file('attachment'))->toMediaCollection('attachments');
        }

        // Redirect to the notifications index with a success message
        return redirect()->route('notifications.index')->with('success', 'Notification updated successfully.');
    }

    public function destroy($id)
    {
        try {
            Notification::findOrFail($id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Notification deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while deleting the notification.']);
        }
    }

    public function markAsRead($id)
    {
        $user = Auth::guard('web')->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated.'], 401);
        }

        $notification = Notification::find($id);
        if (!$notification) {
            return response()->json(['success' => false, 'message' => 'Notification not found.'], 404);
        }

        $notificationRead = new NotificationRead();
        $notificationRead->user_id = $user->id;
        $notificationRead->notification_id = $notification->id;
        $notificationRead->status = 'read';
        $notificationRead->save();

        return response()->json(['success' => true, 'message' => 'Notification marked as read.']);
    }
}
