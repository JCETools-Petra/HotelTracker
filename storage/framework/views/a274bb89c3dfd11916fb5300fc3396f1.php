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
                <?php echo e(__('Admin Dashboard')); ?>

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
                    <?php echo e(__('Dashboard')); ?>

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
                <?php if (isset($component)) { $__componentOriginalc295f12dca9d42f28a259237a5724830 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc295f12dca9d42f28a259237a5724830 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nav-link','data' => ['href' => route('admin.properties.compare.form'),'active' => request()->routeIs('admin.properties.compare.form') || request()->routeIs('admin.properties.compare.results')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.properties.compare.form')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.properties.compare.form') || request()->routeIs('admin.properties.compare.results'))]); ?>
                    <?php echo e(__('Bandingkan Properti')); ?>

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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Ringkasan Pendapatan Keseluruhan</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">Distribusi Sumber Pendapatan (Semua Properti)</h4>
                        <div style="height: 300px;">
                            <canvas id="overallSourcePieChart"></canvas>
                        </div>
                    </div>
                    <div class="p-4 border dark:border-gray-700 rounded-lg">
                        <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-2">Total Pendapatan per Properti</h4>
                        <div style="height: 300px;">
                            <canvas id="overallIncomeByPropertyBarChart"></canvas>
                        </div>
                    </div>
                </div>

                
                <div class="flex flex-wrap justify-between items-center mt-8 mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Daftar Properti</h3>
                    
                    <?php if(!$properties->isEmpty()): ?>
                    <div class="flex space-x-2">
                        <a href="<?php echo e(route('admin.dashboard.export.excel')); ?>"
                           class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Export Excel
                        </a>
                        <a href="<?php echo e(route('admin.dashboard.export.csv')); ?>"
                           class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Export CSV
                        </a>
                    </div>
                    <?php endif; ?>
                    
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php $__empty_1 = true; $__currentLoopData = $properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                            <h4 class="font-semibold text-lg text-gray-900 dark:text-gray-100"><?php echo e($property->name); ?></h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Catatan: <?php echo e($property->total_income_records); ?></p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">MICE: Rp <?php echo e(number_format($property->total_mice_income ?? 0, 0, ',', '.')); ?></p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">F&B: Rp <?php echo e(number_format($property->total_fnb_income ?? 0, 0, ',', '.')); ?></p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">K. Offline: Rp <?php echo e(number_format($property->total_offline_room_income ?? 0, 0, ',', '.')); ?></p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">K. Online: Rp <?php echo e(number_format($property->total_online_room_income ?? 0, 0, ',', '.')); ?></p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Lainnya: Rp <?php echo e(number_format($property->total_others_income ?? 0, 0, ',', '.')); ?></p>
                            <a href="<?php echo e(route('admin.properties.show', $property)); ?>" class="inline-block mt-2 text-sm text-blue-600 dark:text-blue-400 hover:underline">Lihat Detail</a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-gray-600 dark:text-gray-400">Tidak ada properti yang ditemukan.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php $__env->startPush('scripts'); ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('Admin Dashboard DOMContentLoaded'); // Debugging

    const overallIncomeSourceData = <?php echo json_encode($overallIncomeSource, 15, 512) ?>;
    const overallIncomeByPropertyData = <?php echo json_encode($overallIncomeByProperty, 15, 512) ?>;

    console.log('Overall Income Source Data:', overallIncomeSourceData);
    console.log('Overall Income By Property Data:', overallIncomeByPropertyData);

    // 1. Diagram Pie: Distribusi Sumber Pendapatan Keseluruhan
    const overallSourceCanvas = document.getElementById('overallSourcePieChart');
    if (overallSourceCanvas) {
        if (overallIncomeSourceData && (overallIncomeSourceData.total_mice || overallIncomeSourceData.total_fnb || overallIncomeSourceData.total_offline_room || overallIncomeSourceData.total_online_room || overallIncomeSourceData.total_others)) {
            console.log('Rendering Overall Source Pie Chart');
            const overallSourceCtx = overallSourceCanvas.getContext('2d');
            new Chart(overallSourceCtx, {
                type: 'pie',
                data: {
                    labels: ['MICE', 'F&B', 'Kamar Offline', 'Kamar Online', 'Lainnya'],
                    datasets: [{
                        label: 'Distribusi Pendapatan',
                        data: [
                            overallIncomeSourceData.total_mice || 0,
                            overallIncomeSourceData.total_fnb || 0,
                            overallIncomeSourceData.total_offline_room || 0,
                            overallIncomeSourceData.total_online_room || 0,
                            overallIncomeSourceData.total_others || 0
                        ],
                        backgroundColor: ['rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)', 'rgba(153, 102, 255, 0.7)'],
                        borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'],
                        borderWidth: 1
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' }, title: { display: true, text: 'Distribusi Sumber Pendapatan (Semua Properti)' } } }
            });
        } else {
            console.log('No data for Overall Source Pie Chart or canvas not found.');
            const ctx = overallSourceCanvas.getContext('2d');
            ctx.font = '16px Figtree, sans-serif';
            ctx.fillStyle = document.body.classList.contains('dark') ? '#cbd5e1' : '#4b5563'; // Sesuaikan warna dengan tema
            ctx.textAlign = 'center';
            ctx.fillText('Tidak ada data sumber pendapatan keseluruhan.', overallSourceCanvas.width / 2, overallSourceCanvas.height / 2);
        }
    } else {
        console.error('Canvas element with ID "overallSourcePieChart" not found.');
    }

    // 2. Diagram Bar: Total Pendapatan per Properti
    const overallIncomeByPropertyCanvas = document.getElementById('overallIncomeByPropertyBarChart');
    if (overallIncomeByPropertyCanvas) {
        if (overallIncomeByPropertyData && overallIncomeByPropertyData.length > 0) {
            console.log('Rendering Overall Income By Property Bar Chart');
            const propertyLabels = overallIncomeByPropertyData.map(p => p.name);
            const propertyRevenues = overallIncomeByPropertyData.map(p => p.total_revenue || 0);
            const overallIncomeByPropertyCtx = overallIncomeByPropertyCanvas.getContext('2d');
            new Chart(overallIncomeByPropertyCtx, {
                type: 'bar',
                data: {
                    labels: propertyLabels,
                    datasets: [{
                        label: 'Total Pendapatan (Rp)',
                        data: propertyRevenues,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); } } } }, plugins: { legend: { display: false }, title: { display: true, text: 'Total Pendapatan per Properti' } } }
            });
        } else {
            console.log('No data for Overall Income By Property Bar Chart or canvas not found.');
            const ctx = overallIncomeByPropertyCanvas.getContext('2d');
            ctx.font = '16px Figtree, sans-serif';
            ctx.fillStyle = document.body.classList.contains('dark') ? '#cbd5e1' : '#4b5563';
            ctx.textAlign = 'center';
            ctx.fillText('Tidak ada data pendapatan per properti.', overallIncomeByPropertyCanvas.width / 2, overallIncomeByPropertyCanvas.height / 2);
        }
    } else {
        console.error('Canvas element with ID "overallIncomeByPropertyBarChart" not found.');
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
<?php endif; ?><?php /**PATH C:\xampp\htdocs\property_finance\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>