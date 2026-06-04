<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManajemenPembayaranController extends Controller
{
    public function index(Request $request)
    {
        $queryHutang = Penjualan::with(['mitra', 'pelanggan', 'detail_penjualan.produk'])
            ->where('metode_pembayaran', 'hutang');

        if ($request->filled('hutang_start') && $request->filled('hutang_end')) {
            $queryHutang->whereBetween('tanggal_penj', [$request->hutang_start, $request->hutang_end]);
        }
        
        $pembayaran_hutang = $queryHutang->latest()->paginate(10, ['*'], 'page_hutang')->withQueryString();

        $queryLunas = Penjualan::with(['mitra', 'pelanggan'])
            ->where('metode_pembayaran', '!=', 'hutang');

        if ($request->filled('lunas_start') && $request->filled('lunas_end')) {
            $queryLunas->whereBetween('tanggal_penj', [$request->lunas_start, $request->lunas_end]);
        }
        
        $riwayat_lunas = $queryLunas->latest()->paginate(10, ['*'], 'page_lunas')->withQueryString();

        return view('admin.penjualan.manajemenpembayaran', compact('pembayaran_hutang', 'riwayat_lunas'));
    }


    public function lunasi(Request $request, $id)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:cash,transfer,qris'
        ]);

        DB::transaction(function () use ($request, $id) {
            $penjualan = Penjualan::findOrFail($id);

            
            if ($penjualan->metode_pembayaran === 'hutang') {
                foreach ($penjualan->detail_penjualan as $detail) {
                    $produk = $detail->produk;
                    
                    if ($produk->stok_mitra >= $detail->jumlah_produk) {
                        $produk->decrement('stok_mitra', $detail->jumlah_produk);
                    } else {
                        throw new \Exception("Stok mitra untuk produk {$produk->nama_produk} tidak cukup!");
                    }
                }
            }

            $penjualan->update([
                'metode_pembayaran' => $request->metode_pembayaran
            ]);
        });

        return redirect()->route('admin.penjualan.manajemenpembayaran.index')
                        ->with('success', 'Transaksi berhasil dilunasi dan stok mitra telah disesuaikan!');
    }
}