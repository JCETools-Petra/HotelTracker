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
                <a href="<?php echo e(route('admin.properties.edit', $property->id)); ?>"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <?php echo e(__('Edit Properti')); ?>

                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'start_date','class' => 'block mt-1 w-full','type' => 'date','name' => 'start_date','value' => request('start_date', $displayStartDate ? $displayStartDate->toDateString() : ($startDate ? $startDate->toDateString() : ''))]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'start_date','class' => 'block mt-1 w-full','type' => 'date','name' => 'start_date','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request('start_date', $displayStartDate ? $displayStartDate->toDateString() : ($startDate ? $startDate->toDateString() : '')))]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'end_date','class' => 'block mt-1 w-full','type' => 'date','name' => 'end_date','value' => request('end_date', $displayEndDate ? $displayEndDate->toDateString() : ($endDate ? $endDate->toDateString() : ''))]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'end_date','class' => 'block mt-1 w-full','type' => 'date','name' => 'end_date','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request('end_date', $displayEndDate ? $displayEndDate->toDateString() : ($endDate ? $endDate->toDateString() : '')))]); ?>
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
<?php $component->withAttributes(['type' => 'submit']); ?>
                                <?php echo e(__('Filter')); ?>

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
                            <a href="<?php echo e(route('admin.properties.show', $property->id)); ?>"
                               class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <?php echo e(__('Reset')); ?>

                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                    Ringkasan Pendapatan <?php echo e($property->name); ?>

                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                     
                     <?php if($displayStartDate && $displayEndDate): ?>
                        Periode: <?php echo e($displayStartDate->isoFormat('D MMMM YYYY')); ?> - <?php echo e($displayEndDate->isoFormat('D MMMM YYYY')); ?>

                        <?php if($periodIsOneMonth): ?>
                            <span class="text-green-600 dark:text-green-400">(Periode 1 Bulan Penuh)</span>
                        <?php endif; ?>
                    <?php elseif($displayStartDate): ?>
                        Periode: Dari <?php echo e($displayStartDate->isoFormat('D MMMM YYYY')); ?>

                    <?php elseif($displayEndDate): ?>
                        Periode: Sampai <?php echo e($displayEndDate->isoFormat('D MMMM YYYY')); ?>

                    <?php else: ?>
                        (Menampilkan data 30 hari terakhir untuk tren, dan keseluruhan untuk distribusi jika tidak ada filter)
                    <?php endif; ?>
                </p>

                <div class="mb-6 p-4 bg-indigo-50 dark:bg-indigo-900 rounded-lg">
                    <h4 class="text-lg font-medium text-indigo-700 dark:text-indigo-300">Total Pendapatan (Periode Terfilter):</h4>
                    <p class="text-3xl font-bold text-indigo-800 dark:text-indigo-200">
                        Rp <?php echo e(number_format($totalPropertyRevenueFiltered, 0, ',', '.')); ?>

                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Distribusi Sumber Pendapatan
                        </h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                            <?php if($displayStartDate && $displayEndDate): ?>
                                <span class="block sm:inline">Periode: <?php echo e($displayStartDate->isoFormat('D MMM YY')); ?> - <?php echo e($displayEndDate->isoFormat('D MMM YY')); ?></span>
                            <?php elseif($displayStartDate): ?>
                                <span class="block sm:inline">Periode: Dari <?php echo e($displayStartDate->isoFormat('D MMM YY')); ?></span>
                            <?php elseif($displayEndDate): ?>
                                <span class="block sm:inline">Periode: Sampai <?php echo e($displayEndDate->isoFormat('D MMM YY')); ?></span>
                            <?php else: ?>
                                 <span class="block sm:inline">(Keseluruhan Data Properti)</span>
                            <?php endif; ?>
                        </p>
                        <div style="height: 350px;">
                             <canvas id="propertySourceDistributionPieChart"></canvas>
                        </div>
                    </div>

                    <div class="p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Tren Pendapatan Harian
                        </h4>
                         <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                            <?php if($displayStartDate && $displayEndDate): ?>
                                <span class="block sm:inline">Periode: <?php echo e($displayStartDate->isoFormat('D MMM YY')); ?> - <?php echo e($displayEndDate->isoFormat('D MMM YY')); ?></span>
                            <?php elseif($displayStartDate): ?>
                                <span class="block sm:inline">Periode: Dari <?php echo e($displayStartDate->isoFormat('D MMM YY')); ?></span>
                            <?php elseif($displayEndDate): ?>
                                <span class="block sm:inline">Periode: Sampai <?php echo e($displayEndDate->isoFormat('D MMM YY')); ?></span>
                            <?php else: ?>
                                <span class="block sm:inline">(30 Hari Terakhir)</span>
                            <?php endif; ?>
                        </p>
                        <div style="height: 350px;">
                            <canvas id="propertyDailyTrendLineChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Riwayat Pendapatan Harian
                </h3>
                <?php if($incomes->isEmpty()): ?>
                    <p class="text-gray-600 dark:text-gray-400">Tidak ada data pendapatan untuk periode yang dipilih.</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">MICE (Rp)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">F&B (Rp)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">K. Offline (Rp)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">K. Online (Rp)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Lainnya (Rp)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total (Rp)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dicatat Oleh</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <?php $__currentLoopData = $incomes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $income): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"><?php echo e(Carbon\Carbon::parse($income->date)->isoFormat('dddd, D MMM YYYY')); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right"><?php echo e(number_format($income->mice_income, 0, ',', '.')); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right"><?php echo e(number_format($income->fnb_income, 0, ',', '.')); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right"><?php echo e(number_format($income->offline_room_income, 0, ',', '.')); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right"><?php echo e(number_format($income->online_room_income, 0, ',', '.')); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-right"><?php echo e(number_format($income->others_income, 0, ',', '.')); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700 dark:text-gray-200 text-right">
                                            <?php echo e(number_format(
                                                ($income->mice_income ?? 0) +
                                                ($income->fnb_income ?? 0) +
                                                ($income->offline_room_income ?? 0) +
                                                ($income->online_room_income ?? 0) +
                                                ($income->others_income ?? 0), 0, ',', '.')); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300"><?php echo e($income->user->name ?? 'N/A'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        <?php echo e($incomes->appends(request()->query())->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Menggunakan variabel $dailyTrend yang dikirim dari controller
    const propertyDailyTrendData = <?php echo json_encode($dailyTrend, 15, 512) ?>;
    // Menggunakan variabel $sourceDistribution yang dikirim dari controller
    const propertyIncomeSourceData = <?php echo json_encode($sourceDistribution, 15, 512) ?>;

    // Grafik Tren Pendapatan Harian Properti
    const dailyTrendCanvas = document.getElementById('propertyDailyTrendLineChart');
    if (dailyTrendCanvas && propertyDailyTrendData && propertyDailyTrendData.length > 0) {
        new Chart(dailyTrendCanvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: propertyDailyTrendData.map(item => new Date(item.date).toLocaleDateString('id-ID', { day: '2-digit', month: 'short' })),
                datasets: [{
                    label: 'Total Pendapatan Harian (Rp)',
                    data: propertyDailyTrendData.map(item => item.total_daily_income),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); } } },
                    x: { ticks: { autoSkip: true, maxTicksLimit: 15 } } // Batasi jumlah label tanggal agar tidak terlalu padat
                },
                plugins: { legend: { display: true, position: 'bottom' } }
            }
        });
    } else if(dailyTrendCanvas) {
        const ctxTrend = dailyTrendCanvas.getContext('2d');
        ctxTrend.font = '16px Figtree, sans-serif'; // Sesuaikan dengan font Anda
        ctxTrend.fillStyle = document.body.classList.contains('dark') ? '#cbd5e1' : '#4b5563'; // Warna teks sesuai tema
        ctxTrend.textAlign = 'center';
        ctxTrend.fillText('Tidak ada data tren pendapatan untuk periode ini.', dailyTrendCanvas.width / 2, dailyTrendCanvas.height / 2);
    }

    // Grafik Distribusi Sumber Pendapatan Properti
    const sourcePieCanvas = document.getElementById('propertySourceDistributionPieChart');
    // Memastikan propertyIncomeSourceData tidak null sebelum mengakses propertinya
    if (sourcePieCanvas && propertyIncomeSourceData && (parseFloat(propertyIncomeSourceData.total_mice) > 0 || parseFloat(propertyIncomeSourceData.total_fnb) > 0 || parseFloat(propertyIncomeSourceData.total_offline_room) > 0 || parseFloat(propertyIncomeSourceData.total_online_room) > 0 || parseFloat(propertyIncomeSourceData.total_others) > 0)) {
        new Chart(sourcePieCanvas.getContext('2d'), {
            type: 'pie',
            data: {
                labels: ['MICE', 'F&B', 'Kamar Offline', 'Kamar Online', 'Lainnya'],
                datasets: [{
                    label: 'Distribusi Pendapatan',
                    data: [
                        propertyIncomeSourceData.total_mice || 0,
                        propertyIncomeSourceData.total_fnb || 0,
                        propertyIncomeSourceData.total_offline_room || 0,
                        propertyIncomeSourceData.total_online_room || 0,
                        propertyIncomeSourceData.total_others || 0
                    ],
                    backgroundColor: ['rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)', 'rgba(153, 102, 255, 0.7)'],
                    borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += 'Rp ' + context.parsed.toLocaleString('id-ID');
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    } else if(sourcePieCanvas) {
        const ctxPie = sourcePieCanvas.getContext('2d');
        ctxPie.font = '16px Figtree, sans-serif'; // Sesuaikan dengan font Anda
        ctxPie.fillStyle = document.body.classList.contains('dark') ? '#cbd5e1' : '#4b5563'; // Warna teks sesuai tema
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
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\property_finance\resources\views/admin/properties/show.blade.php ENDPATH**/ ?>