<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
        'status',
        'total_price',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    // Отношения
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
    
    // Аксессоры
    protected function formattedStatus(): Attribute
    {
        return Attribute::make(
            get: function () {
                $statuses = [
                    'новый' => ['label' => 'Новый', 'color' => 'primary'],
                    'в_пути' => ['label' => 'В пути', 'color' => 'warning'],
                    'ожидает_на_пункте' => ['label' => 'Ожидает на пункте', 'color' => 'info'],
                    'получен' => ['label' => 'Получен', 'color' => 'success'],
                    'отменен' => ['label' => 'Отменен', 'color' => 'danger'],
                ];
                
                return $statuses[$this->status] ?? ['label' => $this->status, 'color' => 'secondary'];
            },
        );
    }
    
    protected function orderNumber(): Attribute
    {
        return Attribute::make(
            get: fn () => str_pad($this->id, 6, '0', STR_PAD_LEFT),
        );
    }
    
    // Скоупы
    public function scopeNew($query)
    {
        return $query->where('status', 'новый');
    }
    
    public function scopeCompleted($query)
    {
        return $query->where('status', 'получен');
    }
    
    public function scopeCancelled($query)
    {
        return $query->where('status', 'отменен');
    }
    
    // Методы
    public function updateStatus($status)
    {
        $validStatuses = ['новый', 'в_пути', 'ожидает_на_пункте', 'получен', 'отменен'];
        
        if (!in_array($status, $validStatuses)) {
            throw new \Exception('Неверный статус заказа');
        }
        
        $this->status = $status;
        return $this->save();
    }
    
    public function calculateTotal()
    {
        return $this->items->sum(function($item) {
            return $item->price * $item->quantity;
        });
    }
}