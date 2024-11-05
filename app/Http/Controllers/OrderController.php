<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use App\Models\Order;
use App\Models\OrderSubItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'subItems.subItem'])->orderBy('created_at', 'DESC');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $orders = $query->get();

        return view('admin.orders.index', compact('orders'))->with([
            'statusFilter' => $request->status,
            'startDate' => $request->start_date,
            'endDate' => $request->end_date
        ]);
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

        // Check if the order has already been updated
        if ($order->updated_at != $order->created_at) {
            return response()->json(['message' => 'Order has already been updated once and cannot be updated again.'], 403);
        }

        $originalStatus = $order->status;

        $order->status = $request->status;
        $order->save();

        // Check if status was changed from 'active' to 'refunded'
        if (($originalStatus === 'active' && $order->status === 'refunded') ||
            ($originalStatus === 'pending' && $order->status === 'refunded')) {
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


    public static function storeApiOrder(Request $request)
    {
        $data = $request->all();
        try {
            // Find the user by external user ID or some identifier provided in the API request
            $user = User::with('currency')->findOrFail($data['user_id']);
            $external_user = User::with('currency')->findOrFail($data['external_user_id']);

            $totalPrice = $data['total'];

            if ($external_user->wallet->balance < $totalPrice) {
                return response()->json(['success' => false, 'message' => 'Insufficient balance in wallet'], 400);
            }

            // Deduct the amount from the user's wallet
            $before_balance = $external_user->wallet->balance;
            $external_user->wallet->balance -= $totalPrice;
            $external_user->wallet->save();

            $after_balance = $external_user->wallet->balance;


            $order_details = [
                'user_id' => $external_user->id,
                'total' => $totalPrice,
                'status' => 'active',
                'user_email' => $external_user->email ?? $data['user_email'],
                'user_name' => $external_user->name ?? $data['user_name'],
                'user_phone' => $external_user->phone ?? $data['user_phone'],
                'item_price' => $data['item_price'] ?? null,
                'item_fee' => $data['revenue'],
                'fee_name' => $data['fee_name'] ?? "",
                'item_name' => $data['item_name'] ?? null,
                'sub_item_name' => $data['sub_item_name'] ?? "NA",
                'service_id' => $data['service_id']?? "NA",
                'amount' => $data['amount']?? 0,
                'wallet_before' => $before_balance ,
                'wallet_after' => $after_balance,
                'currency_id' => $external_user->currency->id ?? null,
                'item_id' => $data['item_id'] ?? null,
                'sub_item_id' => $data['sub_item_id'],
                'revenue' => $data['revenue'],
                'is_external' => true
            ];

            // Create a new order
            $order = Order::create($order_details);

            // Handle any related sub-items if provided
            if (!empty($data['sub_items'])) {
                foreach ($data['sub_items'] as $subItemData) {
                    OrderSubItem::create([
                        'order_id' => $order->id,
                        'sub_item_id' => $subItemData['sub_item_id'],
                        'price' => $subItemData['price'],
                        'service_id' => $subItemData['service_id']
                    ]);
                }
            }

        } catch (\Exception $e) {
            Log::error('Failed to store external order', ['error' => $e->getMessage()]);
            return null; // Or handle the error as needed
        }
    }

}
