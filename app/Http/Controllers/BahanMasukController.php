<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\BahanMasuk;
use Illuminate\Http\Request;

class BahanMasukController extends Controller
{
    public function index()
    {
        // Data yang masih 'di_pesan' akan muncul di tabel pencatatan
        $pesananPending = BahanMasuk::where('proses_pemesanan', 'di_pesan')->get();
        
        // Data yang sudah selesai dicatat masuk ke riwayat
        $bahanMasuk = BahanMasuk::where('proses_pemesanan', 'selesai_dicatat')->get();

        return view('admin.inventory.bahan_masuk', compact('pesananPending', 'bahanMasuk'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_masuk' => 'required|date',
            'jumlah_total'  => 'required|numeric|min:1',
        ]);

        $bm = BahanMasuk::findOrFail($id);

        // Ambil harga beli yang sudah ada di database dari proses pemesanan
        $hargaBeli = $bm->harga_beli; 

        // Hitung harga satuan berdasarkan harga beli lama dan jumlah yang baru datang
        $hargaSatuan = $hargaBeli / $request->jumlah_total;

        // Update data BahanMasuk
        $bm->update([
            'tanggal_masuk'    => $request->tanggal_masuk,
            'jumlah_total'     => $request->jumlah_total,
            'proses_pemesanan' => 'selesai_dicatat',
            'status'           => 'pending'
        ]);

        // Update stok dan harga di BahanBaku
        $bahan = BahanBaku::find($bm->bahan_baku_id);
        if ($bahan) {
            $bahan->increment('stok', $request->jumlah_total);
            $bahan->update([
                'harga_satuan'     => $hargaSatuan,
                'harga_updated_at' => now()
            ]);
        }

        return redirect()->back()->with('success', 'Barang dicatat, harga satuan otomatis terupdate!');
    }
        
    public function destroy($id)
    {
        BahanMasuk::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Catatan berhasil dihapus.');
    }
}