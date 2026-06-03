<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="p-6">
        @include('admin.partials._stats')

        @include('admin.partials._sales_history', ['riwayatPenjualan' => $riwayatPenjualan])
    </div>
</x-app-layout>