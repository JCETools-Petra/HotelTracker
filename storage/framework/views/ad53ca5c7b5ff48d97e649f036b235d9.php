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
            <?php echo e(__('Master Booking Event')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
                <a href="<?php echo e(route('sales.bookings.create')); ?>" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 whitespace-nowrap">
                    + Tambah Booking
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm mb-6">
                <form action="<?php echo e(route('sales.bookings.index')); ?>" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        
                        <div class="lg:col-span-2">
                            <label for="search" class="sr-only">Cari</label>
                            <input type="text" name="search" id="search" placeholder="Cari nama klien atau no. booking..." value="<?php echo e(request('search')); ?>" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm text-sm">
                        </div>

                        <div>
                            <label for="status" class="sr-only">Status</label>
                            <select name="status" id="status" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">Semua Status</option>
                                <option value="Booking Sementara" <?php if(request('status') == 'Booking Sementara'): echo 'selected'; endif; ?>>Booking Sementara</option>
                                <option value="Booking Pasti" <?php if(request('status') == 'Booking Pasti'): echo 'selected'; endif; ?>>Booking Pasti</option>
                                <option value="Cancel" <?php if(request('status') == 'Cancel'): echo 'selected'; endif; ?>>Cancel</option>
                            </select>
                        </div>

                        <div>
                             <label for="start_date" class="sr-only">Dari Tanggal</label>
                             <input type="date" name="start_date" id="start_date" value="<?php echo e(request('start_date')); ?>" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm text-sm" title="Tanggal Acara Mulai">
                        </div>

                        <div>
                             <label for="end_date" class="sr-only">Sampai Tanggal</label>
                             <input type="date" name="end_date" id="end_date" value="<?php echo e(request('end_date')); ?>" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm text-sm" title="Tanggal Acara Selesai">
                        </div>

                        <div class="flex items-end space-x-2">
                            <button type="submit" class="w-full px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 text-sm">Filter</button>
                            <a href="<?php echo e(route('sales.bookings.index')); ?>" class="w-full text-center px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-400 text-sm">Reset</a>
                        </div>
                    </div>
                </form>
            </div>

            <?php if(session('success')): ?>
                <div class="mb-4 p-4 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-lg">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>
             <?php if(session('error')): ?>
                <div class="mb-4 p-4 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 rounded-lg">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No. Booking</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Klien</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tgl Acara</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ruangan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"><?php echo e($booking->booking_number); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"><?php echo e($booking->client_name); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"><?php echo e(\Carbon\Carbon::parse($booking->event_date)->format('d M Y')); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"><?php echo e($booking->room->name ?? 'N/A'); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php if($booking->status == 'Booking Pasti'): ?> bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                            <?php elseif($booking->status == 'Booking Sementara'): ?> bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                            <?php else: ?> bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 <?php endif; ?>">
                                            <?php echo e($booking->status); ?>

                                        </span>
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            
                                            <a href="<?php echo e(route('sales.bookings.edit', $booking)); ?>" class="px-2 py-1 text-xs bg-indigo-600 text-white rounded hover:bg-indigo-700" title="Edit Booking">Edit</a>
                                            
                                            <?php if($booking->status == 'Booking Pasti'): ?>
                                                <?php if($booking->functionSheet): ?>
                                                    <a href="<?php echo e(route('sales.bookings.showBeo', $booking)); ?>" class="px-2 py-1 text-xs bg-gray-600 text-white rounded hover:bg-gray-700" title="Lihat BEO">Lihat BEO</a>
                                                    <a href="<?php echo e(route('sales.bookings.beo', $booking)); ?>" class="px-2 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700" title="Edit Function Sheet">Edit BEO</a>
                                                <?php else: ?>
                                                    <a href="<?php echo e(route('sales.bookings.beo', $booking)); ?>" class="px-2 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700" title="Lengkapi Function Sheet">Buat BEO</a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            
                                            <a href="<?php echo e(route('sales.documents.quotation', $booking)); ?>" class="px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700" title="Download Quotation">Quote</a>
                                            
                                            <form action="<?php echo e(route('sales.bookings.destroy', $booking)); ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus booking ini?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="px-2 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700" title="Hapus Booking">Hapus</button>
                                            </form>

                                        </div>
                                    </td>
                                    
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        Tidak ada data booking yang cocok dengan filter Anda.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4">
                <?php echo e($bookings->links()); ?>

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
<?php endif; ?><?php /**PATH C:\xampp\htdocs\HotelTracker\resources\views/sales/bookings/index.blade.php ENDPATH**/ ?>