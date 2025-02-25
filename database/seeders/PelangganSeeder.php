<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        ]);
    }
}
