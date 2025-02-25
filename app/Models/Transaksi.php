<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';

    protected $fillable = [
        'kode_transaksi',
        'tanggal',
        'pelanggan_id', 
        'total_harga',
        'total_bayar',
        'kembalian',
        'metode_pembayaran',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($transaksi) {
            $transaksi->total_harga = $transaksi->hitungTotalHarga();
        });
    }


    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function hitungTotalHarga()
    {
        return $this->detailTransaksi->sum(fn($detail) => $detail->subtotal);
    }
}
