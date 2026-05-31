<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanMasuk extends Model
{
    protected $table = 'bahan_masuk';

    protected $fillable = [
        'kode_pesanan',
        'jumlah_pesan',
        'proses_pemesanan',
        'supplier_id',
        'bahan_baku_id',
        'tanggal_pesan',
        'tanggal_masuk',
        'jumlah_total',
        'harga_beli',
        'status',
        'harga_satuan'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function bahan_baku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }

    public function qc_bahan()
    {
        return $this->hasOne(QcBahan::class, 'bahan_masuk_id');
    }
}
