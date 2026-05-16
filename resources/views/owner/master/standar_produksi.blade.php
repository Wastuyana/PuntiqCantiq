<x-app-layout>
    <div class="p-6">
        <div class="text-sm breadcrumbs mb-4 opacity-50">
            <ul>
                <li><a href="{{ route('owner.master.produk.index') }}">PRODUK</a></li>
                <li>STANDAR PRODUKSI</li>
            </ul>
        </div>

        <div role="tablist" class="tabs tabs-lifted">
            <input type="radio" name="stndr_tabs" role="tab"
                class="tab [--tab-bg:#fff] [--tab-border-color:#e5e7eb] text-xs font-bold text-base-200-content checked:text-primary-content"
                aria-label="KONFIGURASI" checked />
            <div role="tabpanel" class="tab-content bg-white border-gray-200 rounded-b-xl rounded-tr-xl p-8 shadow-sm">
                <div class="flex flex-col gap-6">
                    <div class="w-full">
                        <h3 class="font-bold mb-6">
                            Pengaturan Dasar Produksi
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            @foreach ($settings as $setting)
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-bold text-sm">
                                            {{ $setting->description }}
                                        </span>
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
                                            Update terakhir:
                                            {{ $setting->updated_at->format('d M Y') }}
                                        </span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="divider opacity-50"></div>

                    <div class="w-full">
                        <h3 class="font-bold mb-6">
                            Kelayakan Fasilitas & Alat
                        </h3>

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
                                    <tr>
                                        <td>
                                            <div>Area Produksi</div>
                                            <div class="text-[10px] opacity-50">
                                                Ventilasi, pencahayaan, & kebersihan lantai
                                            </div>
                                        </td>
                                        <td>
                                            <select name="sop_fasilitas[area_produksi][status]"
                                                class="select select-bordered select-xs w-full max-w-37.5">
                                                <option value="sesuai" selected>
                                                    Sesuai Standar</option>
                                                <option value="perbaikan">Perlu
                                                    Perbaikan</option>
                                            </select>
                                        </td>
                                        <td>

                                            {{ now()->subDays(2)->format('d/m/Y H:i') }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div>Alat Produksi</div>
                                            <div class="text-[10px] opacity-50">
                                                Material stainless steel & sterilitas alat</div>
                                        </td>
                                        <td>
                                            <select name="sop_fasilitas[alat_produksi][status]"
                                                class="select select-bordered select-xs w-full max-w-37.5">
                                                <option value="sesuai" selected>
                                                    Sesuai Standar</option>
                                                <option value="perbaikan">Perlu
                                                    Perbaikan</option>
                                            </select>
                                        </td>
                                        <td>

                                            {{ now()->subDays(5)->format('d/m/Y H:i') }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div>Penanganan Limbah</div>
                                            <div class="text-[10px] opacity-50">
                                                Pemisahan limbah organik & non-organik</div>
                                        </td>
                                        <td>
                                            <select name="sop_fasilitas[limbah][status]"
                                                class="select select-bordered select-xs w-full max-w-37.5">
                                                <option value="sesuai">Sesuai
                                                    Standar</option>
                                                <option value="perbaikan" selected>Perlu Perbaikan</option>
                                            </select>
                                        </td>
                                        <td>
                                            {{ now()->format('d/m/Y H:i') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <input type="radio" name="stndr_tabs" role="tab"
                class="tab [--tab-bg:#fff] [--tab-border-color:#e5e7eb] text-xs font-bold text-base-200-content checked:text-primary-content"
                aria-label="PANDUAN KERJA" />
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
                                    <form action="{{ route('owner.master.panduan.destroy', $item->id) }}"
                                        method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-error btn-sm"
                                            onclick="return confirm('Hapus poin ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
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
        </div>
    </div>
</x-app-layout>
