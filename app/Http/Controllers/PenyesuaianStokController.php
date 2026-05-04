<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\PenyesuaianStok;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;

class PenyesuaianStokController extends Controller
{
    public function create($batch_id)
    {
        $batch = Batch::with('batch_hasil.produk')->findOrFail($batch_id);

        return view('owner.produksi.penyesuaian_stok', compact('batch'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|exists:batch,id',
            'produk_id' => 'required|exists:produk,id',
            'jumlah_rusak' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string|max:45',
        ]);

        return DB::transaction(function () use ($request) {
            PenyesuaianStok::create([
                'batch_id' => $request->batch_id,
                'produk_id' => $request->produk_id,
                'jumlah_rusak' => $request->jumlah_rusak,
                'keterangan' => $request->keterangan,
            ]);

            $produk = Produk::findOrFail($request->produk_id);
            $produk->decrement('stok', $request->jumlah_rusak);

            $produk->refresh();
            $produk->cekStokKrisis();

            return redirect()->route('owner.produksi.batch.index')
                ->with('success', 'Laporan stok rusak berhasil diproses dan stok produk telah diperbarui.');
        });
    }
}
