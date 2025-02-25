<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Barang::insert([
            [
                'kode_barang' => 'BRG001',
                'nama_barang' => 'Laptop Asus ROG',
                'kategori' => 'Elektronik',
                'merek' => 'Asus',
                'deskripsi' => 'Laptop gaming high performance',
                'satuan' => 'pcs',
                'harga_jual' => 20000000,
                'tanggal_kadaluarsa' => null,
                'stok' => 10,
                'lokasi_barang' => 'Rak A',
                'status' => 'aktif',
            ],
            [
                'kode_barang' => 'BRG002',
                'nama_barang' => 'Air Mineral 600ml',
                'kategori' => 'Minuman',
                'merek' => 'Aqua',
                'deskripsi' => 'Air mineral segar 600ml',
                'satuan' => 'liter',
                'harga_jual' => 3000,
                'tanggal_kadaluarsa' => '2025-12-31',
                'stok' => 100,
                'lokasi_barang' => 'Rak B',
                'status' => 'aktif',
            ],
        ]);
    }
}
