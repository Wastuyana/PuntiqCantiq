<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QcBahan extends Model
{
    protected $table = 'qc_bahan';

    protected $fillable = [
        'bahan_masuk_id',
        'jumlah_bagus',
        'jumlah_rusak',
        'catatan',
        'tanggal_qc'
    ];

    public function bahan_masuks()
    {
        return $this->belongsTo(BahanMasuk::class, 'bahan_masuk_id');
    }
}
