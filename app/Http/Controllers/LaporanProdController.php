<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\LaporanProduksiExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanProdController extends Controller
{
    private function getLaporanData($startDate, $endDate)
    {
        return  DB::table('batch')
            ->join('batch_hasil', 'batch.id', '=', 'batch_hasil.batch_id')
            ->join('produk', 'batch_hasil.produk_id', '=', 'produk.id')
            ->selectRaw("
            batch.id,
            batch.nomor_batch,
            batch.tanggal_produksi,
            batch.total_biaya as biaya_aktual,
            SUM(batch_hasil.hasil_target) as total_target,
            SUM(batch_hasil.hasil_aktual) as total_aktual,
            SUM(produk.hpp_standar * batch_hasil.hasil_aktual) as total_biaya_standar
        ")
            ->whereBetween('batch.tanggal_produksi', [$startDate, $endDate])
            ->where('batch.status', 'selesai')
            ->groupBy('batch.id', 'batch.nomor_batch', 'batch.tanggal_produksi', 'batch.total_biaya')
            ->orderBy('batch.tanggal_produksi', 'desc')
            ->get()
            ->map(function ($item) {
                $item->variance_biaya = ($item->total_biaya_standar > 0)
                    ? (($item->biaya_aktual / $item->total_biaya_standar) * 100) - 100
                    : 0;

                $item->efisiensi_hasil = ($item->total_target > 0)
                    ? ($item->total_aktual / $item->total_target) * 100
                    : 0;

                return $item;
            });
    }

    public function index(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());

        $laporan = $this->getLaporanData($startDate, $endDate);
        return view('owner.laporan.produksi', compact('laporan', 'startDate', 'endDate'));
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());

        $fileName = 'Laporan_Produksi_' . $startDate . '_to_' . $endDate . '.xlsx';
        return Excel::download(new LaporanProduksiExport($startDate, $endDate), $fileName);
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());

        $laporan = $this->getLaporanData($startDate, $endDate);

        $pdf = Pdf::loadView('owner.laporan.pdf', compact('laporan', 'startDate', 'endDate'));
        return $pdf->stream('Laporan_Produksi_' . $startDate . '_to_' . $endDate . '.pdf');
    }
}
