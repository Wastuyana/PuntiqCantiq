<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Hashing\HashManager;

class BahanBaku extends Model
{
    protected $table = 'bahan_baku';

    protected $fillable = [
        'nama',
        'stok',
        'satuan',
        'harga_satuan'
    ];

    public function bom(): HasMany
    {
        return $this->hasMany(Bom::class, 'bahan_baku_id');
    }

    public function batch_bahan_baku(): HasMany
    {
        return $this->hasMany(BatchBahan::class, 'bahan_baku_id');
    }
}
