<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';

    protected $fillable = [
        'kode_Penjualan',
        'tanggal_penj',
        'total_prod',
        'subtotal_harga',
        'status_customer',
        'pelanggan_id',
        'metode_pembayaran'
    ];

    public function detail_penjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'penjualan_id', 'id');
    }
    
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id', 'id');
    }

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id', 'id');
    }
}
