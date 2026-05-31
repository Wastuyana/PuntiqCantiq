<?php

namespace App\Http\Controllers;

use App\Models\BahanMasuk;
use App\Models\BahanBaku;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    public function index()
    {
        // Mengambil semua data untuk ditampilkan di tabel riwayat
        $riwayatPemesanan = \App\Models\BahanMasuk::with(['bahan_baku', 'supplier'])->latest()->get();
        $bahan = \App\Models\BahanBaku::all();
        $suppliers = \App\Models\Supplier::all();

        // Pastikan mengirim variabel ke view
        return view('admin.inventory.pemesanan', compact('riwayatPemesanan', 'bahan', 'suppliers'));
    }

    public function store(Request $request)
    {
        // Validasi agar 'harga_beli' wajib diisi
        $request->validate([
            'bahan_baku_id' => 'required',
            'supplier_id'   => 'required',
            'jumlah_pesan'  => 'required|numeric',
            'harga_beli'    => 'required|numeric', // WAJIB DIISI
            'tanggal_pesan' => 'required|date',
        ]);

        \App\Models\BahanMasuk::create([
            'kode_pesanan' => 'PO-' . date('ymdHis'),
            'bahan_baku_id' => $request->bahan_baku_id,
            'supplier_id'   => $request->supplier_id,
            'jumlah_pesan'  => $request->jumlah_pesan,
            'harga_beli'    => $request->harga_beli, // Masuk ke database
            'tanggal_pesan' => $request->tanggal_pesan,
            'proses_pemesanan' => 'di_pesan',
        ]);

        return redirect()->back()->with('success', 'Pesanan berhasil disimpan!');
    }
}