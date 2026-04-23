<x-app-layout>
    <div class="p-6 bg-base-100 min-h-screen">
        <div class="text-sm breadcrumbs mb-4 opacity-50">
            <ul>
                <li><a href="{{ route('owner.master.bom') }}">Daftar BoM</a></li>
                <li>Detail Komposisi</li>
            </ul>
        </div>

        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4 bg-white p-4 rounded-3xl shadow-sm border border-base-200">
            <div>
                <h1 class="text-2xl font-black  text-base-content">{{ $produk->varian }}</h1>
                <div class="badge badge-warning font-bold mt-2 uppercase text-xs tracking-widest px-4">
                    {{ $produk->kategori }}
                </div>
            </div>
            <div class="text-right bg-primary/5 p-6 rounded-2xl border-2 border-primary/20 min-w-50">
                <p class="text-sm uppercase font-bold opacity-60 mb-1">Total HPP Standar</p>
                <p class="text-2xl font-black text-primary font-mono">
                    Rp {{ number_format($produk->hitungHppStandar(), 0, ',', '.') }}
                </p>
                <div class="flex gap-2 mt-1 text-right">
                    <p class="text-xs opacity-50 mb-1">Upah:
                        Rp{{ number_format($produk->est_biaya_tenaga, 0, ',', '.') }}
                    </p>
                    <p class="text-xs opacity-50 mb-1">Overhead:
                        {{ $produk->est_biaya_overhead }}%</p>
                </div>
            </div>
        </div>

        <div class="card bg-white shadow-xl border border-base-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table table-lg w-full">
                    <thead class="bg-base-200 text-sm text-base-content font-bold">
                        <tr>
                            <th width="40%">Komponen Bahan Baku</th>
                            <th width="20%" class="text-center">Kuantitas / Qty</th>
                            <th width="15%" class="text-right">Harga Satuan</th>
                            <th width="15%" class="text-right">Subtotal</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-base-100">
                        @foreach ($produk->bom as $item)
                            <tr class="hover:bg-warning/5 transition-all group">
                                <td class="font-semibold text-base-content  text-sm">
                                    {{ $item->bahan_baku->nama }}
                                </td>

                                <td class="text-center">
                                    {{-- FORM EDIT PINDAH KE SINI --}}
                                    <form action="{{ route('bom.update', $item->id) }}" method="POST"
                                        id="form-update-{{ $item->id }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="flex items-center justify-center gap-2">
                                            <input type="number" step="0.001" name="jumlah_kebutuhan"
                                                value="{{ $item->jumlah_kebutuhan }}"
                                                class="input input-sm input-ghost w-24 text-center font-mono focus:bg-white focus:border-warning p-0"
                                                onchange="this.form.submit()" />
                                            <span
                                                class="text-xs opacity-50 font-bold">{{ $item->bahan_baku->satuan }}</span>
                                        </div>
                                    </form>
                                </td>

                                <td class="text-right opacity-60 italic text-sm font-mono px-4">
                                    Rp {{ number_format($item->bahan_baku->harga_satuan, 0, ',', '.') }}
                                </td>

                                <td class="text-right font-black text-primary font-mono px-4">
                                    Rp
                                    {{ number_format($item->jumlah_kebutuhan * $item->bahan_baku->harga_satuan, 0, ',', '.') }}
                                </td>

                                <td class="text-center">
                                    <form action="{{ route('bom.destroy', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-ghost btn-circle btn-xs text-error opacity-0 group-hover:opacity-100 transition-opacity"
                                            onclick="return confirm('Hapus bahan ini?')">✕</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        {{-- BARIS INPUT BARU --}}
                        <tr class="bg-base-50 border-t-2 border-base-200">
                            {{-- BUNGKUS SEMUA INPUT DALAM SATU TD AGAR VALID HTML --}}
                            <td colspan="5" class="p-0">
                                <form action="{{ route('bom.store') }}" method="POST"
                                    class="flex items-center w-full">
                                    @csrf
                                    {{-- Kirim ID Produk lewat hidden input karena Route Resource POST /bom tidak punya parameter ID --}}
                                    <input type="hidden" name="produk_id" value="{{ $produk->id }}">

                                    <table class="table table-lg w-full m-0">
                                        <tr>
                                            <td width="40%" class="border-none">
                                                <select name="bahan_baku_id"
                                                    class="select select-sm select-ghost w-full focus:bg-white font-bold"
                                                    required>
                                                    <option disabled selected>+ Tambah bahan...</option>
                                                    @foreach ($allBahanBaku as $bahan)
                                                        <option value="{{ $bahan->id }}">{{ $bahan->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td width="20%" class="text-center border-none">
                                                <input type="number" step="0.001" name="jumlah_kebutuhan"
                                                    placeholder="0.00"
                                                    class="input input-sm input-bordered w-24 text-center font-mono"
                                                    required />
                                            </td>
                                            <td width="30%" class="border-none"></td>
                                            <td width="10%" class="text-center border-none">
                                                <button type="submit"
                                                    class="btn btn-warning btn-sm btn-square shadow-md">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
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
                            <th colspan="3"
                                class="text-right text-base-content font-bold uppercase tracking-widest text-xs">Total
                                HPP Standar :</th>
                            <th class="text-right text-lg font-black text-primary">
                                Rp {{ number_format($produk->hitungHppStandar(), 0, ',', '.') }}
                            </th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="mt-4 flex justify-between items-center px-2">
            <p class="text-[11px] opacity-40 italic">Tips: Masukkan bahan baku dan jumlah kebutuhan, lalu tekan tombol
                kuning atau Enter untuk menyimpan baris.</p>
            <a href="{{ route('owner.master.bom') }}" class="btn btn-ghost btn-sm">Selesai</a>
        </div>
    </div>
</x-app-layout>
