<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role', 
        'is_approved',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'approved_at' => 'datetime',
        ];
    }

    // Relasi untuk manager yang membuat restock order
    public function createdRestockOrders()
    {
        return $this->hasMany(RestockOrder::class, 'manager_id');
    }

    // Relasi untuk supplier yang menerima restock order
    public function receivedRestockOrders()
    {
        return $this->hasMany(RestockOrder::class, 'supplier_id');
    }

    // Relasi untuk staff yang membuat transaksi
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }
}