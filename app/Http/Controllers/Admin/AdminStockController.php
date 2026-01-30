<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Stock;
use Illuminate\Http\Request;

class AdminStockController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'stock'])->has('stock');
        
        // Получаем категории для фильтра
        $categories = Category::all();
        
        // Фильтр по поиску
        if ($request->has('search') && $request->search) {
            $query->where('name', 'LIKE', "%{$request->search}%");
        }
        
        // Фильтр по категории
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        // Фильтр по количеству
        if ($request->has('stock_filter')) {
            if ($request->stock_filter == 'low') {
                $query->whereHas('stock', function($q) {
                    $q->where('quantity', '<', 10)->where('quantity', '>', 0);
                });
            } elseif ($request->stock_filter == 'out') {
                $query->whereHas('stock', function($q) {
                    $q->where('quantity', '<=', 0);
                });
            } elseif ($request->stock_filter == 'enough') {
                $query->whereHas('stock', function($q) {
                    $q->where('quantity', '>=', 10);
                });
            }
        }
        
        $products = $query->paginate(20);
        
        return view('admin.stocks.index', compact('products', 'categories'));
    }
    
    public function lowStock()
    {
        // Товары с количеством менее 10
        $products = Product::with(['category', 'stock'])
            ->whereHas('stock', function($q) {
                $q->where('quantity', '<', 10);
            })
            ->paginate(20);
            
        // Статистика
        $lowStockCount = Product::whereHas('stock', function($q) {
            $q->where('quantity', '<', 10)->where('quantity', '>', 0);
        })->count();
        
        $criticalCount = Product::whereHas('stock', function($q) {
            $q->where('quantity', '<', 5)->where('quantity', '>', 0);
        })->count();
        
        $outOfStockCount = Product::whereHas('stock', function($q) {
            $q->where('quantity', '<=', 0);
        })->count();
        
        $normalCount = Product::whereHas('stock', function($q) {
            $q->where('quantity', '>=', 10);
        })->count();
        
        return view('admin.stocks.low', compact(
            'products', 
            'lowStockCount',
            'criticalCount',
            'outOfStockCount',
            'normalCount'
        ));
    }
    
    public function edit(Product $product)
    {
        $product->load(['category', 'stock']);
        
        // Здесь можно добавить логику для истории изменений запаса
        // $stockHistory = \App\Models\StockHistory::where('product_id', $product->id)->latest()->get();
        $stockHistory = collect(); // Пока пустая коллекция
        
        return view('admin.stocks.edit', compact('product', 'stockHistory'));
    }
    
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'operation' => 'required|in:add,subtract,set',
            'quantity' => 'required|integer|min:0',
            'reason' => 'nullable|string|max:500',
        ]);
        
        $stock = $product->stock ?? $product->stock()->create(['quantity' => 0, 'reserved' => 0]);
        $oldQuantity = $stock->quantity;
        
        switch ($request->operation) {
            case 'add':
                $newQuantity = $oldQuantity + $request->quantity;
                break;
            case 'subtract':
                $newQuantity = max(0, $oldQuantity - $request->quantity);
                break;
            case 'set':
                $newQuantity = $request->quantity;
                break;
            default:
                $newQuantity = $oldQuantity;
        }
        
        $stock->quantity = $newQuantity;
        $stock->save();
        
        // Здесь можно добавить запись в историю изменений
        // StockHistory::create([...])
        
        return redirect()->back()
            ->with('success', 'Запас обновлен. Было: ' . $oldQuantity . ' шт., стало: ' . $newQuantity . ' шт.');
    }
    
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'operation' => 'required|in:add,subtract,set',
            'quantity' => 'required|integer|min:0',
        ]);
        
        $query = Product::query();
        
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        $products = $query->get();
        $updatedCount = 0;
        
        foreach ($products as $product) {
            $stock = $product->stock ?? $product->stock()->create(['quantity' => 0, 'reserved' => 0]);
            $oldQuantity = $stock->quantity;
            
            switch ($request->operation) {
                case 'add':
                    $newQuantity = $oldQuantity + $request->quantity;
                    break;
                case 'subtract':
                    $newQuantity = max(0, $oldQuantity - $request->quantity);
                    break;
                case 'set':
                    $newQuantity = $request->quantity;
                    break;
            }
            
            $stock->quantity = $newQuantity;
            $stock->save();
            $updatedCount++;
        }
        
        return redirect()->route('admin.stocks.index')
            ->with('success', 'Обновлено ' . $updatedCount . ' товаров.');
    }
}