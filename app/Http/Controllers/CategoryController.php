<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();
        return view('categories.index', compact('categories'));
    }
    
    public function show($code)
    {
        $category = Category::where('code', $code)->firstOrFail();
        $products = Product::with('stock')
            ->where('category_id', $category->id)
            ->paginate(12);
        
        return view('categories.show', compact('category', 'products'));
    }
}