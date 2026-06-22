<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\BahanMasuk;
use App\Models\Supplier;
use Illuminate\Http\Request;

class BahanMasukController extends Controller
{
    public function index()
    {
        $pesananPending = BahanMasuk::where('proses_pemesanan', 'di_pesan')->get();
        
        $bahanMasuk = BahanMasuk::where('proses_pemesanan', 'selesai_dicatat')->get();

        return view('admin.inventory.bahan_masuk', compact('pesananPending', 'bahanMasuk'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_masuk' => 'required|date',
            'jumlah_total'  => 'required|numeric',
        ]);

        $bm = BahanMasuk::findOrFail($id);
        $bahan = BahanBaku::find($bm->bahan_baku_id);

        if ($bahan && $request->jumlah_total > 0) {
            $hargaSatuan = $bm->harga_beli / $request->jumlah_total;
            $bahan->update([
                'harga_satuan'     => $hargaSatuan,
                'harga_updated_at' => now()
            ]);
        }

        $bm->update([
            'tanggal_masuk'    => $request->tanggal_masuk,
            'jumlah_total'     => $request->jumlah_total,
            'proses_pemesanan' => 'selesai_dicatat',
            'status'           => $bm->status, 
        ]);

        return redirect()->back()->with('success', 'Barang diterima dan harga terupdate.');
    }
    public function destroy($id)
    {
        BahanMasuk::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Catatan berhasil dihapus.');
    }
}