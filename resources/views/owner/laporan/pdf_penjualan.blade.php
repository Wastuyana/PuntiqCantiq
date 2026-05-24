<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 12px; 
            color: #333;
        }
        h2 { 
            text-align: center; 
            text-transform: uppercase; 
            letter-spacing: 1px;
            margin-bottom: 5px;
        }
        p { 
            text-align: center; 
            margin-bottom: 20px; 
            font-weight: bold;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }
        th { 
            background-color: #f4f4f4; 
            border: 1px solid #333; 
            padding: 10px; 
            text-align: center;
        }
        td { 
            border: 1px solid #ccc; 
            padding: 8px; 
            text-align: left;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
    </style>
</head>
<body>

    <h2>Laporan Penjualan</h2>
    <p>Periode: {{ \Carbon\Carbon::parse($dari)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->penjualan->tanggal_penj)->format('d M Y') }}</td>
                <td>
                    <strong>{{ $item->produk->kategori ?? 'N/A' }}</strong><br>
                    <small>{{ $item->produk->varian ?? '-' }} - {{ $item->produk->ukuran ?? '-' }}</small>
                </td>
                <td class="text-center">{{ $item->jumlah_produk }}</td>
                <td class="text-right">Rp {{ number_format($item->produk->harga_jual ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->jumlah_produk * ($item->produk->harga_jual ?? 0), 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right">Grand Total</td>
                <td class="text-right">
                    Rp {{ number_format($data->sum(fn($i) => $i->jumlah_produk * ($i->produk->harga_jual ?? 0)), 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

</body>
</html>