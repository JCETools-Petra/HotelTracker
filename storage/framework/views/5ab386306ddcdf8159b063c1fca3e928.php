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
            <?php echo e(__('Sales Dashboard')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Booking (Bulan Ini)</h3>
                    <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100"><?php echo e($totalBookingThisMonth); ?></p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Booking Pasti (Bulan Ini)</h3>
                    <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100"><?php echo e($confirmedBookingThisMonth); ?></p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Estimasi Pendapatan (Bulan Ini)</h3>
                    <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100">Rp <?php echo e(number_format($estimatedRevenueThisMonth, 0, ',', '.')); ?></p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Peserta (Bulan Ini)</h3>
                    <p class="mt-2 text-3xl font-semibold text-gray-900 dark:text-gray-100"><?php echo e(number_format($totalParticipantsThisMonth, 0, ',', '.')); ?></p>
                </div>
            </div>

            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100 mb-4">Jadwal Event (7 Hari ke Depan)</h3>
                    <div class="space-y-4">
                        <?php $__empty_1 = true; $__currentLoopData = $upcomingEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 text-center">
                                    <div class="bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded-md px-3 py-1">
                                        <p class="text-2xl font-bold"><?php echo e($event->event_date->format('d')); ?></p>
                                        <p class="text-xs uppercase"><?php echo e($event->event_date->format('M')); ?></p>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800 dark:text-gray-200"><?php echo e($event->client_name); ?></p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        [ <?php echo e($event->person_in_charge); ?> - <?php echo e($event->event_type); ?> - <?php echo e($event->miceCategory->name ?? 'N/A'); ?> ]
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        <span class="font-medium"><?php echo e(number_format($event->participants, 0, ',', '.')); ?></span> Peserta
                                    </p>
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-300 text-right">
                                    <p><?php echo e(\Carbon\Carbon::parse($event->start_time)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($event->end_time)->format('H:i')); ?></p>
                                    <p class="text-xs text-gray-400"><?php echo e($event->room->name ?? 'N/A'); ?></p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-gray-500 dark:text-gray-400">Tidak ada event dalam 7 hari ke depan.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100 mb-4">Booking Terbaru</h3>
                    <?php if($latestBooking): ?>
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-gray-200"><?php echo e($latestBooking->client_name); ?></p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Dibuat pada <?php echo e($latestBooking->created_at->format('d M Y')); ?></p>
                            <span class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                <?php if($latestBooking->status == 'Booking Pasti'): ?> bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                <?php elseif($latestBooking->status == 'Booking Sementara'): ?> bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                <?php else: ?> bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 <?php endif; ?>">
                                <?php echo e($latestBooking->status); ?>

                            </span>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 dark:text-gray-400">Belum ada booking.</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
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
<?php /**PATH C:\xampp\htdocs\HotelTracker\resources\views/sales/dashboard.blade.php ENDPATH**/ ?>