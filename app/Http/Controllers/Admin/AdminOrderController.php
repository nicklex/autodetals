<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('admin.orders.index', compact('orders'));
    }
    
    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }
    
    public function edit(Order $order)
    {
        $order->load(['user', 'items.product']);
        $statuses = ['новый', 'в_пути', 'ожидает_на_пункте', 'получен', 'отменен'];
        
        return view('admin.orders.edit', compact('order', 'statuses'));
    }
    
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'status' => 'required|in:новый,в_пути,ожидает_на_пункте,получен,отменен',
            'total_price' => 'required|numeric|min:0',
        ]);
        
        $order->update($validated);
        
        return redirect()->route('admin.orders.index')
            ->with('success', 'Заказ успешно обновлен');
    }
    
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:новый,в_пути,ожидает_на_пункте,получен,отменен',
        ]);
        
        $order->status = $request->status;
        $order->save();
        
        return redirect()->back()
            ->with('success', 'Статус заказа обновлен');
    }
    
    public function destroy(Order $order)
    {
        // Возвращаем товары на склад
        foreach ($order->items as $item) {
            $stock = \App\Models\Stock::where('product_id', $item->product_id)->first();
            if ($stock) {
                $stock->increment('quantity', $item->quantity);
            }
        }
        
        $order->delete();
        
        return redirect()->route('admin.orders.index')
            ->with('success', 'Заказ успешно удален');
    }
    
    public function generateInvoice(Order $order)
    {
        // Генерация счета/накладной
        $order->load(['user', 'items.product']);
        
        return response()->streamDownload(function() use ($order) {
            echo view('admin.orders.invoice', compact('order'))->render();
        }, 'invoice-' . $order->id . '.pdf');
    }
}