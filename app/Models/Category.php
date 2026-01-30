<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'categories_id',
        'name',
        'code',
        'description',
        'image',
    ];

    // Отношения
    public function parent()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }
    
    public function children()
    {
        return $this->hasMany(Category::class, 'categories_id');
    }
    
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
    
    // Аксессоры
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->image) {
                    return asset('images/no-image.jpg');
                }
                
                $path = public_path('storage/' . $this->image);
                return file_exists($path) ? asset('storage/' . $this->image) : asset('images/no-image.jpg');
            },
        );
    }
    
    protected function productsCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->products()->count(),
        );
    }
}