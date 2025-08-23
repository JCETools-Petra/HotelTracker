<?php if (isset($component)) { $__componentOriginal71d9805c7e50146f2c022bb784cf0e7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal71d9805c7e50146f2c022bb784cf0e7c = $attributes; } ?>
<?php $component = App\View\Components\PropertyUserLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('property-user-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\PropertyUserLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex flex-wrap justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <?php echo e(__('Dashboard')); ?>

            </h2>
            <div>
                <a href="<?php echo e(route('property.income.create')); ?>" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Catat Pendapatan
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            
            <?php if(session('success')): ?>
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                    <span class="font-medium">Sukses!</span> <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Selamat datang, <?php echo e(Auth::user()->name); ?>!</h3>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Anda mengelola properti <strong><?php echo e($property->name); ?></strong>.</p>
                    
                    <hr class="my-4 dark:border-gray-700">

                    <form id="occupancy-form" action="<?php echo e(route('property.occupancy.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="date" value="<?php echo e(today()->toDateString()); ?>">
                        <div class="flex flex-wrap items-end gap-4">
                            <div>
                                <label for="occupied_rooms" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Update Okupansi Hari Ini (<?php echo e(today()->isoFormat('D MMM YYYY')); ?>)</label>
                                <input type="number" name="occupied_rooms" value="<?php echo e($occupancyToday->occupied_rooms ?? 0); ?>" class="mt-1 block rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <button type="submit" id="update-button" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Total Pendapatan (Bulan Ini)</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">Rp <?php echo e(number_format($totalRevenue, 0, ',', '.')); ?></p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Pendapatan Kamar (Bulan Ini)</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">Rp <?php echo e(number_format($totalRoomRevenue, 0, ',', '.')); ?></p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Pendapatan F&B (Bulan Ini)</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">Rp <?php echo e(number_format($totalFbRevenue, 0, ',', '.')); ?></p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Lain-lain (Bulan Ini)</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mt-2">Rp <?php echo e(number_format($totalOthersIncome, 0, ',', '.')); ?></p>
                </div>
            </div>

            
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">5 Catatan Pendapatan Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Tanggal</th>
                                <th scope="col" class="px-6 py-3">Total Pendapatan</th>
                                <th scope="col" class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $latestIncomes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $income): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-medium"><?php echo e($income->date->isoFormat('dddd, D MMMM YYYY')); ?></td>
                                    <td class="px-6 py-4 font-semibold">Rp <?php echo e(number_format($income->total_revenue, 0, ',', '.')); ?></td>
                                    <td class="px-6 py-4">
                                        <a href="<?php echo e(route('property.income.edit', $income)); ?>" class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline">Edit</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center">Belum ada data pendapatan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('occupancy-form');
            const button = document.getElementById('update-button');

            if (form && button) {
                form.addEventListener('submit', function() {
                    // Saat form di-submit...
                    button.disabled = true; // Nonaktifkan tombol
                    button.innerText = 'Memproses...'; // Ubah teks tombol
                });
            }
        });
    </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal71d9805c7e50146f2c022bb784cf0e7c)): ?>
<?php $attributes = $__attributesOriginal71d9805c7e50146f2c022bb784cf0e7c; ?>
<?php unset($__attributesOriginal71d9805c7e50146f2c022bb784cf0e7c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal71d9805c7e50146f2c022bb784cf0e7c)): ?>
<?php $component = $__componentOriginal71d9805c7e50146f2c022bb784cf0e7c; ?>
<?php unset($__componentOriginal71d9805c7e50146f2c022bb784cf0e7c); ?>
<?php endif; ?><?php /**PATH /home/apsx2353/public_html/hoteliermarket.my.id/resources/views/property/dashboard.blade.php ENDPATH**/ ?>