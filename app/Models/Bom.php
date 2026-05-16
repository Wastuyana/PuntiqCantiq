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
        static::saved(fn($bom) => $bom->updateProdukHpp());
        static::deleted(fn($bom) => $bom->updateProdukHpp());
    }

    public function updateProdukHpp()
    {
        $financeService = app(\App\Services\ProductionService::class);
        $this->produk?->update([
            'hpp_standar' => $financeService->hitungHppStandar($this->produk->fresh())
        ]);
    }
}
