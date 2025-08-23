<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php
        $chartColors = ['#e6194B', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#42d4f4', '#f032e6', '#bfef45', '#808000', '#000075', '#a9a9a9'];
        
        $revenueTitle = 'Ringkasan Pendapatan';
        if ($period === 'today') {
            $revenueTitle = 'Daily Revenue';
        } elseif ($period === 'month') {
            $revenueTitle = 'Monthly Revenue';
        } elseif ($period === 'year') {
            $revenueTitle = 'Yearly Revenue';
        } elseif ($period === 'custom') {
            if ($startDate->isSameDay($endDate)) {
                $revenueTitle = 'Revenue ' . $startDate->isoFormat('D MMMM YYYY');
            } elseif ($startDate->day == 1 && $endDate->day == $endDate->daysInMonth) {
                $revenueTitle = $startDate->isoFormat('MMMM YYYY') . ' Revenue';
            } else {
                $revenueTitle = 'Periode Revenue (' . $startDate->isoFormat('D MMM YY') . ' - ' . $endDate->isoFormat('D MMM YY') . ')';
            }
        }
    ?>

     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <?php echo e(__('Admin Dashboard')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                <form action="<?php echo e(route('admin.dashboard')); ?>" method="GET" id="filter-form" class="space-y-4">
                    <input type="hidden" name="property_id" id="property_id_hidden">
                    <input type="hidden" name="start_date" id="start_date_hidden">
                    <input type="hidden" name="end_date" id="end_date_hidden">
                    <input type="hidden" name="period" id="period_hidden">

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div class="lg:col-span-2">
                            <label for="property_select" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Properti</label>
                            <select id="property_select" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm">
                                <option value="">Semua Properti</option>
                                <?php $__currentLoopData = $allPropertiesForFilter; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($property->id); ?>" <?php if($propertyId == $property->id): echo 'selected'; endif; ?>><?php echo e($property->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="lg:col-span-3 flex items-end">
                            <div class="w-full">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Periode Cepat</label>
                                <div class="mt-1 flex rounded-md shadow-sm filter-button-group">
                                    <button type="button" data-period="today" class="filter-button quick-filter-btn rounded-l-md <?php echo e($period == 'today' ? 'active' : ''); ?>">Hari Ini</button>
                                    <button type="button" data-period="month" class="filter-button quick-filter-btn -ml-px <?php echo e($period == 'month' ? 'active' : ''); ?>">Bulan Ini</button>
                                    <button type="button" data-period="year" class="filter-button quick-filter-btn -ml-px <?php echo e($period == 'year' ? 'active' : ''); ?>">Tahun Ini</button>
                                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="filter-button -ml-px rounded-r-md">Reset</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t dark:border-gray-700">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Atau Pilih Periode Kustom:</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                            <div>
                                <label for="year_select" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun</label>
                                <select id="year_select" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm">
                                    <?php for($y = now()->year + 1; $y >= now()->year - 5; $y--): ?>
                                        <option value="<?php echo e($y); ?>" <?php if($startDate->year == $y): echo 'selected'; endif; ?>><?php echo e($y); ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div>
                                <label for="month_select" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bulan</label>
                                <select id="month_select" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm">
                                    <?php for($m = 1; $m <= 12; $m++): ?>
                                        <option value="<?php echo e($m); ?>" <?php if($startDate->month == $m): echo 'selected'; endif; ?>><?php echo e(\Carbon\Carbon::create(null, $m)->isoFormat('MMMM')); ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div>
                                <label for="day_select" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal</label>
                                <select id="day_select" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm"></select>
                            </div>
                            <div class="flex items-center h-10">
                                <label for="full_month_checkbox" class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" id="full_month_checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm"
                                           <?php if($period === 'custom' && $startDate->day == 1 && $endDate->day == $endDate->daysInMonth): ?> checked <?php endif; ?>>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Lihat Sebulan Penuh</span>
                                </label>
                            </div>
                            <div>
                                 <button type="button" id="apply_custom_filter" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">Terapkan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="mb-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="font-semibold text-lg text-gray-600 dark:text-gray-300">Total Pendapatan (Periode Terfilter)</h3>
                    <p class="text-4xl font-bold text-green-600 dark:text-green-400 mt-2">
                        Rp <?php echo e(number_format($totalOverallRevenue ?? 0, 0, ',', '.')); ?>

                    </p>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4"><?php echo e($revenueTitle); ?></h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">Distribusi Sumber Pendapatan</h4>
                        <div id="pieChartContainer" class="flex flex-col md:flex-row items-center gap-4" style="min-height: 300px;">
                            <div class="w-full md:w-1/2"><canvas id="overallSourcePieChart"></canvas></div>
                            <div class="w-full md:w-1/2 space-y-1" id="pieChartLegend"></div>
                        </div>
                    </div>
                    <div class="p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">Total Pendapatan per Properti</h4>
                        <div id="barChartContainer" style="height: 300px;"><canvas id="overallIncomeByPropertyBarChart"></canvas></div>
                    </div>
                </div>
                
                <div class="flex flex-wrap justify-between items-center mt-8 mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Detail Properti</h3>
                    <?php if(!$properties->isEmpty()): ?>
                    <div class="flex space-x-2">
                        <a href="<?php echo e(route('admin.dashboard.export.excel', request()->query())); ?>" class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest">Export Excel</a>
                        <a href="<?php echo e(route('admin.dashboard.export.csv', request()->query())); ?>" class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest">Export CSV</a>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php $__empty_1 = true; $__currentLoopData = $properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-lg shadow-sm">
                            <?php echo $__env->make('admin.properties._property_card', ['property' => $property, 'incomeCategories' => $incomeCategories, 'revenueTitle' => $revenueTitle], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-span-full text-center py-8">
                            <p class="text-gray-600 dark:text-gray-400">Tidak ada data properti yang ditemukan.</p>
                        </div>
                    <?php endif; ?>
                </div>

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
                                    <?php $__empty_1 = true; $__currentLoopData = $recentMiceBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100"><?php echo e($event->client_name); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"><?php echo e($event->property->name ?? 'N/A'); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100"><?php echo e($event->miceCategory->name ?? 'N/A'); ?></span></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"><?php echo e(\Carbon\Carbon::parse($event->event_date)->format('d M Y')); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200 text-right">Rp <?php echo e(number_format($event->total_price, 0, ',', '.')); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">Tidak ada data event MICE pada periode ini.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.getElementById('filter-form');
                const yearSelect = document.getElementById('year_select');
                const monthSelect = document.getElementById('month_select');
                const daySelect = document.getElementById('day_select');
                const fullMonthCheckbox = document.getElementById('full_month_checkbox');
                const propertySelect = document.getElementById('property_select');
                
                const startDateHidden = document.getElementById('start_date_hidden');
                const endDateHidden = document.getElementById('end_date_hidden');
                const propertyIdHidden = document.getElementById('property_id_hidden');
                const periodHidden = document.getElementById('period_hidden');

                const populateDays = () => {
                    const year = yearSelect.value;
                    const month = monthSelect.value;
                    const daysInMonth = new Date(year, month, 0).getDate();
                    
                    const currentDay = '<?php echo e($period === "custom" ? $startDate->day : now()->day); ?>';
                    let selectedDay = daySelect.value || currentDay;
                    if (selectedDay > daysInMonth) {
                        selectedDay = daysInMonth;
                    }
                    
                    daySelect.innerHTML = '';
                    for (let i = 1; i <= daysInMonth; i++) {
                        const option = document.createElement('option');
                        option.value = i;
                        option.text = i;
                        if (i == selectedDay) {
                            option.selected = true;
                        }
                        daySelect.appendChild(option);
                    }
                };

                const toggleDaySelect = () => {
                    daySelect.disabled = fullMonthCheckbox.checked;
                    daySelect.classList.toggle('bg-gray-200', fullMonthCheckbox.checked);
                    daySelect.classList.toggle('dark:bg-gray-800', fullMonthCheckbox.checked);
                };

                const submitForm = () => {
                    propertyIdHidden.value = propertySelect.value;
                    form.submit();
                };

                document.querySelectorAll('.quick-filter-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        periodHidden.value = this.dataset.period;
                        startDateHidden.value = '';
                        endDateHidden.value = '';
                        submitForm();
                    });
                });

                document.getElementById('apply_custom_filter').addEventListener('click', function() {
                    const year = yearSelect.value;
                    const month = monthSelect.value.toString().padStart(2, '0');
                    let startDateStr, endDateStr;

                    if (fullMonthCheckbox.checked) {
                        startDateStr = `${year}-${month}-01`;
                        const lastDay = new Date(year, month, 0).getDate();
                        endDateStr = `${year}-${month}-${String(lastDay).padStart(2, '0')}`;
                    } else {
                        const day = daySelect.value.toString().padStart(2, '0');
                        startDateStr = `${year}-${month}-${day}`;
                        endDateStr = startDateStr;
                    }
                    
                    startDateHidden.value = startDateStr;
                    endDateHidden.value = endDateStr;
                    periodHidden.value = 'custom';
                    
                    submitForm();
                });

                yearSelect.addEventListener('change', populateDays);
                monthSelect.addEventListener('change', populateDays);
                fullMonthCheckbox.addEventListener('change', toggleDaySelect);
                
                populateDays();
                toggleDaySelect();
                
                const isDarkMode = document.documentElement.classList.contains('dark');
                Chart.defaults.color = isDarkMode ? '#e5e7eb' : '#6b7280';
                Chart.defaults.borderColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
                const pieChartData = <?php echo json_encode($pieChartDataSource, 15, 512) ?>;
                const pieChartCategories = <?php echo json_encode($pieChartCategories, 15, 512) ?>;
                const overallIncomeByPropertyData = <?php echo json_encode($overallIncomeByProperty, 15, 512) ?>;
                const chartColors = <?php echo json_encode($chartColors, 15, 512) ?>;
                let myPieChart;
                const pieCanvas = document.getElementById('overallSourcePieChart');
                const pieLegendContainer = document.getElementById('pieChartLegend');
                const pieChartContainer = document.getElementById('pieChartContainer');
                if (pieCanvas && pieLegendContainer && pieChartContainer) {
                    const pieLabels = Object.values(pieChartCategories);
                    const pieDataValues = pieChartData ? Object.keys(pieChartCategories).map(key => pieChartData['total_' + key] || 0) : [];
                    const hasPieData = pieDataValues.some(v => v > 0);
                    if (hasPieData) {
                        myPieChart = new Chart(pieCanvas, {type: 'pie',data: { labels: pieLabels, datasets: [{ data: pieDataValues, backgroundColor: chartColors }] },options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }});
                        pieLegendContainer.innerHTML = '';
                        pieLabels.forEach((label, index) => {
                            const value = pieDataValues[index];
                            if (value > 0) {
                                const legendItem = document.createElement('div');
                                legendItem.classList.add('flex', 'items-center', 'p-1', 'rounded', 'cursor-pointer', 'hover:bg-gray-100', 'dark:hover:bg-gray-700');
                                legendItem.dataset.index = index;
                                legendItem.innerHTML = `<span class="w-3 h-3 rounded-full mr-2 flex-shrink-0" style="background-color: ${chartColors[index % chartColors.length]};"></span><div class="flex justify-between items-center w-full text-xs"><span class="legend-label text-gray-600 dark:text-gray-400 mr-2 truncate" title="${label}">${label}</span><span class="font-semibold text-gray-800 dark:text-gray-200 text-right whitespace-nowrap">Rp ${new Intl.NumberFormat('id-ID').format(value)}</span></div>`;
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
                        new Chart(barCanvas, {type: 'bar',data: {labels: overallIncomeByPropertyData.map(p => p.name),datasets: [{label: 'Total Pendapatan (Rp)',data: overallIncomeByPropertyData.map(p => p.total_revenue || 0),backgroundColor: propertyColors,}]},options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } }});
                    } else {
                        const barContainer = document.getElementById('barChartContainer');
                        barContainer.innerHTML = `<div class="flex items-center justify-center h-full text-gray-500 dark:text-gray-400">Tidak ada data pendapatan pada periode ini.</div>`;
                    }
                }
            });
        </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\HotelTracker\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>