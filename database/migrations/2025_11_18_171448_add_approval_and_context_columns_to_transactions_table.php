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
        Schema::table('transactions', function (Blueprint $table) {
            // Kolom untuk melacak siapa & kapan approval terjadi
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            
            // Kolom untuk konteks transaksi (dari siapa/untuk siapa)
            $table->foreignId('supplier_id')->nullable()->constrained('users')->onDelete('set null')->after('approved_at');
            $table->string('customer_name')->nullable()->after('supplier_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['approved_at', 'customer_name']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn('approved_by');
            $table->dropForeign(['supplier_id']);
            $table->dropColumn('supplier_id');
        });
    }
};