<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanPlgOwnerController extends Controller
{
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

        return view('owner.penjualan.pelanggan', compact('pelanggan', 'produk', 'history'));
    }
}