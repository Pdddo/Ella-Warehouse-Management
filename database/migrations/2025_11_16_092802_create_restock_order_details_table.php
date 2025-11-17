<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('restock_order_details', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel 'restock_orders'
            $table->foreignId('restock_order_id')
                  ->constrained() // Otomatis mencari tabel 'restock_orders'
                  ->onDelete('cascade'); // Jika PO dihapus, detailnya juga ikut terhapus

            // Foreign key ke tabel 'products'
            $table->foreignId('product_id')
                  ->constrained() // Otomatis mencari tabel 'products'
                  ->onDelete('cascade'); // Jika produk dihapus, detail order ini juga terhapus

            // Jumlah produk yang dipesan
            $table->integer('quantity');
            $table->string('unit', 50); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restock_order_details');
    }
};