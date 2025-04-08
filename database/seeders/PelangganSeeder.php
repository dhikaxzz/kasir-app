<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelanggan;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pelanggan::insert([
            [
                'nama' => 'Andi Wijaya',
                'no_telpon' => '081234567890',
                'email' => 'andi@example.com',
                'alamat' => 'Jl. Merdeka No. 1, Jakarta',
            ],
            [
                'nama' => 'Siti Rahma',
                'no_telpon' => '082345678901',
                'email' => 'siti@example.com',
                'alamat' => 'Jl. Raya Bandung No. 10, Bandung',
            ],
            [
                'nama' => 'Budi Santoso',
                'no_telpon' => '081298765432',
                'email' => 'budi@example.com',
                'alamat' => 'Jl. Malioboro No. 5, Yogyakarta',
            ],
            [
                'nama' => 'Dewi Lestari',
                'no_telpon' => '085612345678',
                'email' => 'dewi@example.com',
                'alamat' => 'Jl. Gajah Mada No. 12, Surabaya',
            ],
            [
                'nama' => 'Rudi Hartono',
                'no_telpon' => '087712345678',
                'email' => 'rudi@example.com',
                'alamat' => 'Jl. Diponegoro No. 3, Medan',
            ],
            [
                'nama' => 'Melati Kusuma',
                'no_telpon' => '089901234567',
                'email' => 'melati@example.com',
                'alamat' => 'Jl. Ahmad Yani No. 8, Semarang',
            ],
            [
                'nama' => 'Agus Prabowo',
                'no_telpon' => '081345678912',
                'email' => 'agus@example.com',
                'alamat' => 'Jl. Pemuda No. 6, Makassar',
            ],
            [
                'nama' => 'Yuni Astuti',
                'no_telpon' => '083812345679',
                'email' => 'yuni@example.com',
                'alamat' => 'Jl. Asia Afrika No. 15, Bandung',
            ],
            [
                'nama' => 'Fajar Nugroho',
                'no_telpon' => '082198765432',
                'email' => 'fajar@example.com',
                'alamat' => 'Jl. Cempaka Putih No. 9, Jakarta',
            ],
            [
                'nama' => 'Lina Marlina',
                'no_telpon' => '085876543210',
                'email' => 'lina@example.com',
                'alamat' => 'Jl. Kenanga No. 20, Palembang',
            ],
        ]);
    }
}
