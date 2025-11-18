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
        Schema::create('restock_orders', function (Blueprint $table) {
            $table->id(); // ID unik untuk setiap pesanan

            // Nomor Purchase Order (PO) yang akan kita generate, misal: PO-2025-001
            $table->string('po_number')->unique();

            // ID Manager yang membuat pesanan ini. Foreign key ke tabel 'users'.
            $table->foreignId('manager_id')
                  ->comment('User ID of the manager who created the order')
                  ->constrained('users')
                  ->onDelete('cascade');

            // ID Supplier yang dituju. Foreign key ke tabel 'users'.
            $table->foreignId('supplier_id')
                  ->comment('User ID of the supplier for this order')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->date('order_date');
            $table->date('expected_delivery_date')->nullable();
            $table->text('notes')->nullable();

            // Status pesanan. Enum membatasi nilainya hanya pada yang kita tentukan.
            $table->enum('status', ['pending', 'confirmed', 'in_transit', 'received']);

            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restock_orders');
    }
};