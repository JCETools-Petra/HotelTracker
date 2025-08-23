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
        <div class="flex flex-wrap justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <?php echo e(__('Detail Properti: ')); ?> <?php echo e($property->name); ?>

            </h2>
            <div class="flex space-x-2 mt-2 sm:mt-0">
                <?php if (isset($component)) { $__componentOriginal3b0e04e43cf890250cc4d85cff4d94af = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.secondary-button','data' => ['onclick' => 'window.history.back()']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('secondary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['onclick' => 'window.history.back()']); ?>
                    <?php echo e(__('Kembali')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af)): ?>
<?php $attributes = $__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af; ?>
<?php unset($__attributesOriginal3b0e04e43cf890250cc4d85cff4d94af); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3b0e04e43cf890250cc4d85cff4d94af)): ?>
<?php $component = $__componentOriginal3b0e04e43cf890250cc4d85cff4d94af; ?>
<?php unset($__componentOriginal3b0e04e43cf890250cc4d85cff4d94af); ?>
<?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-data')): ?>
                    <a href="<?php echo e(route('admin.properties.edit', $property->id)); ?>"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <?php echo e(__('Edit Properti')); ?>

                    </a>
                <?php endif; ?>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div x-data="{ showDetailModal: false, selectedIncome: null }" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Atur Okupansi Harian</h3>

                    <?php if(session('success')): ?>
                        <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 dark:bg-green-900 dark:text-green-300 p-3 rounded-md border border-green-300 dark:border-green-700">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('admin.properties.occupancy.update', $property->id)); ?>" method="POST" id="occupancyForm">
                        <?php echo csrf_field(); ?>
                        <div class="flex flex-wrap items-end gap-4">
                            <div>
                                <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'date_selector','value' => __('Pilih Tanggal')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'date_selector','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Pilih Tanggal'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['type' => 'date','id' => 'date_selector','name' => 'date','value' => $selectedDate,'class' => 'block mt-1 w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'date','id' => 'date_selector','name' => 'date','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($selectedDate),'class' => 'block mt-1 w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                            </div>

                            <div>
                                <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'occupied_rooms','value' => __('Jumlah Kamar Terisi')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'occupied_rooms','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Jumlah Kamar Terisi'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                                <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['type' => 'number','name' => 'occupied_rooms','value' => $occupancy->occupied_rooms,'class' => 'block mt-1 w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'number','name' => 'occupied_rooms','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($occupancy->occupied_rooms),'class' => 'block mt-1 w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                            </div>

                            <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['type' => 'submit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit']); ?><?php echo e(__('Simpan')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="<?php echo e(route('admin.properties.show', $property->id)); ?>">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        <div>
                            <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'start_date','value' => __('Dari Tanggal')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'start_date','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Dari Tanggal'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'start_date','class' => 'block mt-1 w-full','type' => 'date','name' => 'start_date','value' => request('start_date', $displayStartDate ? $displayStartDate->toDateString() : '')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'start_date','class' => 'block mt-1 w-full','type' => 'date','name' => 'start_date','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request('start_date', $displayStartDate ? $displayStartDate->toDateString() : ''))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                        </div>
                        <div>
                            <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'end_date','value' => __('Sampai Tanggal')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'end_date','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Sampai Tanggal'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'end_date','class' => 'block mt-1 w-full','type' => 'date','name' => 'end_date','value' => request('end_date', $displayEndDate ? $displayEndDate->toDateString() : '')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'end_date','class' => 'block mt-1 w-full','type' => 'date','name' => 'end_date','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request('end_date', $displayEndDate ? $displayEndDate->toDateString() : ''))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                        </div>
                        <div class="flex items-end space-x-2">
                            <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['type' => 'submit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit']); ?><?php echo e(__('Filter')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
                            <a href="<?php echo e(route('admin.properties.show', $property->id)); ?>"
                               class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <?php echo e(__('Reset')); ?>

                            </a>
                        </div>
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <h3 class="font-semibold text-lg text-gray-600 dark:text-gray-300">Total Pendapatan (Periode Terfilter)</h3>
                                <p class="text-4xl font-bold text-green-600 dark:text-green-400 mt-2">
                                    Rp <?php echo e(number_format($totalPropertyRevenueFiltered ?? 0, 0, ',', '.')); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                 <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                    Ringkasan Pendapatan <?php echo e($property->name); ?>

                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Periode: <?php echo e($displayStartDate->isoFormat('D MMMM YYYY')); ?> - <?php echo e($displayEndDate->isoFormat('D MMMM YYYY')); ?>

                </p>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-3">Tren Pendapatan Harian</h4>
                        <div style="height: 350px;">
                            <canvas id="propertyDailyTrendLineChart"></canvas>
                        </div>
                    </div>
                    <div class="p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-3">Distribusi Sumber Pendapatan</h4>
                        <div style="height: 350px;">
                            <canvas id="propertySourceDistributionPieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        Riwayat Pendapatan Harian
                    </h3>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-data')): ?>
                        <a href="<?php echo e(route('admin.properties.incomes.create', $property)); ?>" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-indigo-700">
                            + Tambah Pendapatan
                        </a>
                    <?php endif; ?>
                </div>
                
                <?php if($incomes->isEmpty()): ?>
                    <p class="text-gray-600 dark:text-gray-400">Tidak ada data pendapatan untuk periode yang dipilih.</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Pendapatan</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <?php $__currentLoopData = $incomes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $income): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $rowTotal = ($income->offline_room_income ?? 0) + ($income->online_room_income ?? 0) + ($income->ta_income ?? 0) + 
                                                    ($income->gov_income ?? 0) + ($income->corp_income ?? 0) + ($income->compliment_income ?? 0) + 
                                                    ($income->house_use_income ?? 0) + ($income->afiliasi_room_income ?? 0) + ($income->mice_booking_total ?? 0) + 
                                                    ($income->breakfast_income ?? 0) + ($income->lunch_income ?? 0) + ($income->dinner_income ?? 0) + ($income->others_income ?? 0);
                                    ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"><?php echo e(\Carbon\Carbon::parse($income->date)->isoFormat('dddd, D MMMM YYYY')); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-right text-gray-900 dark:text-gray-100">Rp <?php echo e(number_format($rowTotal, 0, ',', '.')); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center space-x-4">
                                            <button @click="selectedIncome = <?php echo e(Js::from($income)); ?>; showDetailModal = true" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 font-medium">Lihat Detail</button>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-data')): ?>
                                                <?php if($income->id): ?>
                                                    <a href="<?php echo e(route('admin.incomes.edit', $income->id)); ?>" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 font-medium">Edit</a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div x-show="showDetailModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
            <div @click.away="showDetailModal = false" x-show="showDetailModal" x-transition class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-3xl mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6" x-show="selectedIncome">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                        Detail Pendapatan - <span x-text="new Date(selectedIncome.date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })"></span>
                    </h3>
                    <script>
                        function formatCurrency(value) {
                            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value || 0);
                        }
                    </script>
                    <div class="mt-4 pt-4 border-t dark:border-gray-700 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3 text-sm">
                        <div class="space-y-2">
                            <h4 class="font-semibold text-md mb-2 text-gray-800 dark:text-gray-200">Pendapatan Kamar</h4>
                            <p><strong class="w-32 inline-block">Walk In:</strong> <span x-text="selectedIncome.offline_rooms || 0"></span> Kamar / <span x-text="formatCurrency(selectedIncome.offline_room_income)"></span></p>
                            <p><strong class="w-32 inline-block">OTA:</strong> <span x-text="selectedIncome.online_rooms || 0"></span> Kamar / <span x-text="formatCurrency(selectedIncome.online_room_income)"></span></p>
                            <p><strong class="w-32 inline-block">Travel Agent:</strong> <span x-text="selectedIncome.ta_rooms || 0"></span> Kamar / <span x-text="formatCurrency(selectedIncome.ta_income)"></span></p>
                            <p><strong class="w-32 inline-block">Government:</strong> <span x-text="selectedIncome.gov_rooms || 0"></span> Kamar / <span x-text="formatCurrency(selectedIncome.gov_income)"></span></p>
                            <p><strong class="w-32 inline-block">Corporation:</strong> <span x-text="selectedIncome.corp_rooms || 0"></span> Kamar / <span x-text="formatCurrency(selectedIncome.corp_income)"></span></p>
                            <p><strong class="w-32 inline-block">Compliment:</strong> <span x-text="selectedIncome.compliment_rooms || 0"></span> Kamar / <span x-text="formatCurrency(selectedIncome.compliment_income)"></span></p>
                            <p><strong class="w-32 inline-block">House Use:</strong> <span x-text="selectedIncome.house_use_rooms || 0"></span> Kamar / <span x-text="formatCurrency(selectedIncome.house_use_income)"></span></p>
                            <p><strong class="w-32 inline-block">Afiliasi:</strong> <span x-text="selectedIncome.afiliasi_rooms || 0"></span> Kamar / <span x-text="formatCurrency(selectedIncome.afiliasi_room_income)"></span></p>
                        </div>
                        <div class="space-y-2">
                            <h4 class="font-semibold text-md mb-2 text-gray-800 dark:text-gray-200">Pendapatan Lainnya</h4>
                            <p><strong class="w-32 inline-block">MICE:</strong> <span x-text="formatCurrency(selectedIncome.mice_booking_total)"></span></p>
                            <div class="pl-4 border-l-2 dark:border-gray-600">
                                <p><strong class="w-28 inline-block">F&B (Total):</strong> <strong x-text="formatCurrency(parseFloat(selectedIncome.breakfast_income || 0) + parseFloat(selectedIncome.lunch_income || 0) + parseFloat(selectedIncome.dinner_income || 0))"></strong></p>
                                <p class="text-xs text-gray-500"><span class="w-28 inline-block ml-2">- Breakfast:</span> <span x-text="formatCurrency(selectedIncome.breakfast_income)"></span></p>
                                <p class="text-xs text-gray-500"><span class="w-28 inline-block ml-2">- Lunch:</span> <span x-text="formatCurrency(selectedIncome.lunch_income)"></span></p>
                                <p class="text-xs text-gray-500"><span class="w-28 inline-block ml-2">- Dinner:</span> <span x-text="formatCurrency(selectedIncome.dinner_income)"></span></p>
                            </div>
                            <p><strong class="w-32 inline-block">Lainnya:</strong> <span x-text="formatCurrency(selectedIncome.others_income)"></span></p>
                            <p class="mt-4 pt-3 border-t dark:border-gray-600">
                                <strong class="w-32 inline-block font-bold text-lg">TOTAL:</strong>
                                <strong class="text-lg" x-text="formatCurrency(
                                    (parseFloat(selectedIncome.offline_room_income) || 0) +
                                    (parseFloat(selectedIncome.online_room_income) || 0) +
                                    (parseFloat(selectedIncome.ta_income) || 0) +
                                    (parseFloat(selectedIncome.gov_income) || 0) +
                                    (parseFloat(selectedIncome.corp_income) || 0) +
                                    (parseFloat(selectedIncome.compliment_income) || 0) +
                                    (parseFloat(selectedIncome.house_use_income) || 0) +
                                    (parseFloat(selectedIncome.afiliasi_room_income) || 0) +
                                    (parseFloat(selectedIncome.mice_booking_total) || 0) +
                                    (parseFloat(selectedIncome.breakfast_income) || 0) +
                                    (parseFloat(selectedIncome.lunch_income) || 0) +
                                    (parseFloat(selectedIncome.dinner_income) || 0) +
                                    (parseFloat(selectedIncome.others_income) || 0)
                                )"></strong>
                            </p>
                        </div>
                    </div>
                    <div class="mt-6 text-right">
                        <button @click="showDetailModal = false" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Script untuk me-refresh halaman saat tanggal okupansi diganti
        document.getElementById('date_selector').addEventListener('change', function() {
            let currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('date', this.value);
            // Hapus parameter filter lama agar tidak tumpang tindih
            currentUrl.searchParams.delete('start_date');
            currentUrl.searchParams.delete('end_date');
            window.location.href = currentUrl.toString();
        });

        document.addEventListener('DOMContentLoaded', function () {
            const dailyTrendData = <?php echo json_encode($dailyTrend, 15, 512) ?>;
            const sourceDistributionData = <?php echo json_encode($sourceDistribution, 15, 512) ?>;
            const incomeCategories = <?php echo json_encode($incomeCategories, 15, 512) ?>;
            const isDarkMode = document.documentElement.classList.contains('dark');
            Chart.defaults.color = isDarkMode ? '#e5e7eb' : '#6b7280';
            Chart.defaults.borderColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';

            const dailyTrendCanvas = document.getElementById('propertyDailyTrendLineChart');
            if (dailyTrendCanvas && dailyTrendData && dailyTrendData.length > 0) {
                new Chart(dailyTrendCanvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: dailyTrendData.map(item => new Date(item.date).toLocaleDateString('id-ID', { day: '2-digit', month: 'short' })),
                        datasets: [{
                            label: 'Total Pendapatan Harian (Rp)',
                            data: dailyTrendData.map(item => item.total_daily_income),
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            tension: 0.1,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true, ticks: { callback: value => 'Rp ' + value.toLocaleString('id-ID') } },
                            x: { ticks: { autoSkip: true, maxTicksLimit: 15 } }
                        },
                        plugins: { legend: { display: false } }
                    }
                });
            } else if (dailyTrendCanvas) {
                const ctx = dailyTrendCanvas.getContext('2d');
                ctx.font = '16px Figtree, sans-serif';
                ctx.fillStyle = isDarkMode ? '#cbd5e1' : '#4b5563';
                ctx.textAlign = 'center';
                ctx.fillText('Tidak ada data tren untuk periode ini.', dailyTrendCanvas.width / 2, dailyTrendCanvas.height / 2);
            }

            const sourcePieCanvas = document.getElementById('propertySourceDistributionPieChart');
            const chartLabels = Object.values(incomeCategories);
            const chartData = sourceDistributionData ? Object.keys(incomeCategories).map(key => sourceDistributionData['total_' + key] || 0) : [];
            const hasDataForPie = chartData.some(value => parseFloat(value) > 0);

            if (sourcePieCanvas && hasDataForPie) {
                new Chart(sourcePieCanvas.getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: chartLabels,
                        datasets: [{
                            data: chartData,
                            backgroundColor: ['#e6194B', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#42d4f4', '#f032e6', '#bfef45', '#808000'],
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top', labels: { color: isDarkMode ? '#cbd5e1' : '#4b5563' } },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        if (label) { label += ': '; }
                                        if (context.parsed !== null) { label += 'Rp ' + context.parsed.toLocaleString('id-ID'); }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            } else if(sourcePieCanvas) {
                const ctxPie = sourcePieCanvas.getContext('2d');
                ctxPie.font = '16px Figtree, sans-serif';
                ctxPie.fillStyle = isDarkMode ? '#cbd5e1' : '#4b5563';
                ctxPie.textAlign = 'center';
                ctxPie.fillText('Tidak ada data distribusi pendapatan untuk periode ini.', sourcePieCanvas.width / 2, sourcePieCanvas.height / 2);
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
<?php endif; ?><?php /**PATH /home/apsx2353/public_html/hoteliermarket.my.id/resources/views/admin/properties/show.blade.php ENDPATH**/ ?>