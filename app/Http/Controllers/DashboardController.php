<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use App\Models\Order;
use App\Models\SubItem;
use App\Models\PurchaseRequest;
class DashboardController extends Controller
{
    public function index()
    {
        $customersCount = User::count();
        $ordersCount = Order::count();
        $revenue = Order::sum('total'); // Assuming 'total' is a column in the 'orders' table
        $growth = $this->calculateGrowth(); // Custom function to calculate growth
        $conversationRate = $this->calculateConversationRate(); // Custom function to calculate conversation rate

        // Get top selling products
        $topSellingProducts = Item::withSum('orderItems', 'quantity')
            ->orderBy('order_items_sum_quantity', 'desc')
            ->take(5)
            ->get();

        return view('admin.index', compact(
            'customersCount',
            'ordersCount',
            'revenue',
            'growth',
            'conversationRate',
            'topSellingProducts'
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
}
