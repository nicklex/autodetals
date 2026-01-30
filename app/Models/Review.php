<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'advantages',
        'disadvantages',
        'comment',
        'is_approved',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    // Отношения
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    // Скоупы
    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_at');
    }
    
    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }
    
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
    
    // Методы
    public function approve()
    {
        $this->approved_at = now();
        return $this->save();
    }
    
    public function isApproved()
    {
        return !is_null($this->approved_at);
    }
}