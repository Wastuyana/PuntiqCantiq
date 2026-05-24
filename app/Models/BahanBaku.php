<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Hashing\HashManager;

class BahanBaku extends Model
{
    protected $table = 'bahan_baku';

    protected $fillable = [
        'kode_bahan',
        'nama',
        'stok',
        'satuan',
        'harga_satuan',
        'ss_bahan',
        'rop_bahan',
        'harga_updated_at'
    ];

    public function bom(): HasMany
    {
        return $this->hasMany(Bom::class, 'bahan_baku_id');
    }

    public function batch_bahan_baku(): HasMany
    {
        return $this->hasMany(BatchBahan::class, 'bahan_baku_id');
    }

    public function bahan_masuks(): HasMany
    {
        return $this->hasMany(BahanMasuk::class, 'bahan_baku_id');
    }
}
