<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;

class PenjualanMtrOwnerController extends Controller
{
    public function index(Request $request)
    {
        // 1. Definisikan query dasar
        $query = Penjualan::with(['mitra', 'Detail_Penjualan.produk'])
                            ->where('status_customer', 'mitra');

        // 2. Terapkan filter tanggal (Gunakan $request, bukan $history)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_penj', [$request->start_date, $request->end_date]);
        }

        // 3. Eksekusi query dengan paginate
        $history = $query->latest('tanggal_penj')->paginate(10)->withQueryString();

        // Data pendukung tetap diambil
        $mitra = Mitra::orderBy('nama_mitra', 'asc')->get();
        $produk = Produk::where('stok', '>', 0)->get();

        return view('owner.penjualan.mitra', compact('mitra', 'produk', 'history'));
    }
}