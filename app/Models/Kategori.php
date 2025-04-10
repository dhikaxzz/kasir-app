<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\OnDeleting;

class Kategori extends Model
{
    protected $table = 'kategoris';

    protected $fillable = [
        'nama'
    ];

    // Relasi ke Barang
    public function barangs(): HasMany
    {
        return $this->hasMany(Barang::class, 'kategori_id');
    }

    protected static function booted()
    {
        static::deleting(function ($kategori) {
            if ($kategori->barangs()->exists()) {
                throw new \Exception("Kategori ini tidak bisa dihapus karena masih digunakan oleh barang.");
            }
        });
    }
}
