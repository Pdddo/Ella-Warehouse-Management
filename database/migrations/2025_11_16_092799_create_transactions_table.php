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
        Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->string('transaction_number')->unique(); // Nomor transaksi, misal: IN-20251116-001
        // Foreign Key ke staff yang mencatat transaksi
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
        $table->enum('type', ['incoming', 'outgoing']); // Jenis transaksi: masuk atau keluar
        $table->text('notes')->nullable();
        $table->string('status'); // Status: Pending, Approved, etc.
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
