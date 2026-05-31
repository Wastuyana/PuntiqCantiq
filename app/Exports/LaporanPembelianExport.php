<?php

namespace App\Exports;

use App\Models\BahanMasuk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanPembelianExport implements FromCollection, WithHeadings, WithMapping
{
    protected $start, $end;

    // Tambahkan constructor ini
    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function collection()
    {
        // Gunakan whereBetween berdasarkan tanggal yang dikirim dari controller
        return BahanMasuk::with(['bahan_baku', 'supplier'])
            ->where('proses_pemesanan', 'selesai_dicatat')
            ->whereBetween('tanggal_masuk', [$this->start, $this->end])
            ->get();
    }

    public function headings(): array
    {
        return ['Tanggal Masuk', 'Nama Bahan Baku', 'Supplier', 'Jumlah (Qty)', 'Total Harga Beli (Rp)'];
    }

    public function map($bahanMasuk): array
    {
        return [
            $bahanMasuk->tanggal_masuk,
            $bahanMasuk->bahan_baku->nama ?? 'N/A',
            $bahanMasuk->supplier->nama_supplier ?? 'N/A',
            $bahanMasuk->jumlah_total,
            $bahanMasuk->harga_beli,
        ];
    }
}