<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoriList = [
            'Makanan Ringan',
            'Minuman',
            'Sembako',
            'Produk Susu',
            'Mie Instan',
            'Perlengkapan Mandi',
            'Perlengkapan Bayi',
            'Kesehatan',
            'Pembersih Rumah',
            'Alat Tulis',
            'Makanan Beku',
            'Roti & Kue',
            'Bumbu Dapur',
            'Kosmetik',
        ];

        foreach ($kategoriList as $nama) {
            Kategori::create([
                'nama' => $nama,
            ]);
        }
    }
}
