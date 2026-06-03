<?php

namespace App\Http\Controllers;

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
        // Sesuaikan 'nama_mitra' dan 'nama_pelanggan' dengan nama kolom asli di tabel mitra/pelanggan Anda
        $riwayatPenjualan = DB::table('penjualan')
            ->leftJoin('mitra', 'penjualan.mitra_id', '=', 'mitra.id')
            ->leftJoin('pelanggan', 'penjualan.pelanggan_id', '=', 'pelanggan.id')
            ->select(
                'penjualan.*', 
                'mitra.nama_mitra as nama_mitra',     // Ganti 'nama_mitra' jika nama kolom di DB berbeda
                'pelanggan.nama_pelanggan as nama_pelanggan' // Ganti 'nama_pelanggan' jika nama kolom di DB berbeda
            )
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'riwayatPenjualan'));
    }
}