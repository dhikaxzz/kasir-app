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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique(); // Kode unik transaksi
            $table->dateTime('tanggal')->default(now()); // Waktu transaksi
            $table->decimal('total_harga', 15, 2); // Total harga semua barang
            $table->decimal('total_bayar', 15, 2); // Total uang yang dibayar
            $table->enum('metode_pembayaran', ['cash', 'debit', 'qris']); // Metode pembayaran
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
