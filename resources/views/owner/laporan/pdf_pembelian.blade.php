<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Pembelian Bahan Baku</h2>
    <p>Tanggal Cetak: {{ date('d-m-Y') }}</p>
    <table>
        <thead>
            <tr>
                <th>Tgl Masuk</th>
                <th>Bahan</th>
                <th>Qty</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporan as $row)
            <tr>
                <td>{{ $row->tanggal_masuk }}</td>
                <td>{{ $row->bahan_baku->nama ?? '-' }}</td>
                <td>{{ $row->jumlah_total }}</td>
                <td>Rp {{ number_format($row->harga_beli, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>