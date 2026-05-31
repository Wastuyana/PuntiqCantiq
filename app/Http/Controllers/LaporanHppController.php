<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\LaporanHppExport; 
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanHppController extends Controller
{
    private function getLaporanData(string $startDate, string $endDate)
    {
        return DB::table('batch')
            ->join('batch_hasil', 'batch.id', '=', 'batch_hasil.batch_id')
            ->join('produk', 'batch_hasil.produk_id', '=', 'produk.id')
            ->select([
                'batch.id as batch_id',
                'batch.nomor_batch as nomor_batch', 
                'batch.tanggal_produksi',
                'produk.id as produk_id',
                'produk.kategori',
                'produk.varian',
                'produk.ukuran',
                'produk.hpp_standar', 
                'batch_hasil.hasil_aktual',
                'batch_hasil.hpp_aktual', 
            ])
            ->whereBetween('batch.tanggal_produksi', [$startDate, $endDate])
            ->where('batch.status', 'selesai')
            ->orderBy('batch.tanggal_produksi', 'desc')
            ->get()
            ->map(function ($item) {
                $item->selisih_hpp = $item->hpp_aktual - $item->hpp_standar;
                $item->persentase_varians = $item->hpp_standar > 0 ? ($item->selisih_hpp / $item->hpp_standar) * 100 : 0;
                return $item;
            });
    }

    public function index(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());

        $laporanHpp = $this->getLaporanData($startDate, $endDate);

        $totalPcsProduksi = $laporanHpp->sum('hasil_aktual');
        $rataRataHppAktual = $laporanHpp->avg('hpp_aktual') ?? 0;
        $totalBatchSelesai = $laporanHpp->unique('batch_id')->count();

        return view('owner.laporan.hpp', compact(
            'laporanHpp', 'startDate', 'endDate', 'totalPcsProduksi', 'rataRataHppAktual', 'totalBatchSelesai'
        ));
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());
        return Excel::download(new LaporanHppExport($startDate, $endDate), 'Laporan_HPP_Aktual_' . $startDate . '_to_' . $endDate . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());
        $laporanHpp = $this->getLaporanData($startDate, $endDate);

        $pdf = Pdf::loadView('owner.laporan.pdf_hpp', compact('laporanHpp', 'startDate', 'endDate'));
        return $pdf->stream('Laporan_HPP_Aktual_' . $startDate . '_to_' . $endDate . '.pdf');
    }
}