<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use App\Models\ClientStore;
use App\Models\Config;
use App\Models\Order;
use App\Models\OrderSubItem;
use App\Models\SubItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
//    public function index(Request $request)
//    {
//        $query = Order::with(['user', 'subItems.subItem'])->orderBy('created_at', 'DESC');
//
//        if ($request->has('status') && $request->status != '') {
//            $query->where('status', $request->status);
//        }
//
//        if ($request->has('start_date') && $request->has('end_date')) {
//            $startDate = $request->start_date;
//            $endDate = $request->end_date;
//            $query->whereBetween('created_at', [$startDate, $endDate]);
//        }
//
//        $orders = $query->get();
//
//        foreach ($orders as $order) {
//            if ($order->is_external == 1) {
//                // Check if the related subItem and clientStore exists, then update the external order
//                $domain = $order->subItems->first()->subItem->clientStore->domain ?? "";
//                if ($domain) {
//                    $response = self::updateOrder($domain, $order->id, $order->external_order_id);
//                }
//            }
//        }
//
//        return view('admin.orders.index', compact('orders'))->with([
//            'statusFilter' => $request->status,
//            'startDate' => $request->start_date,
//            'endDate' => $request->end_date
//        ]);
//    }

    public function index(Request $request)
    {
        $clients = ClientStore::where('key_name', 'ZDDK')->where('status', 'active')->get();

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

        try {
            foreach ($orders as $order) {
                if ($order->is_external == 1 &&
                    $order->external_order_id &&
                    ($order->subItem?->out_flag == 1) &&
                    !$clients->isEmpty()) {
                    foreach ($clients as $client) {
                        // Prepare the API URL and headers
                        $url = "{$client->domain}/client/api/check";
                        $headers = [
                            'api-token' => $client->secret_key,
                        ];

                        $originalStatus = $order->status;

                        // API call
                        $response = Http::withHeaders($headers)->get($url, [
                            'orders' => "[$order->external_order_id]",
                        ]);

                        if ($response->ok()) {
                            $responseData = $response->json();


                            // Update the external order status
                            if (isset($responseData['data'][0]['order_id'])) {
                                $order->reply_msg = $responseData['data'][0]['replay_api'][0] ?? null;
                                $status = $responseData['data'][0]['status'] ?? "Error";

                                $order->status = $status === 'wait'
                                    ? 'pending'
                                    : ($status === 'reject'
                                        ? 'refunded'
                                        : ($status === 'accept'
                                            ? 'active'
                                            : $status));

                                $order->save();
                            }

                            if (($originalStatus === 'active' && $order->status === 'refunded') ||
                                ($originalStatus === 'pending' && $order->status === 'refunded')||
                                ($originalStatus === 'pending' && $order->status === 'reject')||
                                ($originalStatus === 'wait' && $order->status === 'reject')||
                                ($originalStatus === 'active' && $order->status === 'reject')||
                                ($originalStatus === 'accept' && $order->status === 'reject'))
                            {
                                $this->refundOrderAmount($order);
                            }

                            if( ($originalStatus === 'accept' && $order->status === 'reject')||
                                ($originalStatus === 'active' && $order->status === 'reject')){
                                $order->reply_msg = "برجاء مراجعة الطلب شخصيا";
                                $order->save();
                            }

                            // Log the response
                            \App\Models\ResponsesLog::create([
                                'type' => 'order_update',
                                'request_data' => ['orders' => $order->external_order_id],
                                'response_data' => $responseData,
                            ]);
                        } else {
                            // Log the error response
                            \App\Models\ResponsesLog::create([
                                'type' => 'order_update_error',
                                'request_data' => ['orders' => $order->external_order_id],
                                'error_message' => $response->body(),
                            ]);
                        }
                    }
                }
            }
        }
        catch (\Exception $e) {
            Log::error($e);
        }


        return view('admin.orders.index', compact('orders'))->with([
            'statusFilter' => $request->status,
            'startDate' => $request->start_date,
            'endDate' => $request->end_date,
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
            ($originalStatus === 'pending' && $order->status === 'refunded')||
            ($originalStatus === 'pending' && $order->status === 'reject')||
            ($originalStatus === 'accept' && $order->status === 'reject')||
            ($originalStatus === 'active' && $order->status === 'reject')) {
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


    public static function updateStatues(Request $request)
    {
        // Step 1: Validate the request
        $validated = $request->validate([
            'secret' => 'required|string',
            'order_id' => 'required|integer|exists:orders,id',
            'status' => 'required|string|in:pending,processing,active,canceled',
        ]);

        // Step 2: Check if the secret is valid (example: compare with a predefined secret or some logic)
        if ($validated['secret'] !== 'your_secret_key') {
            return response()->json(['error' => 'Invalid secret'], 403); // Unauthorized
        }

        // Step 3: Find the order by order_id
        $order = Order::find($validated['order_id']);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404); // Not Found
        }

        // Step 4: Update the status of the order
        $order->status = $validated['status'];  // Assuming status is a column in the orders table
        $order->save();

        // Step 5: Return a response
        return response()->json(['success' => 'Order status updated successfully'], 200);
    }

    public static function getStatues(Request $request)
    {
        $validated = $request->validate([
            'secret' => 'required|string',
            'order_id' => 'required|integer|exists:orders,id',
        ]);

//        if ($validated['secret'] !== 'your_secret_key') {
//            return response()->json(['error' => 'Invalid secret'], 403); // Unauthorized
//        }

        $order = Order::find($validated['order_id']);

        return response()->json(['success' => true, 'status' => $order->status], 200);
    }

    public static function storeApiOrder(Request $request)
    {
        $data = $request->all();
        try {
            $user = User::with(['currency', 'feeGroup'])->where('secret_key', $data['secret_key'])->first();
            $subItem = SubItem::with('item')->findOrFail($data['external_id']);

            $currencyPrice = 1;
            if ($user && $user->currency) {
                $currencyPrice = $user->currency->price;
            }
            $config = Config::first();
            $fee_name = "Default";
            if($user->feeGroup){
                $config->fee = $user->feeGroup->fee;
                $fee_name = $user->feeGroup->name ?? "";
            }
            $feePercentage = $config->fee;

            $sub_price = $subItem->price * $currencyPrice;
            $fe_price = 0;

            $fe_price = floatval($sub_price * $feePercentage / 100);
            $totalPrice = $sub_price + $fe_price;
            $order_amount = $subItem->amount;

            if ($user->wallet->balance < $totalPrice) {
                return response()->json(['success' => false, 'message' => 'Insufficient balance in wallet'], 400);
            }

            // Deduct the amount from the user's wallet
            $before_balance = $user->wallet->balance;
            $user->wallet->balance -= $totalPrice;
            $user->wallet->save();

            $after_balance = $user->wallet->balance;




            $order_details = [
                'user_id' => $user->id,
                'total' => $totalPrice,
                'status' => $data['status'],
                'user_email' => $user->email ?? null,
                'user_name' => $user->name ?? null,
                'user_phone' => $user->phone ?? null,
                'item_price' => $sub_price ?? 0,
                'item_fee' => $fe_price ?? 0,
                'fee_name' => $user->feeGroup->name ?? "",
                'item_name' => $data['item_name'] ?? null,
                'sub_item_name' => $subItem->name ?? null,
                'service_id' => $data['service_id'] ?? null,
                'amount' => $order_amount ?? 0,
                'wallet_before' => $before_balance ,
                'wallet_after' => $after_balance,
                'currency_id' => $user->currency->id ?? null,
                'item_id' => $subItem->item->id ?? null,
                'sub_item_id' => $subItem->id,
                'revenue' => $fe_price,
                'is_external' => true,
                'order_type' => $data['order_type'],
            ];

        // Create a new order
        $order = Order::create($order_details);

        // Handle any related sub-items if provided
            if (!empty($data['sub_items'])) {
                foreach ($data['sub_items'] as $subItemData) {
                    OrderSubItem::create([
                        'order_id' => $order->id,
                        'sub_item_id' => $subItem->id,
                        'price' => $subItemData['price'],
                        'service_id' => $subItemData['service_id'] ?? null,
                    ]);
                }
            }

        return response()->json(['status' => 'success', 'message' => 'Order created successfully.', 'data' => ['order_id' => $order->id]], 200);

        } catch (\Exception $e) {
            Log::error('Failed to store external order', ['error' => $e->getMessage()]);
            return null; // Or handle the error as needed
        }
    }


    protected function updateOrder($domain, $order_id, $external_order_id)
    {
        $payload = [
            'secret' => Auth::user()->secret_key,
            'order_id' => $external_order_id
        ];

        $url = $domain . '/api/get-order-status';
        // Send the request to the external API using the domain from SubItem
        try {

            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->get($url, $payload);
            // Check if the response was successful
            if ($response->successful()) {
                $responseData = $response->json();
                if (isset($responseData['success']) && $responseData['success']) {
                    $order = Order::find($order_id);
                    $originalStatus = $order->status;
                    $order->status = $responseData['status'];
                    $order->save();

                    if (($originalStatus === 'active' && $responseData['status'] === 'refunded') ||
                        ($originalStatus === 'pending' && $responseData['status'] === 'refunded')) {
                        $this->refundOrderAmount($order);
                    }

                    return ['success' => true, 'message' => 'External order processed successfully'];
                }
                return ['success' => false, 'message' => 'Failed to process external order'];

            } else {
                Log::error('Failed to process external order', ['response' => $response->body()]);
                return ['success' => false, 'message' => 'Failed to process external order'];
            }

        } catch (\Exception $e) {
            Log::error('Error processing external order', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Error processing external order'];
        }
    }

    public function order_analytics()
    {
        // Get total counts and revenues for orders
        $totalOrdersCount = Order::count();
        $totalOrdersRevenue = Order::sum('amount');

        // Count sub_items where external_id is NULL (manual)
        $manualOrdersCount = SubItem::whereNull('external_id')->count();
        $manualOrdersRevenue = SubItem::whereNull('external_id')->sum('amount');

        // Count sub_items where external_id is NOT NULL (API)
        $apiOrdersCount = SubItem::whereNotNull('external_id')->count();
        $apiOrdersRevenue = SubItem::whereNotNull('external_id')->sum('amount');

        // Orders grouped by status
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Count SubItems grouped by external_id (manual/API distinction)
        $subItemsCountByExternalId = SubItem::select(DB::raw('
        CASE
            WHEN external_id IS NULL THEN "manual"
            ELSE "API"
        END AS external_type,
        COUNT(*) AS count
    '))
            ->groupBy(DB::raw('
            CASE
                WHEN external_id IS NULL THEN "manual"
                ELSE "API"
            END
        '))
            ->get();

        // Top 3 manual SubItems based on revenue
        $topManualOrders = SubItem::whereNull('external_id')
            ->orderBy('amount', 'desc')
            ->take(3)
            ->get(['name', 'amount']);

        // Top 3 API SubItems based on revenue
        $topApiOrders = SubItem::whereNotNull('external_id')
            ->orderBy('amount', 'desc')
            ->take(3)
            ->get(['name', 'amount']);

        // Orders grouped by sub_item_id
        $ordersGroupedBySubItem = SubItem::select('sub_item_id', 'name', DB::raw('COUNT(*) as order_count'))
            ->join('orders', 'sub_items.id', '=', 'orders.sub_item_id') // Assuming orders table has sub_item_id
            ->groupBy('sub_item_id', 'name')
            ->get();


        // Pass data to the view
        return view('admin.orders.orders_analytics', compact(
            'totalOrdersCount', 'totalOrdersRevenue',
            'manualOrdersCount', 'manualOrdersRevenue',
            'apiOrdersCount', 'apiOrdersRevenue',
            'ordersByStatus',
            'subItemsCountByExternalId',
            'topManualOrders',
            'topApiOrders',
            'ordersGroupedBySubItem'
        ));
    }

}

