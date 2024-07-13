<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'subItems.subItem'])->orderBy('created_at', 'DESC')->get();
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
        $originalStatus = $order->status;

        $order->update($request->all());

        // Check if status was changed from 'active' to 'refunded'
        if ($originalStatus === 'active' && $order->status === 'refunded') {
            $this->refundOrderAmount($order);
        }

        if ($originalStatus === 'pending' && $order->status === 'refunded') {
            $this->refundOrderAmount($order);
        }

        return response()->json(['message' => 'Order updated successfully.']);
    }

    protected function refundOrderAmount($order)
    {
        $user = $order->user; // Assuming the Order model has a relationship with the User model

        $userWallet = $user->wallet; // Assuming the User model has a relationship with the Wallet model
        $userWallet->balance += $order->total;
        $userWallet->save();
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

    public function export()
    {
        return Excel::download(new OrdersExport, 'orders.xlsx');
    }
}
