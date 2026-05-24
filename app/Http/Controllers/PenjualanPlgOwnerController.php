<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanPlgOwnerController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::orderBy('nama_pelanggan', 'asc')->get();
        $produk = Produk::where('stok', '>', 0)->get(); 
        
        $history = Penjualan::with(['pelanggan', 'Detail_Penjualan.produk'])
                    ->where('status_customer', 'pelanggan')
                    ->orderBy('tanggal_penj', 'desc')
                    ->get();

        return view('owner.penjualan.pelanggan', compact('pelanggan', 'produk', 'history'));
    }
}