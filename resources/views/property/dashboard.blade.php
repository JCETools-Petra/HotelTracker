{{-- resources/views/property/dashboard.blade.php --}}

<x-app-layout> {{-- Asumsi Anda menggunakan layout ini --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Properti') }}
            @if(isset($property) && $property)
                : {{ $property->name }}
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium">Selamat Datang di Dashboard Properti Anda!</h3>

                    @if(isset($property) && $property)
                        <p class="mt-2">Anda sedang mengelola properti: <strong>{{ $property->name }}</strong>.</p>
                    @else
                        <p class="mt-2 text-red-600">Informasi properti tidak ditemukan.</p>
                    @endif

                    @if(isset($todayIncome))
                        <div class="mt-4 p-4 bg-green-100 dark:bg-green-700 rounded-lg">
                            <h4 class="font-semibold">Pendapatan Hari Ini ({{ \Carbon\Carbon::today()->isoFormat('LL') }})</h4>
                            <p>MICE: Rp {{ number_format($todayIncome->mice_income ?? 0, 0, ',', '.') }}</p>
                            <p>MICE Rooms: {{ $todayIncome->mice_rooms ?? 0 }} kamar / Rp {{ number_format($todayIncome->mice_room_income ?? 0, 0, ',', '.') }}</p>
                            <p>F&B: Rp {{ number_format($todayIncome->fnb_income ?? 0, 0, ',', '.') }}</p>
                            <p>Kamar Offline: Rp {{ number_format($todayIncome->offline_room_income ?? 0, 0, ',', '.') }}</p>
                            <p>Kamar Online: Rp {{ number_format($todayIncome->online_room_income ?? 0, 0, ',', '.') }}</p>
                            <p>Lainnya: Rp {{ number_format($todayIncome->others_income ?? 0, 0, ',', '.') }}</p>
                            <p class="font-bold mt-1">Total: Rp {{ number_format(
                                ($todayIncome->mice_income ?? 0) +
                                ($todayIncome->mice_room_income ?? 0) +
                                ($todayIncome->fnb_income ?? 0) +
                                ($todayIncome->offline_room_income ?? 0) +
                                ($todayIncome->online_room_income ?? 0) +
                                ($todayIncome->others_income ?? 0)
                            , 0, ',', '.') }}</p>
                            <a href="{{ route('property.income.edit', $todayIncome->id) }}" class="mt-2 inline-block text-blue-600 hover:underline">Edit Pendapatan Hari Ini</a>
                        </div>
                    @else
                        <div class="mt-4 p-4 bg-yellow-100 dark:bg-yellow-700 rounded-lg">
                            <p>Belum ada data pendapatan yang tercatat untuk hari ini.</p>
                            <a href="{{ route('property.income.create') }}" class="mt-2 inline-block text-blue-600 hover:underline">Catat Pendapatan Hari Ini</a>
                        </div>
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('property.income.index') }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">
                            Lihat Riwayat Pendapatan
                        </a>
                        <span class="mx-2">|</span>
                        <a href="{{ route('property.income.create') }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-200">
                            Tambah Catatan Pendapatan Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>