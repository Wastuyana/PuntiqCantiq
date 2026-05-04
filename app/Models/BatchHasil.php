<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchHasil extends Model
{
    protected $table = 'batch_hasil';

    protected $fillable = [
        'batch_id',
        'produk_id',
        'hasil_target',
        'hasil_aktual',
        'detail_biaya_bahan',
        'detail_biaya_tenagakerja',
        'detail_biaya_overhead',
        'hpp_aktual'
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function hitungHppVarian()
    {
        $totalBiayaBahan = $this->pemakaianBahan->sum(function ($item) {
            return $item->bahan_aktual * $item->harga_satuan_saat_ini;
        });

        $totalPcsDihasilkan = $this->hasilProduksi->sum('hasil_aktual');

        if ($totalPcsDihasilkan > 0) {
            $hppPerPcs = $totalBiayaBahan / $totalPcsDihasilkan;

            foreach ($this->hasilProduksi as $hasil) {
                $hasil->update([
                    'hpp_aktual' => $hppPerPcs
                ]);
            }
        }
    }
}
