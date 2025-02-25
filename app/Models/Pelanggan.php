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
        'no_telpon',
        'email',
        'alamat',
    ];
}
