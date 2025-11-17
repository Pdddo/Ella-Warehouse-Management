<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_number', 'user_id', 'type', 'notes', 'status'];

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
}
