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
        $waitingList = BahanMasuk::with(['bahan_baku', 'supplier'])
            ->where('status', 'pending')
            ->get();

        $historyQC = \App\Models\QcBahan::with(['bahan_masuk.bahan_baku', 'bahan_masuk.supplier'])
            ->latest()
            ->get();

        return view('owner.inventory.Qc', compact('waitingList', 'historyQC'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bahan_masuk_id' => 'required|exists:bahan_masuk,id',
            'jumlah_bagus' => 'required|numeric|min:0',
            'jumlah_rusak' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $bm = BahanMasuk::findOrFail($request->bahan_masuk_id);

            QcBahan::create([
                'bahan_masuk_id' => $bm->id,
                'jumlah_bagus' => $request->jumlah_bagus,
                'jumlah_rusak' => $request->jumlah_rusak,
                'catatan' => $request->catatan,
                'tanggal_qc' => now(),
            ]);

            $bahan = BahanBaku::findOrFail($bm->bahan_baku_id);
            $bahan->stok += $request->jumlah_bagus;
            $bahan->save();

            $bm->status = 'completed';
            $bm->save();
        });

        return redirect()->back()->with('success', 'QC Berhasil! Stok bahan telah diperbarui.');
    }
}
