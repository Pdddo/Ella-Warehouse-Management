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
        Schema::create('products', function (Blueprint $table) {
        $table->id();
        // Ini adalah Foreign Key ke tabel 'categories'
        // Jika kategori dihapus, produk di dalamnya juga ikut terhapus (onDelete('cascade'))
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->string('sku')->unique(); // SKU, harus unik
        $table->string('name');
        $table->text('description')->nullable();
        $table->decimal('buy_price', 15, 2); // Harga beli, total 15 digit, 2 di belakang koma
        $table->decimal('sell_price', 15, 2); // Harga jual
        $table->integer('stock'); // Stok saat ini
        $table->integer('min_stock'); // Stok minimum untuk alert
        $table->string('unit'); // Satuan: pcs, box, kg
        $table->string('rack_location')->nullable(); // Lokasi rak, boleh kosong
        $table->string('image')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
