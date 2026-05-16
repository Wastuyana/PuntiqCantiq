<!DOCTYPE html>
<html>

<head>
    <title>Laporan Analisis Produksi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            padding: 0;
            color: #1e293b;
        }

        .header p {
            margin: 5px 0 0 0;
            color: #64748b;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th {
            bg-color: #f8fafc;
            color: #475569;
            font-weight: bold;
            text-transform: uppercase;
            border: 1px solid #cbd5e1;
            padding: 8px;
            text-align: left;
        }

        td {
            border: 1px solid #cbd5e1;
            padding: 8px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-danger {
            color: #ef4444;
            font-weight: bold;
        }

        .text-success {
            color: #22c55e;
            font-weight: bold;
        }

        .insight-box {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 10px;
            margin-top: 20px;
            border-radius: 4px;
        }

        .insight-box h4 {
            margin: 0 0 5px 0;
            color: #1e3a8a;
        }

        .insight-box p {
            margin: 0;
            color: #1d4ed8;
            line-height: 1.4;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Laporan Analisis Produksi</h2>
        <p>Periode: {{ date('d M Y', strtotime($startDate)) }} s/d {{ date('d M Y', strtotime($endDate)) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal/No.Batch</th>
                <th class="text-center">Efisiensi Hasil</th>
                <th class="text-right">Biaya Aktual</th>
                <th class="text-right">Biaya Standar</th>
                <th class="text-center">Efisiensi Biaya</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporan as $row)
                @php
                    $efisiensi = $row->total_target > 0 ? ($row->total_aktual / $row->total_target) * 100 : 0;
                    $variance =
                        $row->total_biaya_standar > 0
                            ? ($row->biaya_aktual / $row->total_biaya_standar) * 100 - 100
                            : 0;
                @endphp
                <tr>
                    <td class="pl-6">
                        <div style="font-weight: bold;">
                            {{ date('d M Y', strtotime($row->tanggal_produksi)) }}
                        </div>
                        <div style="font-weight: bold;">
                            {{ $row->nomor_batch }} </a>
                        </div>
                    </td>
                    <td class="text-center">{{ number_format($efisiensi, 1) }}%</td>
                    <td class="text-right">Rp {{ number_format($row->biaya_aktual, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($row->total_biaya_standar, 0, ',', '.') }}</td>
                    <td class="text-center {{ $variance > 0 ? 'text-danger' : 'text-success' }}">
                        {{ $variance > 0 ? '+' : '' }}{{ number_format($variance, 2) }}%
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
