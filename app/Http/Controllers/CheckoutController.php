<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index()
    {
        // Проверяем, что корзина не пуста
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('basket')->with('error', 'Ваша корзина пуста');
        }
        
        $user = Auth::user();
        
        // Получаем адреса пользователя
        $addresses = [];
        if (!empty($user->addresses)) {
            if (is_string($user->addresses)) {
                $decoded = json_decode($user->addresses, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $addresses = $decoded;
                }
            } elseif (is_array($user->addresses)) {
                $addresses = $user->addresses;
            }
        }
        
        // Рассчитываем итоги
        $cartItems = [];
        $subtotal = 0;
        
        foreach ($cart as $productId => $item) {
            $product = Product::with('stock')->find($productId);
            
            if ($product && $product->stock && $product->stock->quantity > 0) {
                $itemTotal = $item['price'] * $item['quantity'];
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $itemTotal
                ];
                $subtotal += $itemTotal;
            }
        }
        
        if (empty($cartItems)) {
            return redirect()->route('basket')->with('error', 'В вашей корзине нет доступных товаров');
        }
        
        // Стоимость доставки
        $deliveryCost = $subtotal >= 5000 ? 0 : 350; // Бесплатная доставка от 5000 ₽
        
        return view('checkout.index', compact('cartItems', 'subtotal', 'deliveryCost', 'addresses'));
    }
    
    public function store(Request $request)
    {
        // Логируем входные данные для отладки
        Log::info('Checkout form submitted', [
            'user_id' => Auth::id(),
            'form_data' => $request->all(),
            'cart' => session()->get('cart', [])
        ]);
        
        // Валидация данных
        // Валидация
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:500', // Это скрытое поле
        'email' => 'nullable|email|max:255',
        'comment' => 'nullable|string|max:1000',
        'payment_method' => 'required|in:card,cash,online',
        'delivery_method' => 'required|in:standard,express',
        'terms' => 'required|accepted',
        'address_option' => 'required', // Новое поле
        'city' => 'required_if:address_option,new',
        'street' => 'required_if:address_option,new',
    ], [
        'address.required' => 'Адрес доставки обязателен',
        'terms.required' => 'Необходимо согласиться с условиями покупки',
        'city.required_if' => 'Город обязателен для нового адреса',
        'street.required_if' => 'Улица обязательна для нового адреса',
    ]);
    
    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }
    
    // Если выбран новый адрес, формируем полный адрес
    if ($request->address_option === 'new') {
        $fullAddress = $request->city . ', ' . $request->street;
        if (!empty($request->apartment)) {
            $fullAddress .= ', кв. ' . $request->apartment;
        }
        if (!empty($request->postal_code)) {
            $fullAddress .= ' (' . $request->postal_code . ')';
        }
        
        // Используем этот адрес
        $address = $fullAddress;
    } else {
        // Используем выбранный сохраненный адрес
        $address = $request->address_option;
    }
        
        // Проверяем корзину
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('basket')->with('error', 'Ваша корзина пуста');
        }
        
        // Проверяем наличие товаров и рассчитываем сумму
        $cartItems = [];
        $total = 0;
        $errors = [];
        
        foreach ($cart as $productId => $item) {
            $product = Product::with('stock')->find($productId);
            
            if (!$product) {
                $errors[] = "Товар '{$item['name']}' не найден";
                continue;
            }
            
            if (!$product->stock || $product->stock->quantity < $item['quantity']) {
                $errors[] = "Недостаточно товара '{$product->name}' на складе. Доступно: " . ($product->stock ? $product->stock->quantity : 0) . " шт.";
                continue;
            }
            
            $itemTotal = $item['price'] * $item['quantity'];
            $cartItems[] = [
                'product' => $product,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $itemTotal
            ];
            $total += $itemTotal;
        }
        
        if (!empty($errors)) {
            return redirect()->route('basket')
                ->withErrors($errors)
                ->withInput();
        }
        
        if (empty($cartItems)) {
            return redirect()->route('basket')->with('error', 'В вашей корзине нет доступных товаров');
        }
        
        // Стоимость доставки
        $deliveryCost = 0;
        if ($request->delivery_method === 'express') {
            $deliveryCost = 650;
        } elseif ($total < 5000) {
            $deliveryCost = 350;
        }
        
        $totalWithDelivery = $total + $deliveryCost;
        
        try {
            // Начинаем транзакцию
            \DB::beginTransaction();
            
            // Создаем заказ
            $order = Order::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => 'новый',
                'total_price' => $totalWithDelivery,
            ]);
            
            Log::info('Order created', ['order_id' => $order->id]);
            
            // Добавляем товары в заказ
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem['product']->id,
                    'quantity' => $cartItem['quantity'],
                    'price' => $cartItem['price'],
                ]);
                
                Log::info('Order item added', [
                    'order_id' => $order->id,
                    'product_id' => $cartItem['product']->id,
                    'quantity' => $cartItem['quantity']
                ]);
                
                // Уменьшаем количество на складе
                $stock = Stock::where('product_id', $cartItem['product']->id)->first();
                if ($stock) {
                    $stock->decrement('quantity', $cartItem['quantity']);
                    Log::info('Stock updated', [
                        'product_id' => $cartItem['product']->id,
                        'new_quantity' => $stock->quantity
                    ]);
                }
            }
            
            // Если пользователь хочет сохранить адрес
            if ($request->filled('save_address') && Auth::check()) {
                $user = Auth::user();
                $addresses = [];
                
                if (!empty($user->addresses)) {
                    if (is_string($user->addresses)) {
                        $decoded = json_decode($user->addresses, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $addresses = $decoded;
                        }
                    } elseif (is_array($user->addresses)) {
                        $addresses = $user->addresses;
                    }
                }
                
                $fullAddress = $request->address;
                if (!in_array($fullAddress, $addresses) && count($addresses) < 3) {
                    $addresses[] = $fullAddress;
                    $user->addresses = json_encode($addresses);
                    $user->save();
                    Log::info('Address saved for user', ['user_id' => $user->id]);
                }
            }
            
            // Очищаем корзину
            session()->forget('cart');
            
            // Фиксируем транзакцию
            \DB::commit();
            
            Log::info('Checkout completed successfully', ['order_id' => $order->id]);
            
            // Перенаправляем на страницу заказа
            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Заказ успешно оформлен! Номер заказа: #' . str_pad($order->id, 6, '0', STR_PAD_LEFT));
                
        } catch (\Exception $e) {
            // Откатываем транзакцию при ошибке
            \DB::rollBack();
            
            Log::error('Checkout failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Произошла ошибка при оформлении заказа: ' . $e->getMessage())
                ->withInput();
        }
    }
}