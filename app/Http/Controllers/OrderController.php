<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'subItems.subItem'])->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'subItems.subItem'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $users = User::whereHas('roles', function($query) {
            $query->where('name', 'User');
        })->get();

        return view('admin.orders.edit', compact('order', 'users'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update($request->all());

        return response()->json(['message' => 'Order updated successfully.']);
    }

    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();
            return response()->json(['status' => 'success', 'message' => 'Order deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while deleting the order.']);
        }
    }
}
