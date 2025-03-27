<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barangs';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'merek',
        'varian',
        'kategori_id',
        'lokasi_rak',
        'satuan',
        'harga_jual',
        'stok',
        'expired_date',
    ];

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id'); // ✅ Tambah relasi ke kategori
    }
}
