<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Admin Dashboard') }}
            </h2>
            <nav class="flex space-x-4">
                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard') }}
                </x-nav-link>
                <x-nav-link :href="route('admin.properties.compare.form')" :active="request()->routeIs('admin.properties.compare.form') || request()->routeIs('admin.properties.compare.results')">
                    {{ __('Bandingkan Properti') }}
                </x-nav-link>
            </nav>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-col md:flex-row md:items-end md:space-x-4 space-y-4 md:space-y-0">
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
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Ringkasan Pendapatan Keseluruhan (Periode: {{ Str::title($period) }})</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">Distribusi Sumber Pendapatan</h4>
                        <div style="height: 300px;">
                            <canvas id="overallSourcePieChart"></canvas>
                        </div>
                    </div>
                    <div class="p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">Total Pendapatan per Properti</h4>
                        <div style="height: 300px;">
                            <canvas id="overallIncomeByPropertyBarChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap justify-between items-center mt-8 mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Detail Properti</h3>
                    @if(!$properties->isEmpty() && isset($properties->first()->total_income_records))
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.dashboard.export.excel', request()->query()) }}" class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest">Export Excel</a>
                        <a href="{{ route('admin.dashboard.export.csv', request()->query()) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest">Export CSV</a>
                    </div>
                    @endif
                </div>

                {{-- ================== PERBAIKAN UTAMA DI SINI ================== --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($properties as $property)
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="font-semibold text-lg text-gray-900 dark:text-gray-100">{{ $property->name }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Total Catatan: {{ $property->total_income_records ?? 0 }}</p>
                            
                            {{-- Loop dinamis untuk menampilkan semua 10 kategori pendapatan --}}
                            @foreach($incomeCategories as $column => $label)
                                @php
                                    // Membuat nama properti sum secara dinamis, e.g., 'total_offline_room_income'
                                    $sumProperty = 'total_' . $column;
                                @endphp
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $label }}: Rp {{ number_format($property->{$sumProperty} ?? 0, 0, ',', '.') }}
                                </p>
                            @endforeach
                            
                            <a href="{{ route('admin.properties.show', $property) }}" class="inline-block mt-2 text-sm text-blue-600 dark:text-blue-400 hover:underline">Lihat Detail</a>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8">
                            <p class="text-gray-600 dark:text-gray-400">Tidak ada data properti yang ditemukan untuk filter yang dipilih.</p>
                        </div>
                    @endforelse
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

    const overallIncomeSourceData = @json($overallIncomeSource);
    const overallIncomeByPropertyData = @json($overallIncomeByProperty);
    const incomeCategories = @json($incomeCategories); 

    // 1. Diagram Pie
    const overallSourceCanvas = document.getElementById('overallSourcePieChart');
    if (overallSourceCanvas) {
        const pieLabels = Object.values(incomeCategories);
        const pieData = overallIncomeSourceData ? Object.keys(incomeCategories).map(key => overallIncomeSourceData['total_' + key] || 0) : [];
        const hasPieData = pieData.some(v => v > 0);

        if (hasPieData) {
            new Chart(overallSourceCanvas, {
                type: 'pie',
                data: {
                    labels: pieLabels,
                    datasets: [{
                        label: 'Distribusi Pendapatan',
                        data: pieData,
                        backgroundColor: ['#e6194B', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#42d4f4', '#f032e6', '#bfef45', '#808000'],
                    }]
                },
                options: { 
                    responsive: true, maintainAspectRatio: false, 
                    plugins: { legend: { position: 'top', labels: { color: isDarkMode ? '#e5e7eb' : '#6b7280' } } } 
                }
            });
        } else {
            const ctx = overallSourceCanvas.getContext('2d');
            ctx.font = '16px Figtree, sans-serif';
            ctx.fillStyle = isDarkMode ? '#cbd5e1' : '#4b5563';
            ctx.textAlign = 'center';
            ctx.fillText('Tidak ada data pendapatan untuk filter ini.', overallSourceCanvas.width / 2, overallSourceCanvas.height / 2);
        }
    }

    // 2. Diagram Bar
    const overallIncomeByPropertyCanvas = document.getElementById('overallIncomeByPropertyBarChart');
    if (overallIncomeByPropertyCanvas) {
        if (overallIncomeByPropertyData && overallIncomeByPropertyData.length > 0) {
            new Chart(overallIncomeByPropertyCanvas, {
                type: 'bar',
                data: {
                    labels: overallIncomeByPropertyData.map(p => p.name),
                    datasets: [{
                        label: 'Total Pendapatan (Rp)',
                        data: overallIncomeByPropertyData.map(p => p.total_revenue || 0),
                        backgroundColor: '#36A2EB',
                    }]
                },
                options: { 
                    responsive: true, maintainAspectRatio: false, 
                    scales: { 
                        y: { beginAtZero: true, ticks: { callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); }, color: isDarkMode ? '#e5e7eb' : '#6b7280' } },
                        x: { ticks: { color: isDarkMode ? '#e5e7eb' : '#6b7280' } }
                    }, 
                    plugins: { legend: { display: false } } 
                }
            });
        } else {
            const ctx = overallIncomeByPropertyCanvas.getContext('2d');
            ctx.font = '16px Figtree, sans-serif';
            ctx.fillStyle = isDarkMode ? '#cbd5e1' : '#4b5563';
            ctx.textAlign = 'center';
            ctx.fillText('Tidak ada data pendapatan untuk filter ini.', overallIncomeByPropertyCanvas.width / 2, overallIncomeByPropertyCanvas.height / 2);
        }
    }
});
</script>
@endpush
</x-app-layout>