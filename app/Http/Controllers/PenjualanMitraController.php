<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use App\Models\Produk;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanMitraController extends Controller
{
    public function index()
    {
        $mitra = Mitra::orderBy('nama_mitra', 'asc')->get();
        $produk = Produk::where('stok', '>', 0)->get(); 
        
        // Tambahan: .produk artinya kita sekalian mengambil data produk di dalam detail_penjualan
        $history = Penjualan::with(['mitra', 'Detail_Penjualan.produk'])
                    ->where('status_customer', 'mitra')
                    ->orderBy('tanggal_penj', 'desc')
                    ->get();

        return view('admin.penjualan.mitra', compact('mitra', 'produk', 'history'));
    }

        // 2. Proses Simpan Transaksi
    public function store(Request $request)
    {
        $request->validate([
            'mitra_id'          => 'required|exists:mitra,id', 
            'metode_pembayaran' => 'required|in:cash,transfer,qris,hutang',
            'produk_id'         => 'required|array',
            'jumlah_produk'     => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            // --- GENERATE KODE PENJUALAN OTOMATIS ---
            // Format: INV-20260601-001
            $today = date('Ymd');
            $lastPenjualan = DB::table('penjualan')
                ->whereDate('tanggal_penj', date('Y-m-d'))
                ->latest('id')
                ->first();

            // Jika hari ini belum ada transaksi, mulai dari 001. Jika sudah ada, tambah 1.
            $nextNumber = $lastPenjualan ? (intval(substr($lastPenjualan->kode_penjualan, -3)) + 1) : 1;
            $kodePenjualan = 'INV-' . $today . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            // ----------------------------------------

            $total_harga = 0;
            $total_qty = 0;
            $detail_items = [];

            foreach ($request->produk_id as $index => $prod_id) {
                $qty = $request->jumlah_produk[$index];
                if ($qty <= 0) continue;

                $produk = Produk::findOrFail($prod_id);
                
                if ($produk->stok < $qty) {
                    throw new \Exception("Stok gudang untuk produk {$produk->nama_produk} tidak mencukupi!");
                }

                // Kurangi stok gudang
                $produk->decrement('stok', $qty);

                // Jika hutang, pindahkan ke stok_mitra
                if ($request->metode_pembayaran === 'hutang') {
                    $produk->increment('stok_mitra', $qty);
                }

                $subtotal = $produk->harga_jual * $qty;
                $total_harga += $subtotal;
                $total_qty += $qty;

                $detail_items[] = [
                    'produk_id'     => $prod_id,
                    'jumlah_produk' => $qty,
                    'total_harga'   => $subtotal,
                ];
            }

            // Simpan ke tabel penjualan
            $penjualan_id = DB::table('penjualan')->insertGetId([
                'kode_penjualan'    => $kodePenjualan, // Kode disimpan di sini
                'tanggal_penj'      => now(),
                'total_prod'        => $total_qty,
                'subtotal_harga'    => $total_harga,
                'status_customer'   => 'mitra',
                'mitra_id'          => $request->mitra_id,
                'metode_pembayaran' => $request->metode_pembayaran,
                'created_at'        => now(),
            ]);

            // Simpan detail_penjualan
            foreach ($detail_items as $item) {
                DB::table('detail_penjualan')->insert([
                    'penjualan_id'  => $penjualan_id,
                    'produk_id'     => $item['produk_id'],
                    'jumlah_produk' => $item['jumlah_produk'],
                    'total_harga'   => $item['total_harga'],
                    'created_at'    => now(),
                ]);
            }

            DB::commit();
            return back()->with('success', "Transaksi berhasil! Kode: $kodePenjualan");
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal simpan: ' . $e->getMessage()])->withInput();
        }
    }
    // 3. Proses Pembatalan / Hapus Transaksi (Kembalikan Stok Otomatis)
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $penjualan = Penjualan::findOrFail($id);
            $details = DB::table('detail_penjualan')->where('penjualan_id', $id)->get();

            foreach ($details as $item) {
                $produk = Produk::findOrFail($item->produk_id);
                
                if ($penjualan->metode_pembayaran === 'hutang') {
                    // Kembalikan dari mitra ke gudang
                    $produk->decrement('stok_mitra', $item->jumlah_produk);
                    $produk->increment('stok', $item->jumlah_produk);
                } else {
                    // Kembalikan ke gudang saja
                    $produk->increment('stok', $item->jumlah_produk);
                }
            }

            DB::table('detail_penjualan')->where('penjualan_id', $id)->delete();
            DB::table('penjualan')->where('id', $id)->delete();

            DB::commit();
            return back()->with('success', 'Transaksi dibatalkan dan stok dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal membatalkan: ' . $e->getMessage()]);
        }
    }
}