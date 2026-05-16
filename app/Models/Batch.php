<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    protected $table = 'batch';

    protected $fillable = [
        'nomor_batch',
        'tanggal_produksi',
        'tanggal_kadaluarsa',
        'status',
        'checklist_sop',
        'sop_details',
        'biaya_bahan',
        'biaya_tenagakerja',
        'biaya_overhead',
        'total_biaya',
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function batch_hasil(): HasMany
    {
        return $this->hasMany(BatchHasil::class, 'batch_id');
    }

    public function batch_bahan(): HasMany
    {
        return $this->hasMany(BatchBahan::class, 'batch_id');
    }

    public static function generateNoBatch()
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', now())->count() + 1;
        return "B-{$date}-" . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function penyesuaian()
    {
        return $this->hasMany(PenyesuaianStok::class, 'batch_id');
    }
}
