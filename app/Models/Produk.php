<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Notifications\StokKritisProduk;
use Illuminate\Support\Facades\DB;
use App\Models\DetailPenjualan;

class Produk extends Model
{
    protected $table = 'produk';

    protected $fillable = [
        'kategori',
        'varian',
        'ukuran',
        'stok',
        'safety_stok',
        'est_biaya_tenaga',
        'est_biaya_overhead',
        'hpp_standar',
        'harga_jual'
    ];

    public function bom(): HasMany
    {
        return $this->hasMany(Bom::class, 'produk_id');
    }

    public function produk(): HasMany
    {
        return $this->hasMany(BatchHasil::class, 'produk_id');
    }

    public function detail_penjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'produk_id');
    }   
}
