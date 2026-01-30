<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
        public function myReviews()
    {
        $reviews = Review::where('user_id', Auth::id())
            ->with('product')
            ->latest()
            ->paginate(10);
            
        return view('reviews.my', compact('reviews'));
    }
    public function store(Request $request, $productId)
    {
        // Проверяем, существует ли товар
        $product = Product::findOrFail($productId);
        
        // Валидация данных
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
            'advantages' => 'nullable|string|max:500',
            'disadvantages' => 'nullable|string|max:500',
        ]);
        
        // Создаем отзыв
        $review = Review::create([
            'product_id' => $productId,
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'advantages' => $validated['advantages'] ?? null,
            'disadvantages' => $validated['disadvantages'] ?? null,
            'is_approved' => null, // На модерации по умолчанию
        ]);
        
        // Перенаправляем обратно с сообщением об успехе
        return redirect()->back()
            ->with('success', 'Спасибо за ваш отзыв! Он будет опубликован после проверки модератором.');
    }
    
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        
        // Проверяем, что пользователь удаляет свой отзыв или является администратором
        if (Auth::id() !== $review->user_id && !Auth::user()->is_admin) {
            abort(403, 'У вас нет прав для удаления этого отзыва');
        }
        
        $review->delete();
        
        return redirect()->back()
            ->with('success', 'Отзыв успешно удален');
    }
}