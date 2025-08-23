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
            <?php echo e(__('Dashboard Harga BAR')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="<?php echo e(route('ecommerce.dashboard')); ?>" method="GET">
                        <div class="flex items-end gap-4">
                            <div class="flex-grow">
                                <label for="property_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Properti</label>
                                <select name="property_id" id="property_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" onchange="this.form.submit()">
                                    <option value="">-- Pilih Properti --</option>
                                    <?php $__currentLoopData = $properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($property->id); ?>" <?php echo e($selectedPropertyId == $property->id ? 'selected' : ''); ?>>
                                            <?php echo e($property->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php if($selectedProperty): ?>
                
                <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg text-center">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kamar Terisi Hari Ini</h4>
                        <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-white"><?php echo e($currentOccupancyInfo['occupied_rooms']); ?></p>
                    </div>
                    <div class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg text-center">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">BAR Aktif</h4>
                        <p class="mt-1 text-3xl font-semibold text-indigo-600 dark:text-indigo-400">BAR <?php echo e($currentOccupancyInfo['active_bar']); ?></p>
                    </div>
                </div>

                
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">
                            Harga Berlaku untuk: <span class="text-indigo-500"><?php echo e($selectedProperty->name); ?></span>
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipe Kamar</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Harga BAR Berlaku
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <?php $__empty_1 = true; $__currentLoopData = $roomTypePrices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roomType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap font-medium"><?php echo e($roomType['name']); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right font-semibold text-lg">
                                                Rp <?php echo e(number_format($roomType['active_price'], 0, ',', '.')); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="2" class="px-6 py-8 text-center text-gray-500">
                                                Tidak ada tipe kamar yang dikonfigurasi untuk properti ini.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-16 text-gray-500 dark:text-gray-400">
                    <p>Silakan pilih properti untuk menampilkan data harga BAR.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\HotelTracker\resources\views/ecommerce/dashboard.blade.php ENDPATH**/ ?>