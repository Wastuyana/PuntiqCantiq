<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailPenjualan;
use App\Exports\PenjualanExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class LaporanPenjualanController extends Controller
{
   public function index(Request $request)
    {
        $dari = $request->dari ?? now()->startOfMonth()->format('Y-m-d');
        $sampai = $request->sampai ?? now()->endOfMonth()->format('Y-m-d');

        $data = DetailPenjualan::with(['produk', 'penjualan'])
            ->whereHas('penjualan', function($q) use ($dari, $sampai) {
                $q->whereBetween('tanggal_penj', [$dari, $sampai]);
            })
            ->get();

        $produkTerlaris = $data->groupBy('produk_id')->map(function ($group) {
            return [
                'nama' => ($group->first()->produk->kategori ?? 'N/A') . ' - ' . ($group->first()->produk->varian ?? 'N/A'),
                'qty' => $group->sum('jumlah_produk'),
                'total' => $group->sum(fn($i) => $i->jumlah_produk * ($i->produk->harga_jual ?? 0))
            ];
        })->sortByDesc('qty')->take(10);;

        return view('owner.laporan_penjualan', compact('data', 'dari', 'sampai', 'produkTerlaris'));
    }

    public function export(Request $request)
    {
        $dari = $request->dari;
        $sampai = $request->sampai;

        // Validasi input tanggal
        if (!$dari || !$sampai) {
            return redirect()->back()->with('error', 'Silakan pilih rentang tanggal terlebih dahulu.');
        }

        $data = DetailPenjualan::with(['produk', 'penjualan'])
            ->whereHas('penjualan', function($q) use ($dari, $sampai) {
                $q->whereBetween('tanggal_penj', [$dari, $sampai]);
            })
            ->get();

        if ($request->format == 'excel') {
            return Excel::download(new PenjualanExport($data), 'Laporan_Penjualan_' . $dari . '_sd_' . $sampai . '.xlsx');
        }

        if ($request->format == 'pdf') {
            try {
                if (ob_get_contents()) ob_end_clean();

                $pdf = Pdf::loadView('owner.laporan.pdf_penjualan', compact('data', 'dari', 'sampai'));
                $pdf->setPaper('a4', 'landscape');
                
                return $pdf->download('Laporan_Penjualan_' . $dari . '_sd_' . $sampai . '.pdf');
            } catch (\Exception $e) {
                Log::error('PDF Export Error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'Format tidak didukung');
    }
}