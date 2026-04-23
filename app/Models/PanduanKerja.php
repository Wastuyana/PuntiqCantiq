<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanduanKerja extends Model
{
    protected $table = 'panduan_kerja';

    protected $fillable = [
        'parameter',
        'standar',
        'keterangan'
    ];
}
