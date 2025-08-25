<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- =================================================================== --}}
        {{-- >> AWAL PERUBAHAN: Judul dan Favicon Dinamis dari Pengaturan << --}}
        {{-- =================================================================== --}}
        <title>{{ setting('app_name', config('app.name', 'Laravel')) }}</title>
        
        @if(setting('favicon_path'))
            <link rel="icon" href="{{ asset('storage/' . setting('favicon_path')) }}" type="image/png">
        @else
            {{-- Fallback jika tidak ada favicon di pengaturan --}}
            <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
        @endif
        {{-- =================================================================== --}}
        {{-- >> AKHIR PERUBAHAN <<                                             --}}
        {{-- =================================================================== --}}

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div class="mb-4">
                <a href="/">
                    @php
                        // Menggunakan helper untuk mendapatkan pengaturan logo
                        $logoPath = setting('logo_path');
                        $logoSize = setting('logo_size', 100); // Default 100px
                        $appName = setting('app_name', config('app.name', 'Laravel'));
                    @endphp
                    @if($logoPath)
                        {{-- Tampilkan logo dari pengaturan jika ada --}}
                        <img src="{{ asset('storage/' . $logoPath) }}" alt="{{ $appName }}" style="height: {{ $logoSize }}px;" class="w-auto">
                    @else
                        {{-- Fallback: Tampilkan nama aplikasi sebagai teks --}}
                        <span class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ $appName }}</span>
                    @endif
                </a>
            </div>

            <div class="w-full sm:max-w-md px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>