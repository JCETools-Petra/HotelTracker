<x-app-layout>
    @php
        // Definisikan warna di sini agar bisa diakses oleh HTML dan JavaScript
        $chartColors = ['#e6194B', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#42d4f4', '#f032e6', '#bfef45', '#808000', '#000075', '#a9a9a9'];
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Filter Section --}}
            <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                <form action="{{ route('admin.dashboard') }}" method="GET" class="space-y-4">
                    {{-- Baris Filter Properti & Periode Cepat --}}
                    <div class="flex flex-col md:flex-row md:items-end md:space-x-4 space-y-4 md:space-y-0">
                        <div class="flex-1">
                            <label for="property_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Properti</label>
                            <select name="property_id" id="property_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                                <option value="">Semua Properti</option>
                                @foreach($allPropertiesForFilter as $property)
                                    <option value="{{ $property->id }}" {{ $propertyId == $property->id ? 'selected' : '' }}>
                                        {{ $property->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-shrink-0">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Periode</label>
                            <div class="mt-1 flex rounded-md shadow-sm filter-button-group">
                                <button type="submit" name="period" value="today" class="filter-button rounded-l-md {{ $period == 'today' ? 'active' : '' }}">Hari Ini</button>
                                <button type="submit" name="period" value="month" class="filter-button -ml-px {{ $period == 'month' ? 'active' : '' }}">Bulan Ini</button>
                                <button type="submit" name="period" value="year" class="filter-button -ml-px {{ $period == 'year' ? 'active' : '' }}">Tahun Ini</button>
                                <a href="{{ route('admin.dashboard') }}" class="filter-button -ml-px rounded-r-md">Reset</a>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Tombol Filter Bulan --}}
                    <div class="pt-4 border-t dark:border-gray-700">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Bulan (Tahun {{ now()->year }}):</label>
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
                                    $monthStartDate = \Carbon\Carbon::create($currentYear, $monthNumber)->startOfMonth();
                                    $monthEndDate = \Carbon\Carbon::create($currentYear, $monthNumber)->endOfMonth();
                                    
                                    // URL sekarang hanya berisi start_date, end_date, dan property_id jika ada
                                    $queryParams = array_merge(request()->only('property_id'), [
                                        'start_date' => $monthStartDate->toDateString(),
                                        'end_date' => $monthEndDate->toDateString()
                                    ]);
                                    $isActive = ($period == 'custom') && (request('start_date') == $queryParams['start_date']);
                                @endphp
                                <a href="{{ route('admin.dashboard', $queryParams) }}"
                                   class="inline-flex items-center px-3 py-1.5 border rounded-md text-xs font-medium transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                                          {{ $isActive ? 'bg-indigo-600 text-white border-transparent shadow-sm' : 'bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600' }}">
                                    {{ $monthName }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>

            {{-- Main Content Section --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                 <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Ringkasan Pendapatan (Periode: 
                    @if($period == 'custom')
                        {{ \Carbon\Carbon::parse(request('start_date'))->isoFormat('D MMM YY') }} - {{ \Carbon\Carbon::parse(request('end_date'))->isoFormat('D MMM YY') }}
                    @else
                        {{ Str::title($period) }}
                    @endif
                )</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    
                    <div class="p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">Distribusi Sumber Pendapatan</h4>
                        <div id="pieChartContainer" class="flex flex-col md:flex-row items-center gap-4" style="min-height: 300px;">
                            <div class="w-full md:w-1/2">
                                <canvas id="overallSourcePieChart"></canvas>
                            </div>
                            <div class="w-full md:w-1/2 space-y-1" id="pieChartLegend">
                                {{-- Legenda akan dibuat oleh JavaScript --}}
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">Total Pendapatan per Properti</h4>
                        <div id="barChartContainer" style="height: 300px;">
                            <canvas id="overallIncomeByPropertyBarChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Property Details Title and Export Buttons --}}
                <div class="flex flex-wrap justify-between items-center mt-8 mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Detail Properti</h3>
                    @if(!$properties->isEmpty())
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.dashboard.export.excel', request()->query()) }}" class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest">Export Excel</a>
                        <a href="{{ route('admin.dashboard.export.csv', request()->query()) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest">Export CSV</a>
                    </div>
                    @endif
                </div>

                {{-- Property Cards Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($properties as $property)
                        <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-lg shadow-sm">
                            @include('admin.properties._property_card', ['incomeCategories' => $incomeCategories])
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8">
                            <p class="text-gray-600 dark:text-gray-400">Tidak ada data properti yang ditemukan.</p>
                        </div>
                    @endforelse
                </div>
                
                {{-- MICE Report Section --}}
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Laporan Event MICE</h3>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-0">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Pemesan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hotel</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kategori</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($recentMiceBookings as $event)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $event->client_name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $event->property->name ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100">
                                                    {{ $event->miceCategory->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 text-right">Rp {{ number_format($event->total_price, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                                Tidak ada data event MICE pada periode ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
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
        const isDarkMode = document.documentElement.classList.contains('dark');
        Chart.defaults.color = isDarkMode ? '#e5e7eb' : '#6b7280';
        Chart.defaults.borderColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';

        const pieChartData = @json($pieChartDataSource);
        const pieChartCategories = @json($pieChartCategories);
        const overallIncomeByPropertyData = @json($overallIncomeByProperty);
        const chartColors = @json($chartColors);
        let myPieChart;

        const pieCanvas = document.getElementById('overallSourcePieChart');
        const pieLegendContainer = document.getElementById('pieChartLegend');
        const pieChartContainer = document.getElementById('pieChartContainer');

        if (pieCanvas && pieLegendContainer && pieChartContainer) {
            const pieLabels = Object.values(pieChartCategories);
            const pieDataValues = pieChartData ? Object.keys(pieChartCategories).map(key => pieChartData['total_' + key] || 0) : [];
            const hasPieData = pieDataValues.some(v => v > 0);

            if (hasPieData) {
                myPieChart = new Chart(pieCanvas, {
                    type: 'pie',
                    data: { labels: pieLabels, datasets: [{ data: pieDataValues, backgroundColor: chartColors }] },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
                });
                pieLegendContainer.innerHTML = '';
                
                pieLabels.forEach((label, index) => {
                    const value = pieDataValues[index];
                    if (value > 0) {
                        const legendItem = document.createElement('div');
                        legendItem.classList.add('flex', 'items-center', 'p-1', 'rounded', 'cursor-pointer', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');
                        legendItem.dataset.index = index;

                        legendItem.innerHTML = `
                            <span class="w-3 h-3 rounded-full mr-2 flex-shrink-0" style="background-color: ${chartColors[index % chartColors.length]};"></span>
                            <div class="flex justify-between items-center w-full text-xs">
                                <span class="legend-label text-gray-600 dark:text-gray-400 mr-2 truncate" title="${label}">${label}</span>
                                <span class="font-semibold text-gray-800 dark:text-gray-200 text-right whitespace-nowrap">Rp ${new Intl.NumberFormat('id-ID').format(value)}</span>
                            </div>
                        `;

                        legendItem.addEventListener('click', (event) => {
                            const clickedIndex = parseInt(event.currentTarget.dataset.index);
                            myPieChart.toggleDataVisibility(clickedIndex);
                            myPieChart.update();
                            event.currentTarget.classList.toggle('opacity-50');
                            event.currentTarget.querySelector('.legend-label').classList.toggle('line-through');
                        });
                        
                        pieLegendContainer.appendChild(legendItem);
                    }
                });
            } else {
                pieChartContainer.innerHTML = `<div class="flex items-center justify-center w-full h-full text-gray-500 dark:text-gray-400">Tidak ada data untuk filter ini.</div>`;
            }
        }

        const barCanvas = document.getElementById('overallIncomeByPropertyBarChart');
        if (barCanvas) {
            const hasBarData = overallIncomeByPropertyData && overallIncomeByPropertyData.some(p => p.total_revenue > 0);
            if (hasBarData) {
                const propertyColors = overallIncomeByPropertyData.map(p => p.chart_color || '#36A2EB');
                new Chart(barCanvas, {
                    type: 'bar',
                    data: {
                        labels: overallIncomeByPropertyData.map(p => p.name),
                        datasets: [{
                            label: 'Total Pendapatan (Rp)',
                            data: overallIncomeByPropertyData.map(p => p.total_revenue || 0),
                            backgroundColor: propertyColors,
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } }
                });
            } else {
                const barContainer = document.getElementById('barChartContainer');
                barContainer.innerHTML = `<div class="flex items-center justify-center h-full text-gray-500 dark:text-gray-400">Tidak ada data pendapatan pada periode ini.</div>`;
            }
        }
    });
</script>
@endpush
</x-app-layout>