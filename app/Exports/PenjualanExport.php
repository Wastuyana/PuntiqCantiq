<?php

namespace App\Exports;

use App\Models\DetailPenjualan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PenjualanExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $data;

    // Menerima data hasil filter dari controller
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }
    // Memetakan data dari model ke kolom Excel
    public function map($row): array
    {
        // Mengambil data dengan null-safe (??) agar tidak error
        $kategori = $row->produk->kategori ?? 'N/A';
        $varian = $row->produk->varian ?? '-';
        $ukuran = $row->produk->ukuran ?? '-';

        return [
            \Carbon\Carbon::parse($row->penjualan->tanggal_penj)->format('d M Y'),
            $kategori . ' - ' . $varian . ' (' . $ukuran . ')',
            $row->jumlah_produk,
            $row->produk->harga_jual ?? 0,
            $row->jumlah_produk * ($row->produk->harga_jual ?? 0),
        ];
    }

    public function headings(): array
    {
        return ['Tanggal', 'Produk (Detail)', 'Jumlah', 'Harga Satuan', 'Total'];
    }

    // Memberi style pada header agar tebal (bold)
    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}