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
            // Kolom ini akan menyimpan timestamp kapan order diproses menjadi transaksi.
            // Jika nilainya NULL, berarti order tersebut belum diproses.
            // Kita letakkan setelah kolom 'status' agar rapi.
            $table->timestamp('processed_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restock_orders', function (Blueprint $table) {
            $table->dropColumn('processed_at');
        });
    }
};