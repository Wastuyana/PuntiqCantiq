<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManajemenPembayaranController extends Controller
{
    public function index()
    {
    // Data untuk tabel Hutang
    $pembayaran_hutang = Penjualan::with(['mitra', 'pelanggan', 'detail_penjualan.produk'])
        ->where('metode_pembayaran', 'hutang') // sesuaikan dengan kolom di DB kamu
        ->get();

    // Data untuk History Lunas
    $riwayat_lunas = Penjualan::with(['mitra', 'pelanggan'])
        ->where('metode_pembayaran', '!=', 'hutang') // atau status = 'lunas'
        ->latest()
        ->get();

    return view('admin.penjualan.manajemenpembayaran', compact('pembayaran_hutang', 'riwayat_lunas'));
    }

    public function lunasi(Request $request, $id)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:cash,transfer,qris'
        ]);

        DB::transaction(function () use ($request, $id) {
            $penjualan = Penjualan::findOrFail($id);

            // Jika transaksi sebelumnya adalah HUTANG, 
            // maka kita harus mengurangi stok_mitra karena barang sudah lunas terjual
            if ($penjualan->metode_pembayaran === 'hutang') {
                foreach ($penjualan->detail_penjualan as $detail) {
                    $produk = $detail->produk;
                    
                    // Pastikan stok mitra cukup sebelum dikurangi
                    if ($produk->stok_mitra >= $detail->jumlah_produk) {
                        $produk->decrement('stok_mitra', $detail->jumlah_produk);
                    } else {
                        // Opsional: Handle jika stok tidak sinkron
                        throw new \Exception("Stok mitra untuk produk {$produk->nama_produk} tidak cukup!");
                    }
                }
            }

            // Update metode pembayaran menjadi lunas
            $penjualan->update([
                'metode_pembayaran' => $request->metode_pembayaran
            ]);
        });

        return redirect()->route('admin.penjualan.manajemenpembayaran.index')
                        ->with('success', 'Transaksi berhasil dilunasi dan stok mitra telah disesuaikan!');
    }
}