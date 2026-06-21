<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanPelangganController extends Controller
{
    protected $productionService;
    public function __construct()
    {
        $this->productionService = app()->make('App\Services\ProductionService');
    }
    
    public function index(Request $request)
    {
        $pelanggan = Pelanggan::orderBy('nama_pelanggan', 'asc')->get();
        $produk = Produk::where('stok', '>', 0)->get(); 
        
        $query = Penjualan::with(['pelanggan', 'Detail_Penjualan.produk'])
                            ->where('status_customer', 'pelanggan');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_penj', [$request->start_date, $request->end_date]);
        }

        $history = $query->latest('tanggal_penj')->paginate(10)->withQueryString();

        return view('admin.penjualan.pelanggan', compact('pelanggan', 'produk', 'history'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id'      => 'required|exists:pelanggan,id', 
            'metode_pembayaran' => 'required|in:cash,transfer,qris',
            'produk_id'         => 'required|array',
            'jumlah_produk'     => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            
            $today = date('Ymd');
            $lastPenjualan = DB::table('penjualan')
                ->whereDate('tanggal_penj', date('Y-m-d'))
                ->latest('id')
                ->first();

            
            $nextNumber = $lastPenjualan ? (intval(substr($lastPenjualan->kode_penjualan, -3)) + 1) : 1;
            $kodePenjualan = 'INV-' . $today . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

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

                $produk->decrement('stok', $qty);
                $this->productionService->cekStokKritis($produk);
            }

            if (empty($detail_items)) {
                return back()->withErrors(['produk' => "Pilih minimal satu produk dengan jumlah yang valid!"])->withInput();
            }

            $penjualan_id = DB::table('penjualan')->insertGetId([
                'kode_penjualan'    => $kodePenjualan, 
                'tanggal_penj'      => now(),
                'total_prod'        => $total_qty,
                'subtotal_harga'    => $total_harga,
                'status_customer'   => 'pelanggan',
                'pelanggan_id'      => $request->pelanggan_id,
                'metode_pembayaran' => $request->metode_pembayaran,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            foreach ($detail_items as $item) {
                DB::table('detail_penjualan')->insert([
                    'penjualan_id'  => $penjualan_id,
                    'produk_id'     => $item['produk_id'],
                    'jumlah_produk' => $item['jumlah_produk'],
                    'total_harga'   => $item['subtotal_harga'], 
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

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $details = DB::table('detail_penjualan')->where('penjualan_id', $id)->get();

            foreach ($details as $item) {
                Produk::where('id', $item->produk_id)->increment('stok', $item->jumlah_produk);
            }

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