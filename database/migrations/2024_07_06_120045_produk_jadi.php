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
        Schema::create('produk_jadi', function (Blueprint $table) {
            $table->char('id',10)->primary();
            $table->string('nama', 25);
            $table->unsignedBigInteger('id_kategori');
            $table->integer('harga');
            $table->integer('stok');
            $table->timestamps();

            // Menambahkan kunci asing ke tabel pelanggan
            $table->foreign('id_kategori')->references('id')->on('kategori')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_jadi');
    }
};
