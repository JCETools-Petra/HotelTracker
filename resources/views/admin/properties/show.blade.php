<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Properti: ') }} {{ $property->name }}
            </h2>
            <div class="flex space-x-2 mt-2 sm:mt-0">
                <x-secondary-button onclick="window.history.back()">
                    {{ __('Kembali') }}
                </x-secondary-button>
                @can('manage-data')
                    <a href="{{ route('admin.properties.edit', $property->id) }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        {{ __('Edit Properti') }}
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Form Filter --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('admin.properties.show', $property->id) }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        <div>
                            <x-input-label for="start_date" :value="__('Dari Tanggal')" />
                            <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="request('start_date', $displayStartDate ? $displayStartDate->toDateString() : '')" />
                        </div>
                        <div>
                            <x-input-label for="end_date" :value="__('Sampai Tanggal')" />
                            <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="request('end_date', $displayEndDate ? $displayEndDate->toDateString() : '')" />
                        </div>
                        <div class="flex items-end space-x-2">
                            <x-primary-button type="submit">{{ __('Filter') }}</x-primary-button>
                            <a href="{{ route('admin.properties.show', $property->id) }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Reset') }}
                            </a>
                        </div>
                    </div>
                </form>

                {{-- Tombol Filter Bulan --}}
                <div class="mt-4 pt-4 border-t dark:border-gray-700">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Periode Cepat (Tahun {{ now()->year }}):</label>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @php
                            $currentYear = now()->year;
                            $months = [
                                1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
                                7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
                            ];
                        @endphp
                        @foreach ($months as $monthNumber => $monthName)
                            @php
                                $startDate = \Carbon\Carbon::create($currentYear, $monthNumber)->startOfMonth();
                                $endDate = \Carbon\Carbon::create($currentYear, $monthNumber)->endOfMonth();
                                $isActive = (request('start_date') == $startDate->toDateString()) && (request('end_date') == $endDate->toDateString());
                            @endphp
                            <a href="{{ route('admin.properties.show', ['property' => $property->id, 'start_date' => $startDate->toDateString(), 'end_date' => $endDate->toDateString()]) }}"
                               class="inline-flex items-center px-3 py-1.5 border rounded-md text-xs font-medium transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                                    {{ $isActive ? 'bg-indigo-600 text-white border-transparent shadow-sm' : 'bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600' }}">
                                {{ $monthName }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Ringkasan Pendapatan & Charts --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                    Ringkasan Pendapatan {{ $property->name }}
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        @if($displayStartDate && $displayEndDate)
                            Periode: {{ $displayStartDate->isoFormat('D MMMM YYYY') }} - {{ $displayEndDate->isoFormat('D MMMM YYYY') }}
                        @else
                            (Menampilkan data 30 hari terakhir untuk tren, dan keseluruhan untuk distribusi jika tidak ada filter)
                        @endif
                </p>
                <div class="mb-6 p-4 bg-indigo-50 dark:bg-indigo-900 rounded-lg">
                    <h4 class="text-lg font-medium text-indigo-700 dark:text-indigo-300">Total Pendapatan (Periode Terfilter):</h4>
                    <p class="text-3xl font-bold text-indigo-800 dark:text-indigo-200">
                        Rp {{ number_format($totalPropertyRevenueFiltered ?? 0, 0, ',', '.') }}
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="p-4 bg-green-50 dark:bg-green-900 rounded-lg shadow">
                        <h4 class="text-lg font-medium text-green-700 dark:text-green-300">Target Harian ({{ $displayEndDate->isoFormat('D MMM') }})</h4>
                        <p class="text-3xl font-bold text-green-800 dark:text-green-200">
                            Rp {{ number_format($dailyTarget, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="p-4 bg-yellow-50 dark:bg-yellow-900 rounded-lg shadow">
                        <h4 class="text-lg font-medium text-yellow-700 dark:text-yellow-300">Pencapaian Harian ({{ $displayEndDate->isoFormat('D MMM') }})</h4>
                        <p class="text-3xl font-bold text-yellow-800 dark:text-yellow-200">
                            {{ number_format($dailyTargetAchievement, 2, ',', '.') }}%
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            (Aktual: Rp {{ number_format($lastDayIncome, 0, ',', '.') }})
                        </p>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-3">Distribusi Sumber Pendapatan</h4>
                        <div style="height: 350px;">
                             <canvas id="propertySourceDistributionPieChart"></canvas>
                        </div>
                    </div>
                    <div class="p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-3">Tren Pendapatan Harian</h4>
                        <div style="height: 350px;">
                            <canvas id="propertyDailyTrendLineChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Pendapatan Harian Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6" x-data="{ open: false, selectedIncome: null }">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        Riwayat Pendapatan Harian
                    </h3>
                    @can('manage-data')
                        <a href="{{ route('admin.properties.incomes.create', $property) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-indigo-700">
                            + Tambah Pendapatan
                        </a>
                    @endcan
                </div>

                @if($incomes->isEmpty())
                    <p class="text-gray-600 dark:text-gray-400">Tidak ada data pendapatan untuk periode yang dipilih.</p>
                @else
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
                                        <button @click='open = true; selectedIncome = @json($income)' class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-200">
                                            Lihat Data
                                        </button>
                                        @can('manage-data')
                                            @if($income->id)
                                                <a href="{{ route('admin.incomes.edit', $income->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">Edit</a>
                                            @endif
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if ($incomes instanceof \Illuminate\Pagination\AbstractPaginator)
                    <div class="mt-4">
                        {{ $incomes->withQueryString()->links() }}
                    </div>
                    @endif

                @endif

                <!-- Modal -->
                <div x-show="open" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed z-20 inset-0 overflow-y-auto" 
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
                                                <div class="col-span-2 font-semibold border-b pb-1 mb-1">Pendapatan Kamar</div>
                                                {{-- ======================= AWAL PERUBAHAN ======================= --}}
                                                <template x-for="[key, value] of Object.entries({
                                                    'Walk In': [selectedIncome?.offline_rooms, selectedIncome?.offline_room_income],
                                                    'OTA': [selectedIncome?.online_rooms, selectedIncome?.online_room_income],
                                                    'Travel Agent': [selectedIncome?.ta_rooms, selectedIncome?.ta_income],
                                                    'Government': [selectedIncome?.gov_rooms, selectedIncome?.gov_income],
                                                    'Corporation': [selectedIncome?.corp_rooms, selectedIncome?.corp_income],
                                                    'Compliment': [selectedIncome?.compliment_rooms, selectedIncome?.compliment_income],
                                                    'House Use': [selectedIncome?.house_use_rooms, selectedIncome?.house_use_income],
                                                    'Afiliasi': [selectedIncome?.mice_rooms, selectedIncome?.mice_room_income]
                                                })">
                                                {{-- ======================= AKHIR PERUBAHAN ======================= --}}
                                                    <div class="grid grid-cols-3">
                                                        <span class="col-span-1" x-text="key"></span>
                                                        <span class="col-span-2 text-right">
                                                            <span x-text="value[0] || 0"></span> Kamar / 
                                                            <span class="font-mono" x-text="new Intl.NumberFormat('id-ID').format(value[1] || 0)"></span>
                                                        </span>
                                                    </div>
                                                </template>
                                                <div class="col-span-2 font-semibold border-b pb-1 mb-1 mt-4">Pendapatan Lainnya</div>
                                                {{-- ======================= AWAL PERUBAHAN ======================= --}}
                                               
                                                {{-- ======================= AKHIR PERUBAHAN ======================= --}}
                                                
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
        </div>
    </div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dailyTrendData = @json($dailyTrend);
    const sourceDistributionData = @json($sourceDistribution);
    const incomeCategories = @json($incomeCategories);
    const isDarkMode = document.documentElement.classList.contains('dark');
    Chart.defaults.color = isDarkMode ? '#e5e7eb' : '#6b7280';
    Chart.defaults.borderColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';

    const dailyTrendCanvas = document.getElementById('propertyDailyTrendLineChart');
    if (dailyTrendCanvas && dailyTrendData && dailyTrendData.length > 0) {
        new Chart(dailyTrendCanvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: dailyTrendData.map(item => new Date(item.date).toLocaleDateString('id-ID', { day: '2-digit', month: 'short' })),
                datasets: [{
                    label: 'Total Pendapatan Harian (Rp)',
                    data: dailyTrendData.map(item => item.total_daily_income),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { callback: value => 'Rp ' + value.toLocaleString('id-ID') } },
                    x: { ticks: { autoSkip: true, maxTicksLimit: 15 } }
                },
                plugins: { legend: { display: false } }
            }
        });
    } else if (dailyTrendCanvas) {
        const ctx = dailyTrendCanvas.getContext('2d');
        ctx.font = '16px Figtree, sans-serif';
        ctx.fillStyle = isDarkMode ? '#cbd5e1' : '#4b5563';
        ctx.textAlign = 'center';
        ctx.fillText('Tidak ada data tren untuk periode ini.', dailyTrendCanvas.width / 2, dailyTrendCanvas.height / 2);
    }

    const sourcePieCanvas = document.getElementById('propertySourceDistributionPieChart');
    const chartLabels = Object.values(incomeCategories);
    const chartData = sourceDistributionData ? Object.keys(incomeCategories).map(key => sourceDistributionData['total_' + key] || 0) : [];
    const hasDataForPie = chartData.some(value => parseFloat(value) > 0);

    if (sourcePieCanvas && hasDataForPie) {
        new Chart(sourcePieCanvas.getContext('2d'), {
            type: 'pie',
            data: {
                labels: chartLabels,
                datasets: [{
                    data: chartData,
                    backgroundColor: ['#e6194B', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#42d4f4', '#f032e6', '#bfef45', '#808000'],
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { color: isDarkMode ? '#cbd5e1' : '#4b5563' } },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) { label += ': '; }
                                if (context.parsed !== null) { label += 'Rp ' + context.parsed.toLocaleString('id-ID'); }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    } else if(sourcePieCanvas) {
        const ctxPie = sourcePieCanvas.getContext('2d');
        ctxPie.font = '16px Figtree, sans-serif';
        ctxPie.fillStyle = isDarkMode ? '#cbd5e1' : '#4b5563';
        ctxPie.textAlign = 'center';
        ctxPie.fillText('Tidak ada data distribusi pendapatan untuk periode ini.', sourcePieCanvas.width / 2, sourcePieCanvas.height / 2);
    }
});
</script>
@endpush
</x-app-layout>
