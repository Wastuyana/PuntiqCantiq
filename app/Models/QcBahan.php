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

    public function bahan_masuk()
    {
        return $this->belongsTo(BahanMasuk::class, 'bahan_masuk_id');
    }

    public function getBahanBakuAttribute()
    {
        return $this->bahan_masuk ? $this->bahan_masuk->bahan_baku : null;
    }
}
