<x-app-layout>
    <div class="p-6 bg-slate-50 min-h-screen">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-slate-800">Laporan Analisis Produksi</h2>
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
                <a href="{{ route('owner.laporan.export.excel', request()->all()) }}"
                    class="btn btn-success text-white btn-sm text-xs gap-1">
                    Excel
                </a>
                <a href="{{ route('owner.laporan.export.pdf', request()->all()) }}"
                    class="btn btn-error text-white btn-sm text-xs gap-1" target="_blank">
                    PDF
                </a>
            </div>
        </form>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th>Tanggal/No. Batch</th>
                        <th class="text-center">Efisiensi Hasil</th>
                        <th class="text-right">Biaya Aktual</th>
                        <th class="text-right">Biaya Standar</th>
                        <th class="text-center">Efisiensi Biaya</th>
                    </tr>
                </thead>
                <tbody class="">
                    @forelse($laporan as $row)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="pl-6">
                                <div class="text-xs">
                                    {{ date('d M Y', strtotime($row->tanggal_produksi)) }}
                                </div>
                                <div class="opacity-70 font-medium">
                                    <a href="{{ route('owner.produksi.batch.show', $row->id) }}"
                                        class="hover:underline">
                                        {{ $row->nomor_batch }} </a>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="flex flex-col items-center">
                                    <span
                                        class="font-semibold {{ $row->efisiensi_hasil < 100 ? 'text-error' : 'text-success' }}">
                                        {{ number_format($row->efisiensi_hasil, 1) }}%
                                    </span>
                                </div>
                            </td>

                            <td class="text-right font-medium">Rp {{ number_format($row->biaya_aktual, 0, ',', '.') }}
                            </td>
                            <td class="text-right text-slate-500">Rp
                                {{ number_format($row->total_biaya_standar, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if ($row->variance_biaya > 0)
                                    <div class="badge badge-error gap-1 text-white text-[10px] font-bold">
                                        +{{ number_format($row->variance_biaya, 2) }}%
                                    </div>
                                @else
                                    <div class="badge badge-success gap-1 text-white text-[10px] font-bold">
                                        {{ number_format($row->variance_biaya, 2) }}%
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-slate-400 italic">Tidak ada data produksi
                                pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
