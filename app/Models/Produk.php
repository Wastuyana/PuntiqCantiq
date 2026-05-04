<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Notifications\StokKritisProduk;
use Illuminate\Support\Facades\DB;
use App\Models\DetailPenjualan;

class Produk extends Model
{
    protected $table = 'produk';

    protected $fillable = [
        'kategori',
        'varian',
        'ukuran',
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

    public function detail_penjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'produk_id');
    }

    public function hitungHppStandar()
    {
        // 1. Hitung total bahan dari BOM
        $totalBahan = $this->bom->sum(function ($item) {
            return $item->jumlah_kebutuhan * $item->bahan_baku->harga_satuan;
        });

        // 2. Hitung Overhead pakai % 
        $overhead = $totalBahan * ($this->est_biaya_overhead / 100);

        // 3. Tambahkan Tenaga Kerja 
        $totalHpp = $totalBahan + $overhead + $this->est_biaya_tenaga;

        return $totalHpp;
    }

    public function cekStokKrisis()
    {
        $stokSekarang = (int) $this->stok;
        $stokMinimal = (int) $this->safety_stok;

        if ($stokSekarang <= $stokMinimal) {
            $users = User::all();

            foreach ($users as $user) {
                $alreadyNotified = $user->unreadNotifications()
                    ->whereJsonContains('data->id_produk', $this->id)
                    ->exists();

                if (!$alreadyNotified) {
                    $user->notify(new StokKritisProduk($this));
                }
            }
        }
    }

    public function getDavg()
    {
        $total = DetailPenjualan::getTotalPenjualan($this->id, 30);
        return $total / 30;
    }
}
