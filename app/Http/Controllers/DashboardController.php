<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\SubItem;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\UserWallet;
use App\Models\Currency;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Count of users with the "User" role
        $customersCount = User::where('is_external', 0)->whereHas('roles', function ($query) {
            $query->where('name', 'User');
        })->count();

        // Count of active users with the "User" role
        $activeUsersCount = User::where('is_external', 0)->whereHas('roles', function ($query) {
            $query->where('name', 'User');
        })->where('status', 'active')->count();

        // Count of inactive users with the "User" role
        $inactiveUsersCount = User::where('is_external', 0)->whereHas('roles', function ($query) {
            $query->where('name', 'User');
        })->where('status', 'inactive')->count();

        $partnersCount = User::where('is_external', 1)->whereHas('roles', function ($query) {
            $query->where('name', 'User');
        })->count();

        // Count of active users with the "User" role
        $activePartnersCount = User::where('is_external', 1)->whereHas('roles', function ($query) {
            $query->where('name', 'User');
        })->where('status', 'active')->count();

        // Count of inactive users with the "User" role
        $inactivePartnersCount = User::where('is_external', 1)->whereHas('roles', function ($query) {
            $query->where('name', 'User');
        })->where('status', 'inactive')->count();

        // Total orders count
        $ordersCount = Order::count();

        // Count of active orders
        $activeOrdersCount = Order::where('status', 'active')->count();

        // Count of refunded orders
        $refundedOrdersCount = Order::where('status', 'refunded')->count();

        $pendingOrdersCount = Order::where('status', 'pending')->count();

        $totalOrders = Order::sum('item_price'); // Sum of all orders
        $growth = $this->calculateGrowth(); // Custom function to calculate growth
        $conversationRate = $this->calculateConversationRate(); // Custom function to calculate conversation rate

        // Get top selling products
        $topSellingProducts = Item::withSum('orderItems', 'quantity')
            ->orderBy('order_items_sum_quantity', 'desc')
            ->take(5)
            ->get();

        // Get wallet and order data grouped by currency
        $currencyData = $this->getCurrencyData();

        // Get recent orders
        $recentOrders = Order::with(['user' => function ($query) {
            $query->with('currency');
        }])
            ->latest()
            ->take(10)
            ->get();

        // Get top selling sub-items
        $topSellingSubItems = SubItem::with('item')
            ->withCount('orderSubItems')
            ->orderBy('order_sub_items_count', 'desc')
            ->take(6)
            ->get();

        // Get revenue by locations
        $revenueByLocations = User::selectRaw('address, COALESCE(currencies.currency, "USD") as currency, SUM(orders.total) as total_revenue')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->leftJoin('currencies', 'users.currency_id', '=', 'currencies.id')
            ->whereIn('orders.status', ['active', 'pending'])
            ->groupBy('address', 'currencies.currency')
            ->get();

        return view('admin.index', compact(
            'customersCount',
            'activeUsersCount',
            'inactiveUsersCount',
            'ordersCount',
            'activeOrdersCount',
            'refundedOrdersCount',
            'pendingOrdersCount',
            'totalOrders',
            'growth',
            'conversationRate',
            'topSellingProducts',
            'currencyData',
            'recentOrders',
            'topSellingSubItems',
            'revenueByLocations',
            'partnersCount',
            'activePartnersCount',
            'inactivePartnersCount',
        ));
    }

    private function calculateGrowth()
    {
        // Implement logic to calculate growth
        return 20.6; // Example value
    }

    private function calculateConversationRate()
    {
        // Implement logic to calculate conversation rate
        return 9.62; // Example value
    }

    private function getCurrencyData()
    {
        $currencies = Currency::where('status', 'active')->get();
        $currencyData = [];

        // Loop through each currency
        foreach ($currencies as $currency) {
            $totalBalance = UserWallet::whereHas('user', function($query) use ($currency) {
                $query->where('currency_id', $currency->id);
            })->sum('balance');

            $revenue = Order::whereHas('user', function($query) use ($currency) {
                $query->where('currency_id', $currency->id);
            })->whereIn('status', ['active', 'pending'])->sum('revenue');

//            dd($revenue);

            $totalOrders = Order::whereHas('user', function($query) use ($currency) {
                $query->where('currency_id', $currency->id);
            })->whereIn('status', ['active', 'pending'])->sum('item_price');


            $approvedPurchaseRequests = PurchaseRequest::whereHas('user', function($query) use ($currency) {
                $query->where('currency_id', $currency->id);
            })->where('status', 'approved')->sum('amount');

            $previousMonthRevenue = Order::whereHas('user', function($query) use ($currency) {
                $query->where('currency_id', $currency->id);
            })->whereIn('status', ['active', 'pending'])
                ->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)
                ->sum('revenue');

            $percentageChange = $previousMonthRevenue > 0 ? (($revenue - $previousMonthRevenue) / $previousMonthRevenue) * 100 : ($revenue > 0 ? 100 : 0);

            $currencyData[] = [
                'currency' => $currency->currency,
                'total_balance' => $totalBalance,
                'revenue' => $revenue,
                'total_orders' => $totalOrders,
                'approved_purchase_requests' => $approvedPurchaseRequests,
                'percentage_change' => $percentageChange
            ];
        }

        return $currencyData;
    }
}
