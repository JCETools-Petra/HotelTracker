<<<<<<< HEAD
<x-property-user-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div>
                <a href="{{ route('property.income.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Catat Pendapatan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Pesan Sukses --}}
            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                    <span class="font-medium">Sukses!</span> {{ session('success') }}
                </div>
            @endif

            {{-- Kartu Selamat Datang & Update Okupansi --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Selamat datang, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Anda mengelola properti <strong>{{ $property->name }}</strong>.</p>
                    
                    <hr class="my-4 dark:border-gray-700">

                    <form id="occupancy-form" action="{{ route('property.occupancy.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="date" value="{{ today()->toDateString() }}">
                        <div class="flex flex-wrap items-end gap-4">
                            <div>
                                <label for="occupied_rooms" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Update Okupansi Hari Ini ({{ today()->isoFormat('D MMM YYYY') }})</label>
                                <input type="number" name="occupied_rooms" value="{{ $occupancyToday->occupied_rooms ?? 0 }}" class="mt-1 block rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <button type="submit" id="update-button" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            {{-- Kartu Ringkasan Pendapatan --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Total Pendapatan (Bulan Ini)</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Pendapatan Kamar (Bulan Ini)</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">Rp {{ number_format($totalRoomRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Pendapatan F&B (Bulan Ini)</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">Rp {{ number_format($totalFbRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Lain-lain (Bulan Ini)</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">Rp {{ number_format($totalOthersIncome, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Tabel Pendapatan Terbaru --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">5 Catatan Pendapatan Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Tanggal</th>
                                <th scope="col" class="px-6 py-3">Total Pendapatan</th>
                                <th scope="col" class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($latestIncomes as $income)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-medium">{{ $income->date->isoFormat('dddd, D MMMM YYYY') }}</td>
                                    <td class="px-6 py-4 font-semibold">Rp {{ number_format($income->total_revenue, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('property.income.edit', $income) }}" class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center">Belum ada data pendapatan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
=======
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
                            <p>F&B: Rp {{ number_format($todayIncome->fnb_income ?? 0, 0, ',', '.') }}</p>
                            <p>Kamar Offline: Rp {{ number_format($todayIncome->offline_room_income ?? 0, 0, ',', '.') }}</p>
                            <p>Kamar Online: Rp {{ number_format($todayIncome->online_room_income ?? 0, 0, ',', '.') }}</p>
                            <p>Lainnya: Rp {{ number_format($todayIncome->others_income ?? 0, 0, ',', '.') }}</p>
                            <p class="font-bold mt-1">Total: Rp {{ number_format(
                                ($todayIncome->mice_income ?? 0) +
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
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
                </div>
            </div>
        </div>
    </div>
<<<<<<< HEAD
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('occupancy-form');
            const button = document.getElementById('update-button');

            if (form && button) {
                form.addEventListener('submit', function() {
                    // Saat form di-submit...
                    button.disabled = true; // Nonaktifkan tombol
                    button.innerText = 'Memproses...'; // Ubah teks tombol
                });
            }
        });
    </script>
    @endpush
</x-property-user-layout>
=======
</x-app-layout>
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
