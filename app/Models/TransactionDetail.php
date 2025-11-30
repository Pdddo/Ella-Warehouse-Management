<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_id', 'product_id', 'quantity'];

    // detail ini milik satu transaksi (milik dari)
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // detail produk merujuk ke satu produk (milik dari)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
