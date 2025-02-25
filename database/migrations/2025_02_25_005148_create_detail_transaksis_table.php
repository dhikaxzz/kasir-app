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
        Schema::create('detail_transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksis')->onDelete('cascade'); // Relasi ke transaksi
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade'); // Relasi ke barang
            $table->integer('jumlah'); // Jumlah barang yang dibeli
            $table->decimal('harga_satuan', 15, 2); // Harga per barang
            $table->decimal('subtotal', 15, 2); // Harga total per barang (jumlah * harga_satuan)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksis');
    }
};
