<x-app-layout>
    <div class="p-6 bg-base-100 min-h-screen">
        <div class="text-sm breadcrumbs mb-4 opacity-50">
            <ul>
                <li><a href="{{ route('owner.master.bom') }}">DAFTAR BOM</a></li>
                <li>DETAIL BOM</li>
            </ul>
        </div>

        <div class="mb-4">
            <h1 class="text-2xl font-bold text-base-content">{{ $produk->kategori }} - {{ $produk->varian }} - {{ $produk->ukuran }}</h1>
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
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produk->bom as $item)
                            <tr class="hover:bg-warning/5 transition-all group">
                                <td class="font-semibold text-base-content text-sm">
                                    {{ $item->bahan_baku->nama }}
                                </td>

                                <td>
                                    <form action="{{ route('bom.update', $item->id) }}" method="POST"
                                        id="form-update-{{ $item->id }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="">
                                            <input type="number" step="0.001" name="jumlah_kebutuhan"
                                                value="{{ $item->jumlah_kebutuhan }}"
                                                class="input input-sm input-ghost w-24 text-center font-mono focus:bg-white focus:border-warning p-0"
                                                onchange="this.form.submit()" />
                                            <span class="opacity-50 font-bold">{{ $item->bahan_baku->satuan }}</span>
                                        </div>
                                    </form>
                                </td>

                                <td>
                                    Rp {{ number_format($item->bahan_baku->harga_satuan, 0, ',', '.') }}
                                </td>

                                <td>
                                    Rp
                                    {{ number_format($item->jumlah_kebutuhan * $item->bahan_baku->harga_satuan, 0, ',', '.') }}
                                </td>

                                <td class="text-center">
                                    <form action="{{ route('bom.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline btn-error btn-xs btn-square"
                                            onclick="return confirm('Hapus bahan ini?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18 18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        <tr class="bg-base-50 border-t-2 border-base-200">
                            <td colspan="5" class="p-0">
                                <form action="{{ route('bom.store') }}" method="POST"
                                    class="flex items-center w-full">
                                    @csrf
                                    <input type="hidden" name="produk_id" value="{{ $produk->id }}">

                                    <table class="table table-lg w-full m-0">
                                        <tr>
                                            <td class="border-none">
                                                <select name="bahan_baku_id"
                                                    class="select select-sm select-ghost w-full focus:bg-white font-bold"
                                                    required>
                                                    <option disabled selected>Tambah bahan...</option>
                                                    @foreach ($allBahanBaku as $bahan)
                                                        <option value="{{ $bahan->id }}">{{ $bahan->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center border-none">
                                                <input type="number" step="0.001" name="jumlah_kebutuhan"
                                                    placeholder="0.00"
                                                    class="input input-sm input-bordered w-24 text-center font-mono"
                                                    required />
                                            </td>
                                            <td width="40%" class="border-none"></td>
                                            <td class="text-center border-none">
                                                <button type="submit"
                                                    class="btn btn-outline btn-warning btn-sm btn-square">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </td>
                        </tr>
                    </tbody>

                    <tfoot class="bg-base-50">
                        <tr>
                            <th colspan="3" class="text-right text-base-content font-bold">Total
                                HPP Standar :</th>
                            <th class="text-base-content font-bold">
                                Rp {{ number_format($produk->hitungHppStandar(), 0, ',', '.') }}
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
            <a href="{{ route('owner.master.bom') }}" class="btn btn-ghost btn-sm">Selesai</a>
        </div>
    </div>
</x-app-layout>
