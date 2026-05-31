<x-app-layout>
    <div class="p-6">
        <div class="text-sm breadcrumbs mb-4 opacity-50">
            <ul>
                <li><a href="{{ route('admin.produksi.batch.index') }}">BATCH</a></li>
                <li>DAFTAR BATCH</li>
            </ul>
        </div>

        @if (session('success'))
            <div class="alert alert-success mb-6 shadow-sm font-medium">
                <span>{{ session('success') }}</span>
            </div>
        @endif
        
        <div class="overflow-x-auto bg-base-200 rounded-xl shadow-sm border border-base-300">
            <table class="table table-zebra w-full">
                <thead class="bg-base-200 text-base-content">
                    <tr>
                        <th>Tanggal Produksi</th>
                        {{-- <th>Produk yang Dibuat</th> --}}
                        <th>Status Produksi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-base-100">
                    @forelse($batches->groupBy(fn($item) => $item->created_at->format('F Y')) as $bulan => $groupBatch)
                        @foreach ($groupBatch as $batch)
                            <tr class="hover:bg-neutral/5 transition-colors group">
                                <td class="pl-6">
                                    <div class="group-hover:text-primary transition-colors tracking-tight">
                                        {{ $batch->created_at->format('d M Y') }}
                                    </div>
                                    <div class="opacity-40 text-xs font-medium">
                                        {{ $batch->nomor_batch }}
                                    </div>
                                </td>

                                {{-- <td>
                                    <div class="flex flex-col gap-1">
                                        @foreach ($batch->batch_hasil->take(3) as $hasil)
                                            <span
                                                class="badge badge-sm badge-soft badge-accent">{{ $hasil->produk->kategori }}
                                                - {{ $hasil->produk->varian }}</span>
                                        @endforeach

                                        @if ($batch->batch_hasil->count() > 3)
                                            <span class="text-xs opacity-50">+{{ $batch->batch_hasil->count() - 3 }}
                                                lainnya</span>
                                        @endif
                                    </div>
                                </td> --}}

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
                                            <button class="btn btn-square btn-sm btn-outline btn-error hover:scale-105"
                                                onclick="modal_hapus_batch_{{ $batch->id }}.showModal()">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
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

                            <dialog id="modal_hapus_batch_{{ $batch->id }}" class="modal">
                                <div class="modal-box border-t-4 border-error">
                                    <h3 class="font-bold text-lg text-error">Batalkan Batch Produksi?</h3>
                                    <div class="modal-action">
                                        <form action="{{ route('admin.produksi.batch.destroy', $batch->id) }}"
                                            method="POST">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-ghost"
                                                onclick="this.closest('dialog').close()">Tutup</button>
                                            <button type="submit" class="btn btn-error text-white font-bold">Ya,
                                                Batalkan Batch</button>
                                        </form>
                                    </div>
                                </div>
                            </dialog>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-20">
                                <div class="flex flex-col items-center opacity-20 italic">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <span class="text-xl font-bold">Belum ada riwayat produksi</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
