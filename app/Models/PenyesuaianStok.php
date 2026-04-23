<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenyesuaianStok extends Model
{
    protected $table = 'penyesuaian_stok';

    protected $fillable = [
        'produk_id',
        'batch_id',
        'jumlah_rusak',
        'keterangan'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }
}
