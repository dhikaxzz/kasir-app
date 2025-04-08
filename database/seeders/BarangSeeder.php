<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Kategori;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil daftar kategori dari DB, buat array key: nama, value: id
        $kategoriMap = Kategori::pluck('id', 'nama');

        $barangs = [
            [
                'kode_barang' => 'BRG-001',
                'nama_barang' => 'Indomie Goreng',
                'merek' => 'Indomie',
                'varian' => 'Goreng Original',
                'satuan' => 'pcs',
                'harga_jual' => 3000,
                'stok' => 100,
                'lokasi_rak' => 'Rak A1',
                'expired_date' => now()->addMonths(6),
                'kategori' => 'Mie Instan',
            ],
            [
                'kode_barang' => 'BRG-002',
                'nama_barang' => 'Aqua Botol 600ml',
                'merek' => 'Aqua',
                'varian' => '600ml',
                'satuan' => 'pcs',
                'harga_jual' => 4000,
                'stok' => 80,
                'lokasi_rak' => 'Rak B2',
                'expired_date' => now()->addMonths(4),
                'kategori' => 'Minuman',
            ],
            [
                'kode_barang' => 'BRG-003',
                'nama_barang' => 'Beras Ramos 5kg',
                'merek' => 'Maknyuss',
                'varian' => 'Ramos',
                'satuan' => 'kg',
                'harga_jual' => 60000,
                'stok' => 40,
                'lokasi_rak' => 'Rak C1',
                'expired_date' => null,
                'kategori' => 'Sembako',
            ],
            [
                'kode_barang' => 'BRG-004',
                'nama_barang' => 'Lifebuoy Body Wash',
                'merek' => 'Lifebuoy',
                'varian' => 'Total 10',
                'satuan' => 'liter',
                'harga_jual' => 18000,
                'stok' => 60,
                'lokasi_rak' => 'Rak D3',
                'expired_date' => now()->addMonths(8),
                'kategori' => 'Perlengkapan Mandi',
            ],
            [
                'kode_barang' => 'BRG-005',
                'nama_barang' => 'Sweety Silver Pants',
                'merek' => 'Sweety',
                'varian' => 'M34',
                'satuan' => 'pcs',
                'harga_jual' => 75000,
                'stok' => 30,
                'lokasi_rak' => 'Rak E1',
                'expired_date' => now()->addMonths(12),
                'kategori' => 'Perlengkapan Bayi',
            ],
            [
                'kode_barang' => 'BRG-006',
                'nama_barang' => 'Paracetamol 500mg',
                'merek' => 'Generic',
                'varian' => 'Tablet',
                'satuan' => 'pcs',
                'harga_jual' => 1000,
                'stok' => 200,
                'lokasi_rak' => 'Rak F2',
                'expired_date' => now()->addMonths(10),
                'kategori' => 'Kesehatan',
            ],
            [
                'kode_barang' => 'BRG-007',
                'nama_barang' => 'Sunlight Jeruk Nipis 750ml',
                'merek' => 'Sunlight',
                'varian' => 'Jeruk Nipis',
                'satuan' => 'liter',
                'harga_jual' => 13000,
                'stok' => 50,
                'lokasi_rak' => 'Rak G1',
                'expired_date' => now()->addMonths(14),
                'kategori' => 'Pembersih Rumah',
            ],
            [
                'kode_barang' => 'BRG-008',
                'nama_barang' => 'Pensil 2B',
                'merek' => 'Faber-Castell',
                'varian' => '2B',
                'satuan' => 'pcs',
                'harga_jual' => 3000,
                'stok' => 120,
                'lokasi_rak' => 'Rak H1',
                'expired_date' => null,
                'kategori' => 'Alat Tulis',
            ],
        ];

        foreach ($barangs as $data) {
            Barang::create([
                'kode_barang' => $data['kode_barang'],
                'nama_barang' => $data['nama_barang'],
                'merek' => $data['merek'],
                'varian' => $data['varian'],
                'satuan' => $data['satuan'],
                'harga_jual' => $data['harga_jual'],
                'stok' => $data['stok'],
                'lokasi_rak' => $data['lokasi_rak'],
                'expired_date' => $data['expired_date'],
                'kategori_id' => $kategoriMap[$data['kategori']] ?? null,
            ]);
        }
    }
}
