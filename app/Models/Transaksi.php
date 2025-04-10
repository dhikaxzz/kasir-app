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
            $transaksi->saveQuietly();
        });

        static::created(function ($transaksi) {
            $transaksi->refresh(); // Pastikan data terbaru setelah dibuat
        });
    }
    

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    public function setTotalHargaAttribute($value)
    {
        $this->attributes['total_harga'] = $this->hitungTotalHarga();
    }

    protected static function booted()
    {
        static::created(function ($transaksi) {
            $pelanggan = $transaksi->pelanggan;
            if ($pelanggan) {
                $total = self::where('pelanggan_id', $pelanggan->id)->sum('total_harga');
                $pelanggan->update([
                    'total_transaksi' => $total,
                ]);
            }
        });

        static::deleted(function ($transaksi) {
            $pelanggan = $transaksi->pelanggan;
            if ($pelanggan) {
                $total = self::where('pelanggan_id', $pelanggan->id)->sum('total_harga');
                $pelanggan->update([
                    'total_transaksi' => $total,
                ]);
            }
        });
        
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    // public function hitungTotalHarga()
    // {
    //     return $this->detailTransaksi->sum(fn($detail) => $detail->subtotal);
    // }
}
