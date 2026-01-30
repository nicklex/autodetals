<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    // Отношения
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
    // Скоупы
    public function scopeLowStock($query, $threshold = 5)
    {
        return $query->where('quantity', '<=', $threshold)->where('quantity', '>', 0);
    }
    
    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', '<=', 0);
    }
    
    // Методы
    public function decrease($amount)
    {
        if ($this->quantity < $amount) {
            throw new \Exception('Недостаточно товара на складе');
        }
        
        $this->quantity -= $amount;
        return $this->save();
    }
    
    public function increase($amount)
    {
        $this->quantity += $amount;
        return $this->save();
    }
    
    public function isAvailable($quantity = 1)
    {
        return $this->quantity >= $quantity;
    }
}