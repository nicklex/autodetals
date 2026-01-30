<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'new_orders' => Order::where('status', 'новый')->count(),
            'total_orders' => Order::count(),
            'total_products' => Product::count(),
            'total_users' => User::count(),
            'total_reviews' => Review::count(),
            'revenue' => Order::where('status', 'получен')->sum('total_price'),
        ];
        
        $latestOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        $lowStock = \App\Models\Product::with('stock')
            ->whereHas('stock', function($query) {
                $query->where('quantity', '<=', 5)->where('quantity', '>', 0);
            })
            ->limit(10)
            ->get();
        
        // Данные для графика (последние 7 дней)
        $chartData = [
            'labels' => [],
            'data' => []
        ];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('d.m');
            $chartData['labels'][] = $date;
            
            $ordersCount = Order::whereDate('created_at', now()->subDays($i))->count();
            $chartData['data'][] = $ordersCount;
        }
        
        return view('admin.dashboard', compact('stats', 'latestOrders', 'lowStock', 'chartData'));
    }
    
    public function statistics()
    {
        return view('admin.statistics');
    }
    
    public function salesStatistics()
    {
        return view('admin.statistics.sales');
    }
    
    public function productsStatistics()
    {
        return view('admin.statistics.products');
    }
    
    public function usersStatistics()
    {
        return view('admin.statistics.users');
    }
    
    public function settings()
    {
        return view('admin.settings');
    }
    
    public function updateSettings(Request $request)
    {
        // Логика обновления настроек
        return redirect()->route('admin.settings')->with('success', 'Настройки обновлены');
    }
}