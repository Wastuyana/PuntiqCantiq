<div class="container mt-6">
    <div class="card border-light shadow-sm">
        <div class="card-body p-0">
            <h2 class="text-lg p-3 font-bold text-gray-700">Riwayat Produksi Terbaru</h2>
            @if ($semuaBatch->isEmpty())
                <p class="text-muted text-center my-4">Belum ada data produksi hari ini.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No. Batch</th>
                                <th>Tanggal Produksi</th>
                                <th>Status</th>
                                <th class="text-center">Aksi / Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($semuaBatch as $batch)
                                <!-- Berikan sedikit background tipis untuk baris yang masih draft agar mencolok -->
                                <tr class="{{ $batch->status == 'draft' ? 'table-warning-bg' : '' }}">
                                    <td><strong>{{ $batch->nomor_batch }}</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($batch->tanggal_produksi)->format('d M Y') }}</td>
                                    <td>
                                        @if ($batch->status == 'draft')
                                            <div class="flex flex-col">
                                                <span class="badge badge-neutral badge-sm font-bold py-3">Dalam
                                                    Proses</span>
                                            </div>
                                        @else
                                            <div class="flex flex-col">
                                                <span
                                                    class="badge badge-success badge-sm font-bold py-3 text-white">Selesai</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="flex justify-center gap-2">
                                            @if ($batch->status == 'draft')
                                                <a href="{{ route('admin.produksi.batch.edit', $batch->id) }}"
                                                    class="btn btn-sm btn-info btn-outline gap-2 shadow-sm normal-case">
                                                    Update
                                                </a>
                                                <button
                                                    class="btn btn-square btn-sm btn-outline btn-error hover:scale-105"
                                                    onclick="modal_hapus_batch_{{ $batch->id }}.showModal()">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            @else
                                                <a href="{{ route('admin.produksi.batch.show', $batch->id) }}"
                                                    class="btn btn-sm btn-info btn-outline gap-2 shadow-sm normal-case">
                                                    Detail
                                                </a>
                                                <a href="{{ route('admin.produksi.penyesuaian.create', $batch->id) }}"
                                                    class="btn btn-sm btn-info btn-outline gap-2 shadow-sm normal-case"
                                                    title="Laporkan Barang Rusak">Lapor Rusak
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
