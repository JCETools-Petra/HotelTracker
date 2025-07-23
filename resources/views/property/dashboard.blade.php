{{-- resources/views/property/dashboard.blade.php --}}

<x-property-user-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Selamat datang, {{ Auth::user()->name }}! Anda login sebagai pengguna untuk properti {{ $property->name }}.
                </div>
            </div>

            <div class="mt-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    {{-- AWAL PERUBAHAN --}}
                    
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold text-gray-700">Total Pendapatan</h3>
                        <p class="text-3xl font-bold text-gray-900 mt-2">Rp {{ number_format($property->dailyIncomes()->sum('total_revenue'), 0, ',', '.') }}</p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold text-gray-700">Pendapatan Kamar</h3>
                        {{-- Menggunakan 'total_rooms_revenue' yang benar --}}
                        <p class="text-3xl font-bold text-gray-900 mt-2">Rp {{ number_format($property->dailyIncomes()->sum('total_rooms_revenue'), 0, ',', '.') }}</p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold text-gray-700">Pendapatan Makanan & Minuman</h3>
                        {{-- Menggunakan 'total_fb_revenue' yang benar --}}
                        <p class="text-3xl font-bold text-gray-900 mt-2">Rp {{ number_format($property->dailyIncomes()->sum('total_fb_revenue'), 0, ',', '.') }}</p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h3 class="text-lg font-semibold text-gray-700">Lain-lain</h3>
                        {{-- Menggunakan 'others_income' yang benar --}}
                        <p class="text-3xl font-bold text-gray-900 mt-2">Rp {{ number_format($property->dailyIncomes()->sum('others_income'), 0, ',', '.') }}</p>
                    </div>

                    {{-- AKHIR PERUBAHAN --}}
                </div>
            </div>

            {{-- Bagian lain dari file dashboard Anda... --}}
            <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Pendapatan Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Tanggal</th>
                                <th scope="col" class="px-6 py-3">Total Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($property->dailyIncomes()->latest()->take(5)->get() as $income)
                                <tr>
                                    <td class="px-6 py-4">{{ $income->date }}</td>
                                    <td class="px-6 py-4">Rp {{ number_format($income->total_revenue, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-center">Belum ada data pendapatan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-property-user-layout>