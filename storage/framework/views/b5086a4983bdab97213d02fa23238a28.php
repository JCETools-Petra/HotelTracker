
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
                <?php echo e(__('Pusat Analisis Kinerja (KPI)')); ?>

            </h2>
            <nav class="flex space-x-4">
                <?php if (isset($component)) { $__componentOriginalc295f12dca9d42f28a259237a5724830 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc295f12dca9d42f28a259237a5724830 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nav-link','data' => ['href' => route('admin.dashboard'),'active' => request()->routeIs('admin.dashboard')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.dashboard')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.dashboard'))]); ?>
                    <?php echo e(__('Dashboard Utama')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc295f12dca9d42f28a259237a5724830)): ?>
<?php $attributes = $__attributesOriginalc295f12dca9d42f28a259237a5724830; ?>
<?php unset($__attributesOriginalc295f12dca9d42f28a259237a5724830); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc295f12dca9d42f28a259237a5724830)): ?>
<?php $component = $__componentOriginalc295f12dca9d42f28a259237a5724830; ?>
<?php unset($__componentOriginalc295f12dca9d42f28a259237a5724830); ?>
<?php endif; ?>
                
            </nav>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Filter Data</h3>
                <form method="GET" action="<?php echo e(route('admin.kpi.analysis')); ?>">
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 items-end">
                        <div>
                            <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'start_date_filter','value' => __('Dari Tanggal')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'start_date_filter','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Dari Tanggal'))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'start_date_filter','class' => 'block mt-1 w-full','type' => 'date','name' => 'start_date','value' => $filterStartDate ? $filterStartDate->format('Y-m-d') : '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'start_date_filter','class' => 'block mt-1 w-full','type' => 'date','name' => 'start_date','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($filterStartDate ? $filterStartDate->format('Y-m-d') : '')]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'end_date_filter','value' => __('Sampai Tanggal')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'end_date_filter','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Sampai Tanggal'))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'end_date_filter','class' => 'block mt-1 w-full','type' => 'date','name' => 'end_date','value' => $filterEndDate ? $filterEndDate->format('Y-m-d') : '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'end_date_filter','class' => 'block mt-1 w-full','type' => 'date','name' => 'end_date','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($filterEndDate ? $filterEndDate->format('Y-m-d') : '')]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'property_mom_filter_id','value' => __('Properti untuk Analisis Detail')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'property_mom_filter_id','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Properti untuk Analisis Detail'))]); ?>
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
                            <select name="property_mom_filter_id" id="property_mom_filter_id" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-200">
                                <option value="all" <?php echo e(($propertyMomFilterId == 'all' || !$propertyMomFilterId) ? 'selected' : ''); ?>>Semua Properti (Gabungan)</option>
                                <?php if(isset($allPropertiesForFilter)): ?>
                                    <?php $__currentLoopData = $allPropertiesForFilter; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($property->id); ?>" <?php echo e($propertyMomFilterId == $property->id ? 'selected' : ''); ?>>
                                            <?php echo e($property->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="pt-5 md:pt-0">
                            <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['type' => 'submit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit']); ?>
                                <?php echo e(__('Terapkan Filter')); ?>

                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
                             <a href="<?php echo e(route('admin.kpi.analysis')); ?>"
                                class="ml-2 inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                 <?php echo e(__('Reset')); ?>

                             </a>
                        </div>
                    </div>
                </form>
            </div>

            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Ringkasan Kinerja Keseluruhan</h3>
                 <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                     Periode: <?php echo e($filterStartDate->isoFormat('D MMMM YYYY')); ?> - <?php echo e($filterEndDate->isoFormat('D MMMM YYYY')); ?>

                     (Total <?php echo e($totalDaysInPeriod); ?> hari)
                 </p>
                 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pendapatan</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">Rp <?php echo e(number_format($totalOverallRevenue ?? 0, 0, ',', '.')); ?></p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Rata-Rata Pendapatan Harian</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">Rp <?php echo e(number_format($averageDailyOverallRevenue ?? 0, 0, ',', '.')); ?></p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Jml. Properti Aktif</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100"><?php echo e($activePropertiesCount ?? 0); ?></p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Jml. Pengguna Properti</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100"><?php echo e($activePropertyUsersCount ?? 0); ?></p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Rata-Rata Pendapatan/Properti</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">Rp <?php echo e(number_format($averageRevenuePerProperty ?? 0, 0, ',', '.')); ?></p>
                    </div>
                 </div>

                 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                     <div class="p-4 border dark:border-gray-700 rounded-lg">
                         <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">Distribusi Sumber Pendapatan (Periode Terfilter)</h4>
                         <div style="height: 300px;">
                             <canvas id="kpiOverallSourcePieChart"></canvas>
                         </div>
                     </div>
                     <div class="p-4 border dark:border-gray-700 rounded-lg">
                         <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">Total Pendapatan per Properti (Periode Terfilter)</h4>
                         <div style="height: 300px;">
                             <canvas id="kpiOverallIncomeByPropertyBarChart"></canvas>
                         </div>
                     </div>
                 </div>
            </div>

            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Analisis Detail per Kategori Pendapatan</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                    Periode: <?php echo e($filterStartDate->isoFormat('D MMMM YYYY')); ?> - <?php echo e($filterEndDate->isoFormat('D MMMM YYYY')); ?>

                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tren Total Pendapatan per Kategori
                             <?php if($propertyMomFilterId && $propertyMomFilterId != 'all' && isset($allPropertiesForFilter)): ?>
                                 <?php
                                     $selectedPropNameTrend = $allPropertiesForFilter->firstWhere('id', $propertyMomFilterId)->name ?? '';
                                 ?>
                                 - <?php echo e($selectedPropNameTrend); ?>

                             <?php else: ?>
                                 - Gabungan Semua Properti
                             <?php endif; ?>
                        </h4>
                        <div style="height: 350px;">
                             <?php if(!empty($trendKontribusiData['labels'])): ?>
                                 <canvas id="monthlyCategoryTrendChart"></canvas>
                             <?php else: ?>
                                 <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center justify-center h-full">Data tren tidak cukup untuk periode/properti yang dipilih.</p>
                             <?php endif; ?>
                        </div>
                    </div>

                    <div class="p-4 border dark:border-gray-700 rounded-lg space-y-2">
                        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-3">
                            Pertumbuhan Kategori Pendapatan (MoM)
                             <?php if($propertyMomFilterId && $propertyMomFilterId != 'all' && isset($allPropertiesForFilter)): ?>
                                 <?php
                                     $selectedPropNameMoM = $allPropertiesForFilter->firstWhere('id', $propertyMomFilterId)->name ?? '';
                                 ?>
                                 - <?php echo e($selectedPropNameMoM); ?>

                             <?php else: ?>
                                 - Gabungan Semua Properti
                             <?php endif; ?>
                        </h4>
                         <?php if(!empty($multiMonthCategoryGrowth)): ?>
                             <?php $__currentLoopData = $multiMonthCategoryGrowth; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $monthName => $growthData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                 <div class="mb-3 bg-gray-50 dark:bg-gray-700 p-3 rounded-md shadow-sm">
                                     <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 border-b border-gray-200 dark:border-gray-600 pb-1 mb-2"><?php echo e($monthName); ?>:</p>
                                     <?php if(!empty($growthData)): ?>
                                         <ul class="space-y-1 text-sm">
                                             <?php $__currentLoopData = $growthData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                 <li class="flex justify-between <?php echo e(str_contains($data['growth'], '+') || $data['growth'] === 'Baru' ? 'text-green-600 dark:text-green-400' : (str_contains($data['growth'], '-') ? 'text-red-600 dark:text-red-400' : 'text-gray-600 dark:text-gray-400')); ?>">
                                                     <span class="font-medium text-gray-800 dark:text-gray-300"><?php echo e($data['category']); ?>:</span>
                                                     <span class="<?php echo e(str_contains($data['growth'], '+') || $data['growth'] === 'Baru' ? 'font-semibold' : (str_contains($data['growth'], '-') ? 'font-semibold' : '')); ?>">
                                                         <?php echo e($data['growth']); ?>

                                                     </span>
                                                 </li>
                                                 <li class="flex justify-between text-xs text-gray-400 dark:text-gray-500 pl-4 mb-1">
                                                     <span>(Saat Ini: <?php echo e(number_format($data['current_value'], 0, ',', '.')); ?>;</span>
                                                     <span>Sebelumnya: <?php echo e(number_format($data['previous_value'], 0, ',', '.')); ?>)</span>
                                                 </li>
                                             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                         </ul>
                                     <?php else: ?>
                                         <p class="text-xs text-gray-500 dark:text-gray-400 italic">Tidak ada data pertumbuhan untuk bulan ini.</p>
                                     <?php endif; ?>
                                 </div>
                             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                             <p class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-600 text-xs text-gray-500 dark:text-gray-400">
                                 <strong>Catatan MoM:</strong><br>
                                 * "Baru" menandakan ada pendapatan di bulan ini & nol pada bulan pembanding (bulan sebelumnya).<br>
                                 * "Data pembanding tidak cukup" menandakan tidak ada data sama sekali pada bulan pembanding.<br>
                                 * Jika periode filter tidak mencakup seluruh bulan untuk bulan terakhir yang ditampilkan, perbandingan MoM untuk bulan tersebut mungkin menunjukkan perubahan signifikan karena membandingkan periode parsial dengan pendapatan total bulan sebelumnya secara penuh.
                             </p>
                         <?php else: ?>
                             <p class="text-sm text-gray-500 dark:text-gray-400">Data pertumbuhan MoM tidak tersedia untuk periode atau properti yang dipilih.</p>
                         <?php endif; ?>
                    </div>
                </div>
            </div>
            
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Analisis Pencapaian Target Pendapatan</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                    Periode: <?php echo e($filterStartDate->isoFormat('D MMMM YYYY')); ?> - <?php echo e($filterEndDate->isoFormat('D MMMM YYYY')); ?>

                </p>
                 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Rata-Rata Pencapaian Target</h4>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            <?php echo e(isset($targetAnalysis['average_achievement_percentage']) && $targetAnalysis['average_achievement_percentage'] !== null ? number_format($targetAnalysis['average_achievement_percentage'], 2) . '%' : 'N/A'); ?>

                        </p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Properti Pencapaian Tertinggi</h4>
                        <?php if($targetAnalysis['top_property_target']): ?>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100"><?php echo e($targetAnalysis['top_property_target']['name']); ?></p>
                            <p class="text-xl font-bold text-green-600 dark:text-green-400"><?php echo e(number_format($targetAnalysis['top_property_target']['achievement_percentage'], 2)); ?>%</p>
                        <?php else: ?>
                            <p class="text-gray-900 dark:text-gray-100">N/A</p>
                        <?php endif; ?>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Properti Pencapaian Terendah</h4>
                         <?php if($targetAnalysis['bottom_property_target']): ?>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100"><?php echo e($targetAnalysis['bottom_property_target']['name']); ?></p>
                            <p class="text-xl font-bold text-red-600 dark:text-red-400"><?php echo e(number_format($targetAnalysis['bottom_property_target']['achievement_percentage'], 2)); ?>%</p>
                        <?php else: ?>
                            <p class="text-gray-900 dark:text-gray-100">N/A</p>
                        <?php endif; ?>
                    </div>
                 </div>
                 <?php if(isset($targetAnalysis['details']) && count($targetAnalysis['details']) > 0): ?>
                 <div class="mt-6 overflow-x-auto">
                     <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">Detail Pencapaian per Properti</h4>
                     <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                         <thead class="bg-gray-50 dark:bg-gray-700">
                             <tr>
                                 <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Properti</th>
                                 <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Target (Rp)</th>
                                 <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Aktual (Rp)</th>
                                 <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pencapaian (%)</th>
                             </tr>
                         </thead>
                         <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                             <?php $__currentLoopData = $targetAnalysis['details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                             <tr>
                                 <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"><?php echo e($detail['name']); ?></td>
                                 <td class="px-4 py-2 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-300"><?php echo e(number_format($detail['total_target'],0,',','.')); ?></td>
                                 <td class="px-4 py-2 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-300"><?php echo e(number_format($detail['total_actual'],0,',','.')); ?></td>
                                 <td class="px-4 py-2 whitespace-nowrap text-sm text-right font-semibold <?php echo e($detail['achievement_percentage'] === null ? 'text-gray-500 dark:text-gray-400' : ($detail['achievement_percentage'] >= 100 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400')); ?>">
                                     <?php echo e($detail['achievement_percentage'] !== null ? number_format($detail['achievement_percentage'], 2) . '%' : 'N/A (Target 0)'); ?>

                                 </td>
                             </tr>
                             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                         </tbody>
                     </table>
                 </div>
                 <?php endif; ?>
            </div>

            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">Analisis Kepatuhan Input Data</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                    Berdasarkan periode: <?php echo e($filterStartDate->isoFormat('D MMMM YYYY')); ?> - <?php echo e($filterEndDate->isoFormat('D MMMM YYYY')); ?>

                    (Total <?php echo e($totalDaysInPeriod); ?> hari)
                </p>
                 <?php if(empty($dataCompliance['days_without_entry'])): ?>
                     <p class="text-gray-600 dark:text-gray-400">
                         Semua properti patuh melakukan entri data untuk periode yang dipilih.
                     </p>
                 <?php else: ?>
                     <div class="overflow-x-auto">
                         <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                             <thead class="bg-gray-50 dark:bg-gray-700">
                                 <tr>
                                     <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Properti</th>
                                     <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jml Hari Tanpa Entri</th>
                                     <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">% Kepatuhan</th>
                                 </tr>
                             </thead>
                             <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                 <?php $__currentLoopData = $dataCompliance['days_without_entry']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $compliance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                 <tr>
                                     <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100"><?php echo e($compliance['property_name']); ?></td>
                                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300"><?php echo e($compliance['days']); ?> dari <?php echo e($compliance['total_days_in_period']); ?> hari</td>
                                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                         <?php echo e(number_format($compliance['compliance_percentage'], 2)); ?>%
                                     </td>
                                 </tr>
                                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                             </tbody>
                         </table>
                     </div>
                 <?php endif; ?>
            </div>

        </div>
    </div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDarkMode = document.documentElement.classList.contains('dark');
    Chart.defaults.color = isDarkMode ? '#e5e7eb' : '#6b7280';
    Chart.defaults.borderColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';

    const overallIncomeSourceData = <?php echo json_encode($overallIncomeSource ?? null, 15, 512) ?>;
    const overallIncomeByPropertyData = <?php echo json_encode($overallIncomeByProperty ?? [], 15, 512) ?>;
    const trendKontribusiKategoriData = <?php echo json_encode($trendKontribusiData ?? ['labels' => [], 'datasets' => []], 512) ?>;
    const categories = <?php echo json_encode($categories ?? [], 15, 512) ?>;
    
    // 1. Diagram Pie: Distribusi Sumber Pendapatan Keseluruhan (KPI Page)
    const kpiPieChartCanvas = document.getElementById('kpiOverallSourcePieChart');
    if (kpiPieChartCanvas) {
        const pieLabels = Object.values(categories);
        const pieData = overallIncomeSourceData ? Object.keys(categories).map(key => overallIncomeSourceData['total_' + key] || 0) : [];
        const hasData = pieData.some(v => v > 0);

        if (hasData) {
            new Chart(kpiPieChartCanvas, {
                type: 'pie',
                data: {
                    labels: pieLabels,
                    datasets: [{
                        data: pieData,
                        backgroundColor: ['#e6194B', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#42d4f4', '#f032e6', '#bfef45', '#808000'],
                        borderWidth: 1
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' }, title: { display: false } } }
            });
        } else {
            const ctx = kpiPieChartCanvas.getContext('2d');
            ctx.font = '14px Figtree, sans-serif';
            ctx.fillStyle = isDarkMode ? '#cbd5e1' : '#6b7280';
            ctx.textAlign = 'center';
            ctx.fillText('Tidak ada data distribusi pendapatan.', kpiPieChartCanvas.width / 2, kpiPieChartCanvas.height / 2);
        }
    }

    // 2. Diagram Bar: Total Pendapatan per Properti (KPI Page)
    const kpiBarChartCanvas = document.getElementById('kpiOverallIncomeByPropertyBarChart');
    if (kpiBarChartCanvas) {
        const hasData = overallIncomeByPropertyData && overallIncomeByPropertyData.length > 0 && overallIncomeByPropertyData.some(p => p.total_revenue > 0);
        if (hasData) {
            new Chart(kpiBarChartCanvas, {
                type: 'bar',
                data: {
                    labels: overallIncomeByPropertyData.map(p => p.name),
                    datasets: [{
                        label: 'Total Pendapatan (Rp)',
                        data: overallIncomeByPropertyData.map(p => p.total_revenue || 0),
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); } } } }, plugins: { legend: { display: false }, title: { display: false } } }
            });
        } else if (kpiBarChartCanvas) {
            const ctx = kpiBarChartCanvas.getContext('2d');
            ctx.font = '14px Figtree, sans-serif';
            ctx.fillStyle = isDarkMode ? '#cbd5e1' : '#6b7280';
            ctx.textAlign = 'center';
            ctx.fillText('Tidak ada data pendapatan per properti.', kpiBarChartCanvas.width / 2, kpiBarChartCanvas.height / 2);
        }
    }
    
    // 3. Diagram Line: Tren Bulanan per Kategori
    const monthlyTrendCanvas = document.getElementById('monthlyCategoryTrendChart');
    if (monthlyTrendCanvas) {
        const hasData = trendKontribusiKategoriData.labels && trendKontribusiKategoriData.labels.length > 0 && trendKontribusiKategoriData.datasets.some(ds => ds.data.some(d => d > 0));
        if (hasData) {
            new Chart(monthlyTrendCanvas, {
                type: 'line',
                data: trendKontribusiKategoriData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, stacked: false, ticks: { callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); } }, title: { display: true, text: 'Total Pendapatan (Rp)' } },
                        x: { title: { display: true, text: 'Periode (Bulan)' } }
                    },
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: false },
                        tooltip: { callbacks: { label: function(context) { let label = context.dataset.label || ''; if (label) { label += ': '; } if (context.parsed.y !== null) { label += 'Rp ' + context.parsed.y.toLocaleString('id-ID'); } return label; } } }
                    }
                }
            });
        } else if (monthlyTrendCanvas) {
            const ctx = monthlyTrendCanvas.getContext('2d');
            ctx.font = '14px Figtree, sans-serif';
            ctx.fillStyle = isDarkMode ? '#cbd5e1' : '#6b7280';
            ctx.textAlign = 'center';
            ctx.fillText('Tidak ada data tren untuk periode/properti ini.', monthlyTrendCanvas.width / 2, monthlyTrendCanvas.height / 2);
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
<?php endif; ?><?php /**PATH C:\xampp\htdocs\property_finance\resources\views/admin/kpi_analysis.blade.php ENDPATH**/ ?>