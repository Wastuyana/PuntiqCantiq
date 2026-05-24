<x-app-layout>
    <div class="p-6">
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

                                <td class="text-center font-bold">
                                    <span
                                        class="badge {{ $item['stok_aktual'] <= $item['safety_stock'] ? 'badge-error text-white' : 'badge-success text-white' }} px-3 py-2 text-xs">
                                        {{ $item['stok_aktual'] }}
                                    </span>
                                </td>

                                <td class="text-center font-bold">
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
                                <th class="text-center">Stok </th>
                                <th class="text-center">Target Kebutuhan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($totalKebutuhanBahan) > 0)
                                @foreach ($totalKebutuhanBahan as $nama => $total)
                                    <tr>
                                        <td class="font-medium">{{ $nama }}</td>

                                        <td class="text-center">
                                            {{ number_format($stokBahan[$nama], 0, ',', '.') }}
                                            <small class="opacity-60">{{ $satuanBahan[$nama] }}</small>
                                        </td>

                                        <td
                                            class="text-center font-bold {{ $stokBahan[$nama] < $total ? 'text-error animate-pulse' : 'text-base-content' }}">
                                            {{ number_format($total, 2, ',', '.') }}
                                            <small class="font-normal opacity-60">{{ $satuanBahan[$nama] }}</small>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        <div class="mt-4">
                                            <button type="button" class="btn btn-primary w-full shadow-md"
                                                onclick="confirmBatch(this)">
                                                Konfirmasi Batch
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="3" class="text-center py-4 opacity-50 italic text-sm">
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmBatch(button) {
            Swal.fire({
                title: 'Konfirmasi Batch?',
                text: "Apakah Anda yakin ingin membuat batch produksi berdasarkan rekomendasi ini?.",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Buat Sekarang!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            })
        }
    </script>
</x-app-layout>
