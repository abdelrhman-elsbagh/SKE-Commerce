<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return Order::with('user', 'orderItems.item')->get();
    }

    public function show($id)
    {
        return Order::with('user', 'orderItems.item')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $order = Order::create([
            'user_id' => $request->user_id,
            'total_in_diamonds' => 0, // Initial total
            'status' => $request->status,
        ]);

        $totalInDiamonds = 0;
        foreach ($request->order_items as $orderItem) {
            $item = Item::findOrFail($orderItem['item_id']);
            $totalInDiamonds += $item->price_in_diamonds * $orderItem['quantity'];
            $order->orderItems()->create([
                'item_id' => $orderItem['item_id'],
                'quantity' => $orderItem['quantity'],
                'price_in_diamonds' => $item->price_in_diamonds,
            ]);
        }

        $order->update(['total_in_diamonds' => $totalInDiamonds]);
        return $order->load('user', 'orderItems.item');
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update($request->all());

        // Update order items
        $order->orderItems()->delete();
        $totalInDiamonds = 0;
        foreach ($request->order_items as $orderItem) {
            $item = Item::findOrFail($orderItem['item_id']);
            $totalInDiamonds += $item->price_in_diamonds * $orderItem['quantity'];
            $order->orderItems()->create([
                'item_id' => $orderItem['item_id'],
                'quantity' => $orderItem['quantity'],
                'price_in_diamonds' => $item->price_in_diamonds,
            ]);
        }

        $order->update(['total_in_diamonds' => $totalInDiamonds]);
        return $order->load('user', 'orderItems.item');
    }

    public function destroy($id)
    {
        Order::findOrFail($id)->delete();
        return response()->noContent();
    }
}
