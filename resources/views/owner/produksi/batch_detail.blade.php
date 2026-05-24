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
            <div class="card-body p-4">
                <h2 class="card-title text-sm uppercase opacity-50 mb-2">Hasil Produksi</h2>

                <div class="overflow-x-auto rounded-xl border border-base-100">
                    <table class="table table-zebra w-full text-sm">
                        <thead class="bg-base-50 text-base-content">
                            <tr>
                                <th>Detail Produk</th>
                                <th class="text-center">Target Produksi</th>
                                <th class="text-center">Hasil Aktual</th>
                                <th class="text-right">HPP Aktual</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($batch->batch_hasil as $hasil)
                                <tr class="hover">
                                    <td class="font-medium text-base-content">
                                        {{ $hasil->produk->kategori }} -
                                        {{ $hasil->produk->varian }}
                                        <span class="text-xs opacity-60">({{ $hasil->produk->ukuran }})</span>
                                    </td>
                                    <td class="text-center font-semibold">
                                        {{ number_format($hasil->hasil_target) }} Pcs
                                    </td>
                                    <td class="text-center font-semibold">
                                        <span
                                            class="badge {{ $hasil->hasil_aktual > $hasil->hasil_target ? 'badge-error' : 'badge-ghost' }}">
                                            {{ number_format($hasil->hasil_aktual, 2) }}
                                            {{ $hasil->produk->satuan }}
                                        </span>
                                    </td>
                                    <td class="text-right font-bold text-success">
                                        Rp {{ number_format($hasil->hpp_aktual, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot class="bg-error/5 border-t border-error/20">
                            <tr>
                                <td colspan="3" class="text-error font-bold uppercase text-xs p-3">
                                    Tgl Kadaluarsa :
                                </td>
                                <td class="text-right text-error font-bold text-sm p-3">
                                    {{ \Carbon\Carbon::parse($batch->tanggal_kadaluarsa)->format('d M Y') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-base-200">
            <div class="p-4 bg-base-200 font-bold flex items-center gap-2">
                <span>Realisasi Pemakaian Bahan Baku</span>
            </div>
            <table class="table w-full">
                <thead>
                    <tr class="bg-base-100">
                        <th>Nama Bahan</th>
                        <th class="text-center">Target Terpakai</th>
                        <th class="text-center">Aktual Terpakai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($batch->batch_bahan as $bahan)
                        <tr>
                            <td class="font-bold">{{ $bahan->bahan_baku->nama }}</td>
                            <td class="text-center">{{ number_format($bahan->bahan_target, 2) }}
                                {{ $bahan->bahan_baku->satuan }}</td>
                            <td class="text-center">
                                <span
                                    class="badge {{ $bahan->bahan_aktual > $bahan->bahan_target ? 'badge-error' : 'badge-ghost' }}">
                                    {{ number_format($bahan->bahan_aktual, 2) }} {{ $bahan->bahan_baku->satuan }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card bg-white shadow-sm border border-base-200">
            <div class="card-body">
                <h2 class="card-title text-sm uppercase opacity-50">Ringkasan Biaya Batch</h2>
                <div class="space-y-3 mt-2 text-sm">
                    <div class="flex justify-between">
                        <span>Total Bahan Baku:</span>
                        <b>Rp
                            {{ number_format($batch->biaya_bahan) }}</b>
                    </div>
                    <div class="flex justify-between">
                        <span>Total Tenaga Kerja:</span>
                        <b>Rp {{ number_format($batch->biaya_tenagakerja) }}</b>
                    </div>
                    <div class="flex justify-between">
                        <span>Total Overhead:</span>
                        <b>Rp {{ number_format($batch->biaya_overhead) }}</b>
                    </div>
                    <div class="divider m-0"></div>
                    <div class="flex justify-between text-md font-bold">
                        <span>Total Biaya Produksi:</span>
                        <span class="text-primary">Rp {{ number_format($batch->total_biaya) }}</span>
                    </div>
                    <p class="text-[10px] italic opacity-50 mt-2">*Total biaya ini kemudian dibagi secara
                        proporsional ke setiap varian untuk menghasilkan HPP Aktual di samping.</p>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-error/20 mt-8">
            <div class="card-body">
                <h3 class="font-bold text-lg text-error flex items-center gap-2">
                    <i class="fas fa-exclamation-circle"></i> Riwayat Barang Rusak
                </h3>
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
            {{-- <button onclick="window.print()" class="btn btn-ghost btn-sm">🖨️ Cetak Detail Batch</button> --}}
        </div>
    </div>
</x-app-layout>
