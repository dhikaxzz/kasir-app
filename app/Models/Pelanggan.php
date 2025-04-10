<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pelanggan extends Model
{
    use HasFactory;
    
    protected $table = 'pelanggans';

    protected $fillable = [
        'nama',
        'email',
        'no_telpon',
        'alamat',
        'member',
        'loyalty_level',
        'total_transaksi',
    ];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function updateTotalTransaksi(): void
    {
        $this->total_transaksi = $this->transaksis()->sum('total');
        $this->save();
    }

}