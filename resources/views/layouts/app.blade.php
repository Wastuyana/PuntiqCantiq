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
                                        stroke-width="2" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.712 4.33a9.027 9.027 0 0 1 1.652 1.306c.51.51.944 1.064 1.306 1.652M16.712 4.33l-3.448 4.138m3.448-4.138a9.014 9.014 0 0 0-9.424 0M19.67 7.288l-4.138 3.448m4.138-3.448a9.014 9.014 0 0 1 0 9.424m-4.138-5.976a3.736 3.736 0 0 0-.88-1.388 3.737 3.737 0 0 0-1.388-.88m2.268 2.268a3.765 3.765 0 0 1 0 2.528m-2.268-4.796a3.765 3.765 0 0 0-2.528 0m4.796 4.796c-.181.506-.475.982-.88 1.388a3.736 3.736 0 0 1-1.388.88m2.268-2.268 4.138 3.448m0 0a9.027 9.027 0 0 1-1.306 1.652c-.51.51-1.064.944-1.652 1.306m0 0-3.448-4.138m3.448 4.138a9.014 9.014 0 0 1-9.424 0m5.976-4.138a3.765 3.765 0 0 1-2.528 0m0 0a3.736 3.736 0 0 1-1.388-.88 3.737 3.737 0 0 1-.88-1.388m2.268 2.268L7.288 19.67m0 0a9.024 9.024 0 0 1-1.652-1.306 9.027 9.027 0 0 1-1.306-1.652m0 0 4.138-3.448M4.33 16.712a9.014 9.014 0 0 1 0-9.424m4.138 5.976a3.765 3.765 0 0 1 0-2.528m0 0c.181-.506.475-.982.88-1.388a3.736 3.736 0 0 1 1.388-.88m-2.268 2.268L4.33 7.288m6.406 1.18L7.288 4.33m0 0a9.024 9.024 0 0 0-1.652 1.306A9.025 9.025 0 0 0 4.33 7.288" />
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
                                        stroke-width="2" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                    </svg>
                                    Data Master
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
                                    <a href="{{ route('owner.master.bom') }}"
                                        class="{{ request()->routeIs('owner.master.bom') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block text-sm">
                                        Bills Of Materials (BoM)
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('owner.master.panduan.index') }}"
                                        class="{{ request()->routeIs('owner.master.panduan.index') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block text-sm">
                                        Panduan Kerja
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('owner.master.higiene_profile') }}"
                                        class="{{ request()->routeIs('owner.master.higiene_profile') ? 'text-primary-content font-semibold' : '' }} rounded-lg ml-6 py-2 px-4 block text-sm">
                                        Profil Higiene
                                    </a>
                                </li>
                            </ul>
                        </details>
                    </li>

                    <li class="mt-2">
                        <a href="{{ route('owner.bahan_baku') }}"
                            class="{{ request()->routeIs('owner.bahan_baku') ? 'bg-blue-50 text-blue-600 font-medium' : '' }} rounded-lg px-4 py-3 flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Inventory
                        </a>
                    </li>
                @endrole

                @role('admin')
                    <li class="mt-4 menu-title text-xs font-bold opacity-40 uppercase tracking-widest text-secondary">
                        Produksi</li>
                    <li><a href="{{ route('admin.dashboard') }}"
                            class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">📦 Input Produksi</a></li>
                @endrole
            </ul>
        </div>
    </div>
</body>

</html>
