<!DOCTYPE html>
<html>
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