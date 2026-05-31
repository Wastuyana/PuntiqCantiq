<x-app-layout>
    <div class="p-6 space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Quality Control Bahan</h2>
                <p class="text-sm text-gray-500 italic">Verifikasi kualitas barang masuk sebelum jadi stok.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success shadow-sm text-white border-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div x-data="{ tab: 'antrian' }">
            <div class="flex gap-2 mb-6">
                <button @click="tab = 'antrian'" :class="tab == 'antrian' ? 'btn-primary text-white' : 'btn-ghost'"
                    class="btn btn-md shadow-sm">
                    Antrian QC ({{ $waitingList->count() }})
                </button>
                <button @click="tab = 'history'" :class="tab == 'history' ? 'btn-primary text-white' : 'btn-ghost'"
                    class="btn btn-md shadow-sm">
                    Riwayat Pemeriksaan
                </button>
            </div>

            <!-- ISI TAB 1: ANTRIAN -->
            <div x-show="tab === 'antrian'" x-transition>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($waitingList as $item)
                        <div class="card bg-base-100 shadow-sm border border-base-200">
                            <div class="card-body p-6">
                                <div class="flex justify-between">
                                    <span class="badge badge-outline badge-warning font-bold italic">PENDING</span>
                                    <span class="text-xs opacity-50">{{ $item->tanggal_masuk }}</span>
                                </div>
                                <h3 class="text-2xl font-extrabold text-gray-700 mt-2">{{ $item->bahan_baku->nama }}</h3>
                                <div class="mt-4 p-3 bg-base-200 rounded-lg text-sm space-y-2">
                                    <p class="flex justify-between"><span>Total:</span> <b>{{ $item->jumlah_total }}
                                            unit</b></p>
                                    <p class="flex justify-between"><span>Supplier:</span> <b
                                            class="truncate ml-2">{{ $item->supplier->nama_supplier }}</b></p>
                                    <p class="flex justify-between">
                                    <span>Kode:</span> 
                                    <b class="truncate ml-2">{{ $item->kode_pesanan }}</b>
                                </p>
                                </div>
                                <div class="card-actions mt-6">
                                    <button
                                        onclick="document.getElementById('modal_qc_{{ $item->bahan_masuk_id }}').showModal()"
                                        class="btn btn-warning btn-block text-white font-bold">
                                        Mulai Verifikasi
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- MODAL (DaisyUI Native Dialog - NO CHECKBOX) -->
                        <dialog id="modal_qc_{{ $item->bahan_masuk_id }}" class="modal modal-bottom sm:modal-middle">
                            <div class="modal-box border-t-8 border-warning">
                                <h3 class="font-bold text-xl">Cek Kondisi: {{ $item->bahan_baku->nama }}</h3>
                                <p class="text-sm py-2 opacity-70">Pastikan total Bagus + Rusak sesuai dengan jumlah
                                    kedatangan ({{ $item->jumlah_total }}).</p>
                                <form action="{{ route('owner.inventory.qc.store') }}" method="POST" class="mt-4">
                                    @csrf
                                    <input type="hidden" name="bahan_masuk_id" value="{{ $item->id }}">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="form-control">
                                            <label class="label font-bold text-success">Kondisi Bagus</label>
                                            <input type="number" name="jumlah_bagus" max="{{ $item->jumlah_total }}"
                                                class="input input-bordered border-success focus:ring-success"
                                                placeholder="0" required>
                                        </div>
                                        <div class="form-control">
                                            <label class="label font-bold text-error">Kondisi Rusak</label>
                                            <input type="number" name="jumlah_rusak"
                                                class="input input-bordered border-error focus:ring-error"
                                                placeholder="0" required>
                                        </div>
                                    </div>

                                    <div class="form-control mt-4">
                                        <label class="label font-semibold text-gray-600">Catatan Pemeriksaan</label>
                                        <textarea name="catatan" class="textarea textarea-bordered h-24" placeholder="Misal: 2 botol pecah di perjalanan..."></textarea>
                                    </div>

                                    <div class="modal-action">
                                        <button type="button"
                                            onclick="document.getElementById('modal_qc_{{ $item->bahan_masuk_id }}').close()"
                                            class="btn btn-ghost">Batal</button>
                                        <button type="submit" class="btn btn-primary px-10">Selesaikan QC</button>
                                    </div>
                                </form>
                            </div>
                        </dialog>
                    @empty
                        <div
                            class="col-span-full py-20 text-center bg-base-200 rounded-3xl border-4 border-dashed border-base-300">
                            <p class="text-gray-500 mt-4 italic font-medium">Belum ada barang baru yang
                                perlu dicek.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- ISI TAB 2: HISTORY -->
            <div x-show="tab === 'history'" x-transition>
                <div class="bg-base-100 rounded-2xl border border-base-300 shadow-sm overflow-hidden">
                    <table class="table table-zebra">
                        <thead class="bg-base-200">
                            <tr class="text-gray-700">
                                <th>Tanggal</th>
                                <th>Bahan</th>
                                <th>Supplier</th>
                                <th class="text-center">Bagus</th>
                                <th class="text-center">Rusak</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($historyQC as $h)
                                <tr>
                                    <td class="text-xs">{{ \Carbon\Carbon::parse($h->tanggal_qc)->format('d M Y') }}
                                    </td>
                                    <td><span
                                            class="font-bold text-primary">{{ $h->bahan_baku->nama ?? 'Bahan tidak ditemukan' }}</span>
                                    </td>
                                    <td class="text-xs">{{ $h->bahan_masuk->supplier->nama_supplier ?? 'Supplier Tidak Ditemukan' }}</td>
                                    <td class="text-center">
                                        <div class="badge badge-success text-white font-bold">{{ $h->jumlah_bagus }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="badge badge-error text-white font-bold">{{ $h->jumlah_rusak }}
                                        </div>
                                    </td>
                                    <td class="italic text-gray-500 text-sm">{{ $h->catatan ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-10 opacity-40 italic font-medium">Belum ada
                                        data riwayat pemeriksaan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
