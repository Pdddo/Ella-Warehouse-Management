<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('restock_orders', function (Blueprint $table) {
            // skala 1-5
            $table->unsignedTinyInteger('rating')->nullable();
            // Feedback teks (opsional ajah)
            $table->text('supplier_feedback')->nullable();
        });
    }

    public function down()
    {
        Schema::table('restock_orders', function (Blueprint $table) {
            $table->dropColumn(['rating', 'supplier_feedback']);
        });
}
};
