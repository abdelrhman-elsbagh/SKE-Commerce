<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'item_id' => 'nullable|exists:items,id',
            'sub_item_id' => 'nullable|exists:sub_items,id',
        ]);

        $user = Auth::user();

        $favorite = new Favorite();
        $favorite->user_id = $user->id;
        $favorite->item_id = $request->input('item_id');
        $favorite->sub_item_id = $request->input('sub_item_id');

        // Prevent adding the same favorite multiple times
        $exists = Favorite::where('user_id', $user->id)
            ->where(function($query) use ($request) {
                $query->where('item_id', $request->input('item_id'))
                    ->orWhere('sub_item_id', $request->input('sub_item_id'));
            })
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'This item is already in your favorites.']);
        }

        $favorite->save();

        return response()->json(['success' => true, 'message' => 'Item added to favorites successfully.']);
    }
}
