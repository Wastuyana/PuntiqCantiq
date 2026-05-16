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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl">
                            @foreach ($settings as $setting)
                                <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 flex flex-col gap-1">
                                    <span class="font-bold text-sm">
                                        {{ $setting->description }}
                                    </span>

                                    <div class="flex items-baseline gap-2 mt-1">
                                        <span class="text-sm">    
                                            {{ number_format($setting->value, 0, ',', '.') }}
                                        </span>
                                        <span class="text-xs text-slate-500">
                                            {{ $setting->key == 'kapasitas_maks' ? 'Kilogram' : 'Hari Kerja' }}
                                        </span>
                                    </div>

                                    {{-- Footer Info --}}
                                    <div class="flex items-center gap-1.5 mt-2 opacity-60">
                                        <i class="fa-regular fa-clock text-[10px]"></i>
                                        <span class="text-[10px] font-medium italic text-slate-400">
                                            Pembaruan terakhir: {{ $setting->updated_at->format('d M Y') }}
                                        </span>
                                    </div>
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
                        @forelse ($panduans as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->parameter }}</td>
                                <td>{{ $item->standar }}</td>
                                @if ($item->keterangan)
                                    <span class="text-slate-500 italic">{{ $item->keterangan }}</span>
                                @else
                                    <span class="text-slate-300 italic">Tidak ada catatan</span>
                                @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-10 text-center">
                                    <p class="font-medium">Belum ada data panduan kerja</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
