<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    protected $table = 'produk';

    protected $fillable = [
        'kategori',
        'varian',
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

    public function hitungHppStandar()
    {
        // 1. Hitung total bahan dari BOM
        $totalBahan = $this->bom->sum(function ($item) {
            return $item->jumlah_kebutuhan * $item->bahan_baku->harga_satuan;
        });

        // 2. Hitung Overhead pakai % (misal kolomnya: overhead_persen)
        $overhead = $totalBahan * ($this->est_biaya_overhead / 100);

        // 3. Tambahkan Tenaga Kerja (biasanya tetap/rupiah)
        $totalHpp = $totalBahan + $overhead + $this->est_biaya_tenaga;

        return $totalHpp;
    }

    public function detail_penjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'produk_id');
    }
}
