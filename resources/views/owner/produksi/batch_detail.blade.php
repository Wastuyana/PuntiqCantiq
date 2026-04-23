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
                <h2 class="card-title text-sm uppercase opacity-50">Hasil Produksi</h2>
                <div class="space-y-4 mt-2">
                    @foreach ($batch->batch_hasil as $hasil)
                        <div class="p-3 bg-base-50 rounded-lg border border-base-100">
                            <span class="block text-md opacity-50 font-bold">{{ $hasil->produk->kategori }}</span>
                            <span class="font-bold text-md text-primary-content">{{ $hasil->produk->varian }}</span>

                            <div class="flex justify-between text-sm mt-2">
                                <span class="opacity-70">Hasil Aktual:</span>
                                <span class="font-bold">{{ number_format($hasil->hasil_aktual) }} Pcs</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="opacity-70">HPP Aktual:</span>
                                <span class="font-bold text-success">Rp
                                    {{ number_format($hasil->hpp_aktual) }}</span>
                            </div>
                        </div>
                    @endforeach

                    <div class="divider m-0"></div>
                    <div class="flex justify-between items-center text-sm text-error font-bold">
                        <span>Tgl Kadaluarsa:</span>
                        <span>{{ \Carbon\Carbon::parse($batch->tanggal_kadaluarsa)->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-white shadow-sm border border-base-200">
            <div class="card-body">
                <h2 class="card-title text-sm uppercase opacity-50">Ringkasan Biaya Batch</h2>
                <div class="space-y-3 mt-2 text-sm">
                    <div class="flex justify-between">
                        <span>Total Bahan Baku:</span>
                        <b>Rp
                            {{ number_format($batch->total_biaya) }}</b>
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
                        <span class="text-primary">Rp {{ number_format($batch->total_biaya_produksi) }}</span>
                    </div>
                    <p class="text-[10px] italic opacity-50 mt-2">*Total biaya ini kemudian dibagi secara
                        proporsional ke setiap varian untuk menghasilkan HPP Aktual di samping.</p>
                </div>
            </div>
        </div>

        {{-- <div class="card bg-primary text-primary-content shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-sm uppercase opacity-70">Audit Keamanan Pangan</h2>
                <p class="text-xs italic">Batch ini telah divalidasi sesuai standar CPPOB-IRT untuk Home Industry.
                </p>
                <div class="mt-4 bg-white/20 p-3 rounded-lg">
                    <span class="text-xs">Status Higienitas:</span>
                    <p class="font-bold">Tervalidasi Sistem</p>
                </div>
            </div>
        </div> --}}

        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-base-200">
            <div class="p-4 bg-base-200 font-bold flex items-center gap-2">
                <span>Realisasi Pemakaian Bahan Baku</span>
            </div>
            <table class="table w-full">
                <thead>
                    <tr class="bg-base-100">
                        <th>Nama Bahan</th>
                        <th class="text-center">Target (Estimasi)</th>
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
