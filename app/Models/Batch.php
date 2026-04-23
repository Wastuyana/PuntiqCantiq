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

    public function hitungHPPHppAktual()
    {
        return DB::transaction(function () {
            $biayaBersama = 0;
            $biayaSpesifikPerProduk = []; 
            
            foreach ($this->batch_bahan as $item) {
                $jumlahProdukDiBom = BoM::where('bahan_baku_id', $item->bahan_baku_id)->count();

                $hargaTotalBahan = $item->bahan_aktual * $item->bahan_baku->harga_satuan;

                if ($jumlahProdukDiBom > 1 || $jumlahProdukDiBom == 0) {
                    $biayaBersama += $hargaTotalBahan;
                } else {
                    $bom = BoM::where('bahan_baku_id', $item->bahan_baku_id)->first();
                    $prodId = $bom->produk_id;

                    $biayaSpesifikPerProduk[$prodId] = ($biayaSpesifikPerProduk[$prodId] ?? 0) + $hargaTotalBahan;
                }
            }

            $totalUnitJadi = $this->batch_hasil->sum('hasil_aktual');
            if ($totalUnitJadi <= 0) return 0;

            $overheadNominal = $biayaBersama * ($this->biaya_overhead / 100);
            $modalDasarPerPcs = ($biayaBersama + $overheadNominal + $this->biaya_tenagakerja) / $totalUnitJadi;

            foreach ($this->batch_hasil as $hasil) {
                $biayaBumbuVarian = $biayaSpesifikPerProduk[$hasil->produk_id] ?? 0;
                $biayaBumbuPerPcs = $hasil->hasil_aktual > 0 ? ($biayaBumbuVarian / $hasil->hasil_aktual) : 0;

                $hppFinal = $modalDasarPerPcs + $biayaBumbuPerPcs;

                $hasil->update(['hpp_aktual' => $hppFinal]);
            }

            $this->update(['total_biaya' => ($biayaBersama + array_sum($biayaSpesifikPerProduk) + $overheadNominal + $this->biaya_tenagakerja)]);

            return true;
        });
    }

    public function penyesuaian()
    {
        return $this->hasMany(PenyesuaianStok::class, 'batch_id');
    }
}
