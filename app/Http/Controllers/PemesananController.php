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
        $riwayatPemesanan = \App\Models\BahanMasuk::with(['bahan_baku', 'supplier'])->latest()->get();
        $bahan = \App\Models\BahanBaku::all();
        $supplier = \App\Models\Supplier::all();
        $suppliers = Supplier::with('bahanBaku')->get();

        return view('admin.inventory.pemesanan', compact('riwayatPemesanan', 'bahan', 'supplier', 'suppliers'));
    }
    public function create() {
        $bahan = BahanBaku::all();
        $supplier = Supplier::all(); 
        return view('admin.inventory.pemesanan', compact('bahan', 'supplier'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'bahan_baku_id' => 'required|exists:bahan_baku,id',
            'supplier_id'   => 'required|exists:supplier,id',
            'jumlah_pesan'  => 'required|numeric|min:1',
            'harga_beli'    => 'required|numeric',
            'tanggal_pesan' => 'required|date',
        ]);

        $tanggal = date('ymd'); 
        $random = rand(100, 999); 
        $kodePesanan = 'PO-' . $tanggal . '-' . $random;

        BahanMasuk::create([
            'kode_pesanan'     => $kodePesanan,
            'bahan_baku_id'    => $request->bahan_baku_id,
            'supplier_id'      => $request->supplier_id,
            'jumlah_pesan'     => $request->jumlah_pesan,
            'harga_beli'       => $request->harga_beli,
            'tanggal_pesan'    => $request->tanggal_pesan,
            'proses_pemesanan' => 'di_pesan', 
        ]);

        return redirect()->route('admin.inventory.pemesanan.index')
                        ->with('success', 'Pesanan bahan baku berhasil dibuat!');
    }
}