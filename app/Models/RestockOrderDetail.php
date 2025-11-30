<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RestockOrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'restock_order_id',
        'product_id',
        'quantity',
        'unit',
    ];

    // relasi dengan restock order (milik dari).
    public function restockOrder(): BelongsTo
    {
        return $this->belongsTo(RestockOrder::class);
    }

    // relasi dengan produk (milik dari).
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
