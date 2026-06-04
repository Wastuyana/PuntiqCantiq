<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardAdminController extends Controller
{
    public function index()
    {
        // 1. Statistik
        $stats = DB::table('penjualan')
            ->selectRaw("
                COUNT(CASE WHEN metode_pembayaran IN ('cash', 'transfer', 'qris') THEN 1 END) as lunas_count,
                COUNT(CASE WHEN metode_pembayaran = 'hutang' THEN 1 END) as hutang_count,
                SUM(subtotal_harga) as total_omzet
            ")
            ->first();

        // 2. Riwayat Penjualan
        $riwayatPenjualan = DB::table('penjualan')
            ->leftJoin('mitra', 'penjualan.mitra_id', '=', 'mitra.id')
            ->leftJoin('pelanggan', 'penjualan.pelanggan_id', '=', 'pelanggan.id')
            ->select(
                'penjualan.*',
                'mitra.nama_mitra as nama_mitra',     
                'pelanggan.nama_pelanggan as nama_pelanggan' 
            )
            ->latest()
            ->take(10)
            ->get();

        $batchAktif = DB::table('batch')
            ->where('status', 'draft')
            ->orderBy('tanggal_produksi', 'asc')
            ->get();

        $riwayatBatch = DB::table('batch')
            ->where('status', 'selesai')
            ->orderBy('tanggal_produksi', 'desc')
            ->take(5)
            ->get();

        $semuaBatch = $batchAktif->concat($riwayatBatch);

        return view('admin.dashboard', compact('stats', 'riwayatPenjualan', 'semuaBatch'));
    }
}
