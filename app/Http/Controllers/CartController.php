<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        // Если корзина пуста
        if (empty($cart)) {
            return view('basket.index', [
                'cartItems' => [],
                'total' => 0
            ]);
        }
        
        $cartItems = [];
        $total = 0;
        
        foreach ($cart as $productId => $item) {
            $product = Product::with('stock')->find($productId);
            
            if ($product) {
                // Добавляем информацию о товаре
                $item['product'] = $product;
                $item['total'] = $item['price'] * $item['quantity'];
                $cartItems[$productId] = $item;
                $total += $item['total'];
            }
        }
        
        return view('basket.index', compact('cartItems', 'total'));
    }
    
    public function add($id)
    {
        $product = Product::with('stock')->findOrFail($id);
        
        // Проверяем наличие на складе
        if (!$product->stock || $product->stock->quantity <= 0) {
            return redirect()->back()->with('error', 'Товара нет в наличии');
        }
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            // Проверяем, не превышает ли запрашиваемое количество остаток на складе
            $requestedQuantity = $cart[$id]['quantity'] + 1;
            if ($requestedQuantity > $product->stock->quantity) {
                return redirect()->back()->with('error', 'Недостаточно товара на складе. Максимум: ' . $product->stock->quantity);
            }
            
            $cart[$id]['quantity'] = $requestedQuantity;
        } else {
            // Получаем первое изображение товара
            $firstImage = 'images/no-image.jpg';
            if (!empty($product->images)) {
                $images = is_array($product->images) ? $product->images : json_decode($product->images, true);
                if (!empty($images) && is_array($images)) {
                    $firstImage = 'storage/' . $images[0];
                }
            }
            
            $cart[$id] = [
                'product_id' => $id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $firstImage,
                'stock_quantity' => $product->stock->quantity
            ];
        }
        
        session()->put('cart', $cart);
        
        return redirect()->back()->with('success', 'Товар "' . $product->name . '" добавлен в корзину');
    }
    
    public function update(Request $request, $id)
    {
        $product = Product::with('stock')->findOrFail($id);
        $quantity = $request->input('quantity', 1);
        
        // Проверяем наличие на складе
        if (!$product->stock || $quantity > $product->stock->quantity) {
            return redirect()->back()->with('error', 'Недостаточно товара на складе. Максимум: ' . $product->stock->quantity);
        }
        
        if ($quantity < 1) {
            return $this->remove($id);
        }
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $quantity;
            session()->put('cart', $cart);
            
            return redirect()->back()->with('success', 'Количество товара обновлено');
        }
        
        return redirect()->back()->with('error', 'Товар не найден в корзине');
    }
    
    public function remove($id)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            $productName = $cart[$id]['name'] ?? 'Товар';
            unset($cart[$id]);
            session()->put('cart', $cart);
            
            return redirect()->back()->with('success', 'Товар "' . $productName . '" удален из корзины');
        }
        
        return redirect()->back()->with('error', 'Товар не найден в корзине');
    }
    
    public function clear()
    {
        session()->forget('cart');
        
        return redirect()->route('basket')->with('success', 'Корзина очищена');
    }
    
    // ==================== AJAX МЕТОДЫ ====================
    
    public function getCartCount()
    {
        $cart = session()->get('cart', []);
        $count = 0;
        
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }
        
        return response()->json(['count' => $count]);
    }
    
    public function addAjax(Request $request)
    {
        try {
            $productId = $request->input('product_id');
            
            if (!$productId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Не указан ID товара'
                ], 400);
            }
            
            $product = Product::with('stock')->find($productId);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Товар не найден'
                ]);
            }
            
            // Проверяем наличие на складе
            if (!$product->stock || $product->stock->quantity <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Товара нет в наличии'
                ]);
            }
            
            $cart = session()->get('cart', []);
            
            if (isset($cart[$productId])) {
                // Проверяем остаток на складе
                $requestedQuantity = $cart[$productId]['quantity'] + 1;
                if ($requestedQuantity > $product->stock->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Недостаточно товара на складе. Максимум: ' . $product->stock->quantity
                    ]);
                }
                
                $cart[$productId]['quantity'] = $requestedQuantity;
            } else {
                // Получаем первое изображение товара
                $firstImage = 'images/no-image.jpg';
                if (!empty($product->images)) {
                    $images = is_array($product->images) ? $product->images : json_decode($product->images, true);
                    if (!empty($images) && is_array($images) && isset($images[0])) {
                        $firstImage = 'storage/' . $images[0];
                    }
                }
                
                $cart[$productId] = [
                    'product_id' => $productId,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => 1,
                    'image' => $firstImage,
                    'stock_quantity' => $product->stock->quantity
                ];
            }
            
            session()->put('cart', $cart);
            
            // Пересчитываем общее количество
            $totalCount = 0;
            $totalPrice = 0;
            
            foreach ($cart as $item) {
                $totalCount += $item['quantity'];
                $totalPrice += $item['price'] * $item['quantity'];
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Товар "' . $product->name . '" добавлен в корзину',
                'count' => $totalCount,
                'total' => $totalPrice,
                'cart' => $cart
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function updateAjax(Request $request, $id)
    {
        try {
            $quantity = $request->input('quantity', 1);
            
            $product = Product::with('stock')->find($id);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Товар не найден'
                ]);
            }
            
            // Проверяем наличие на складе
            if (!$product->stock || $quantity > $product->stock->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Недостаточно товара на складе. Максимум: ' . $product->stock->quantity
                ]);
            }
            
            if ($quantity < 1) {
                return $this->removeAjax($id);
            }
            
            $cart = session()->get('cart', []);
            
            if (isset($cart[$id])) {
                $cart[$id]['quantity'] = $quantity;
                session()->put('cart', $cart);
                
                // Пересчитываем общее количество и сумму
                $totalCount = 0;
                $totalPrice = 0;
                
                foreach ($cart as $item) {
                    $totalCount += $item['quantity'];
                    $totalPrice += $item['price'] * $item['quantity'];
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Количество обновлено',
                    'count' => $totalCount,
                    'item_total' => $product->price * $quantity,
                    'cart_total' => $totalPrice,
                    'item' => $cart[$id]
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Товар не найден в корзине'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function removeAjax($id)
    {
        try {
            $cart = session()->get('cart', []);
            
            if (isset($cart[$id])) {
                $productName = $cart[$id]['name'] ?? 'Товар';
                unset($cart[$id]);
                session()->put('cart', $cart);
                
                // Пересчитываем общее количество и сумму
                $totalCount = 0;
                $totalPrice = 0;
                
                foreach ($cart as $item) {
                    $totalCount += $item['quantity'];
                    $totalPrice += $item['price'] * $item['quantity'];
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Товар "' . $productName . '" удален из корзины',
                    'count' => $totalCount,
                    'cart_total' => $totalPrice
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Товар не найден в корзине'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка: ' . $e->getMessage()
            ], 500);
        }
    }
}