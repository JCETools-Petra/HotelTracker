{{-- resources/views/admin/kpi_analysis.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Pusat Analisis Kinerja (KPI)') }}
            </h2>
            <nav class="flex space-x-4">
                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard Utama') }}
                </x-nav-link>
                {{-- Tambahkan link navigasi lain jika perlu --}}
            </nav>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Filter Global --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Filter Data</h3>
                <form method="GET" action="{{ route('admin.kpi.analysis') }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        <div>
                            <x-input-label for="start_date_filter" :value="__('Dari Tanggal')" />
                            <x-text-input id="start_date_filter" class="block mt-1 w-full" type="date" name="start_date" :value="$filterStartDate ? $filterStartDate->toDateString() : ''" />
                        </div>
                        <div>
                            <x-input-label for="end_date_filter" :value="__('Sampai Tanggal')" />
                            <x-text-input id="end_date_filter" class="block mt-1 w-full" type="date" name="end_date" :value="$filterEndDate ? $filterEndDate->toDateString() : ''" />
                        </div>
                        <div class="pt-5 md:pt-0">
                            <x-primary-button type="submit">
                                {{ __('Terapkan Filter') }}
                            </x-primary-button>
                             <a href="{{ route('admin.kpi.analysis') }}"
                               class="ml-2 inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Reset') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Ringkasan Kinerja Keseluruhan</h3>
                 <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                    Periode: {{ $filterStartDate->isoFormat('D MMMM YYYY') }} - {{ $filterEndDate->isoFormat('D MMMM YYYY') }}
                    (Total {{ $filterEndDate->diffInDays($filterStartDate) + 1 }} hari)
                </p>
                 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pendapatan</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($totalOverallRevenue, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Rata-Rata Pendapatan Harian</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($averageDailyOverallRevenue, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Jml. Properti Aktif</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $activePropertiesCount }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Jml. Pengguna Properti</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $activePropertyUsersCount }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Rata-Rata Pendapatan/Properti</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">Rp {{ number_format($averageRevenuePerProperty, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">Distribusi Sumber Pendapatan (Periode Terfilter)</h4>
                        <div style="height: 300px;">
                            <canvas id="kpiOverallSourcePieChart"></canvas>
                        </div>
                    </div>
                    <div class="p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">Total Pendapatan per Properti (Periode Terfilter)</h4>
                        <div style="height: 300px;">
                            <canvas id="kpiOverallIncomeByPropertyBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Analisis Detail per Kategori Pendapatan</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                    Periode: {{ $filterStartDate->isoFormat('D MMMM YYYY') }} - {{ $filterEndDate->isoFormat('D MMMM YYYY') }}
                </p>
                 <div class="grid grid-cols-1 @if(!empty($multiMonthCategoryGrowth) && count($multiMonthCategoryGrowth) > 0) md:grid-cols-2 @else md:grid-cols-1 @endif gap-6 mb-6">
                    <div class="p-4 border dark:border-gray-700 rounded-lg @if(empty($multiMonthCategoryGrowth) || count($multiMonthCategoryGrowth) == 0) md:col-span-1 @else md:col-span-1 @endif">
                        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">Tren Total Pendapatan per Kategori (Bulanan dalam Periode)</h4>
                        <div style="height: 350px;">
                            <canvas id="kpiTrendKontribusiKategoriLineChart"></canvas>
                        </div>
                    </div>

                    {{-- Bagian Pertumbuhan Kategori Pendapatan (MoM per Bulan) --}}
                    @if(!empty($multiMonthCategoryGrowth) && count($multiMonthCategoryGrowth) > 0)
                    <div class="p-4 border dark:border-gray-700 rounded-lg space-y-2">
                        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-3">Pertumbuhan Kategori Pendapatan (MoM)</h4>
                        @foreach($multiMonthCategoryGrowth as $monthName => $growths)
                            <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-md shadow-sm">
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 border-b border-gray-200 dark:border-gray-600 pb-1 mb-2">{{ $monthName }}:</p>
                                @if(empty($growths))
                                    <p class="text-xs text-gray-500 dark:text-gray-400 italic">Tidak ada data pertumbuhan untuk bulan ini.</p>
                                @else
                                    <ul class="space-y-1 text-sm">
                                    @foreach($growths as $growthData)
                                        <li class="flex justify-between">
                                            <span class="font-medium text-gray-800 dark:text-gray-300">{{ $growthData['category'] }}:</span>
                                            <span class="{{ Str::contains($growthData['growth'], '+') || $growthData['growth'] === 'Baru' ? 'text-green-600 dark:text-green-400 font-semibold' : (Str::contains($growthData['growth'], '-') ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-gray-600 dark:text-gray-400') }}">
                                                {{ $growthData['growth'] }}
                                            </span>
                                        </li>
                                    @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endforeach
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            *) MoM: Perbandingan dengan bulan sebelumnya.
                            <br>
                            *) "Baru" menandakan ada pendapatan saat nilai bulan pembanding 0.
                            <br>
                            *) "Data pembanding tidak cukup" menandakan tidak ada data sama sekali pada bulan pembanding.
                        </p>
                    </div>
                    @else
                         <div class="p-4 border dark:border-gray-700 rounded-lg space-y-4">
                            <h4 class="text-md font-medium text-gray-700 dark:text-gray-300">Pertumbuhan Kategori Pendapatan (MoM)</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Pilih rentang tanggal yang mencakup minimal dua bulan untuk melihat pertumbuhan MoM antar bulan dalam periode tersebut.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Analisis Pencapaian Target Pendapatan</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                    Periode: {{ $filterStartDate->isoFormat('D MMMM YYYY') }} - {{ $filterEndDate->isoFormat('D MMMM YYYY') }}
                </p>
                 <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Properti Mencapai Target</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $targetAnalysis['properties_achieved_count'] }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">% Properti Mencapai Target</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $targetAnalysis['properties_achieved_percentage'] }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Rata-rata Pencapaian Target</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $targetAnalysis['average_achievement_percentage'] }}</p>
                    </div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-4">*) Fitur ini memerlukan implementasi modul penetapan target pendapatan.</p>
            </div>


            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Analisis Kepatuhan Input Data</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                    Berdasarkan periode: {{ $filterStartDate->isoFormat('D MMMM YYYY') }} - {{ $filterEndDate->isoFormat('D MMMM YYYY') }}
                    (Total {{ $totalDaysInPeriod }} hari)
                </p>
                @if(empty($dataCompliance['days_without_entry']))
                    <p class="text-gray-600 dark:text-gray-400">
                        Semua properti patuh melakukan entri data untuk periode yang dipilih.
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Properti</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jml Hari Tanpa Entri</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">% Kepatuhan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($dataCompliance['days_without_entry'] as $compliance)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $compliance['property_name'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $compliance['days'] }} dari {{ $compliance['total_days_in_period'] }} hari</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ number_format($compliance['compliance_percentage'], 2) }}%
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const overallIncomeSourceData = @json($overallIncomeSource);
    const overallIncomeByPropertyData = @json($overallIncomeByProperty);
    const trendKontribusiKategoriData = @json($trendKontribusiData);

    // 1. Diagram Pie: Distribusi Sumber Pendapatan Keseluruhan
    const kpiOverallSourceCanvas = document.getElementById('kpiOverallSourcePieChart');
    if (kpiOverallSourceCanvas && overallIncomeSourceData && (parseFloat(overallIncomeSourceData.total_mice) > 0 || parseFloat(overallIncomeSourceData.total_fnb) > 0 || parseFloat(overallIncomeSourceData.total_offline_room) > 0 || parseFloat(overallIncomeSourceData.total_online_room) > 0 || parseFloat(overallIncomeSourceData.total_others) > 0)) {
        new Chart(kpiOverallSourceCanvas.getContext('2d'), {
            type: 'pie',
            data: {
                labels: ['MICE', 'F&B', 'Kamar Offline', 'Kamar Online', 'Lainnya'],
                datasets: [{
                    label: 'Distribusi Pendapatan',
                    data: [
                        overallIncomeSourceData.total_mice || 0,
                        overallIncomeSourceData.total_fnb || 0,
                        overallIncomeSourceData.total_offline_room || 0,
                        overallIncomeSourceData.total_online_room || 0,
                        overallIncomeSourceData.total_others || 0
                    ],
                    backgroundColor: ['rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)', 'rgba(153, 102, 255, 0.7)'],
                    borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'],
                    borderWidth: 1
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' }, title: { display: true, text: 'Distribusi Sumber Pendapatan (Periode Terfilter)' } } }
        });
    } else if(kpiOverallSourceCanvas) {
        const ctx = kpiOverallSourceCanvas.getContext('2d');
        ctx.font = '16px Figtree, sans-serif';
        ctx.fillStyle = document.body.classList.contains('dark') ? '#cbd5e1' : '#4b5563';
        ctx.textAlign = 'center';
        ctx.fillText('Tidak ada data distribusi.', kpiOverallSourceCanvas.width / 2, kpiOverallSourceCanvas.height / 2);
    }

    // 2. Diagram Bar: Total Pendapatan per Properti
    const kpiOverallIncomeByPropertyCanvas = document.getElementById('kpiOverallIncomeByPropertyBarChart');
    if (kpiOverallIncomeByPropertyCanvas && overallIncomeByPropertyData && overallIncomeByPropertyData.length > 0) {
        new Chart(kpiOverallIncomeByPropertyCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: overallIncomeByPropertyData.map(p => p.name),
                datasets: [{
                    label: 'Total Pendapatan (Rp)',
                    data: overallIncomeByPropertyData.map(p => p.total_revenue || 0),
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); } } } }, plugins: { legend: { display: false }, title: { display: true, text: 'Total Pendapatan per Properti (Periode Terfilter)' } } }
        });
    } else if (kpiOverallIncomeByPropertyCanvas) {
         const ctx = kpiOverallIncomeByPropertyCanvas.getContext('2d');
        ctx.font = '16px Figtree, sans-serif';
        ctx.fillStyle = document.body.classList.contains('dark') ? '#cbd5e1' : '#4b5563';
        ctx.textAlign = 'center';
        ctx.fillText('Tidak ada data pendapatan properti.', kpiOverallIncomeByPropertyCanvas.width / 2, kpiOverallIncomeByPropertyCanvas.height / 2);
    }

    // 3. KPI 2.1: Tren Total Pendapatan per Kategori (Bulanan dalam Periode)
    const kpiTrendKontribusiKategoriCanvas = document.getElementById('kpiTrendKontribusiKategoriLineChart');
    if (kpiTrendKontribusiKategoriCanvas && trendKontribusiKategoriData && trendKontribusiKategoriData.labels && trendKontribusiKategoriData.labels.length > 0 && trendKontribusiKategoriData.datasets.some(ds => ds.data.length > 0 && ds.data.some(d => d > 0))) {
        new Chart(kpiTrendKontribusiKategoriCanvas.getContext('2d'), {
            type: 'line',
            data: trendKontribusiKategoriData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        stacked: false,
                        ticks: { callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); } },
                        title: { display: true, text: 'Total Pendapatan (Rp)' }
                    },
                    x: { title: { display: true, text: 'Periode (Bulan)' } }
                },
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Tren Total Pendapatan per Kategori (Bulanan dalam Periode)' }
                }
            }
        });
    } else if (kpiTrendKontribusiKategoriCanvas) {
        const ctx = kpiTrendKontribusiKategoriCanvas.getContext('2d');
        ctx.font = '16px Figtree, sans-serif';
        ctx.fillStyle = document.body.classList.contains('dark') ? '#cbd5e1' : '#4b5563';
        ctx.textAlign = 'center';
        ctx.fillText('Tidak ada data tren pendapatan per kategori untuk periode ini.', kpiTrendKontribusiKategoriCanvas.width / 2, kpiTrendKontribusiKategoriCanvas.height / 2);
    }
});
</script>
@endpush
</x-app-layout>
