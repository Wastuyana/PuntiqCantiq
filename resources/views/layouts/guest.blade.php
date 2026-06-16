<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Puntiq Cantiq') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('images/puntiq-cantiq.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-base-200">

        <div class="mb-6 drop-shadow-sm">
            <a href="/">
                <x-application-logo class="w-24 h-24 fill-current text-primary" />
            </a>
        </div>

        <div class="w-full sm:max-w-md">
            {{ $slot }}
        </div>

        <div class="mt-8 text-xs text-base-content/30 uppercase tracking-widest font-bold">
            &copy; {{ date('Y') }} Puntiq Cantiq System
        </div>
    </div>
</body>

</html>
