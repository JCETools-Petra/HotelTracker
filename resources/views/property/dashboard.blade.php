<x-property-user-layout>
    <x-slot name="header">
    <div class="flex flex-wrap justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>

        {{-- Tombol Aksi dengan Jarak --}}
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
            
            {{-- Kartu Selamat Datang & Update Okupansi --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Selamat datang, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Anda mengelola properti <strong>{{ $property->name }}</strong>.</p>
                    
                    <hr class="my-4 dark:border-gray-700">

                    <form action="{{ route('property.occupancy.update') }}" method="POST">
                        @csrf
                        <div class="flex flex-wrap items-end gap-4">
                            <div>
                                <label for="occupied_rooms" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Update Okupansi Hari Ini ({{ today()->isoFormat('D MMM YYYY') }})</label>
                                <input type="number" name="occupied_rooms" value="{{ $occupancyToday->occupied_rooms }}" class="mt-1 block rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            {{-- BAGIAN HARGA KAMAR SAAT INI SUDAH DIHAPUS --}}

            {{-- Kartu Ringkasan Pendapatan --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Total Pendapatan</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">Rp {{ number_format($property->dailyIncomes()->sum('total_revenue'), 0, ',', '.') }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Pendapatan Kamar</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">Rp {{ number_format($property->dailyIncomes()->sum('total_rooms_revenue'), 0, ',', '.') }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Pendapatan F&B</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">Rp {{ number_format($property->dailyIncomes()->sum('total_fb_revenue'), 0, ',', '.') }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Lain-lain</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">Rp {{ number_format($property->dailyIncomes()->sum('others_income'), 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Tabel Pendapatan Terbaru --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Pendapatan Terbaru</h3>
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
                            @forelse ($property->dailyIncomes()->latest()->take(5)->get() as $income)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ Carbon\Carbon::parse($income->date)->isoFormat('dddd, D MMMM YYYY') }}</td>
                                    <td class="px-6 py-4 font-semibold">Rp {{ number_format($income->total_revenue, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        <a href="#" class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline">Lihat Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center">Belum ada data pendapatan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-property-user-layout>