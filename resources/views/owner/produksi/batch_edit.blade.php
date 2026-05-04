<x-app-layout>
    <div class="p-6 bg-base-100 min-h-screen">
        <div class="w-full">
            <div class="text-sm breadcrumbs mb-6 opacity-50 uppercase font-bold tracking-widest">
                <ul>
                    <li><a href="{{ route('owner.produksi.batch.index') }}">Batch</a></li>
                    <li>Finalisasi Produksi</li>
                </ul>
            </div>

            <div class="bg-white p-8 rounded-3xl shadow-sm border border-base-200">
                <div class="border-b pb-6 mb-8">
                    <h2 class="font-bold text-3xl text-gray-800">Batch: {{ $batch->nomor_batch }}</h2>
                    <p class="text-sm opacity-50 mt-1">Lengkapi data aktual untuk menyelesaikan proses produksi.</p>
                </div>

                <form action="{{ route('owner.produksi.batch.update', $batch->id) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="space-y-10">

                        <section class="space-y-4">
                            <h3 class="text-xs uppercase opacity-50 font-bold tracking-wider">Hasil Produksi</h3>

                            <div class="space-y-4">
                                @foreach ($batch->batch_hasil as $hasil)
                                    <div class="bg-base-50 p-6 rounded-2xl border border-base-200 space-y-6">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <span
                                                    class="block text-[10px] uppercase opacity-50 font-extrabold mb-1">Varian
                                                    Produk</span>
                                                <span
                                                    class="font-bold text-xl text-secondary-content">{{ $hasil->produk->kategori }}
                                                    - {{ $hasil->produk->varian }} - {{ $hasil->produk->ukuran }}</span>
                                                </span>
                                            </div>
                                            <div class="badge badge-outline opacity-50 italic">ID:
                                                {{ $hasil->produk_id }}</div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="form-control w-full">
                                                <label class="label"><span class="label-text font-bold text-xs">Target
                                                        Produksi</span></label>
                                                <div
                                                    class="input input-bordered bg-base-200 flex items-center font-bold text-gray-500">
                                                    {{ $hasil->hasil_target }} Pcs
                                                </div>
                                            </div>
                                            <div class="form-control w-full">
                                                <label class="label"><span class="label-text font-bold text-xs">Hasil
                                                        Produksi Aktual</span></label>
                                                <input type="number" name="hasil_aktual[{{ $hasil->id }}]"
                                                    value="{{ $hasil->hasil_target }}"
                                                    class="input input-bordered font-bold text-sm focus:border-primary"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>

                        <div class="form-control w-full grid grid-cols-1">
                            <label class="label"><span
                                    class="text-xs uppercase opacity-50 font-bold tracking-wider">Tanggal
                                    Kadaluarsa
                                </span></label>
                            <input type="date" name="tanggal_kadaluarsa"
                                value="{{ now()->addMonths(6)->format('Y-m-d') }}"
                                class="input input-bordered font-bold text-sm focus:border-primary">
                        </div>

                        <section class="space-y-4">
                            <h3 class="text-xs uppercase opacity-50 font-bold tracking-wider">Pemakaian Bahan Baku</h3>
                            <div class="space-y-3">
                                @foreach ($batch->batch_bahan as $bahan)
                                    <div
                                        class="flex items-center gap-4 bg-white p-2 rounded-2xl border group hover:border-primary transition-all shadow-sm">
                                        <div class="flex-1">
                                            <span
                                                class="text-sm font-bold block text-gray-700">{{ $bahan->bahan_baku->nama }}</span>
                                            <span class="text-[10px] opacity-40 italic font-medium">
                                                Estimasi: {{ $bahan->bahan_target }} {{ $bahan->bahan_baku->satuan }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <input type="number" step="0.01"
                                                name="bahan_aktual[{{ $bahan->id }}]"
                                                value="{{ $bahan->bahan_target }}"
                                                class="input input-bordered input-sm w-28 font-bold text-right focus:input-primary">
                                            <span
                                                class="text-xs opacity-50 w-8 font-bold">{{ $bahan->bahan_baku->satuan }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>

                        <section class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="text-xs uppercase opacity-50 font-bold tracking-wider">Total Biaya
                                            Tenaga Kerja (Rp)</span>
                                    </label>
                                    <input type="number" name="biaya_tenagakerja"
                                        value="{{ $batch->estimasi_tenaga_kerja }}"
                                        class="input input-bordered font-bold text-sm focus:border-primary"
                                        placeholder="Masukkan total biaya tenaga kerja">
                                    <label class="label">
                                        <span class="label-text-alt opacity-50 italic">Estimasi awal: Rp
                                            {{ number_format($batch->estimasi_tenaga_kerja) }}</span>
                                    </label>
                                </div>
                                <div class="form-control w-full">
                                    <label class="label">
                                        <span class="label-text font-bold text-xs">Total Biaya Overhead (Rp)</span>
                                    </label>
                                    <input type="number" name="biaya_overhead" value="{{ $batch->estimasi_overhead }}"
                                        class="input input-bordered font-bold text-sm focus:border-primary"
                                        placeholder="Masukkan total biaya overhead">
                                    <label class="label">
                                        <span class="label-text-alt opacity-50 italic">
                                            Estimasi otomatis: Rp {{ number_format($batch->estimasi_overhead) }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </section>

                        <section class="space-y-4">
                            <h3 class="text-xs uppercase opacity-50 font-bold tracking-wider flex items-center gap-2">
                                Checklist CPPOB-IRT
                            </h3>
                            <!-- Kita kirim hidden input checklist_sop sebagai 1 jika disubmit -->
                            <input type="hidden" name="checklist_sop" value="1">

                            <div class="bg-warning/5 p-6 rounded-2xl border border-warning/20 space-y-4">
                                @php
                                    $checks = [
                                        'Peralatan & wadah produksi telah dicuci bersih dan dikeringkan sebelum digunakan',
                                        'Personil produksi dalam kondisi kesehatan yang baik.',
                                        'Bahan baku pisang telah melalui sortir kualitas dan pencucian bersih.',
                                        'Kualitas produk akhir telah diperiksa serta siap untuk dikemas.',
                                    ];
                                @endphp

                                @foreach ($checks as $index => $text)
                                    <label class="flex items-start gap-4 cursor-pointer group">
                                        <!-- Name menggunakan array sop_details agar bisa disimpan sebagai JSON -->
                                        <input type="checkbox" name="sop_details[]" value="{{ $text }}"
                                            required class="checkbox checkbox-warning checkbox-sm mt-1">
                                        <span
                                            class="text-sm italic text-gray-600 group-hover:text-black leading-relaxed">
                                            {{ $text }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </section>

                        <div class="pt-10 flex gap-4 border-t">
                            <button type="submit"
                                class="btn btn-success font-bold text-success-content shadow-lg shadow-success/20">
                                Simpan & Selesaikan Produksi
                            </button>

                            <a href="{{ route('owner.produksi.batch.index') }}"
                                class="btn btn-ghost px-4 opacity-50 hover:opacity-100 font-bold ">
                                Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
