<?php

namespace App\Http\Controllers;

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
        $customersCount = User::count();
        $ordersCount = Order::count();
        $totalOrders = Order::sum('total'); // Sum of all orders
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
            ->take(5)
            ->get();

        // Add this to your index method in DashboardController
        // Add this to your index method in DashboardController
        $topSellingSubItems = SubItem::with('item')
            ->withCount('orderSubItems')
            ->orderBy('order_sub_items_count', 'desc')
            ->take(5)
            ->get();

        $revenueByLocations = User::selectRaw('address, COALESCE(currencies.currency, "USD") as currency, SUM(orders.total) as total_revenue')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->leftJoin('currencies', 'users.currency_id', '=', 'currencies.id')
            ->whereIn('orders.status', ['active', 'pending'])
            ->groupBy('address', 'currencies.currency')
            ->get();



        return view('admin.index', compact(
            'customersCount',
            'ordersCount',
            'totalOrders',
            'growth',
            'conversationRate',
            'topSellingProducts',
            'currencyData',
            'recentOrders',
            'topSellingSubItems',
            'revenueByLocations',
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
        $currencies = Currency::all();
        $currencyData = [];

        // Loop through each currency
        foreach ($currencies as $currency) {
            $totalBalance = UserWallet::whereHas('user', function($query) use ($currency) {
                $query->where('currency_id', $currency->id);
            })->sum('balance');

            $revenue = Order::whereHas('user', function($query) use ($currency) {
                $query->where('currency_id', $currency->id);
            })->whereIn('status', ['active', 'pending'])->sum('total');

            $totalOrders = Order::whereHas('user', function($query) use ($currency) {
                $query->where('currency_id', $currency->id);
            })->sum('total');

            $previousMonthRevenue = Order::whereHas('user', function($query) use ($currency) {
                $query->where('currency_id', $currency->id);
            })->whereIn('status', ['active', 'pending'])
                ->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)
                ->sum('total');

            $percentageChange = $previousMonthRevenue > 0 ? (($revenue - $previousMonthRevenue) / $previousMonthRevenue) * 100 : ($revenue > 0 ? 100 : 0);

            $currencyData[] = [
                'currency' => $currency->currency,
                'total_balance' => $totalBalance,
                'revenue' => $revenue,
                'total_orders' => $totalOrders,
                'percentage_change' => $percentageChange
            ];
        }

        // Aggregate data for users with no specified currency (default to USD)
        $totalBalance = UserWallet::whereHas('user', function($query) {
            $query->whereNull('currency_id');
        })->sum('balance');

        $revenue = Order::whereHas('user', function($query) {
            $query->whereNull('currency_id');
        })->whereIn('status', ['active', 'pending'])->sum('total');

        $totalOrders = Order::whereHas('user', function($query) {
            $query->whereNull('currency_id');
        })->sum('total');

        $previousMonthRevenue = Order::whereHas('user', function($query) {
            $query->whereNull('currency_id');
        })->whereIn('status', ['active', 'pending'])
            ->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)
            ->sum('total');

        $percentageChange = $previousMonthRevenue > 0 ? (($revenue - $previousMonthRevenue) / $previousMonthRevenue) * 100 : ($revenue > 0 ? 100 : 0);

        $currencyData[] = [
            'currency' => 'USD',
            'total_balance' => $totalBalance,
            'revenue' => $revenue,
            'total_orders' => $totalOrders,
            'percentage_change' => $percentageChange
        ];

        return $currencyData;
    }
}
