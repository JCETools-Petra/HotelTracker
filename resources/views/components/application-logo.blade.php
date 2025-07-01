@php
// Ambil pengaturan yang sudah kita share dari AppServiceProvider
$logoPath = $appSettings['logo_path'] ?? null;
$logoSize = $appSettings['logo_size'] ?? 80; // Default ukuran 80px jika tidak ada pengaturan
$appName = $appSettings['app_name'] ?? config('app.name', 'Laravel');
@endphp

@if ($logoPath)
    {{-- Menggunakan inline style untuk mengatur tinggi logo secara dinamis --}}
    <img src="{{ asset('storage/' . $logoPath) }}" 
         style="height: {{ $logoSize }}px;" 
         {{ $attributes->merge(['alt' => 'Logo', 'class' => 'w-auto']) }}>
@else
    {{-- Fallback jika tidak ada logo, tampilkan teks --}}
    <span {{ $attributes->merge(['class' => 'text-gray-800 dark:text-gray-200 text-2xl font-extrabold']) }}>
        {{ $appName }}
    </span>
@endif