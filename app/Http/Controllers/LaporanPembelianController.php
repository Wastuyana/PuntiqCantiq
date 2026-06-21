<?php

namespace App\Http\Controllers;

use App\Models\BahanMasuk;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPembelianExport;

class LaporanPembelianController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-m-01')); 
        $endDate = $request->input('end_date', date('Y-m-d'));

        $laporan = BahanMasuk::whereBetween('tanggal_masuk', [$startDate, $endDate])->get();

        return view('owner.laporan_pembelian', compact('laporan', 'startDate', 'endDate'));
    }

    public function exportPdf(Request $request)
    {
        $laporan = BahanMasuk::with(['bahan_baku'])
            ->whereBetween('tanggal_masuk', [$request->start_date, $request->end_date])
            ->get();

        $pdf = Pdf::loadView('owner.laporan.pdf_pembelian', compact('laporan'));
        return $pdf->download('Laporan_Pembelian_' . $request->start_date . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $start = $request->input('start_date', date('Y-m-01'));
        $end = $request->input('end_date', date('Y-m-d'));

        return Excel::download(new \App\Exports\LaporanPembelianExport($start, $end), 'Laporan_Pembelian.xlsx');
    }
}