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

    public function getEstimasiTenagaKerjaAttribute()
    {
        if ($this->biaya_tenagakerja > 0) {
            return $this->biaya_tenagakerja;
        }

        $totalEstimasi = 0;
        foreach ($this->batch_hasil as $hasil) {
            // Gunakan hasil_target sebagai acuan awal
            $totalEstimasi += ($hasil->produk->est_biaya_tenaga ?? 0) * $hasil->hasil_target;
        }

        return $totalEstimasi;
    }

    public function getEstimasiOverheadAttribute()
    {
        if ($this->biaya_overhead > 0) {
            return $this->biaya_overhead;
        }

        $totalEstimasiOverhead = 0;
        foreach ($this->batch_hasil as $hasil) {
            $biayaBahanStandar = $hasil->produk->hpp_standar ?? 0;
            $persenOverhead = $hasil->produk->est_biaya_overhead ?? 0;

            $overheadPerUnit = $biayaBahanStandar * ($persenOverhead / 100);
            $totalEstimasiOverhead += $overheadPerUnit * $hasil->hasil_target;
        }

        return $totalEstimasiOverhead;
    }

    public function hitungHPPHppAktual()
    {
        return DB::transaction(function () {
            // Inisialisasi akumulator total untuk tabel Batch
            $totalBiayaBahan = 0;
            $totalBiayaTK = 0;
            $totalBiayaOverhead = 0;
            $biayaPerProduk = []; // Penampung biaya bahan per produk

            foreach ($this->batch_bahan as $item) {
                // 1. Hitung total kebutuhan bahan baku ini berdasarkan BoM (Reset ke 0)
                $totalKebutuhanBom = 0;

                foreach ($this->batch_hasil as $hasil) {
                    $bom = BoM::where('produk_id', $hasil->produk_id)
                        ->where('bahan_baku_id', $item->bahan_baku_id)
                        ->first();
                    if ($bom) {
                        $totalKebutuhanBom += $bom->jumlah_kebutuhan * $hasil->hasil_aktual;
                    }
                }

                // 2. Hitung harga total bahan AKTUAL yang keluar
                $hargaTotalBahanAktual = $item->bahan_aktual * $item->bahan_baku->harga_satuan;

                // 3. Bagikan biaya AKTUAL ke masing-masing produk sesuai porsi BoM-nya
                foreach ($this->batch_hasil as $hasil) {
                    $bom = BoM::where('produk_id', $hasil->produk_id)
                        ->where('bahan_baku_id', $item->bahan_baku_id)
                        ->first();

                    if ($bom && $totalKebutuhanBom > 0) {
                        // Proporsi: (Kebutuhan Teori Unit Ini / Total Kebutuhan Teori Batch) * Total Biaya Aktual
                        $porsiBiaya = (($bom->jumlah_kebutuhan * $hasil->hasil_aktual) / $totalKebutuhanBom) * $hargaTotalBahanAktual;
                        $biayaPerProduk[$hasil->produk_id] = ($biayaPerProduk[$hasil->produk_id] ?? 0) + $porsiBiaya;
                    }
                }
            }

            // 4. Update HPP di tabel batch_hasil (Rincian per Varian)
            foreach ($this->batch_hasil as $hasil) {
                $totalBahanPerUnit = $hasil->hasil_aktual > 0
                    ? ($biayaPerProduk[$hasil->produk_id] / $hasil->hasil_aktual)
                    : 0;

                $biayaTKPerUnit = $hasil->produk->est_biaya_tenaga ?? 0;
                // Overhead dihitung dari persentase biaya bahan
                $biayaOverheadPerUnit = $totalBahanPerUnit * (($hasil->produk->est_biaya_overhead ?? 0) / 100);

                // Akumulasi untuk total batch (Nilai Satuan * Jumlah Hasil)
                $totalBiayaBahan += $totalBahanPerUnit * $hasil->hasil_aktual;
                $totalBiayaTK += $biayaTKPerUnit * $hasil->hasil_aktual;
                $totalBiayaOverhead += $biayaOverheadPerUnit * $hasil->hasil_aktual;

                $hasil->update([
                    'detail_biaya_bahan' => $totalBahanPerUnit,
                    'detail_biaya_tenagakerja' => $biayaTKPerUnit,
                    'detail_biaya_overhead' => $biayaOverheadPerUnit,
                    'hpp_aktual' => $totalBahanPerUnit + $biayaTKPerUnit + $biayaOverheadPerUnit
                ]);
            }

            // 5. Update Ringkasan Total Biaya di tabel Batch
            $this->update([
                'biaya_bahan'       => $totalBiayaBahan,
                'biaya_tenagakerja' => $totalBiayaTK,
                'biaya_overhead'    => $totalBiayaOverhead,
                'total_biaya'       => $totalBiayaBahan + $totalBiayaTK + $totalBiayaOverhead
            ]);

            return true;
        });
    }

    public function penyesuaian()
    {
        return $this->hasMany(PenyesuaianStok::class, 'batch_id');
    }
}
