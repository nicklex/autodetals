<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.product'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('orders.index', compact('orders'));
    }
    
    public function show(Order $order)
    {
        // Проверяем, что заказ принадлежит текущему пользователю
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Доступ запрещен');
        }
        
        $order->load(['items.product']);
        return view('orders.show', compact('order'));
    }
    
    public function store(Request $request)
    {
        // Этот метод теперь в CheckoutController
        return redirect()->route('checkout');
    }
    
    public function cancel(Request $request, Order $order)
    {
        // Проверяем, что заказ принадлежит текущему пользователю
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Доступ запрещен');
        }
        
        // Проверяем, можно ли отменить заказ
        if (!in_array($order->status, ['новый', 'в_пути'])) {
            return redirect()->back()
                ->with('error', 'Нельзя отменить заказ в текущем статусе');
        }
        
        // Обновляем статус заказа
        $order->status = 'отменен';
        $order->save();
        
        // Возвращаем товары на склад
        foreach ($order->items as $item) {
            $stock = \App\Models\Stock::where('product_id', $item->product_id)->first();
            if ($stock) {
                $stock->increment('quantity', $item->quantity);
            }
        }
        
        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Заказ успешно отменен');
    }
}