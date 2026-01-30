<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'admin',
        'phone',
        'addresses',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'admin' => 'boolean',
        'addresses' => 'array',
    ];

    // Отношения
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }
    
    public function cartItems()
    {
        // Если используете таблицу для корзины
        return $this->hasMany(CartItem::class, 'user_id');
    }
    
    // Аксессоры
    protected function addresses(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true) ?? [],
            set: fn ($value) => json_encode($value),
        );
    }
    
    protected function defaultAddress(): Attribute
    {
        return Attribute::make(
            get: function () {
                $addresses = $this->addresses;
                return !empty($addresses) ? $addresses[0] : null;
            },
        );
    }
    
    // Методы
    public function isAdmin()
    {
        return $this->admin == 1;
    }
    
    public function addAddress($address)
    {
        $addresses = $this->addresses;
        
        if (count($addresses) >= 3) {
            throw new \Exception('Можно добавить не более 3 адресов');
        }
        
        $addresses[] = $address;
        $this->addresses = $addresses;
        return $this->save();
    }
    
    public function removeAddress($index)
    {
        $addresses = $this->addresses;
        
        if (isset($addresses[$index])) {
            array_splice($addresses, $index, 1);
            $this->addresses = $addresses;
            return $this->save();
        }
        
        return false;
    }
}