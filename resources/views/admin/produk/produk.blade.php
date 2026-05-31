<x-app-layout>
    <div class="p-6">
        <div class="text-sm breadcrumbs mb-4 opacity-50">
            <ul>
                <li><a href="{{ route('admin.produk.index') }}">PRODUK</a></li>
                <li>DAFTAR PRODUK</li>
            </ul>
        </div>

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-base-content">Daftar Produk</h1>
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
                                    <div class="opacity-40 font-mono tracking-tighter text-xs">Kode:
                                        #PRD-{{ str_pad($produk->id, 3, '0', STR_PAD_LEFT) }}</div>
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
                                        @else
                                            <span class="badge badge-success badge-sm badge-outline">Aman</span>
                                        @endif
                                        <div class="flex items-center gap-1.5 opacity-60 text-xs mt-1">
                                            Min: {{ $produk->ss_produk }}
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <div class="flex justify-center gap-2">
                                        <form action="{{ route('admin.produk.updateStokMinimal', $produk->id) }}"
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

                                    </div>
                                </td>
                            </tr>
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
    </div>
</x-app-layout>
