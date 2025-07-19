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

    <div class="py-12" x-data="{ open: false, selectedIncome: null }">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-700 border border-green-400 dark:border-green-600 rounded-md p-3">
                            {{ session('success') }}
                        </div>
                    @endif

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
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Pendapatan</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($incomes as $income)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ \Carbon\Carbon::parse($income->date)->isoFormat('dddd, D MMMM YYYY') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-mono text-gray-700 dark:text-gray-300">
                                            Rp {{ number_format($income->total_revenue ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center space-x-2">
                                            <button @click="open = true; selectedIncome = {{ $income->toJson() }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-200">
                                                Lihat Data
                                            </button>
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

        <!-- Modal -->
        <div x-show="open" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed z-10 inset-0 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true"
             style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="open = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                    Rincian Pendapatan - <span x-text="new Date(selectedIncome?.date).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })"></span>
                                </h3>
                                <div class="mt-4 text-sm text-gray-600 dark:text-gray-300">
                                    <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                                        <!-- Room Details -->
                                        <div class="col-span-2 font-semibold border-b pb-1 mb-1">Pendapatan Kamar</div>
                                        <template x-for="[key, value] of Object.entries({
                                            'Walk In': [selectedIncome?.offline_rooms, selectedIncome?.offline_room_income],
                                            'OTA': [selectedIncome?.online_rooms, selectedIncome?.online_room_income],
                                            'Travel Agent': [selectedIncome?.ta_rooms, selectedIncome?.ta_income],
                                            'Government': [selectedIncome?.gov_rooms, selectedIncome?.gov_income],
                                            'Corporation': [selectedIncome?.corp_rooms, selectedIncome?.corp_income],
                                            'Compliment': [selectedIncome?.compliment_rooms, selectedIncome?.compliment_income],
                                            'House Use': [selectedIncome?.house_use_rooms, selectedIncome?.house_use_income],
                                            'Afiliasi': [selectedIncome?.afiliasi_rooms, selectedIncome?.afiliasi_income]
                                        })">
                                            <div class="grid grid-cols-3">
                                                <span class="col-span-1" x-text="key"></span>
                                                <span class="col-span-2 text-right">
                                                    <span x-text="value[0] || 0"></span> Kamar / 
                                                    <span class="font-mono" x-text="new Intl.NumberFormat('id-ID').format(value[1] || 0)"></span>
                                                </span>
                                            </div>
                                        </template>

                                        <!-- Other Incomes -->
                                        <div class="col-span-2 font-semibold border-b pb-1 mb-1 mt-4">Pendapatan Lainnya</div>
                                        
                                        <div class="col-span-2 pl-4 border-l-2 border-gray-200 dark:border-gray-600">
                                            <p class="font-medium">F&B:</p>
                                            <div class="pl-4 text-gray-500 dark:text-gray-400">
                                                <p>Breakfast: <span class="float-right font-mono" x-text="new Intl.NumberFormat('id-ID').format(selectedIncome?.breakfast_income || 0)"></span></p>
                                                <p>Lunch: <span class="float-right font-mono" x-text="new Intl.NumberFormat('id-ID').format(selectedIncome?.lunch_income || 0)"></span></p>
                                                <p>Dinner: <span class="float-right font-mono" x-text="new Intl.NumberFormat('id-ID').format(selectedIncome?.dinner_income || 0)"></span></p>
                                                <p class="font-medium border-t border-dashed mt-1 pt-1">Total F&B: <span class="float-right font-mono" x-text="new Intl.NumberFormat('id-ID').format((parseFloat(selectedIncome?.breakfast_income) || 0) + (parseFloat(selectedIncome?.lunch_income) || 0) + (parseFloat(selectedIncome?.dinner_income) || 0))"></span></p>
                                            </div>
                                        </div>

                                        <p>Lainnya: <span class="float-right font-mono" x-text="new Intl.NumberFormat('id-ID').format(selectedIncome?.others_income || 0)"></span></p>
                                        
                                        <!-- Totals -->
                                        <div class="col-span-2 font-semibold border-b pb-1 mb-1 mt-4">Total</div>
                                        <p>Total Kamar Terjual: <span class="float-right font-mono" x-text="selectedIncome?.total_rooms_sold || 0"></span></p>
                                        <p>Total Pendapatan Kamar: <span class="float-right font-mono" x-text="new Intl.NumberFormat('id-ID').format(selectedIncome?.total_rooms_revenue || 0)"></span></p>
                                        <p class="font-bold text-lg">Total Pendapatan: <span class="float-right font-mono" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(selectedIncome?.total_revenue || 0)"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="open = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-600 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
