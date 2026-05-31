<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Puntiq Cantiq') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="drawer lg:drawer-open">
        <input id="my-sidebar" type="checkbox" class="drawer-toggle" />

        <div class="drawer-content flex flex-col min-h-screen bg-base-200">
            <div class="navbar bg-base-100 border-b border-base-300 px-4 h-16 sticky top-0 z-30 justify-between">
                <div class="lg:hidden">
                    <label for="my-sidebar" class="btn btn-square btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            class="w-6 h-6 stroke-current">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </label>
                </div>

                <div class="hidden lg:block"></div>

                <div class="flex items-center gap-3">
                    @include('layouts.navigation')
                </div>
            </div>

            <main class="p-4 lg:p-4">
                <div class="bg-base-100 shadow-sm rounded-xl border border-base-300 min-h-[50vh]">
                    {{ $slot }}
                </div>
            </main>
        </div>

        <div class="drawer-side z-40">
            <label for="my-sidebar" class="drawer-overlay"></label>
            <ul class="menu p-4 w-64 min-h-full bg-base-100 border-r border-base-300 text-base-content">
                <div class="flex px-4 py-2 mb-4">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}">
                            <x-application-logo class="block h-9 w-auto " />
                        </a>
                    </div>
                </div>

                <li>
                    <a href="{{ route('dashboard') }}"
                        class="{{ request()->routeIs('*dashboard*') ? 'text-primary-content font-semibold' : '' }} rounded-lg px-4 py-3 flex items-center gap-3">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>

                        <span>Dashboard</span>
                    </a>
                </li>

                @role('owner')
                    <li>
                        <details class="group"
                            {{ request()->routeIs('owner.produksi.*') || request()->routeIs('owner.dashboard') ? 'open' : '' }}>
                            <summary
                                class="{{ request()->routeIs('owner.produksi.*') ? 'text-primary-content font-medium' : '' }} hover:bg-base-200 rounded-lg px-4 py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4.5 12a7.5 7.5 0 0 0 15 0m-15 0a7.5 7.5 0 1 1 15 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077 1.41-.513m14.095-5.13 1.41-.513M5.106 17.785l1.15-.964m11.49-9.642 1.149-.964M7.501 19.795l.75-1.3m7.5-12.99.75-1.3m-6.063 16.658.26-1.477m2.605-14.772.26-1.477m0 17.726-.26-1.477M10.698 4.614l-.26-1.477M16.5 19.794l-.75-1.299M7.5 4.205 12 12m6.894 5.785-1.149-.964M6.256 7.178l-1.15-.964m15.352 8.864-1.41-.513M4.954 9.435l-1.41-.514M12.002 12l-3.75 6.495" />
                                    </svg>

                                    Produksi
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 transition-transform group-open:rotate-180" fill="none">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </summary>

                            <ul class="mt-2 space-y-1">
                                <li>
                                    <a href="{{ route('owner.produksi.batch.index') }}"
                                        class="{{ request()->routeIs('owner.produksi.batch.index') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block">
                                        Batch
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('owner.produksi.rekomendasi') }}"
                                        class="{{ request()->routeIs('owner.produksi.rekomendasi') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block">
                                        Rekomendasi Produksi
                                    </a>
                                </li>
                            </ul>
                        </details>
                    </li>

                    <li>
                        <details class="group"
                            {{ request()->routeIs('owner.master.*') || request()->routeIs('owner.dashboard') ? 'open' : '' }}>
                            <summary
                                class="{{ request()->routeIs('owner.master.*') ? 'text-primary-content font-medium' : '' }} hover:bg-base-200 rounded-lg px-4 py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                                    </svg>

                                    Produk
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 transition-transform group-open:rotate-180" fill="none">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </summary>

                            <ul class="mt-2 space-y-1">
                                <li>
                                    <a href="{{ route('owner.master.produk.index') }}"
                                        class="{{ request()->routeIs('owner.master.produk.index') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block">
                                        Produk
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('owner.master.bom.index') }}"
                                        class="{{ request()->routeIs('owner.master.bom.index') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block text-sm">
                                        Bills Of Materials (BoM)
                                    </a>
                                </li>
                                {{-- <li>
                                    <a href="{{ route('owner.master.panduan.index') }}"
                                        class="{{ request()->routeIs('owner.master.panduan.index') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block text-sm">
                                        Panduan Kerja
                                    </a>
                                </li> --}}
                                <li>
                                    <a href="{{ route('owner.master.standar_produksi') }}"
                                        class="{{ request()->routeIs('owner.master.standar_produksi') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block text-sm">
                                        Standar Produksi
                                    </a>
                                </li>
                            </ul>
                        </details>
                    </li>

                    <li>
                        <details class="group"
                            {{ request()->routeIs('owner.inventory.*') || request()->routeIs('owner.dashboard') ? 'open' : '' }}>
                            <summary
                                class="{{ request()->routeIs('owner.inventory.*') ? 'text-primary-content font-medium' : '' }} hover:bg-base-200 rounded-lg px-4 py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>

                                    Inventory
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 transition-transform group-open:rotate-180" fill="none">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </summary>

                            <ul class="mt-2 space-y-1">
                                <li>
                                    <a href="{{ route('owner.inventory.bahan_baku.index') }}"
                                        class="{{ request()->routeIs('owner.inventory.bahan_baku.index') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block">
                                        Bahan Baku
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('owner.inventory.qc.index') }}"
                                        class="{{ request()->routeIs('owner.inventory.qc.index') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block">
                                        QC
                                    </a>
                                </li>
                            </ul>
                        </details>
                    </li>
                    <li>
                        <details class="group"
                            {{ request()->routeIs('owner.partner.*') || request()->routeIs('owner.dashboard') ? 'open' : '' }}>
                            <summary
                                class="{{ request()->routeIs('owner.partner.*') ? 'text-primary-content font-medium' : '' }} hover:bg-base-200 rounded-lg px-4 py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                    </svg>
                                    Partner
                                </div>
                                <ul class="mt-2 space-y-1">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 transition-transform group-open:rotate-180" fill="none">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </summary>
                            <ul class="mt-2 space-y-1">
                                <li>
                                    <a href="{{ route('owner.partner.supplier.index') }}"
                                        class="{{ request()->routeIs('owner.partner.supplier.index') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block">
                                        Supplier
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('owner.partner.mitra.index') }}"
                                        class="{{ request()->routeIs('owner.partner.mitra.index') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block">
                                        Mitra Toko
                                    </a>
                                </li>
                            </ul>
                        </details>
                    </li>
                    <li>
                        <details class="group"
                            {{ request()->routeIs('owner.penjualan.*') || request()->routeIs('owner.dashboard') ? 'open' : '' }}>
                            <summary
                                class="{{ request()->routeIs('owner.penjualan.*') ? 'text-primary-content font-medium' : '' }} hover:bg-base-200 rounded-lg px-4 py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    Penjualan
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 transition-transform group-open:rotate-180" fill="none">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </summary>
                            <ul class="mt-2 space-y-1">
                                <li>
                                    <a href="{{ route('owner.penjualan.pelanggan.index') }}"
                                        class="{{ request()->routeIs('owner.penjualan.pelanggan.index.index') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block">
                                        Pelanggan
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('owner.penjualan.mitra.index') }}"
                                        class="{{ request()->routeIs('owner.penjualan.mitra.index') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block">
                                        Mitra Toko
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('owner.penjualan.manajemenpembayaran.index') }}"
                                        class="{{ request()->routeIs('owner.penjualan..index') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block">
                                        Manajemen Pembayaran
                                    </a>
                                </li>
                            </ul>
                        </details>
                    </li>
                    <li>
                    <a href="{{ route('owner.stok.index') }}"
                        class="{{ request()->routeIs('owner.stok.index') ? 'text-primary-content font-semibold' : '' }} rounded-lg px-4 py-3 flex items-center gap-3">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                        </svg>
                        <span>Manajemen Stok Produk</span>
                    </a>
                    </li>
                    <li>
                        <details class="group"
                            {{ request()->routeIs('owner.laporan.*') || request()->routeIs('owner.dashboard') ? 'open' : '' }}>
                            <summary
                                class="{{ request()->routeIs('owner.laporan*') ? 'text-primary-content font-medium' : '' }} hover:bg-base-200 rounded-lg px-4 py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
                                    </svg>
                                    Laporan
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 transition-transform group-open:rotate-180" fill="none">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </summary>

                            <ul class="mt-2 space-y-1">
                                <li>
                                    <a href="{{ route('owner.laporan.produksi') }}"
                                        class="{{ request()->routeIs('owner.laporan.produksi') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block">
                                        Laporan Produksi
                                    </a>
                                </li>
                            </ul>
                            <ul class="mt-2 space-y-1">
                                <li>
                                    <a href="{{ route('owner.laporan.hpp') }}"
                                        class="{{ request()->routeIs('owner.laporan.hpp') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block">
                                        Laporan HPP
                                    <a href="{{ route('owner.laporan.penjualan') }}"
                                        class="{{ request()->routeIs('owner.laporan.penjualan') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block">
                                        Laporan Penjualan
                                    </a>
                                </li>
                            </ul>
                            <ul class="mt-2 space-y-1">
                                <li>
                                    <a href="{{ route('owner.laporan.pembelian') }}"
                                        class="{{ request()->routeIs('owner.laporan.pembelian') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block">
                                        Laporan Pembelian
                                    </a>
                                </li>
                            </ul>
                        </details>
                    </li>
                @endrole

                @role('admin')
                    <li>
                        <details class="group"
                            {{ request()->routeIs('admin.produk.*') || request()->routeIs('admin.dashboard') ? 'open' : '' }}>
                            <summary
                                class="{{ request()->routeIs('admin.produk.*') ? 'text-primary-content font-medium' : '' }} hover:bg-base-200 rounded-lg px-4 py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                                    </svg>

                                    Produk
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 transition-transform group-open:rotate-180" fill="none">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </summary>

                            <ul class="mt-2 space-y-1">
                                <li>
                                    <a href="{{ route('admin.produk.index') }}"
                                        class="{{ request()->routeIs('admin.produk.index') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block">
                                        Produk
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.produk.bom') }}"
                                        class="{{ request()->routeIs('admin.produk.bom') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block text-sm">
                                        Bills Of Materials (BoM)
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.produksi.standar_produksi') }}"
                                        class="{{ request()->routeIs('admin.produksi.standar_produksi') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block text-sm">
                                        Standar Produksi
                                    </a>
                                </li>
                            </ul>
                        </details>
                    </li>

                    <li>
                        <details class="group"
                            {{ request()->routeIs('admin.produksi.*') || request()->routeIs('admin.dashboard') ? 'open' : '' }}>
                            <summary
                                class="{{ request()->routeIs('admin.produksi.*') ? 'text-primary-content font-medium' : '' }} hover:bg-base-200 rounded-lg px-4 py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4.5 12a7.5 7.5 0 0 0 15 0m-15 0a7.5 7.5 0 1 1 15 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077 1.41-.513m14.095-5.13 1.41-.513M5.106 17.785l1.15-.964m11.49-9.642 1.149-.964M7.501 19.795l.75-1.3m7.5-12.99.75-1.3m-6.063 16.658.26-1.477m2.605-14.772.26-1.477m0 17.726-.26-1.477M10.698 4.614l-.26-1.477M16.5 19.794l-.75-1.299M7.5 4.205 12 12m6.894 5.785-1.149-.964M6.256 7.178l-1.15-.964m15.352 8.864-1.41-.513M4.954 9.435l-1.41-.514M12.002 12l-3.75 6.495" />
                                    </svg>

                                    Produksi
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 transition-transform group-open:rotate-180" fill="none">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </summary>

                            <ul class="mt-2 space-y-1">
                                <li>
                                    <a href="{{ route('admin.produksi.batch.index') }}"
                                        class="{{ request()->routeIs('admin.produksi.batch.index') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block">
                                        Batch
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.produksi.rekomendasi') }}"
                                        class="{{ request()->routeIs('admin.produksi.rekomendasi') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block">
                                        Rekomendasi Produksi
                                    </a>
                                </li>
                            </ul>
                        </details>
                    </li>
                    <li>
                        <details class="group"
                            {{ request()->routeIs('admin.inventory.*') || request()->routeIs('owner.dashboard') ? 'open' : '' }}>
                            <summary
                                class="{{ request()->routeIs('admin.inventory.*') ? 'text-primary-content font-medium' : '' }} hover:bg-base-200 rounded-lg px-4 py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    Inventory
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 transition-transform group-open:rotate-180" fill="none">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </summary>
                            <ul class="mt-2 space-y-1">
                                <li>
                                    <a href="{{ route('admin.inventory.pemesanan.index') }}"
                                        class="{{ request()->routeIs('admin.inventory.pemesanan.index') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block">
                                        Pemesanan Bahan Baku
                                    </a>
                                </li>
                            </ul>
                            <ul class="mt-2 space-y-1">
                                <li>
                                    <a href="{{ route('admin.inventory.bahan_masuk.index') }}"
                                        class="{{ request()->routeIs('admin.inventory.bahan_masuk.index') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block">
                                        Pencatatan Bahan Masuk
                                    </a>
                                </li>
                            </ul>
                        </details>
                    </li>
                    <li>
                    <details class="group"
                            {{ request()->routeIs('admin.partner.*') || request()->routeIs('admin.dashboard') ? 'open' : '' }}>
                            <summary
                                class="{{ request()->routeIs('admin.partner.*') ? 'text-primary-content font-medium' : '' }} hover:bg-base-200 rounded-lg px-4 py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                    </svg>
                                    Partner
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 transition-transform group-open:rotate-180" fill="none">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </summary>

                            <ul class="mt-2 space-y-1">
                                <li>
                                    <a href="{{ route('admin.partner.pelanggan.index') }}"
                                        class="{{ request()->routeIs('admin.partner.pelanggan.index') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block">
                                        Pelanggan
                                    </a>
                                </li>
                            </ul>
                        </details>
                    </li>
                    <li>
                    <details class="group"
                            {{ request()->routeIs('admin.penjualan.*') || request()->routeIs('owner.dashboard') ? 'open' : '' }}>
                            <summary
                                class="{{ request()->routeIs('admin.penjualan.*') ? 'text-primary-content font-medium' : '' }} hover:bg-base-200 rounded-lg px-4 py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                </svg>
                                    Penjualan
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 transition-transform group-open:rotate-180" fill="none">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </summary>

                            <ul class="mt-2 space-y-1">
                                <li>
                                    <a href="{{ route('admin.penjualan.pelanggan.index') }}"
                                        class="{{ request()->routeIs('admin.penjualan.pelanggan.index') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block">
                                        Pelanggan
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.penjualan.mitra.index') }}"
                                        class="{{ request()->routeIs('admin.penjualan.mitra.index') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block">
                                        Mitra Toko
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.penjualan.manajemenpembayaran.index') }}"
                                        class="{{ request()->routeIs('admin.penjualan..index') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block">
                                        Manajemen Pembayaran
                                    </a>
                                </li>
                            </ul>
                        </details>
                    </li>
                @endrole
            </ul>
        </div>
    </div>
</body>

</html>
