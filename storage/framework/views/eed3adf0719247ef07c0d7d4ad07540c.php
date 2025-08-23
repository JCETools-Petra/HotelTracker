<?php if (isset($component)) { $__componentOriginal91fdd17964e43374ae18c674f95cdaa3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91fdd17964e43374ae18c674f95cdaa3 = $attributes; } ?>
<?php $component = App\View\Components\AdminLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AdminLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <?php echo e(__('Atur Tipe Kamar & Harga Dinamis')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100"><?php echo e($property->name); ?></h3>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Kelola semua tipe kamar dan aturan harganya di bawah ini.</p>
            </div>

            <?php if(session('success')): ?>
                <div class="p-4 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 border border-green-300 rounded-lg">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>
            <?php if($errors->any()): ?>
                <div class="p-4 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300 border border-red-300 rounded-lg">
                    <p class="font-bold">Terjadi kesalahan:</p>
                    <ul class="list-disc list-inside mt-1">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h4 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Tambah Tipe Kamar Baru</h4>
                <form action="<?php echo e(route('admin.pricing-rules.room-type.store', $property->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="flex items-end gap-4">
                        <div class="flex-grow">
                            <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Nama Tipe Kamar</label>
                            <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm">
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            + Tambah
                        </button>
                    </div>
                </form>
            </div>
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h4 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">Atur Kapasitas Bar (Berlaku untuk Semua Tipe Kamar)</h4>
                <form action="<?php echo e(route('admin.pricing-rules.property-bars.update', $property->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                        <div>
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Kapasitas Bar 1</label>
                            <input type="number" name="bar_1" value="<?php echo e(old('bar_1', $property->bar_1)); ?>" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm">
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Kapasitas Bar 2</label>
                            <input type="number" name="bar_2" value="<?php echo e(old('bar_2', $property->bar_2)); ?>" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm">
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Kapasitas Bar 3</label>
                            <input type="number" name="bar_3" value="<?php echo e(old('bar_3', $property->bar_3)); ?>" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm">
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Kapasitas Bar 4</label>
                            <input type="number" name="bar_4" value="<?php echo e(old('bar_4', $property->bar_4)); ?>" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm">
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Kapasitas Bar 5</label>
                            <input type="number" name="bar_5" value="<?php echo e(old('bar_5', $property->bar_5)); ?>" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm">
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Simpan Kapasitas Bar
                        </button>
                    </div>
                </form>
            </div>

            <?php if($property->roomTypes->isNotEmpty()): ?>
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="mb-6">
                        <label for="room_type_selector" class="block font-medium text-lg text-gray-700 dark:text-gray-300">Pilih Tipe Kamar untuk Diedit</label>
                        <select id="room_type_selector" class="mt-1 block w-full lg:w-1/2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm">
                            <?php $__currentLoopData = $property->roomTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roomType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($roomType->id); ?>"><?php echo e($roomType->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <div>
                        <?php $__currentLoopData = $property->roomTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roomType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div id="form-container-<?php echo e($roomType->id); ?>" class="room-type-form" style="display: none;">
                                <?php echo $__env->make('admin.pricing_rules.partials._form', ['roomType' => $roomType, 'property' => $property], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-center">
                    <p class="text-gray-500">Belum ada tipe kamar. Silakan tambahkan terlebih dahulu.</p>
                </div>
            <?php endif; ?>
            

        </div>
    </div>
    
    <?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selector = document.getElementById('room_type_selector');
            const forms = document.querySelectorAll('.room-type-form');
            function toggleForms() {
                if (!selector) return;
                const selectedId = selector.value;
                forms.forEach(form => {
                    form.style.display = 'none';
                });
                const selectedForm = document.getElementById(`form-container-${selectedId}`);
                if (selectedForm) {
                    selectedForm.style.display = 'block';
                }
            }
            if(selector){
                selector.addEventListener('change', toggleForms);
                toggleForms();
            }
        });
    </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91fdd17964e43374ae18c674f95cdaa3)): ?>
<?php $attributes = $__attributesOriginal91fdd17964e43374ae18c674f95cdaa3; ?>
<?php unset($__attributesOriginal91fdd17964e43374ae18c674f95cdaa3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91fdd17964e43374ae18c674f95cdaa3)): ?>
<?php $component = $__componentOriginal91fdd17964e43374ae18c674f95cdaa3; ?>
<?php unset($__componentOriginal91fdd17964e43374ae18c674f95cdaa3); ?>
<?php endif; ?><?php /**PATH /home/apsx2353/public_html/hoteliermarket.my.id/resources/views/admin/pricing_rules/edit.blade.php ENDPATH**/ ?>