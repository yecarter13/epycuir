<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'categories' => Category::count(),
            'users' => User::count(),
            'low_stock' => Product::where('stock_quantity', '<', 5)->where('is_active', true)->count(),
            'revenue_today' => Order::where('status', 'paid')
                ->whereDate('created_at', today())
                ->sum('total'),
            'revenue_month' => Order::where('status', 'paid')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total'),
        ];

        $recentProducts = Product::latest()->take(5)->get();
        $categories = Category::withCount('products')->orderBy('sort_order')->get();

        return view('admin.dashboard', compact('stats', 'recentProducts', 'categories'));
    }
}
