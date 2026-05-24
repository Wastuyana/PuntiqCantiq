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

        // Menggunakan with() agar tidak terjadi N+1 query yang membuat loading lambat
        $data = DetailPenjualan::with(['produk', 'penjualan'])
            ->whereHas('penjualan', function($q) use ($dari, $sampai) {
                $q->whereBetween('tanggal_penj', [$dari, $sampai]);
            })
            ->get();

        return view('owner.laporan_penjualan', compact('data', 'dari', 'sampai'));
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

        // 1. Logika Ekspor Excel
        if ($request->format == 'excel') {
            return Excel::download(new PenjualanExport($data), 'Laporan_Penjualan_' . $dari . '_sd_' . $sampai . '.xlsx');
        }

        // 2. Logika Ekspor PDF
        if ($request->format == 'pdf') {
            try {
                // Memastikan tidak ada output yang tersisa di buffer
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