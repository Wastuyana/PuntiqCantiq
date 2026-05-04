<x-app-layout>
    <div class="p-6 bg-base-100 min-h-screen">
        <div class="text-sm breadcrumbs mb-4 opacity-50">
            <ul>
                <li><a href="{{ route('owner.produksi.rekomendasi') }}">REKOMENDASI PRODUKSI</a></li>
                <li>DAFTAR REKOMENDASI</li>
            </ul>
        </div>

        <div class="flex justify-between items-center mb-4">
            <div>
                <h1 class="text-2xl font-bold text-base-content">Rekomendasi Produksi</h1>
            </div>
        </div>

        <form action="{{ route('owner.produksi.batch.create') }}" method="GET"> 
            <input type="hidden" name="no_batch" value="{{ $generatedNoBatch }}">
            <input type="hidden" name="tanggal_produksi" value="{{ date('Y-m-d') }}">
            <input type="hidden" name="status" value="draft">
            <div class="overflow-x-auto bg-base-200 rounded-xl shadow-sm border border-base-300 mb-6">
                <table class="table table-zebra w-full">
                    <thead class="bg-base-200 text-base-content">
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Target Produksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($batchAktif as $item)
                            <tr>
                                <td>
                                    <div>{{ $item['nama'] }}</div>
                                    <div class="text-xs text-muted">Estimasi habis: {{ round($item['prioritas'], 1) }}
                                        hari</div>
                                    <input type="hidden" name="produk_ids[]" value="{{ $item['id'] }}">
                                </td>

                                <td class="text-center">{{ $item['stok_aktual'] }}</td>

                                <td class="text-center">
                                    {{ $item['jumlah_acc'] }}
                                    <input type="hidden" name="hasil_target[{{ $item['id'] }}]"
                                        value="{{ $item['jumlah_acc'] }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mb-4">
                <h2 class="text-xl font-bold text-base-content">Estimasi Kebutuhan Bahan Baku</h2>
                <p class="text-sm text-muted mb-2">Daftar bahan baku yang perlu disiapkan untuk memenuhi target
                    produksi.
                </p>

                <div class="overflow-x-auto bg-base-200 rounded-xl shadow-sm border border-base-300 mb-6">
                    <table class="table table-zebra w-full">
                        <thead class="bg-base-200 text-base-content">
                            <tr>
                                <th>Bahan Baku</th>
                                <th class="text-center">Target Kebutuhan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($totalKebutuhanBahan) > 0)
                                @foreach ($totalKebutuhanBahan as $nama => $total)
                                    <tr>
                                        <td>{{ $nama }}</td>
                                        <td class="text-center">{{ number_format($total, 2, ',', '.') }}
                                            <small>{{ $satuanBahan[$nama] }}</small>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="2" class="text-center py-4">
                                        <div class="mt-6">
                                            <button type="submit" class="btn btn-primary w-full shadow-lg"
                                                onclick="return confirm('Apakah Anda yakin ingin membuat batch produksi berdasarkan rekomendasi ini?')">
                                                Konfirmasi Batch </button>
                                        </div>
                                    </td>
                                @else
                                <tr>
                                    <td colspan="2" class="text-center py-4 opacity-50 italic small">
                                        Tidak ada bahan yang perlu disiapkan.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </form>

        <div class="mb-4">
            <h2 class="text-xl font-bold text-base-content">Daftar Tunggu</h2>
            <p class="text-sm text-muted mb-2">Produk yang belum bisa diproduksi karena keterbatasan kapasitas.</p>

            <div class="overflow-x-auto bg-base-200 rounded-xl shadow-sm border border-base-300">
                @if ($daftarTunggu->count() > 0)
                    <table class="table table-zebra mb-0">
                        <thead class="bg-base-200 text-base-content">
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Stok</th>
                                <th class="text-center">Kebutuhan</th>
                            </tr>
                        </thead>
                        <tbody class="text-muted" style="font-size: 0.85rem;">
                            @foreach ($daftarTunggu as $item)
                                <tr>
                                    <td class="ps-4">{{ $item['nama'] }}</td>
                                    <td class="text-center">{{ $item['stok_aktual'] }}</td>
                                    <td class="text-danger text-center">{{ $item['q_rec'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
