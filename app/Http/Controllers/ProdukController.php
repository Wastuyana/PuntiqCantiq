<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\DetailPenjualan;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produks = Produk::orderBy('kategori')->get();

        // Ambil semua kategori unik yang pernah diinput
        $daftarKategori = Produk::distinct()->pluck('kategori');

        return view('owner.master.produk', compact('produks', 'daftarKategori'));
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
        // dd($request->all());
        $request->validate([
            'kategori' => 'required',
            'varian' => 'required',
            'stok' => 'required|integer|min:0',
        ]);

        Produk::create([
            'kategori' => $request->kategori,
            'varian' => $request->varian,
            'stok' => $request->stok,
            'safety_stok' => $request->safety_stok ?? 0,
            'est_biaya_tenaga' => $request->est_biaya_tenaga ?? 0,
            'est_biaya_overhead' => $request->est_biaya_overhead ?? 0,
            'hpp_standar' => 0,
            'harga_jual' => $request->harga_jual ?? 0,
        ]);

        return redirect()->route('owner.master.produk.index')->with('success', 'Master Produk Berhasil Dicatat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori' => 'required|string',
            'varian'   => 'required|string',
            'stok'     => 'required|integer|min:0',
            'est_biaya_tenaga' => 'required|numeric|min:0',
            'est_biaya_overhead' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        $produk = Produk::findOrFail($id);
        $produk->update([
            'kategori' => $request->kategori,
            'varian'   => $request->varian,
            'stok'     => $request->stok,
            'est_biaya_tenaga' => $request->est_biaya_tenaga,
            'est_biaya_overhead' => $request->est_biaya_overhead,
            'harga_jual' => $request->harga_jual,
        ]);

        return redirect()->back()->with('success', 'Data produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        $namaVarian = $produk->varian;

        $produk->delete();

        return redirect()->back()->with('success', 'Produk "' . $namaVarian . '" telah dihapus dari sistem.');
    }

    public function indexBom()
    {
        $produks = Produk::with('bom.bahan_baku')->get();

        return view('owner.master.bom', compact('produks'));
    }

    public function updateStokMinimal($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->save();
        $leadTime = 2; // Contoh: butuh 2 hari untuk produksi kembali

        // 1. Ambil data penjualan 30 hari terakhir untuk produk ini
        $dataPenjualan = DetailPenjualan::where('produk_id', $id)
            ->whereHas('penjualan', function ($q) {
                $q->where('tanggal_penj', '>=', now()->subDays(30));
            })
            ->selectRaw('DATE(created_at) as tanggal, SUM(jumlah_produk) as total')
            ->groupBy('tanggal')
            ->get();

        if ($dataPenjualan->isEmpty()) {
            return back()->with('error', 'Data penjualan 30 hari terakhir tidak ditemukan.');
        }

        // 2. Hitung d (rata-rata) dan dmax (maksimal harian)
        $d = $dataPenjualan->avg('total');
        $dmax = $dataPenjualan->max('total');

        // 3. Hitung sesuai rumus 
        $safetyStock = ($dmax - $d) * $leadTime;
        $batasMinimal = ($d * $leadTime) + $safetyStock;

        // 4. Update data ke database
        $produk->update([
            'safety_stok' => ceil($batasMinimal) // Kita bulatkan ke atas
        ]);

        return back()->with('success', 'Batas stok minimal berhasil diperbarui berdasarkan tren 30 hari terakhir!');
    }
}
