<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'code',
        'description',
        'images',
        'price',
        'popular',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'double',
        'popular' => 'boolean',
    ];

    // Отношения
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    
    public function stock()
    {
        return $this->hasOne(Stock::class, 'product_id');
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }
    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }
    
    // Аксессоры
    protected function images(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true) ?? [],
            set: fn ($value) => json_encode($value),
        );
    }
    
    protected function firstImage(): Attribute
    {
        return Attribute::make(
            get: fn () => !empty($this->images) ? asset('storage/' . $this->images[0]) : asset('images/no-image.jpg'),
        );
    }
    
    protected function avgRating(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->reviews()->avg('rating') ?? 0,
        );
    }
    
    protected function reviewsCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->reviews()->count(),
        );
    }
    
    // Скоупы
    public function scopePopular($query)
    {
        return $query->where('popular', 1);
    }
    
    public function scopeInStock($query)
    {
        return $query->whereHas('stock', function($q) {
            $q->where('quantity', '>', 0);
        });
    }
    
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}