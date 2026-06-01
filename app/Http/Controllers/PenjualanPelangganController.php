<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanPelangganController extends Controller
{
    // 1. Halaman Utama (Gabungan Form Input & Tabel History)
    // 1. Halaman Utama (Gabungan Form Input & Tabel History)
    public function index()
    {
        $pelanggan = Pelanggan::orderBy('nama_pelanggan', 'asc')->get();
        $produk = Produk::where('stok', '>', 0)->get(); 
        
        // Tambahan: .produk artinya kita sekalian mengambil data produk di dalam detail_penjualan
        $history = Penjualan::with(['pelanggan', 'Detail_Penjualan.produk'])
                    ->where('status_customer', 'pelanggan')
                    ->orderBy('tanggal_penj', 'desc')
                    ->get();

        return view('admin.penjualan.pelanggan', compact('pelanggan', 'produk', 'history'));
    }

    // 2. Proses Simpan Transaksi
    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id'      => 'required|exists:pelanggan,id', // Menyesuaikan dengan nama tabel pelangan/pelanggan kamu
            'metode_pembayaran' => 'required|in:cash,transfer,qris',
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
                    return back()->withErrors(['stok' => "Stok untuk produk {$produk->varian} tidak mencukupi!"])->withInput();
                }

                $subtotal = $produk->harga_jual * $qty;
                $total_harga += $subtotal;
                $total_qty += $qty;

                $detail_items[] = [
                    'produk_id'     => $prod_id,
                    'jumlah_produk' => $qty,
                    'subtotal_harga'=> $subtotal,
                ];

                // Kurangi stok produk secara real-time
                $produk->decrement('stok', $qty);
            }

            if (empty($detail_items)) {
                return back()->withErrors(['produk' => "Pilih minimal satu produk dengan jumlah yang valid!"])->withInput();
            }

            // Simpan data ke tabel penjualan utama
            $penjualan_id = DB::table('penjualan')->insertGetId([
                'kode_penjualan'    => $kodePenjualan, // Kode disimpan di sini
                'tanggal_penj'      => now(),
                'total_prod'        => $total_qty,
                'subtotal_harga'    => $total_harga,
                'status_customer'   => 'pelanggan',
                'pelanggan_id'      => $request->pelanggan_id,
                'metode_pembayaran' => $request->metode_pembayaran,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            // Simpan data ke tabel detail_penjualan (Menggunakan kolom total_harga)
            foreach ($detail_items as $item) {
                DB::table('detail_penjualan')->insert([
                    'penjualan_id'  => $penjualan_id,
                    'produk_id'     => $item['produk_id'],
                    'jumlah_produk' => $item['jumlah_produk'],
                    'total_harga'   => $item['subtotal_harga'], // <--- SUDAH FIX: menggunakan nama kolom 'total_harga'
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }

            DB::commit();
            return back()->with('success', "Transaksi berhasil! Kode: $kodePenjualan");
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal transaksi: ' . $e->getMessage()])->withInput();
        }
    }

    // 3. Proses Pembatalan / Hapus Transaksi (Kembalikan Stok Otomatis)
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // Ambil detail items transaksi yang mau dihapus
            $details = DB::table('detail_penjualan')->where('penjualan_id', $id)->get();

            // Kembalikan stok masing-masing produk ke tabel produk
            foreach ($details as $item) {
                Produk::where('id', $item->produk_id)->increment('stok', $item->jumlah_produk);
            }

            // Hapus data dari detail dan tabel utama penjualan
            DB::table('detail_penjualan')->where('penjualan_id', $id)->delete();
            DB::table('penjualan')->where('id', $id)->delete();

            DB::commit();
            return back()->with('success', 'Transaksi berhasil dibatalkan dan stok produk telah dikembalikan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal membatalkan transaksi: ' . $e->getMessage()]);
        }
    }
}