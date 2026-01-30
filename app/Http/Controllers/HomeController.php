<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Популярные товары с запасом на складе
        $products = Product::with(['stock', 'category', 'reviews'])
            ->where('popular', 1)
            ->whereHas('stock', function($query) {
                $query->where('quantity', '>', 0);
            })
            ->limit(8)
            ->get();
        
        // Категории с количеством товаров
        $categories = Category::withCount(['products' => function($query) {
            $query->whereHas('stock', function($q) {
                $q->where('quantity', '>', 0);
            });
        }])
            ->whereNull('categories_id')
            ->limit(8)
            ->get();
        
        // Последние отзывы
        $recentReviews = Review::with(['user', 'product'])
            ->latest()
            ->limit(3)
            ->get();
        
        return view('index', compact('products', 'categories', 'recentReviews'));
    }
}