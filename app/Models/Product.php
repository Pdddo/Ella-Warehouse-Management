<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'sku', 'name', 'description', 'buy_price',
        'sell_price', 'stock', 'min_stock', 'unit', 'rack_location', 'image'
    ];

    //relasi ke kategori (milik dari)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    //relasi ke transactions details (memiliki banyak)
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    //relasi ke restock order details (memiliki banyak)
    public function restockOrderDetails()
    {
        return $this->hasMany(RestockOrderDetail::class);
    }
}
