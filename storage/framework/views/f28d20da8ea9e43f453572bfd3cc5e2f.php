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
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <?php echo e(__('Kalender Reservasi')); ?>

            </h2>
            <a href="<?php echo e(route('property.reservations.create')); ?>" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                + Reservasi Baru
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200 mb-4">Grafik Okupansi (30 Hari Terakhir)</h3>
                    <div class="h-80 relative"><canvas id="occupancyChart"></canvas></div>
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
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Daftarkan plugin chart
                Chart.register(ChartDataLabels);

                const calendarEl = document.getElementById('calendar');
                const ctx = document.getElementById('occupancyChart').getContext('2d');
                let occupancyChart;

                // Inisialisasi FullCalendar
                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,listWeek'
                    },
                    eventClick: function(info) {
                        // Arahkan ke halaman edit reservasi di dalam scope 'property'
                        window.location.href = `/property/reservations/${info.event.id}/edit`;
                    }
                });
                
                // Fungsi untuk merender chart
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
                                    label: 'Kamar Terisi',
                                    data: chartData.data,
                                    borderColor: 'rgba(79, 70, 229, 1)',
                                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                                    fill: true,
                                    tension: 0.3
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: { y: { beginAtZero: true } },
                                plugins: {
                                    datalabels: {
                                        anchor: 'end', align: 'end', color: '#555',
                                        font: { weight: 'bold' },
                                        formatter: (value) => value > 0 ? value : '',
                                        backgroundColor: 'rgba(255, 255, 255, 0.7)',
                                        borderRadius: 4, padding: 4
                                    }
                                }
                            }
                        });
                    }
                }
                
                // Fungsi untuk mengambil semua data (event kalender dan data chart)
                function fetchData() {
                    // Gunakan route yang benar untuk 'pengguna_properti'
                    fetch("<?php echo e(route('property.calendar.data')); ?>")
                        .then(response => response.json())
                        .then(data => {
                            calendar.removeAllEvents();
                            calendar.addEventSource(data.events);
                            
                            if (data.chartData) {
                                renderChart(data.chartData);
                            }
                        })
                        .catch(error => console.error('Error fetching data:', error));
                }

                // Render kalender dan ambil data awal saat halaman dimuat
                calendar.render();
                fetchData();
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
<?php endif; ?><?php /**PATH /home/apsx2353/public_html/hoteliermarket.my.id/resources/views/property/calendar/index.blade.php ENDPATH**/ ?>