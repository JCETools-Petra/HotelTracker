<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Riwayat Pendapatan Harian untuk ') }} {{ $property->name }}
            </h2>
            <nav class="flex flex-wrap items-center space-x-2 sm:space-x-3">
                <x-nav-link :href="route('property.dashboard')" class="ml-3">
                    {{ __('Dashboard') }}
                </x-nav-link>
                <x-nav-link :href="route('property.income.create')" class="ml-3">
                    {{ __('+ Catat Pendapatan') }}
                </x-nav-link>
            </nav>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-700 border border-green-400 dark:border-green-600 rounded-md p-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- FORM FILTER TANGGAL --}}
                    <form method="GET" action="{{ route('property.income.index') }}" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg shadow">
                        <div class="flex flex-col md:flex-row md:items-end md:space-x-4 space-y-4 md:space-y-0">
                            <div class="flex-1">
                                <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
                                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="flex-1">
                                <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Selesai</label>
                                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Filter</button>
                                <a href="{{ route('property.income.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Reset</a>
                            </div>
                        </div>
                    </form>
                    
                    @if(!$incomes->isEmpty())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Walk In (Kamar/Rp)</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">OTA (Kamar/Rp)</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">TA (Kamar/Rp)</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Gov (Kamar/Rp)</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Corp (Kamar/Rp)</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Compliment (Kamar/Rp)</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">House Use (Kamar/Rp)</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">MICE</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">F&B</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Lainnya</th>
                                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($incomes as $income)
                                    @php
                                        // ======================= AWAL PERUBAHAN KALKULASI =======================
                                        // Kalkulasi total pendapatan dari semua sumber
                                        // ($income->fb_income) akan secara otomatis memanggil accessor di model
                                        $totalIncome = ($income->offline_room_income ?? 0) +
                                                       ($income->online_room_income ?? 0) +
                                                       ($income->ta_income ?? 0) +
                                                       ($income->gov_income ?? 0) +
                                                       ($income->corp_income ?? 0) +
                                                       ($income->compliment_income ?? 0) +
                                                       ($income->house_use_income ?? 0) +
                                                       ($income->mice_income ?? 0) +
                                                       ($income->fb_income ?? 0) + // Ini akan menjumlahkan Breakfast, Lunch, Dinner
                                                       ($income->others_income ?? 0);
                                        // ======================= AKHIR PERUBAHAN KALKULASI ======================
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($income->date)->isoFormat('D MMM YY') }}</td>
                                        
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center">{{ $income->offline_rooms ?? 0 }} / <span class="text-gray-500 dark:text-gray-300">{{ number_format($income->offline_room_income ?? 0, 0, ',', '.') }}</span></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center">{{ $income->online_rooms ?? 0 }} / <span class="text-gray-500 dark:text-gray-300">{{ number_format($income->online_room_income ?? 0, 0, ',', '.') }}</span></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center">{{ $income->ta_rooms ?? 0 }} / <span class="text-gray-500 dark:text-gray-300">{{ number_format($income->ta_income ?? 0, 0, ',', '.') }}</span></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center">{{ $income->gov_rooms ?? 0 }} / <span class="text-gray-500 dark:text-gray-300">{{ number_format($income->gov_income ?? 0, 0, ',', '.') }}</span></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center">{{ $income->corp_rooms ?? 0 }} / <span class="text-gray-500 dark:text-gray-300">{{ number_format($income->corp_income ?? 0, 0, ',', '.') }}</span></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center">{{ $income->compliment_rooms ?? 0 }} / <span class="text-gray-500 dark:text-gray-300">{{ number_format($income->compliment_income ?? 0, 0, ',', '.') }}</span></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center">{{ $income->house_use_rooms ?? 0 }} / <span class="text-gray-500 dark:text-gray-300">{{ number_format($income->house_use_income ?? 0, 0, ',', '.') }}</span></td>

                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-300">{{ number_format($income->mice_income ?? 0, 0, ',', '.') }}</td>
                                        
                                        {{-- ======================= AWAL PERUBAHAN TAMPILAN ======================= --}}
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-300">{{ number_format($income->fb_income ?? 0, 0, ',', '.') }}</td>
                                        {{-- ======================= AKHIR PERUBAHAN TAMPILAN ====================== --}}

                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-300">{{ number_format($income->others_income ?? 0, 0, ',', '.') }}</td>
                                        
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-right text-gray-900 dark:text-gray-100">{{ number_format($totalIncome, 0, ',', '.') }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="{{ route('property.income.edit', $income->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">Edit</a>
                                            <form method="POST" action="{{ route('property.income.destroy', $income->id) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $incomes->links() }}
                        </div>
                    @else
                        <p class="text-center text-gray-500 dark:text-gray-400 py-4">
                            Belum ada data pendapatan yang tercatat.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>