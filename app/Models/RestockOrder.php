<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RestockOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'manager_id',
        'supplier_id',
        'order_date',
        'expected_delivery_date',
        'notes',
        'status',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'date',
            'expected_delivery_date' => 'date',
        ];
    }
    // Relasi: Restock order dibuat oleh satu manager (user)
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    // Relasi: Restock order diterima dari satu supplier (user)
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    // Relasi: Satu restock order memiliki banyak detail produk
    public function details(): HasMany
    {
        return $this->hasMany(RestockOrderDetail::class);
    }
}