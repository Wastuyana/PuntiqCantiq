<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        
        .kop-header-table { 
            width: 100%; 
            border: none; 
            border-collapse: collapse; 
            margin-bottom: 20px;
            border-bottom: 3px solid #000; 
        }
        .kop-header-table td { border: none; padding: 5px; }
        .logo-cell { width: 120px; text-align: left; }
        .logo-cell img { width: 80px; }
        .title-cell { text-align: center; }
        .brand-name { font-size: 20px; font-weight: bold; text-transform: uppercase; margin: 0; }
        
        /* Layout Tabel Data */
        h2 { text-align: center; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
        .periode { text-align: center; margin-bottom: 20px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #f4f4f4; border: 1px solid #333; padding: 10px; text-align: center; }
        td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
    </style>
</head>
<body>

    <table class="kop-header-table">
        <tr>
            <td class="logo-cell">
                <img src="{{ public_path('images/puntiq-cantiq.png') }}" alt="Logo">
            </td>
            <td class="title-cell">
                <div class="brand-name">PUNTIQ CANTIQ</div>
                <div style="font-size: 11px;">Jl. Dr. Wahidin Gg. Batam No.7, Rembiga, Kec. Selaparang, Kota Mataram, Nusa Tenggara Bar. 83124</div>
                <div style="font-size: 11px;">Shopee: Puntiq Cantiq Official| Instagram: puntiqcantiq</div>
            </td>
            <td style="width: 120px;"></td> 
        </tr>
    </table>

    <h2>Laporan Penjualan</h2>
    <p class="periode">Periode: {{ \Carbon\Carbon::parse($dari)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}</p>

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