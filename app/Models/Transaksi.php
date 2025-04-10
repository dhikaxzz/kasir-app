<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';

    protected $fillable = [
        'pelanggan_id',
        'kode_transaksi',
        'tanggal',
        'total_harga',
        'total_bayar',
        'kembalian',
        'metode_pembayaran',
    ];

    public function hitungTotalHarga()
    {
        return $this->detailTransaksi()->sum('subtotal');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($transaksi) {
            $total = $transaksi->detailTransaksi()->sum('subtotal');
            $transaksi->total_harga = $total;
            $transaksi->kembalian = max(0, $transaksi->total_bayar - $total);
            // $transaksi->saveQuietly(); 
        });        

        static::created(function ($transaksi) {
            $transaksi->refresh(); // Pastikan data terbaru setelah dibuat
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


    // public function setTotalHargaAttribute($value)
    // {
    //     $this->attributes['total_harga'] = $this->hitungTotalHarga();
    // }

    // public function hitungTotalHarga()
    // {
    //     return $this->detailTransaksi->sum(fn($detail) => $detail->subtotal);
    // }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::saving(function ($transaksi) {
    //         $transaksi->total_harga = $transaksi->hitungTotalHarga();
    //     });
    // }

    // public function hitungTotalHarga()
    // {
    //     return $this->detailTransaksi->sum(fn($detail) => $detail->subtotal);
    // }
}
