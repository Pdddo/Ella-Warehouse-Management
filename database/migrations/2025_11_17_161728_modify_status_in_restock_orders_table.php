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
        Schema::table('restock_orders', function (Blueprint $table) {
            $newStatuses = ['pending', 'confirmed', 'denied', 'in_transit', 'received', 'cancelled'];
            // Ubah kolom enum untuk menggunakan daftar status yang baru
            $table->enum('status', $newStatuses)->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('restock_orders', function (Blueprint $table) {
            // Kembalikan ke daftar status lama jika migration di-rollback
            $oldStatuses = ['pending', 'completed', 'cancelled'];
            $table->enum('status', $oldStatuses)->default('pending')->change();
        });
    }
};
