<x-app-layout>
    <div class="p-6 bg-base-100 min-h-screen">
        <div class="text-sm breadcrumbs mb-4 opacity-50">
            <ul>
                <li><a href="{{ route('admin.produk.bom') }}">DAFTAR BOM</a></li>
                <li>DETAIL BOM</li>
            </ul>
        </div>

        <div class="mb-4">
            <h1 class="text-2xl font-bold text-base-content">{{ $produk->kategori }} - {{ $produk->varian }} -
                {{ $produk->ukuran }}</h1>
        </div>

        <div class="card bg-white border border-base-200 overflow-hidden mb-4">
            <div class="overflow-x-auto">
                <table class="table table-compact w-fit border border-base-300">
                    <tbody>
                        <tr>
                            <td class="font-bold text-sm">Harga Jual</td>
                            <td class="font-bold text-sm">
                                Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold text-sm">Estimasi Biaya Tenaga Kerja</td>
                            <td class="font-bold text-sm">
                                Rp {{ number_format($produk->est_biaya_tenaga, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-bold text-sm">Estimasi Biaya Overhead </td>
                            <td class="font-bold text-smy">
                                {{ $produk->est_biaya_overhead }}%
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card bg-white shadow-xl border border-base-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead class="bg-base-200 text-sm text-base-content font-bold">
                        <tr>
                            <th>Komponen Bahan Baku</th>
                            <th>Kuantitas</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produk->bom as $item)
                            <tr class="hover:bg-warning/5 transition-all group">
                                <td class="font-semibold text-base-content text-sm">
                                    {{ $item->bahan_baku->nama }}
                                </td>

                                <td>
                                    <div class="">
                                        <input type="number" step="0.001" name="jumlah_kebutuhan"
                                            value="{{ $item->jumlah_kebutuhan }}"
                                            class="input input-sm input-ghost w-24 text-center font-mono focus:bg-white focus:border-warning p-0" />
                                        <span class="opacity-50 font-bold">{{ $item->bahan_baku->satuan }}</span>
                                    </div>
                                </td>

                                <td>
                                    Rp {{ number_format($item->bahan_baku->harga_satuan, 0, ',', '.') }}
                                </td>

                                <td>
                                    Rp
                                    {{ number_format($item->jumlah_kebutuhan * $item->bahan_baku->harga_satuan, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot class="bg-base-50 text-lg">
                        <tr>
                            <th colspan="3" class="text-right text-base-content font-bold">Total
                                HPP Standar :</th>
                            <th class="text-base-content font-bold">
                                Rp {{ number_format($produk->hpp_standar, 0, ',', '.') }}
                            </th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="mt-4 flex justify-between items-center px-2">
            <p class="text-[11px] opacity-40 italic">Tips: Masukkan bahan baku dan jumlah kebutuhan, lalu tekan
                tombol
                kuning atau Enter untuk menyimpan baris.</p>
            <a href="{{ route('admin.produk.bom') }}" class="btn btn-ghost btn-sm">Kembali</a>
        </div>
    </div>
</x-app-layout>
