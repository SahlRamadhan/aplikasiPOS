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
        // Hapus kunci asing
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropForeign('penjualan_id_pelanggan_foreign');
        });

        // Ubah kolom menjadi nullable
        Schema::table('penjualan', function (Blueprint $table) {
            $table->integer('id_pelanggan')
                ->nullable()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ubah kolom kembali ke nullable false
        Schema::table('penjualan', function (Blueprint $table) {
            $table->integer('id_pelanggan')
                ->nullable(false)
                ->change();
        });

        // Tambahkan kembali kunci asing
        Schema::table('penjualan', function (Blueprint $table) {
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')
                ->onDelete('restrict')->onUpdate('restrict');
        });
    }
};
