<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'session_id',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    // Отношения
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->with('stock');
    }
    
    // Аксессоры
    protected function total(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->product ? $this->product->price * $this->quantity : 0;
            },
        );
    }
    
    // Скоупы
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    
    public function scopeBySession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }
}