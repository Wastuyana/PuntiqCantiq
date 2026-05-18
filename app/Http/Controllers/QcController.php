<?php

namespace App\Http\Controllers;

use App\Models\BahanMasuk;
use App\Models\BahanBaku;
use App\Models\QcBahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QcController extends Controller
{
    public function index()
    {
        // Antrian QC (yang masih pending)
        $waitingList = BahanMasuk::with(['bahanBaku', 'supplier'])
            ->where('status', 'pending')
            ->get();

        // History QC (yang sudah diproses)
        $historyQC = \App\Models\QcBahan::with(['bahanMasuk.bahanBaku', 'bahanMasuk.supplier'])
            ->latest()
            ->get();

        return view('owner.Qc', compact('waitingList', 'historyQC'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bm_id' => 'required',
            'jumlah_bagus' => 'required|numeric|min:0',
            'jumlah_rusak' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $bm = BahanMasuk::findOrFail($request->bm_id);

            // 1. Simpan ke tabel qc_bahan
            QcBahan::create([
                'bm_id' => $bm->bm_id,
                'jumlah_bagus' => $request->jumlah_bagus,
                'jumlah_rusak' => $request->jumlah_rusak,
                'catatan' => $request->catatan,
                'tanggal_qc' => now(),
            ]);

            // 2. Tambah stok ke tabel bahan_baku (Hanya yang bagus)
            $bahan = BahanBaku::findOrFail($bm->id);
            $bahan->stok += $request->jumlah_bagus;
            $bahan->save();

            // 3. Update status kedatangan jadi completed
            $bm->status = 'completed';
            $bm->save();
        });

        return redirect()->back()->with('success', 'QC Berhasil! Stok bahan telah diperbarui.');
    }
}
