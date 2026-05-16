<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanProduksiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate, $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return DB::table('batch')
            ->join('batch_hasil', 'batch.id', '=', 'batch_hasil.batch_id')
            ->join('produk', 'batch_hasil.produk_id', '=', 'produk.id')
            ->selectRaw("
                batch.nomor_batch, batch.tanggal_produksi, batch.total_biaya as biaya_aktual,
                SUM(batch_hasil.hasil_target) as total_target, SUM(batch_hasil.hasil_aktual) as total_aktual,
                SUM(produk.hpp_standar * batch_hasil.hasil_aktual) as total_biaya_standar
            ")
            ->whereBetween('batch.tanggal_produksi', [$this->startDate, $this->endDate])
            ->where('batch.status', 'selesai')
            ->groupBy('batch.id', 'batch.nomor_batch', 'batch.tanggal_produksi', 'batch.total_biaya')
            ->orderBy('batch.tanggal_produksi', 'desc')->get();
    }

    public function headings(): array
    {
        return ['Tanggal', 'No. Batch', 'Target Qty', 'Aktual Qty', 'Efisiensi Hasil (%)', 'Biaya Aktual (Rp)', 'Biaya Standar (Rp)', 'Variance Biaya (%)'];
    }

    public function map($row): array
    {
        $efisiensi_hasil = $row->total_target > 0 ? ($row->total_aktual / $row->total_target) * 100 : 0;
        $variance_biaya = $row->total_biaya_standar > 0 ? (($row->biaya_aktual / $row->total_biaya_standar) * 100) - 100 : 0;

        return [
            date('d-m-Y', strtotime($row->tanggal_produksi)),
            $row->nomor_batch,
            $row->total_target,
            $row->total_aktual,
            round($efisiensi_hasil, 1) . '%',
            $row->biaya_aktual,
            $row->total_biaya_standar,
            ($variance_biaya > 0 ? '+' : '') . round($variance_biaya, 2) . '%'
        ];
    }
}
