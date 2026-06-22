<x-app-layout>
    <div class="p-6 space-y-6">
        <div class="flex justify-between items-center bg-white p-4 rounded-xl shadow-sm border-l-4 border-primary">
            <div>
                <h1 class="text-2xl font-bold">Detail Batch: {{ $batch->nomor_batch }}</h1>
                <p class="text-sm opacity-60 italic">Dibuat oleh: {{ $batch->user->name ?? 'System' }} pada
                    {{ $batch->created_at->format('d M Y H:i') }}</p>
            </div>
            <div
                class="badge {{ $batch->status == 'completed' ? 'badge-success' : 'badge-warning' }} p-4 font-bold uppercase">
                {{ $batch->status }}
            </div>
        </div>

        <div class="card bg-white shadow-sm border border-base-200">
            <div class="card-body">
                <h1 class="card-title text-sm font-bold uppercase mb-4">Hasil Produksi</h1>

                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead class="bg-base-50">
                            <tr>
                                <th>Produk</th>
                                <th>Hasil Aktual</th>
                                <th>HPP Aktual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($batch->batch_hasil as $hasil)
                                <tr>
                                    <td>
                                        <div class="font-bold m-0,5">{{ $hasil->produk->kategori }}</div>
                                        <div class="text-md">{{ $hasil->produk->varian }} -
                                            {{ $hasil->produk->ukuran }}</div>
                                    </td>
                                    <td class="font-semibold">
                                        {{ number_format($hasil->hasil_aktual) }} Pcs
                                    </td>
                                    <td class="font-bold">
                                        Rp {{ number_format($hasil->hpp_aktual, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="divider my-4"></div>

                <div class="flex justify-between items-center px-2">
                    <div>
                        <span class="text-sm font-bold uppercase">Tgl Kadaluarsa:</span>
                    </div>
                    <span class="text-sm font-bold">
                        {{ \Carbon\Carbon::parse($batch->tanggal_kadaluarsa)->format('d M Y') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-base-200">
            <div class="card-body">
                <h2 class="card-title text-sm font-bold uppercase mb-4">Pemakaian Bahan Baku</h2>
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <tr class="bg-base-100">
                            <th>Nama Bahan</th>
                            <th>Aktual Terpakai</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($batch->batch_bahan as $bahan)
                                <tr>
                                    <td>{{ $bahan->bahan_baku->nama }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $bahan->bahan_aktual > $bahan->bahan_target ? 'badge-error' : 'badge-ghost' }}">
                                            {{ number_format($bahan->bahan_aktual, 3, '.', '') }}
                                            {{ $bahan->bahan_baku->satuan }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card bg-white shadow-sm border border-base-200 h-full">
            <div class="card-body">
                <h2 class="card-title text-sm uppercase font-bold mb-4">Ringkasan Biaya Batch</h2>

                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <tbody>
                            <tr>
                                <td>Total Bahan Baku</td>
                                <td>
                                    Rp {{ number_format($batch->biaya_bahan, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td>Total Tenaga Kerja</td>
                                <td>
                                    Rp {{ number_format($batch->biaya_tenagakerja, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td>Total Overhead</td>
                                <td>
                                    Rp {{ number_format($batch->biaya_overhead, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td>Total Biaya Produksi</td>
                                <td>
                                    Rp {{ number_format($batch->total_biaya, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-error/20 mt-8">
            <div class="card-body">
                <h2 class="card-title text-sm uppercase font-bold mb-4">Riwayat Barang Rusak</h2>
                <div class="overflow-x-auto mt-4">
                    <table class="table table-zebra w-full text-sm">
                        <thead>
                            <tr class="bg-base-200">
                                <th>Produk</th>
                                <th>Jumlah Rusak</th>
                                <th>Keterangan</th>
                                <th>Waktu Lapor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($batch->penyesuaian as $rusak)
                                <tr>
                                    <td class="font-bold">{{ $rusak->produk->varian }}</td>
                                    <td class="text-error font-bold">- {{ $rusak->jumlah_rusak }} Pcs</td>
                                    <td class="italic text-opacity-70">{{ $rusak->keterangan ?? '-' }}</td>
                                    <td>{{ $rusak->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center opacity-50 italic">Tidak ada laporan barang
                                        rusak untuk batch ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('owner.produksi.batch.index') }}" class="btn btn-outline btn-sm">Kembali</a>
        </div>
    </div>
</x-app-layout>
