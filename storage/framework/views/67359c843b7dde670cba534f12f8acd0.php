<?php if (isset($component)) { $__componentOriginal344aa5c449b472d432919b85e31fdaa1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal344aa5c449b472d432919b85e31fdaa1 = $attributes; } ?>
<?php $component = App\View\Components\SalesLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sales-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SalesLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <?php echo e(__('Event Calendar')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    
    <?php $__env->startPush('styles'); ?>
    <style>
        /* Pastikan kalender memiliki tinggi */
        #calendar {
            min-height: 70vh;
        }
        /* Style kustom untuk event di kalender */
        .fc-event {
            cursor: pointer;
            border: none !important;
        }
        .fc-event .fc-event-main {
            padding: 5px;
            font-size: 0.8rem;
        }
    </style>
    <?php $__env->stopPush(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    
    <?php $__env->startPush('scripts'); ?>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth', // Tampilan awal: bulanan
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek' // Pilihan view
                },
                // Ambil data event dari route yang kita buat di controller
                events: '<?php echo e(route("sales.calendar.events")); ?>',
                // Buat event bisa diklik
                eventClick: function(info) {
                    info.jsEvent.preventDefault(); // Mencegah browser mengikuti link
                    if (info.event.url) {
                        window.location.href = info.event.url; // Arahkan ke link secara manual
                    }
                },
                eventDisplay: 'block', // Tampilkan event sebagai blok
                dayMaxEvents: true, // Batasi jumlah event per hari dengan tombol "+ more"
            });
            calendar.render();
        });
    </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal344aa5c449b472d432919b85e31fdaa1)): ?>
<?php $attributes = $__attributesOriginal344aa5c449b472d432919b85e31fdaa1; ?>
<?php unset($__attributesOriginal344aa5c449b472d432919b85e31fdaa1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal344aa5c449b472d432919b85e31fdaa1)): ?>
<?php $component = $__componentOriginal344aa5c449b472d432919b85e31fdaa1; ?>
<?php unset($__componentOriginal344aa5c449b472d432919b85e31fdaa1); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\HotelTracker\resources\views/sales/calendar/index.blade.php ENDPATH**/ ?>