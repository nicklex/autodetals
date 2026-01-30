<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'stock', 'reviews'])
            ->where('is_active', true);
        
        // Поиск по названию, артикулу или описанию
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        // Фильтр по категории
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }
        
        // Фильтр по цене
        if ($request->has('price') && $request->price) {
            $priceRange = $request->price;
            if ($priceRange === '0-1000') {
                $query->where('price', '<=', 1000);
            } elseif ($priceRange === '1000-5000') {
                $query->whereBetween('price', [1000, 5000]);
            } elseif ($priceRange === '5000-10000') {
                $query->whereBetween('price', [5000, 10000]);
            } elseif ($priceRange === '10000+') {
                $query->where('price', '>=', 10000);
            }
        }
        
        // Получаем категории для фильтра
        $categories = Category::where('is_active', true)->get();
        
        // Сортировка
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                // Предполагаем, что есть поле views
                if (\Schema::hasColumn('products', 'views')) {
                    $query->orderBy('views', 'desc');
                } else {
                    $query->orderBy('created_at', 'desc');
                }
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $products = $query->paginate(12)->withQueryString();
        
        return view('catalog.index', compact('products', 'categories'));
    }
}