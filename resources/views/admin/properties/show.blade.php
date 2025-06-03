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
                <a href="{{ route('admin.properties.edit', $property->id) }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Edit Properti') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('admin.properties.show', $property->id) }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        <div>
                            <x-input-label for="start_date" :value="__('Dari Tanggal')" />
                            {{-- Menggunakan $displayStartDate dan $displayEndDate untuk nilai default filter jika ada --}}
                            <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="request('start_date', $displayStartDate ? $displayStartDate->toDateString() : ($startDate ? $startDate->toDateString() : ''))" />
                        </div>
                        <div>
                            <x-input-label for="end_date" :value="__('Sampai Tanggal')" />
                            <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="request('end_date', $displayEndDate ? $displayEndDate->toDateString() : ($endDate ? $endDate->toDateString() : ''))" />
                        </div>
                        <div class="flex items-end space-x-2">
                            <x-primary-button type="submit">
                                {{ __('Filter') }}
                            </x-primary-button>
                            <a href="{{ route('admin.properties.show', $property->id) }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Reset') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                    Ringkasan Pendapatan {{ $property->name }}
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                     {{-- Menggunakan $displayStartDate dan $displayEndDate untuk menampilkan periode yang sebenarnya difilter atau default --}}
                     @if($displayStartDate && $displayEndDate)
                        Periode: {{ $displayStartDate->isoFormat('D MMMM YYYY') }} - {{ $displayEndDate->isoFormat('D MMMM YYYY') }}
                        @if($periodIsOneMonth)
                            <span class="text-green-600 dark:text-green-400">(Periode 1 Bulan Penuh)</span>
                        @endif
                    @elseif($displayStartDate)
                        Periode: Dari {{ $displayStartDate->isoFormat('D MMMM YYYY') }}
                    @elseif($displayEndDate)
                        Periode: Sampai {{ $displayEndDate->isoFormat('D MMMM YYYY') }}
                    @else
                        (Menampilkan data 30 hari terakhir untuk tren, dan keseluruhan untuk distribusi jika tidak ada filter)
                    @endif
                </p>

                <div class="mb-6 p-4 bg-indigo-50 dark:bg-indigo-900 rounded-lg">
                    <h4 class="text-lg font-medium text-indigo-700 dark:text-indigo-300">Total Pendapatan (Periode Terfilter):</h4>
                    <p class="text-3xl font-bold text-indigo-800 dark:text-indigo-200">
                        Rp {{ number_format($totalPropertyRevenueFiltered, 0, ',', '.') }}
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Distribusi Sumber Pendapatan
                        </h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                            @if($displayStartDate && $displayEndDate)
                                <span class="block sm:inline">Periode: {{ $displayStartDate->isoFormat('D MMM YY') }} - {{ $displayEndDate->isoFormat('D MMM YY') }}</span>
                            @elseif($displayStartDate)
                                <span class="block sm:inline">Periode: Dari {{ $displayStartDate->isoFormat('D MMM YY') }}</span>
                            @elseif($displayEndDate)
                                <span class="block sm:inline">Periode: Sampai {{ $displayEndDate->isoFormat('D MMM YY') }}</span>
                            @else
                                 <span class="block sm:inline">(Keseluruhan Data Properti)</span>
                            @endif
                        </p>
                        <div style="height: 350px;">
                             <canvas id="propertySourceDistributionPieChart"></canvas>
                        </div>
                    </div>

                    <div class="p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tren Pendapatan Harian
                        </h4>
                         <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                            @if($displayStartDate && $displayEndDate)
                                <span class="block sm:inline">Periode: {{ $displayStartDate->isoFormat('D MMM YY') }} - {{ $displayEndDate->isoFormat('D MMM YY') }}</span>
                            @elseif($displayStartDate)
                                <span class="block sm:inline">Periode: Dari {{ $displayStartDate->isoFormat('D MMM YY') }}</span>
                            @elseif($displayEndDate)
                                <span class="block sm:inline">Periode: Sampai {{ $displayEndDate->isoFormat('D MMM YY') }}</span>
                            @else
                                <span class="block sm:inline">(30 Hari Terakhir)</span>
                            @endif
                        </p>
                        <div style="height: 350px;">
                            <canvas id="propertyDailyTrendLineChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Riwayat Pendapatan Harian
                </h3>
                @if($incomes->isEmpty())
                    <p class="text-gray-600 dark:text-gray-400">Tidak ada data pendapatan untuk periode yang dipilih.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">MICE (Rp)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">F&B (Rp)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">K. Offline (Rp)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">K. Online (Rp)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Lainnya (Rp)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total (Rp)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dicatat Oleh</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($incomes as $income)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ Carbon\Carbon::parse($income->date)->isoFormat('dddd, D MMM YYYY') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right">{{ number_format($income->mice_income, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right">{{ number_format($income->fnb_income, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right">{{ number_format($income->offline_room_income, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right">{{ number_format($income->online_room_income, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right">{{ number_format($income->others_income, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700 dark:text-gray-200 text-right">
                                            {{ number_format(
                                                ($income->mice_income ?? 0) +
                                                ($income->fnb_income ?? 0) +
                                                ($income->offline_room_income ?? 0) +
                                                ($income->online_room_income ?? 0) +
                                                ($income->others_income ?? 0), 0, ',', '.')
                                            }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $income->user->name ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        {{ $incomes->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Menggunakan variabel $dailyTrend yang dikirim dari controller
    const propertyDailyTrendData = @json($dailyTrend);
    // Menggunakan variabel $sourceDistribution yang dikirim dari controller
    const propertyIncomeSourceData = @json($sourceDistribution);

    // Grafik Tren Pendapatan Harian Properti
    const dailyTrendCanvas = document.getElementById('propertyDailyTrendLineChart');
    if (dailyTrendCanvas && propertyDailyTrendData && propertyDailyTrendData.length > 0) {
        new Chart(dailyTrendCanvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: propertyDailyTrendData.map(item => new Date(item.date).toLocaleDateString('id-ID', { day: '2-digit', month: 'short' })),
                datasets: [{
                    label: 'Total Pendapatan Harian (Rp)',
                    data: propertyDailyTrendData.map(item => item.total_daily_income),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); } } },
                    x: { ticks: { autoSkip: true, maxTicksLimit: 15 } } // Batasi jumlah label tanggal agar tidak terlalu padat
                },
                plugins: { legend: { display: true, position: 'bottom' } }
            }
        });
    } else if(dailyTrendCanvas) {
        const ctxTrend = dailyTrendCanvas.getContext('2d');
        ctxTrend.font = '16px Figtree, sans-serif'; // Sesuaikan dengan font Anda
        ctxTrend.fillStyle = document.body.classList.contains('dark') ? '#cbd5e1' : '#4b5563'; // Warna teks sesuai tema
        ctxTrend.textAlign = 'center';
        ctxTrend.fillText('Tidak ada data tren pendapatan untuk periode ini.', dailyTrendCanvas.width / 2, dailyTrendCanvas.height / 2);
    }

    // Grafik Distribusi Sumber Pendapatan Properti
    const sourcePieCanvas = document.getElementById('propertySourceDistributionPieChart');
    // Memastikan propertyIncomeSourceData tidak null sebelum mengakses propertinya
    if (sourcePieCanvas && propertyIncomeSourceData && (parseFloat(propertyIncomeSourceData.total_mice) > 0 || parseFloat(propertyIncomeSourceData.total_fnb) > 0 || parseFloat(propertyIncomeSourceData.total_offline_room) > 0 || parseFloat(propertyIncomeSourceData.total_online_room) > 0 || parseFloat(propertyIncomeSourceData.total_others) > 0)) {
        new Chart(sourcePieCanvas.getContext('2d'), {
            type: 'pie',
            data: {
                labels: ['MICE', 'F&B', 'Kamar Offline', 'Kamar Online', 'Lainnya'],
                datasets: [{
                    label: 'Distribusi Pendapatan',
                    data: [
                        propertyIncomeSourceData.total_mice || 0,
                        propertyIncomeSourceData.total_fnb || 0,
                        propertyIncomeSourceData.total_offline_room || 0,
                        propertyIncomeSourceData.total_online_room || 0,
                        propertyIncomeSourceData.total_others || 0
                    ],
                    backgroundColor: ['rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)', 'rgba(153, 102, 255, 0.7)'],
                    borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += 'Rp ' + context.parsed.toLocaleString('id-ID');
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    } else if(sourcePieCanvas) {
        const ctxPie = sourcePieCanvas.getContext('2d');
        ctxPie.font = '16px Figtree, sans-serif'; // Sesuaikan dengan font Anda
        ctxPie.fillStyle = document.body.classList.contains('dark') ? '#cbd5e1' : '#4b5563'; // Warna teks sesuai tema
        ctxPie.textAlign = 'center';
        ctxPie.fillText('Tidak ada data distribusi pendapatan untuk periode ini.', sourcePieCanvas.width / 2, sourcePieCanvas.height / 2);
    }
});
</script>
@endpush
</x-app-layout>
