{{-- resources/views/property/income/index.blade.php --}}
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-700 border border-green-400 dark:border-green-600 rounded-md p-3">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('info'))
                        <div class="mb-4 font-medium text-sm text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-700 border border-blue-400 dark:border-blue-600 rounded-md p-3">
                            {{ session('info') }}
                        </div>
                    @endif

                    {{-- FORM FILTER TANGGAL --}}
                    <form method="GET" action="{{ route('property.income.index') }}" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg shadow">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <x-input-label for="start_date" :value="__('Dari Tanggal')" />
                                <x-text-input id="start_date_filter" class="block mt-1 w-full" type="date" name="start_date" :value="request('start_date', $startDate ?? old('start_date'))" />
                            </div>
                            <div>
                                <x-input-label for="end_date_filter" :value="__('Sampai Tanggal')" />
                                <x-text-input id="end_date_filter" class="block mt-1 w-full" type="date" name="end_date" :value="request('end_date', $endDate ?? old('end_date'))" />
                            </div>
                            <div class="flex space-x-2 pt-5 md:pt-0">
                                <x-primary-button type="submit">
                                    {{ __('Filter') }}
                                </x-primary-button>
                                <a href="{{ route('property.income.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    {{ __('Reset') }}
                                </a>
                            </div>
                        </div>
                    </form>
                    {{-- AKHIR FORM FILTER --}}

                    {{-- Tombol Export diletakkan setelah form filter --}}
                    @if(!$incomes->isEmpty())
                    <div class="mb-4 flex flex-wrap gap-2">
                        <a href="{{ route('property.income.export.excel', ['start_date' => request('start_date', $startDate), 'end_date' => request('end_date', $endDate)]) }}"
                           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Export ke Excel
                        </a>
                        <a href="{{ route('property.income.export.csv', ['start_date' => request('start_date', $startDate), 'end_date' => request('end_date', $endDate)]) }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Export ke CSV
                        </a>
                    </div>
                    @endif


                    @if($incomes->isEmpty())
                        <p class="text-center text-gray-500 dark:text-gray-400 py-4">
                            Belum ada data pendapatan yang tercatat @if(request('start_date') || request('end_date')) untuk periode yang dipilih @endif.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">MICE</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">F&B</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kamar Offline</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kamar Online</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Lainnya</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($incomes as $income)
                                    @php
                                        $totalIncome = ($income->mice_income ?? 0) +
                                                       ($income->fnb_income ?? 0) +
                                                       ($income->offline_room_income ?? 0) +
                                                       ($income->online_room_income ?? 0) +
                                                       ($income->others_income ?? 0);
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($income->date)->isoFormat('D MMMM YYYY') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right">Rp {{ number_format($income->mice_income ?? 0, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right">Rp {{ number_format($income->fnb_income ?? 0, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right">Rp {{ number_format($income->offline_room_income ?? 0, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right">Rp {{ number_format($income->online_room_income ?? 0, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right">Rp {{ number_format($income->others_income ?? 0, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100 text-right">Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
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
                            {{ $incomes->links() }} {{-- Paginasi --}}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>