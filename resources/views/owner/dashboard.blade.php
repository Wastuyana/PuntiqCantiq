<x-app-layout>
    <div class="p-6">
        <form action="{{ route('owner.dashboard') }}" method="GET"
            class="flex items-center gap-2 mb-6 bg-white p-2 px-3 rounded-lg shadow-sm border border-slate-100 w-fit">
            <div class="flex items-center gap-2">
                <select name="bulan" class="select select-bordered select-sm text-xs focus:outline-none">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('bulan', now()->month) == $m ? 'selected' : '' }}>
                            {{ date('M', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </select>

                <select name="tahun" class="select select-bordered select-sm text-xs focus:outline-none">
                    @for ($y = now()->year; $y >= now()->year - 2; $y--)
                        <option value="{{ $y }}" {{ request('tahun', now()->year) == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="flex gap-1 border-l pl-2 border-slate-200">
                <button type="submit" class="btn btn-primary btn-sm px-3 normal-case h-8 min-h-0">
                    Filter
                </button>
            </div>
        </form>

        @include('owner.partials._stats_grid')

        @include('owner.partials._avg_chart_hpp')

        @include('owner.partials._rekap_produksi')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            @include('owner.partials._proporsi_biaya_prod')
        </div>
    @include('owner.partials._sales_chart')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</x-app-layout>
