<x-app-layout>
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-4">Laporan Pembelian</h2>
        
        <form method="GET" class="flex gap-2 mb-4">
            <input type="date" name="start_date" value="{{ $startDate }}" class="input input-bordered">
            <input type="date" name="end_date" value="{{ $endDate }}" class="input input-bordered">
            <button class="btn btn-primary">Filter</button>
            <a href="{{ route('owner.laporan.pembelian.export.pdf', ['start_date'=>$startDate, 'end_date'=>$endDate]) }}" class="btn btn-error">PDF</a>
            <a href="{{ route('owner.laporan.pembelian.export.excel', ['start_date'=>$startDate, 'end_date'=>$endDate]) }}" class="btn btn-success">Excel</a>
        </form>

        <table class="table w-full border">
            <thead><tr><th>Tgl</th><th>Bahan</th><th>Qty</th><th>Harga</th></tr></thead>
            <tbody>
                @foreach($laporan as $row)
                <tr>
                    <td>{{ $row->tanggal_masuk }}</td>
                    <td>{{ $row->bahan_baku->nama ?? '-' }}</td>
                    <td>{{ $row->jumlah_total }}</td>
                    <td>Rp {{ number_format($row->harga_beli) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>