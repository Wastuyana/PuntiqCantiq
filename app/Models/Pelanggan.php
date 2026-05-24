<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';

    protected $fillable = [
        'kode_pelanggan',
        'nama_pelanggan',
        'alamat_pelanggan',
        'no_hp'
    ];
}
