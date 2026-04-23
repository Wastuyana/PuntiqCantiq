<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatchBahan extends Model
{
    protected $table = 'batch_bahan';

    protected $fillable = [
        'batch_id',
        'bahan_baku_id',
        'bahan_target',
        'bahan_aktual'
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function bahan_baku(): BelongsTo
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }
}
