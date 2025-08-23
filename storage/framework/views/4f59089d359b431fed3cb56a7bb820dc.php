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
                <?php echo e(__('Riwayat Pendapatan Harian untuk ')); ?> <?php echo e($property->name); ?>

            </h2>
            <nav class="flex flex-wrap items-center space-x-2 sm:space-x-3">
                <?php if (isset($component)) { $__componentOriginalc295f12dca9d42f28a259237a5724830 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc295f12dca9d42f28a259237a5724830 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nav-link','data' => ['href' => route('property.dashboard'),'class' => 'ml-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('property.dashboard')),'class' => 'ml-3']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.nav-link','data' => ['href' => route('property.income.create'),'class' => 'ml-3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('property.income.create')),'class' => 'ml-3']); ?>
                    <?php echo e(__('+ Catat Pendapatan')); ?>

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

    <div class="py-12" x-data="{ showDetailModal: false, selectedIncome: null }">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <?php if(session('success')): ?>
                        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-700 border border-green-400 dark:border-green-600 rounded-md p-3">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <form method="GET" action="<?php echo e(route('property.income.index')); ?>" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg shadow">
                        <div class="flex flex-col md:flex-row md:items-end md:space-x-4 space-y-4 md:space-y-0">
                            <div class="flex-1">
                                <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
                                <input type="date" name="start_date" id="start_date" value="<?php echo e(request('start_date')); ?>" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="flex-1">
                                <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Selesai</label>
                                <input type="date" name="end_date" id="end_date" value="<?php echo e(request('end_date')); ?>" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Filter</button>
                                <a href="<?php echo e(route('property.income.index')); ?>" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">Reset</a>
                            </div>
                        </div>
                    </form>
                    
                    <?php if(!$incomes->isEmpty()): ?>
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
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"><?php echo e(\Carbon\Carbon::parse($income->date)->isoFormat('dddd, D MMMM YYYY')); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-right text-gray-900 dark:text-gray-100">Rp <?php echo e(number_format($income->total_revenue, 0, ',', '.')); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center space-x-4">
                                            <button @click="selectedIncome = <?php echo e(Js::from($income)); ?>; showDetailModal = true" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 font-medium">Lihat Detail</button>
                                            <a href="<?php echo e(route('property.income.edit', $income->id)); ?>" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 font-medium">Edit</a>
                                            <form method="POST" action="<?php echo e(route('property.income.destroy', $income->id)); ?>" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200 font-medium">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            <?php echo e($incomes->links()); ?>

                        </div>
                    <?php else: ?>
                        <p class="text-center text-gray-500 dark:text-gray-400 py-4">Belum ada data pendapatan yang tercatat.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div x-show="showDetailModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
            <div @click.away="showDetailModal = false" x-show="showDetailModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-3xl mx-4 max-h-[90vh] overflow-y-auto">
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
                            <p><strong class="w-32 inline-block">MICE:</strong> <span x-text="formatCurrency(selectedIncome.mice_income)"></span></p>
                            
                            
                            <div class="pl-4 border-l-2 dark:border-gray-600">
                                <p><strong class="w-28 inline-block">F&B (Total):</strong> <strong x-text="formatCurrency(parseFloat(selectedIncome.breakfast_income || 0) + parseFloat(selectedIncome.lunch_income || 0) + parseFloat(selectedIncome.dinner_income || 0))"></strong></p>
                                <p class="text-xs text-gray-500"><span class="w-28 inline-block ml-2">- Breakfast:</span> <span x-text="formatCurrency(selectedIncome.breakfast_income)"></span></p>
                                <p class="text-xs text-gray-500"><span class="w-28 inline-block ml-2">- Lunch:</span> <span x-text="formatCurrency(selectedIncome.lunch_income)"></span></p>
                                <p class="text-xs text-gray-500"><span class="w-28 inline-block ml-2">- Dinner:</span> <span x-text="formatCurrency(selectedIncome.dinner_income)"></span></p>
                            </div>
                            
                            <p><strong class="w-32 inline-block">Lainnya:</strong> <span x-text="formatCurrency(selectedIncome.others_income)"></span></p>
                            
                            <p class="mt-4 pt-3 border-t dark:border-gray-600"><strong class="w-32 inline-block font-bold text-lg">TOTAL:</strong> <strong class="text-lg" x-text="formatCurrency(selectedIncome.total_revenue)"></strong></p>
                        </div>
                    </div>

                    <div class="mt-6 text-right">
                        <button @click="showDetailModal = false" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300">Tutup</button>
                    </div>
                </div>
            </div>
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
<?php endif; ?><?php /**PATH /home/apsx2353/public_html/hoteliermarket.my.id/resources/views/property/income/index.blade.php ENDPATH**/ ?>