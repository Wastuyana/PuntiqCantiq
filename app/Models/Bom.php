<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bom extends Model
{
    protected $table = 'bom';

    protected $fillable = [
        'produk_id',
        'bahan_baku_id',
        'jumlah_kebutuhan'
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function bahan_baku(): BelongsTo
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }

    protected static function booted()
    {
        static::saved(function ($bom) {
            $produk = $bom->produk;
            if ($produk) {
                $produk->update([
                    'hpp_standar' => $produk->hitungHppStandar()
                ]);
            }
        });

        static::deleted(function ($bom) {
            $produk = $bom->produk;
            if ($produk) {
                $produk->update([
                    'hpp_standar' => $produk->hitungHppStandar()
                ]);
            }
        });
    }
}
