<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksis';

    protected $fillable = [
       'transaksi_id',
        'barang_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($detail) {
            $barang = Barang::find($detail->barang_id);
            if (!$barang || $barang->stok < $detail->jumlah) {
                throw new \Exception("Stok tidak cukup untuk barang {$barang->nama_barang}");
            }
    
            $detail->harga_satuan = $barang->harga_jual; // Pastikan harga satuan diambil dari barang
            $detail->subtotal = $detail->harga_satuan * $detail->jumlah;
    
            $barang->stok -= $detail->jumlah;
            $barang->save();
        });

        static::deleting(function ($detail) {
            $barang = Barang::find($detail->barang_id);
            if ($barang) {
                $barang->stok += $detail->jumlah;
                $barang->save();
            }
        });
    }



}
