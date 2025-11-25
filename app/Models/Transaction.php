<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_number', 'user_id', 'type', 'notes', 'status', 'supplier_id', 'customer_name', 'approved_by', 'approved_at',];

    
    protected $casts = [
            'approved_at' => 'datetime',
        ];


    // Relasi: Transaksi dibuat oleh satu user (staff)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Satu transaksi memiliki banyak detail produk
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    // Relasi: Transaksi diterima dari satu supplier 
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
