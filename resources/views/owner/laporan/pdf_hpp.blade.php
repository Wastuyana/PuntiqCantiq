<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laporan HPP Aktual</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }

        .header h2 {
            margin: 0;
            text-transform: uppercase;
            font-size: 18px;
        }

        .header p {
            margin: 5px 0 0 0;
            font-size: 11px;
            color: #555;
        }

        .meta-info {
            margin-bottom: 15px;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            border: 1px solid #ddd;
            padding: 8px;
        }

        td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* Indikator Warna Evaluasi khusus PDF */
        .badge-boros {
            color: #dc2626;
            font-weight: bold;
        }

        .badge-efisien {
            color: #16a34a;
            font-weight: bold;
        }

        .badge-sesuai {
            color: #4b5563;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>Puntiq Cantiq</h2>
        <p>Laporan Evaluasi Harga Pokok Produksi (HPP) Aktual Per Produk</p>
    </div>

    <div class="meta-info">
        <strong>Periode Laporan:</strong>
        {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d
        {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
        <br>
        <strong>Tanggal Cetak:</strong> {{ date('d M Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">Batch & Tanggal</th>
                <th>Detail Produk</th>
                <th width="12%" class="text-center">Hasil Aktual</th>
                <th width="15%" class="text-right">HPP Standar</th>
                <th width="15%" class="text-right">HPP Aktual</th>
                <th width="15%" class="text-right">Selisih</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporanHpp as $row)
                <tr>
                    <td class="text-center">
                        <strong>{{ $row->nomor_batch }}</strong><br>
                        <span style="font-size: 9px; color: #666;">
                            {{ \Carbon\Carbon::parse($row->tanggal_produksi)->format('d-m-Y') }}
                        </span>
                    </td>
                    <td>
                        <strong>{{ $row->kategori }} - {{ $row->varian }}</strong><br>
                        <span style="font-size: 10px; color: #666;">Ukuran: {{ $row->ukuran }}</span>
                    </td>
                    <td class="text-center">{{ number_format($row->hasil_aktual) }} Pcs</td>
                    <td class="text-right">Rp {{ number_format($row->hpp_standar, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($row->hpp_aktual, 0, ',', '.') }}</td>

                    <td class="text-right" style="font-weight: 500;">
                        {{ $row->selisih_hpp > 0 ? '+' : '' }}{{ number_format($row->selisih_hpp, 0, ',', '.') }}
                        <span style="font-size: 9px; color:#555; block; font-weight: normal;">
                            ({{ number_format($row->persentase_varians, 1) }}%)
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
