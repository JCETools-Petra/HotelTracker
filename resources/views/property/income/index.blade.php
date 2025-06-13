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
                        {{-- ... (Isi form filter Anda biarkan seperti apa adanya) ... --}}
                    </form>
                    
                    @if(!$incomes->isEmpty())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                        {{-- Header Tabel Baru --}}
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
                                        // Kalkulasi total pendapatan dari semua sumber
                                        $totalIncome = ($income->offline_room_income ?? 0) +
                                                       ($income->online_room_income ?? 0) +
                                                       ($income->ta_income ?? 0) +
                                                       ($income->gov_income ?? 0) +
                                                       ($income->corp_income ?? 0) +
                                                       ($income->compliment_income ?? 0) +
                                                       ($income->house_use_income ?? 0) +
                                                       ($income->mice_income ?? 0) +
                                                       ($income->fnb_income ?? 0) +
                                                       ($income->others_income ?? 0);
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($income->date)->isoFormat('D MMM YYYY') }}</td>
                                        
                                        {{-- ================== PERBAIKAN UTAMA DI SINI ================== --}}
                                        {{-- Menampilkan data dari kolom yang benar: offline_* dan online_* --}}
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center">{{ $income->offline_rooms ?? 0 }} / <span class="text-gray-500 dark:text-gray-300">{{ number_format($income->offline_room_income ?? 0, 0, ',', '.') }}</span></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center">{{ $income->online_rooms ?? 0 }} / <span class="text-gray-500 dark:text-gray-300">{{ number_format($income->online_room_income ?? 0, 0, ',', '.') }}</span></td>
                                        
                                        {{-- Menampilkan data untuk kategori baru --}}
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center">{{ $income->ta_rooms ?? 0 }} / <span class="text-gray-500 dark:text-gray-300">{{ number_format($income->ta_income ?? 0, 0, ',', '.') }}</span></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center">{{ $income->gov_rooms ?? 0 }} / <span class="text-gray-500 dark:text-gray-300">{{ number_format($income->gov_income ?? 0, 0, ',', '.') }}</span></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center">{{ $income->corp_rooms ?? 0 }} / <span class="text-gray-500 dark:text-gray-300">{{ number_format($income->corp_income ?? 0, 0, ',', '.') }}</span></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center">{{ $income->compliment_rooms ?? 0 }} / <span class="text-gray-500 dark:text-gray-300">{{ number_format($income->compliment_income ?? 0, 0, ',', '.') }}</span></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-center">{{ $income->house_use_rooms ?? 0 }} / <span class="text-gray-500 dark:text-gray-300">{{ number_format($income->house_use_income ?? 0, 0, ',', '.') }}</span></td>

                                        {{-- Menampilkan data lama --}}
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-300">{{ number_format($income->mice_income ?? 0, 0, ',', '.') }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-300">{{ number_format($income->fnb_income ?? 0, 0, ',', '.') }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-300">{{ number_format($income->others_income ?? 0, 0, ',', '.') }}</td>
                                        
                                        {{-- Total dan Aksi --}}
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