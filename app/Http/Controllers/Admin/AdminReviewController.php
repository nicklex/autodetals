<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['product', 'user'])->latest();
        
        // Фильтр по статусу
        if ($request->has('status')) {
            if ($request->status == 'pending') {
                $query->whereNull('is_approved');
            } elseif ($request->status == 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status == 'rejected') {
                $query->where('is_approved', false);
            }
        }
        
        // Фильтр по рейтингу
        if ($request->has('rating') && $request->rating) {
            $query->where('rating', $request->rating);
        }
        
        // Поиск
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('product', function($q2) use ($search) {
                    $q2->where('name', 'LIKE', "%{$search}%");
                })->orWhereHas('user', function($q2) use ($search) {
                    $q2->where('name', 'LIKE', "%{$search}%")
                       ->orWhere('email', 'LIKE', "%{$search}%");
                })->orWhere('comment', 'LIKE', "%{$search}%");
            });
        }
        
        // Фильтр по товару
        if ($request->has('product_id') && $request->product_id) {
            $query->where('product_id', $request->product_id);
        }
        
        $reviews = $query->paginate(20);
        
        // Статистика
        $totalReviews = Review::count();
        $approvedReviews = Review::where('is_approved', true)->count();
        $pendingReviews = Review::whereNull('is_approved')->count();
        $rejectedReviews = Review::where('is_approved', false)->count();
        
        return view('admin.reviews.index', compact(
            'reviews',
            'totalReviews',
            'approvedReviews',
            'pendingReviews',
            'rejectedReviews'
        ));
    }
    
    public function show(Review $review)
    {
        $review->load(['product', 'user']);
        return view('admin.reviews.show', compact('review'));
    }
    
public function approve(Review $review)
{
    \Log::info('Approve review', [
        'review_id' => $review->id,
        'current_is_approved' => $review->is_approved,
    ]);
    
    $review->update(['is_approved' => true]);
    
    \Log::info('Review approved', [
        'review_id' => $review->id,
        'new_is_approved' => $review->fresh()->is_approved,
    ]);
    
    return back()->with('success', 'Отзыв одобрен');
}

public function reject(Review $review)
{
    \Log::info('Reject review', [
        'review_id' => $review->id,
        'current_is_approved' => $review->is_approved,
    ]);
    
    $review->update(['is_approved' => false]);
    
    \Log::info('Review rejected', [
        'review_id' => $review->id,
        'new_is_approved' => $review->fresh()->is_approved,
    ]);
    
    return back()->with('success', 'Отзыв отклонен');
}
    
    public function destroy(Review $review)
    {
        $review->delete();
        
        return back()->with('success', 'Отзыв удален');
    }
    
    public function bulkActions(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:reviews,id',
        ]);
        
        $count = 0;
        
        switch ($request->action) {
            case 'approve':
                Review::whereIn('id', $request->review_ids)->update(['is_approved' => true]);
                $count = count($request->review_ids);
                $message = "Одобрено {$count} отзывов";
                break;
                
            case 'reject':
                Review::whereIn('id', $request->review_ids)->update(['is_approved' => false]);
                $count = count($request->review_ids);
                $message = "Отклонено {$count} отзывов";
                break;
                
            case 'delete':
                Review::whereIn('id', $request->review_ids)->delete();
                $count = count($request->review_ids);
                $message = "Удалено {$count} отзывов";
                break;
        }
        
        return back()->with('success', $message);
    }
}