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
        $bahanBakus = BahanBaku::all();
        // Eager loading agar tidak berat saat ambil data relasi
        $bahanMasuk = BahanMasuk::with(['supplier', 'bahanBaku'])->orderBy('tanggal_masuk', 'desc')->get();

        return view('owner.inventory.bahan_masuk', compact('suppliers', 'bahanBakus', 'bahanMasuk'));
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
            'id'         => 'required',
            'tanggal_masuk' => 'required|date',
            'jumlah_total'  => 'required|numeric|min:1',
            'harga_beli'    => 'required|numeric|min:0',
        ]);

        BahanMasuk::create($request->all());

        return redirect()->back()->with('success', 'Data kedatangan dan harga berhasil dicatat!');
    }

    public function destroy(int $id)
    {
        $bm = BahanMasuk::findOrFail($id);
        $bm->delete();

        return redirect()->back()->with('success', 'Catatan kedatangan berhasil dihapus.');
    }
}
