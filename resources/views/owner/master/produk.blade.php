<x-app-layout>
    <div class="p-6">
        <div class="text-sm breadcrumbs mb-4 opacity-50">
            <ul>
                <li><a href="{{ route('owner.master.produk.index') }}">PRODUK</a></li>
                <li>DAFTAR PRODUK</li>
            </ul>
        </div>

        <div class="flex justify-between items-center mb-4">
            <div>
                <h1 class="text-2xl font-bold text-base-content">Daftar Produk</h1>
            </div>
            <button class="btn btn-outline btn-primary" onclick="modal_tambah_produk.showModal()">
                Tambah Produk
            </button>
        </div>

        @if (session('success'))
            <div class="alert alert-success mb-6 shadow-sm font-medium">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="overflow-x-auto bg-base-200 rounded-xl shadow-sm border border-base-300">
            <table class="table table-zebra w-full">
                <thead class="bg-base-200 text-base-content font-bold">
                    <tr>
                        <th>Nama Produk</th>
                        <th>Ukuran</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-base-100">
                    @forelse($produks->groupBy('kategori') as $kategori => $groupProduk)
                        @foreach ($groupProduk as $produk)
                            <tr class="hover:bg-primary/5 transition-colors group">
                                <td>
                                    <div class="group-hover:text-primary transition-colors">
                                        {{ $produk->kategori }} - {{ $produk->varian }}</div>
                                    <div class="opacity-80 font-mono tracking-tighter text-xs">Kode:
                                          {{ $produk->kode_produk }}</div>
                                </td>

                                <td>
                                    <div class="group-hover:text-primary transition-colors">
                                        {{ $produk->ukuran }}</div>
                                </td>

                                <td>
                                    <div class="group-hover:text-primary transition-colors">
                                        {{ $produk->stok }}</div>
                                </td>

                                <td>
                                    <div class="flex flex-col">
                                        @if ($produk->stok <= $produk->ss_produk)
                                            <span class="badge badge-error badge-sm badge-outline">Kritis</span>
                                        @elseif ($produk->stok <= $produk->rop_produk)
                                            <span class="badge badge-warning badge-sm badge-outline">Butuh
                                                Produksi</span>
                                        @else
                                            <span class="badge badge-success badge-sm badge-outline">Aman</span>
                                        @endif
                                        <div class="flex items-center gap-1.5 opacity-60 text-xs mt-1">
                                            Min: {{ $produk->rop_produk }}
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <div class="flex justify-center gap-2">
                                        <form action="{{ route('owner.produk.updateStokMinimal', $produk->id) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-info btn-square btn-outline"
                                                title="Sesuaikan Stok Minimal">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="size-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                                </svg>
                                            </button>
                                        </form>
                                        <button class="btn btn-square btn-sm btn-outline btn-warning"
                                            onclick="modal_edit_{{ $produk->id }}.showModal()" title="Edit Produk">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        <button class="btn btn-square btn-sm btn-outline btn-error"
                                            onclick="modal_hapus_{{ $produk->id }}.showModal()" title="Hapus Produk">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <dialog id="modal_edit_{{ $produk->id }}" class="modal">
                                <div class="modal-box w-11/12 max-w-2xl border-t-4 border-warning">
                                    <h3 class="font-bold text-lg mb-4">Edit Master Produk</h3>
                                    <form action="{{ route('owner.master.produk.update', $produk->id) }}"
                                        method="POST">
                                        @csrf @method('PUT')
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="form-control">
                                                <label class="label"><span
                                                        class="label-text font-bold text-xs uppercase opacity-60">Kategori</span></label>
                                                <input list="kategori_suggestions" name="kategori"
                                                    value="{{ $produk->kategori }}" class="input input-bordered w-full"
                                                    required>
                                            </div>
                                            <div class="form-control">
                                                <label class="label"><span
                                                        class="label-text font-bold text-xs uppercase opacity-60">Varian
                                                        Rasa</span></label>
                                                <input type="text" name="varian" value="{{ $produk->varian }}"
                                                    class="input input-bordered w-full" required />
                                            </div>
                                            <div class="form-control">
                                                <label class="label"><span
                                                        class="label-text font-bold text-xs uppercase opacity-60">Ukuran</span></label>
                                                <input type="text" name="ukuran" value="{{ $produk->ukuran }}"
                                                    class="input input-bordered w-full" required />
                                            </div>
                                        </div>

                                        <div class="divider text-xs opacity-50">Standard Costing & Inventory</div>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div class="form-control">
                                                <label class="label"><span
                                                        class="label-text font-bold text-xs uppercase opacity-60">
                                                        Harga Jual</span></label>
                                                <input type="number" name="harga_jual" step="any"
                                                    value="{{ $produk->harga_jual }}"
                                                    class="input input-bordered w-full" required />
                                            </div>
                                            <div class="form-control">
                                                <label class="label"><span
                                                        class="label-text font-bold text-xs uppercase opacity-60">Upah
                                                        Tenaga Kerja (Rp)</span></label>
                                                <input type="number" name="est_biaya_tenaga" step="any"
                                                    value="{{ $produk->est_biaya_tenaga }}"
                                                    class="input input-bordered w-full" required />
                                            </div>
                                            <div class="form-control">
                                                <label class="label"><span
                                                        class="label-text font-bold text-xs uppercase opacity-60">Overhead
                                                        (%)
                                                    </span></label>
                                                <input type="number" step="any" name="est_biaya_overhead"
                                                    value="{{ $produk->est_biaya_overhead }}"
                                                    class="input input-bordered w-full" required />
                                            </div>
                                            <div class="form-control">
                                                <label class="label"><span
                                                        class="label-text font-bold text-xs uppercase opacity-60">Stok
                                                        Saat Ini</span></label>
                                                <input type="number" name="stok" value="{{ $produk->stok }}"
                                                    class="input input-bordered w-full" required />
                                            </div>
                                        </div>

                                        <div class="modal-action">
                                            <button type="button" class="btn btn-ghost"
                                                onclick="this.closest('dialog').close()">Batal</button>
                                            <button type="submit" class="btn btn-outline btn-warning px-8">Simpan
                                                Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </dialog>

                            <dialog id="modal_hapus_{{ $produk->id }}" class="modal">
                                <div class="modal-box border-t-4 border-error">
                                    <h3 class="font-bold text-lg text-error flex items-center gap-2">Konfirmasi Hapus
                                    </h3>
                                    <p class="py-4 text-sm">Hapus <strong>{{ $produk->varian }}</strong>? Tindakan ini
                                        akan menghapus data HPP terkait.</p>
                                    <div class="modal-action">
                                        <form action="{{ route('owner.master.produk.destroy', $produk->id) }}"
                                            method="POST">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-ghost"
                                                onclick="this.closest('dialog').close()">Batal</button>
                                            <button type="submit" class="btn btn-error text-white">Ya, Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </dialog>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-20 opacity-30 italic">Belum ada produk terdaftar
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <dialog id="modal_tambah_produk" class="modal modal-bottom sm:modal-middle">
            <div class="modal-box w-11/12 max-w-3xl border-t-4 border-primary">
                <h3 class="font-bold text-xl mb-6">Input Produk Baru</h3>
                <form action="{{ route('owner.master.produk.store') }}" method="POST">
                    @csrf
                    <div class="bg-base-200 p-6 rounded-xl space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label"><span class="label-text font-bold">Kategori
                                        Produk</span></label>
                                <input list="kategori_suggestions" name="kategori"
                                    class="input input-bordered w-full" required>
                            </div>
                            <div class="form-control">
                                <label class="label"><span class="label-text font-bold">Varian Rasa</span></label>
                                <input type="text" name="varian" class="input input-bordered w-full" required />
                            </div>
                            <div class="form-control">
                                <label class="label"><span class="label-text font-bold">Ukuran</span></label>
                                <input type="text" name="ukuran" class="input input-bordered w-full" required />
                            </div>
                            <div class="form-control">
                                <label class="label"><span class="label-text font-bold">Harga Jual</span></label>
                                <input type="number" name="harga_jual" step="any"
                                    class="input input-bordered w-full" required />
                            </div>
                        </div>

                        <div class="divider text-xs opacity-50 uppercase tracking-widest">Parameter Biaya Standar</div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Est. Upah per Pcs (Rp)</span>
                                </label>
                                <input type="number" name="est_biaya_tenaga" step="any"
                                    class="input input-bordered w-full border-primary/30" required />
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text ">Est. Overhead (%)</span>
                                </label>
                                <input type="number" step="any" name="est_biaya_overhead"
                                    class="input input-bordered w-full border-primary/30" required />
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text font-bold opacity-60 text-xs uppercase">Stok
                                    Awal</span></label>
                            <input type="number" name="stok" step="amy"
                                class="input input-bordered w-24 shadow-inner" required />
                        </div>
                    </div>

                    <div class="modal-action mt-6">
                        <button type="button" class="btn btn-ghost"
                            onclick="modal_tambah_produk.close()">Batal</button>
                        <button type="submit" class="btn btn-outline btn-primary px-10">Simpan Produk</button>
                    </div>
                </form>
            </div>
        </dialog>
    </div>
</x-app-layout>
