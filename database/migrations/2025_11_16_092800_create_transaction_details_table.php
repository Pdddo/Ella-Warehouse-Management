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
        Schema::create('transaction_details', function (Blueprint $table) {
        $table->id();
        // Foreign Key ke tabel transactions
        $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
        // Foreign Key ke tabel products
        $table->foreignId('product_id')->constrained()->onDelete('cascade');
        $table->integer('quantity'); // Jumlah produk dalam transaksi ini
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
