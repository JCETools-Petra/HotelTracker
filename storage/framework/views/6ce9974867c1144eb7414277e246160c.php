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
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <?php echo e(__('Kalender Reservasi')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-wrap justify-between items-center mb-4 gap-4">
                        <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200">Grafik Okupansi</h3>
                        <div class="flex items-center space-x-4">
                            <select id="property-filter" class="block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                <option value="all">Semua Properti</option>
                                <?php $__currentLoopData = $properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($property->id); ?>"><?php echo e($property->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div>
                                <button id="btn-month" class="px-3 py-1 text-sm font-medium text-white bg-indigo-600 rounded-md shadow-sm">1 Bulan</button>
                                <button id="btn-year" class="px-3 py-1 text-sm font-medium text-gray-700 bg-gray-200 dark:text-gray-200 dark:bg-gray-600 rounded-md">1 Tahun</button>
                            </div>
                        </div>
                    </div>
                    <div class="h-80"><canvas id="occupancyChart"></canvas></div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('styles'); ?>
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('scripts'); ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const calendarEl = document.getElementById('calendar');
                const propertyFilter = document.getElementById('property-filter');
                const btnMonth = document.getElementById('btn-month');
                const btnYear = document.getElementById('btn-year');
                const ctx = document.getElementById('occupancyChart').getContext('2d');
                let occupancyChart;
                let currentRange = 'month';

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,listWeek'
                    },
                });

                function renderChart(chartData) {
                    if (occupancyChart) {
                        occupancyChart.data.labels = chartData.labels;
                        occupancyChart.data.datasets[0].data = chartData.data;
                        occupancyChart.update();
                    } else {
                        occupancyChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: chartData.labels,
                                datasets: [{
                                    label: 'Total Kamar Terisi',
                                    data: chartData.data,
                                    borderColor: 'rgba(79, 70, 229, 1)',
                                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                                    fill: true,
                                    tension: 0.3
                                }]
                            },
                            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
                        });
                    }
                }

                function fetchData() {
                    const propertyId = propertyFilter.value;
                    const range = currentRange;
                    
                    fetch(`<?php echo e(route('ecommerce.dashboard.calendar')); ?>?range=${range}&property_id=${propertyId}`)
                        .then(response => response.json())
                        .then(data => {
                            calendar.removeAllEvents();
                            calendar.addEventSource(data.events);
                            
                            if (data.chartData) {
                                renderChart(data.chartData);
                            }
                        });
                }

                function setActiveButton(range) {
                    currentRange = range;
                    const isMonth = range === 'month';

                    btnMonth.classList.toggle('bg-indigo-600', isMonth);
                    btnMonth.classList.toggle('text-white', isMonth);
                    btnMonth.classList.toggle('bg-gray-200', !isMonth);
                    btnMonth.classList.toggle('dark:bg-gray-600', !isMonth);
                    btnMonth.classList.toggle('text-gray-700', !isMonth);

                    btnYear.classList.toggle('bg-indigo-600', !isMonth);
                    btnYear.classList.toggle('text-white', !isMonth);
                    btnYear.classList.toggle('bg-gray-200', isMonth);
                    btnYear.classList.toggle('dark:bg-gray-600', isMonth);
                    btnYear.classList.toggle('text-gray-700', isMonth);
                }

                btnMonth.addEventListener('click', function() {
                    setActiveButton('month');
                    fetchData();
                });

                btnYear.addEventListener('click', function() {
                    setActiveButton('year');
                    fetchData();
                });

                propertyFilter.addEventListener('change', fetchData);
                
                calendar.render();
                fetchData(); // Muat data awal
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
<?php endif; ?><?php /**PATH C:\xampp\htdocs\HotelTracker\resources\views/ecommerce/calendar/index.blade.php ENDPATH**/ ?>