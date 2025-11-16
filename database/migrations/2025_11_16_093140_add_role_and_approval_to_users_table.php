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
    Schema::table('users', function (Blueprint $table) {
        // Menambahkan kolom 'role' setelah kolom 'email'
        // Role bisa: admin, manager, staff, supplier
        $table->string('role')->after('email');

        // Menambahkan kolom 'approved_at' untuk supplier.
        // Bisa NULL jika belum disetujui atau bukan supplier.
        $table->timestamp('approved_at')->nullable()->after('role');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
