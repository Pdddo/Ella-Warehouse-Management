<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('restock_order_details', function (Blueprint $table) {
            if (Schema::hasColumn('restock_order_details', 'unit')) {
                $table->dropColumn('unit');
            }
        });
    }


    public function down(): void
    {
        Schema::table('restock_order_details', function (Blueprint $table) {
            $table->string('unit')->after('quantity')->nullable();
        });
    }
};