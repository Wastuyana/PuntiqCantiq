<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\BahanMasuk;
use App\Models\Supplier;
use Illuminate\Http\Request;

class BahanMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::all();
        $bahanBaku = BahanBaku::all();
        $bahanMasuk = BahanMasuk::with(['supplier', 'bahan_baku'])->orderBy('tanggal_masuk', 'desc')->get();

        return view('admin.inventory.bahan_masuk', compact('suppliers', 'bahanBaku', 'bahanMasuk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'   => 'required',
            'bahan_baku_id' => 'required',
            'tanggal_pesan' => 'required|date',
            'tanggal_masuk' => 'required|date|after_or_equal:tanggal_pesan',
            'jumlah_total'  => 'required|numeric|min:1',
            'harga_beli'    => 'required|numeric', 
        ]);

        // Hitung Harga Satuan
        $hargaSatuan = $request->harga_beli / $request->jumlah_total;

        // 1. Simpan Bahan Masuk
        $bm = new \App\Models\BahanMasuk();
        $bm->supplier_id   = $request->supplier_id;
        $bm->bahan_baku_id = $request->bahan_baku_id;
        $bm->tanggal_pesan = $request->tanggal_pesan;
        $bm->tanggal_masuk = $request->tanggal_masuk;
        $bm->jumlah_total  = $request->jumlah_total;
        $bm->harga_beli    = $request->harga_beli;
        $bm->status        = 'pending';
        $bm->save();

        // 2. Update Data Bahan Baku
        $bahan = \App\Models\BahanBaku::find($request->bahan_baku_id);
        $bahan->harga_satuan = $hargaSatuan;
        $bahan->harga_updated_at = now();
        $bahan->save();

        return redirect()->back()->with('success', 'Data masuk & harga berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        $bm = BahanMasuk::findOrFail($id);
        $bm->delete();

        return redirect()->back()->with('success', 'Catatan kedatangan berhasil dihapus.');
    }
}
