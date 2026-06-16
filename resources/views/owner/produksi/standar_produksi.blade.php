<x-app-layout>
    <div class="p-6">
        <div class="text-sm breadcrumbs mb-4 opacity-50">
            <ul>
                <li><a href="{{ route('owner.master.produk.index') }}">PRODUK</a></li>
                <li>STANDAR PRODUKSI</li>
            </ul>
        </div>

        @if (session('success'))
            <div class="alert alert-success mb-6 shadow-sm font-medium">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div role="tablist" class="tabs tabs-lifted">
            <input type="radio" name="stndr_tabs" role="tab"
                class="tab [--tab-bg:#fff] [--tab-border-color:#e5e7eb] text-xs font-bold text-base-200-content checked:text-primary-content"
                aria-label="PANDUAN KERJA" checked />
            <div role="tabpanel" class="tab-content bg-white border-gray-200 rounded-b-xl rounded-tr-xl p-8 shadow-sm">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 50px">No</th>
                            <th>Parameter</th>
                            <th>Standar / Instruksi</th>
                            <th>Keterangan</th>
                            <th style="width: 100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($panduans as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->parameter }}</td>
                                <td>{{ $item->standar }}</td>
                                <td>{{ $item->keterangan ?? '-' }}</td>
                                <td>
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-error btn-sm"
                                        onclick="modal_hapus_panduan_{{ $item->id }}.showModal()">
                                        Hapus
                                    </button>
                                </td>
                            </tr>

                            <dialog id="modal_hapus_panduan_{{ $item->id }}" class="modal">
                                <div class="modal-box border-t-4 border-error">
                                    <h3 class="font-bold text-lg text-error flex items-center gap-2">Konfirmasi Hapus
                                    </h3>
                                    <p class="py-4 text-sm">Hapus standar {{ $item->parameter }}?.</p>
                                    <div class="modal-action">
                                        <form action="{{ route('owner.master.panduan.destroy', $item->id) }}"
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

                        <form action="{{ route('owner.master.panduan.store') }}" method="POST">
                            @csrf
                            <tr class="bg-light">
                                <td>#</td>
                                <td>
                                    <input type="text" name="parameter" class="form-control"
                                        placeholder="Contoh: Suhu Goreng" required>
                                </td>
                                <td>
                                    <input type="text" name="standar" class="form-control"
                                        placeholder="Contoh: 170 C" required>
                                </td>
                                <td>
                                    <input type="text" name="keterangan" class="form-control"
                                        placeholder="Catatan tambahan...">
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-success btn-sm">
                                        Simpan
                                    </button>
                                </td>
                            </tr>
                        </form>
                    </tbody>
                </table>
            </div>

            <input type="radio" name="stndr_tabs" role="tab"
                class="tab [--tab-bg:#fff] [--tab-border-color:#e5e7eb] text-xs font-bold text-base-200-content checked:text-primary-content"
                aria-label="KONFIGURASI" />
            <div role="tabpanel" class="tab-content bg-white border-gray-200 rounded-b-xl rounded-tr-xl p-8 shadow-sm">
                <form action="{{ route('owner.produksi.standar_prod.store') }}" method="POST">
                    @csrf

                    <div class="flex flex-col gap-6">

                        <div class="w-full">
                            <h3 class="font-bold mb-6">Pengaturan Dasar Produksi</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                @foreach ($settings as $setting)
                                    <div class="form-control">
                                        <label class="label">
                                            <span
                                                class="label-text font-bold text-sm">{{ $setting->description }}</span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" name="settings[{{ $setting->key }}]"
                                                value="{{ $setting->value }}" class="input input-bordered w-full pl-4"
                                                placeholder="0">
                                            <div
                                                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <span class="text-[10px] text-gray-400 font-bold uppercase">
                                                    {{ $setting->key == 'kapasitas_maks' ? 'Kg' : 'Hari' }}
                                                </span>
                                            </div>
                                        </div>
                                        <label class="label">
                                            <span class="label-text-alt text-gray-400 italic text-[10px]">
                                                Update terakhir: {{ $setting->updated_at->format('d M Y') }}
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="divider opacity-50"></div>

                        <div class="w-full">
                            <h3 class="font-bold mb-6">Kelayakan Fasilitas & Alat</h3>
                            <div class="overflow-x-auto">
                                <table class="table table-zebra w-full border border-gray-100">
                                    <thead class="bg-gray-50">
                                        <tr class="text-gray-600">
                                            <th>Komponen</th>
                                            <th>Kondisi</th>
                                            <th>Tanggal Cek</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm">
                                        @foreach ($fasilitas as $item)
                                            <tr>
                                                <td>
                                                    <div class="font-medium">{{ $item->komponen }}</div>
                                                    <div class="text-[10px] opacity-50">{{ $item->deskripsi }}</div>
                                                </td>
                                                <td>
                                                    <select name="sop_fasilitas[{{ $item->slug }}][status]"
                                                        class="select select-bordered select-xs w-full max-w-37.5">
                                                        <option value="sesuai"
                                                            {{ $item->status == 'sesuai' ? 'selected' : '' }}>
                                                            Sesuai Standar
                                                        </option>
                                                        <option value="perbaikan"
                                                            {{ $item->status == 'perbaikan' ? 'selected' : '' }}>
                                                            Perlu Perbaikan
                                                        </option>
                                                    </select>
                                                </td>
                                                <td class="text-xs text-gray-500">
                                                    <div>{{ $item->tanggal_cek }}</div>
                                                    <div class="text-[10px] text-primary italic font-medium mt-0.5">
                                                        Oleh: {{ $item->user->name ?? 'Sistem' }}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="flex justify-end mt-4">
                            <button type="submit" class="btn btn-primary btn-sm px-6 text-white rounded-lg shadow-md">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
