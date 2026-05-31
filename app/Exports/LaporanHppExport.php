<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanHppExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Ambil data mentah yang sama dengan Controller
     */
    public function collection()
    {
        return DB::table('batch')
            ->join('batch_hasil', 'batch.id', '=', 'batch_hasil.batch_id')
            ->join('produk', 'batch_hasil.produk_id', '=', 'produk.id')
            ->select([
                'batch.nomor_batch',
                'batch.tanggal_produksi',
                'produk.kategori',
                'produk.varian',
                'produk.ukuran',
                'produk.hpp_standar',
                'batch_hasil.hasil_aktual',
                'batch_hasil.hpp_aktual',
            ])
            ->whereBetween('batch.tanggal_produksi', [$this->startDate, $this->endDate])
            ->where('batch.status', 'selesai')
            ->orderBy('batch.tanggal_produksi', 'desc')
            ->get();
    }

    /**
     * Header Kolom di Excel
     */
    public function headings(): array
    {
        return [
            'Nomor Batch',
            'Tanggal Produksi',
            'Produk',
            'Hasil Aktual (Pcs)',
            'HPP Standar (Rp)',
            'HPP Aktual (Rp)',
            'Selisih (Rp)',
        ];
    }

    /**
     * Mapping / Baris data yang akan dimasukkan ke Excel
     */
    public function map($row): array
    {
        $selisih = $row->hpp_aktual - $row->hpp_standar;

        return [
            $row->nomor_batch,
            \Carbon\Carbon::parse($row->tanggal_produksi)->format('d-m-Y'),
            $row->kategori . ' - ' . $row->varian . ' (' . $row->ukuran . ')',
            $row->hasil_aktual,
            $row->hpp_standar,
            $row->hpp_aktual,
            $selisih
        ];
    }

    /**
     * Styling agar tampilan Excel terlihat rapi dan profesional untuk laporan
     */
    public function styles(Worksheet $sheet)
    {
        // Beri warna background abu-abu tebal pada header tabel
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1:H1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('E2E8F0');

        // Beri format angka akuntansi / mata uang untuk kolom angka
        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('D2:D' . $highestRow)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('E2:G' . $highestRow)->getNumberFormat()->setFormatCode('Rp#,##0');

        return [
            1 => ['font' => ['size' => 11]],
        ];
    }
}
