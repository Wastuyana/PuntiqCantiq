<x-app-layout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-slate-800">Laporan Analisis HPP</h2>
        </div>

        <form action="" method="GET"
            class="flex flex-wrap justify-between items-end gap-4 mb-6 bg-white p-3 rounded-xl border border-slate-100 shadow-sm">
            <div class="flex gap-2 items-end">
                <div class="form-control">
                    <input type="date" name="start_date" value="{{ $startDate }}"
                        class="input input-bordered input-sm text-xs">
                </div>
                <span class="text-sm pb-1.5 text-slate-500 font-medium">s/d</span>
                <div class="form-control">
                    <input type="date" name="end_date" value="{{ $endDate }}"
                        class="input input-bordered input-sm text-xs">
                </div>
                <button type="submit" class="btn btn-primary btn-sm px-4 text-xs">Filter</button>
            </div>

            <div class="flex gap-1">
                <a href="{{ route('owner.laporan.hpp.excel', request()->all()) }}"
                    class="btn btn-success text-white btn-sm text-xs gap-1">
                    Excel
                </a>
                <a href="{{ route('owner.laporan.hpp.pdf', request()->all()) }}"
                    class="btn btn-error text-white btn-sm text-xs gap-1" target="_blank">
                    PDF
                </a>
            </div>
        </form>

        <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-base-200">
            <table class="table table-zebra w-full text-sm">
                <thead class="bg-base-50 text-base-content border-b border-base-200">
                    <tr>
                        <th>Batch & Tanggal</th>
                        <th>Detail Produk</th>
                        <th class="text-center">Hasil Produksi</th>
                        <th class="text-right">HPP Standar</th>
                        <th class="text-right">HPP Aktual</th>
                        <th class="text-right">Selisih (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporanHpp as $row)
                        <tr class="hover">
                            <td class="pl-6">
                                <div class="text-xs">
                                    {{ date('d M Y', strtotime($row->tanggal_produksi)) }}
                                </div>
                                <div class="opacity-70 font-medium">
                                    <a href="{{ route('owner.produksi.batch.show', $row->batch_id) }}"
                                        class="hover:underline">
                                        {{ $row->nomor_batch }} </a>
                                </div>
                            </td>
                            <td class="font-medium">
                                {{ $row->kategori }} - {{ $row->varian }}
                                <div class="opacity-80">{{ $row->ukuran }}</div>
                            </td>
                            <td class="text-center font-semibold">{{ number_format($row->hasil_aktual) }} Pcs</td>
                            <td class="text-right opacity-70">
                                Rp {{ number_format($row->hpp_standar, 0, ',', '.') }}
                            </td>
                            <td class="text-right font-bold text-base-content">
                                Rp {{ number_format($row->hpp_aktual, 0, ',', '.') }}
                            </td>
                            <td
                                class="text-right font-semibold {{ $row->selisih_hpp > 0 ? 'text-error' : 'text-success' }}">
                                {{ $row->selisih_hpp > 0 ? '+' : '' }}{{ number_format($row->selisih_hpp, 0, ',', '.') }}
                                <span
                                    class="text-[10px] block opacity-60">({{ number_format($row->persentase_varians, 1) }}%)</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10 opacity-50 italic">
                                Tidak ada riwayat laporan HPP pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
