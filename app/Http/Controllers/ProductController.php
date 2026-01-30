<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use App\Models\Stock;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Главная страница
    public function index(Request $request)
    {
        // Получаем популярные товары (можно изменить логику)
        $products = Product::with(['category', 'stock', 'reviews'])
            ->where('is_active', true)
            ->whereHas('stock', function($query) {
                $query->where('quantity', '>', 0); // Только товары в наличии
            })
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Получаем активные категории с количеством товаров
        $categories = Category::withCount(['products' => function($query) {
            $query->where('is_active', true);
        }])
        ->where('is_active', true)
        ->get();

        return view('home', compact('products', 'categories'));
    }
    
    public function show($id)
    {
        $product = Product::with(['category', 'stock'])->findOrFail($id);
        
        // Получаем отзывы для этого товара
        $reviews = Review::where('product_id', $id)
            ->where('is_approved', true) // Только одобренные отзывы
            ->with('user')
            ->latest()
            ->get();
        
        // Проверяем, есть ли у товара категория
        if ($product->category) {
            $category = $product->category;
        } else {
            // Если нет категории, создаем пустой объект
            $category = (object) [
                'name' => 'Без категории',
                'code' => '#',
            ];
        }
        
        // Увеличиваем счетчик просмотров (если поле существует)
        if (Schema::hasColumn('products', 'views')) {
            $product->increment('views');
        }
        
        // Получаем похожие товары
        $similarProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $id)
            ->where('is_active', true)
            ->take(4)
            ->get();
        
        // Передаем все переменные в шаблон
        return view('product.show', compact(
            'product', 
            'category', 
            'similarProducts', 
            'reviews'
        ));
    }
    
    // Метод для страницы каталога/поиска
    public function catalog(Request $request)
    {
        $query = Product::with(['category', 'stock', 'reviews'])
            ->where('is_active', true);
        
        // Поиск по названию или артикулу
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
        
        // Получаем все активные категории для фильтра
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
                $query->orderBy('views', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $products = $query->paginate(12)->withQueryString();
        
        return view('catalog.index', compact('products', 'categories'));
    }
}