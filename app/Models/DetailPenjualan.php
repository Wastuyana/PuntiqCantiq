<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    protected $table = 'detail_penjualan';

    protected $fillable = [
        'penjualan_id',
        'produk_id',
        'jumlah_produk',
        'total_harga',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public static function getTotalPenjualan($produkId, $hari = 30)
    {
        return self::where('produk_id', $produkId)
            ->whereHas('penjualan', function ($q) use ($hari) {
                $q->where('tanggal_penj', '>=', now()->subDays($hari));
            })
            ->sum('jumlah_produk');
    }
}
