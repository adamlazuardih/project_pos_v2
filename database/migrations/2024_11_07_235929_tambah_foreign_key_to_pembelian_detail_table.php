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
        Schema::table('pembelian_detail', function (Blueprint $table) {
            $table->unsignedInteger('id_pembelian')->change();
            $table->foreign('id_pembelian')->references('id_pembelian')->on('pembelian')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelian_detail', function (Blueprint $table) {
            $table->integer('id_pembelian')->change();
            $table->dropForeign('pembelian_detail_id_supplier_foreign');
        });
    }
};
