<aside class="flex-shrink-0 w-64 bg-white dark:bg-gray-800 border-r dark:border-gray-700 flex flex-col">
    <div class="h-16 flex items-center justify-center border-b dark:border-gray-700 px-4">
        <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center space-x-2">
            <?php
                // Menggunakan helper untuk mendapatkan pengaturan dengan aman
                $logoPath = setting('logo_path');
                $sidebarLogoSize = setting('sidebar_logo_size', 40); // Default 40px
                $appName = setting('app_name', config('app.name', 'Laravel'));
            ?>

            <?php if($logoPath): ?>
                
                <img src="<?php echo e(asset('storage/' . $logoPath)); ?>"
                     alt="App Logo"
                     style="height: <?php echo e($sidebarLogoSize); ?>px;"
                     class="w-auto">
            <?php else: ?>
                
                <span class="text-lg font-bold text-gray-800 dark:text-gray-200"><?php echo e($appName); ?></span>
            <?php endif; ?>
        </a>
    </div>

    <nav class="flex-grow p-4 space-y-2">
        <?php if(auth()->guard()->check()): ?>
            
            
            
            <?php if(Auth::user()->role === 'admin' || Auth::user()->role === 'owner'): ?>
                <?php if (isset($component)) { $__componentOriginal2e340925a8bf40d3894bf118093fdd54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e340925a8bf40d3894bf118093fdd54 = $attributes; } ?>
<?php $component = App\View\Components\SideNavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('side-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SideNavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.dashboard')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.dashboard'))]); ?>
                     <?php $__env->slot('icon', null, []); ?> 
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                     <?php $__env->endSlot(); ?>
                    <?php echo e(__('Dashboard Admin')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $attributes = $__attributesOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $component = $__componentOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__componentOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal2e340925a8bf40d3894bf118093fdd54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e340925a8bf40d3894bf118093fdd54 = $attributes; } ?>
<?php $component = App\View\Components\SideNavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('side-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SideNavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.kpi.analysis')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.kpi.analysis'))]); ?>
                     <?php $__env->slot('icon', null, []); ?> 
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                     <?php $__env->endSlot(); ?>
                    <?php echo e(__('Pusat Analisis Kinerja')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $attributes = $__attributesOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $component = $__componentOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__componentOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal2e340925a8bf40d3894bf118093fdd54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e340925a8bf40d3894bf118093fdd54 = $attributes; } ?>
<?php $component = App\View\Components\SideNavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('side-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SideNavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.revenue-targets.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.revenue-targets.*'))]); ?>
                     <?php $__env->slot('icon', null, []); ?> 
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                     <?php $__env->endSlot(); ?>
                    <?php echo e(__('Manajemen Target')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $attributes = $__attributesOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $component = $__componentOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__componentOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal2e340925a8bf40d3894bf118093fdd54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e340925a8bf40d3894bf118093fdd54 = $attributes; } ?>
<?php $component = App\View\Components\SideNavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('side-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SideNavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.sales.analytics')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.sales.analytics'))]); ?>
                     <?php $__env->slot('icon', null, []); ?> 
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 13v-1m4 1v-3m4 3V8M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                     <?php $__env->endSlot(); ?>
                    <?php echo e(__('Analisis Sales')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $attributes = $__attributesOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $component = $__componentOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__componentOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal2e340925a8bf40d3894bf118093fdd54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e340925a8bf40d3894bf118093fdd54 = $attributes; } ?>
<?php $component = App\View\Components\SideNavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('side-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SideNavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.properties.compare_page')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.properties.compare.*'))]); ?>
                     <?php $__env->slot('icon', null, []); ?> 
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                     <?php $__env->endSlot(); ?>
                    <?php echo e(__('Bandingkan Properti')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $attributes = $__attributesOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $component = $__componentOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__componentOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal2e340925a8bf40d3894bf118093fdd54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e340925a8bf40d3894bf118093fdd54 = $attributes; } ?>
<?php $component = App\View\Components\SideNavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('side-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SideNavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.users.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.users.*'))]); ?>
                     <?php $__env->slot('icon', null, []); ?> 
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A5.995 5.995 0 0112 12.75a5.995 5.995 0 01-3 5.197m0 0A7.963 7.963 0 0012 21a7.963 7.963 0 003-5.197M15 21a6 6 0 00-9-5.197"></path></svg>
                     <?php $__env->endSlot(); ?>
                    <?php echo e(__('Manajemen Pengguna')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $attributes = $__attributesOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $component = $__componentOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__componentOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal2e340925a8bf40d3894bf118093fdd54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e340925a8bf40d3894bf118093fdd54 = $attributes; } ?>
<?php $component = App\View\Components\SideNavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('side-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SideNavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.properties.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.properties.index') || request()->routeIs('admin.properties.create') || request()->routeIs('admin.properties.edit') || request()->routeIs('admin.properties.show'))]); ?>
                     <?php $__env->slot('icon', null, []); ?> 
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                     <?php $__env->endSlot(); ?>
                    <?php echo e(__('Manajemen Properti')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $attributes = $__attributesOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $component = $__componentOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__componentOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
                
                
                <?php if (isset($component)) { $__componentOriginal2e340925a8bf40d3894bf118093fdd54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e340925a8bf40d3894bf118093fdd54 = $attributes; } ?>
<?php $component = App\View\Components\SideNavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('side-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SideNavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.mice-categories.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.mice-categories.*'))]); ?>
                     <?php $__env->slot('icon', null, []); ?> 
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.125-1.274-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.125-1.274.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                     <?php $__env->endSlot(); ?>
                    <?php echo e(__('Kategori MICE')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $attributes = $__attributesOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $component = $__componentOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__componentOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal2e340925a8bf40d3894bf118093fdd54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e340925a8bf40d3894bf118093fdd54 = $attributes; } ?>
<?php $component = App\View\Components\SideNavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('side-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SideNavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.price-packages.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.price-packages.*'))]); ?>
                      <?php $__env->slot('icon', null, []); ?> 
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                     <?php $__env->endSlot(); ?>
                    <?php echo e(__('Manajemen Harga')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $attributes = $__attributesOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $component = $__componentOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__componentOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal2e340925a8bf40d3894bf118093fdd54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e340925a8bf40d3894bf118093fdd54 = $attributes; } ?>
<?php $component = App\View\Components\SideNavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('side-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SideNavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.activity_log.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.activity_log.index'))]); ?>
                      <?php $__env->slot('icon', null, []); ?> 
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                     <?php $__env->endSlot(); ?>
                    <?php echo e(__('Log Aktivitas')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $attributes = $__attributesOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $component = $__componentOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__componentOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal2e340925a8bf40d3894bf118093fdd54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e340925a8bf40d3894bf118093fdd54 = $attributes; } ?>
<?php $component = App\View\Components\SideNavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('side-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SideNavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.calendar.unified')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.calendar.unified'))]); ?>
                     <?php $__env->slot('icon', null, []); ?> 
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                     <?php $__env->endSlot(); ?>
                    <?php echo e(__('Kalender Terpusat')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $attributes = $__attributesOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $component = $__componentOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__componentOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>

                
                <?php if (isset($component)) { $__componentOriginal2e340925a8bf40d3894bf118093fdd54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e340925a8bf40d3894bf118093fdd54 = $attributes; } ?>
<?php $component = App\View\Components\SideNavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('side-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SideNavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.inventories.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.inventories.*'))]); ?>
                     <?php $__env->slot('icon', null, []); ?> 
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-14L4 7m0 10l8 4m-8-14v10l8 4"></path></svg>
                     <?php $__env->endSlot(); ?>
                    <?php echo e(__('Inventaris')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $attributes = $__attributesOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $component = $__componentOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__componentOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>

                
                <?php if (isset($component)) { $__componentOriginal2e340925a8bf40d3894bf118093fdd54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e340925a8bf40d3894bf118093fdd54 = $attributes; } ?>
<?php $component = App\View\Components\SideNavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('side-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SideNavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.reports.amenities')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.reports.amenities'))]); ?>
                     <?php $__env->slot('icon', null, []); ?> 
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V7a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                     <?php $__env->endSlot(); ?>
                    <?php echo e(__('Laporan Amenities')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $attributes = $__attributesOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $component = $__componentOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__componentOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal2e340925a8bf40d3894bf118093fdd54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e340925a8bf40d3894bf118093fdd54 = $attributes; } ?>
<?php $component = App\View\Components\SideNavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('side-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SideNavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.settings.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('admin.settings.*'))]); ?>
                    <?php $__env->slot('icon', null, []); ?> 
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                     <?php $__env->endSlot(); ?>
                    <?php echo e(__('Pengaturan')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $attributes = $__attributesOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $component = $__componentOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__componentOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>

            
            
            
            <?php elseif(Auth::user()->role === 'hk'): ?>
                <?php if (isset($component)) { $__componentOriginal2e340925a8bf40d3894bf118093fdd54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e340925a8bf40d3894bf118093fdd54 = $attributes; } ?>
<?php $component = App\View\Components\SideNavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('side-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SideNavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('housekeeping.inventory.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('housekeeping.inventory.*'))]); ?>
                     <?php $__env->slot('icon', null, []); ?> 
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                     <?php $__env->endSlot(); ?>
                    <?php echo e(__('Inventaris Kamar')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $attributes = $__attributesOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $component = $__componentOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__componentOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
                 
                <?php if (isset($component)) { $__componentOriginal2e340925a8bf40d3894bf118093fdd54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e340925a8bf40d3894bf118093fdd54 = $attributes; } ?>
<?php $component = App\View\Components\SideNavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('side-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SideNavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('housekeeping.inventory.history')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('housekeeping.inventory.history'))]); ?>
                     <?php $__env->slot('icon', null, []); ?> 
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                     <?php $__env->endSlot(); ?>
                    <?php echo e(__('Riwayat')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $attributes = $__attributesOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $component = $__componentOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__componentOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
            
            
            
            <?php elseif(Auth::user()->role === 'sales'): ?>
                <?php if (isset($component)) { $__componentOriginal2e340925a8bf40d3894bf118093fdd54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e340925a8bf40d3894bf118093fdd54 = $attributes; } ?>
<?php $component = App\View\Components\SideNavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('side-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SideNavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('sales.dashboard')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('sales.dashboard'))]); ?>
                     <?php $__env->slot('icon', null, []); ?> 
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                     <?php $__env->endSlot(); ?>
                    <?php echo e(__('Dashboard')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $attributes = $__attributesOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $component = $__componentOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__componentOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal2e340925a8bf40d3894bf118093fdd54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e340925a8bf40d3894bf118093fdd54 = $attributes; } ?>
<?php $component = App\View\Components\SideNavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('side-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SideNavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('sales.bookings.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('sales.bookings.*'))]); ?>
                     <?php $__env->slot('icon', null, []); ?> 
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                     <?php $__env->endSlot(); ?>
                    <?php echo e(__('Master Booking')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $attributes = $__attributesOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $component = $__componentOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__componentOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal2e340925a8bf40d3894bf118093fdd54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e340925a8bf40d3894bf118093fdd54 = $attributes; } ?>
<?php $component = App\View\Components\SideNavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('side-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SideNavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('sales.calendar.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('sales.calendar.index'))]); ?>
                     <?php $__env->slot('icon', null, []); ?> 
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                     <?php $__env->endSlot(); ?>
                    <?php echo e(__('Event Calendar')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $attributes = $__attributesOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $component = $__componentOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__componentOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>

            
            
            
            <?php elseif(Auth::user()->role === 'pengguna_properti'): ?>
                <?php if (isset($component)) { $__componentOriginal2e340925a8bf40d3894bf118093fdd54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e340925a8bf40d3894bf118093fdd54 = $attributes; } ?>
<?php $component = App\View\Components\SideNavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('side-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SideNavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('property.dashboard')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('property.dashboard'))]); ?>
                     <?php $__env->slot('icon', null, []); ?> 
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                     <?php $__env->endSlot(); ?>
                    <?php echo e(__('Dashboard Properti')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $attributes = $__attributesOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $component = $__componentOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__componentOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal2e340925a8bf40d3894bf118093fdd54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e340925a8bf40d3894bf118093fdd54 = $attributes; } ?>
<?php $component = App\View\Components\SideNavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('side-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SideNavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('property.calendar.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('property.calendar.index'))]); ?>
                     <?php $__env->slot('icon', null, []); ?> 
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                     <?php $__env->endSlot(); ?>
                    <?php echo e(__('Kalender')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $attributes = $__attributesOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $component = $__componentOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__componentOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal2e340925a8bf40d3894bf118093fdd54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e340925a8bf40d3894bf118093fdd54 = $attributes; } ?>
<?php $component = App\View\Components\SideNavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('side-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SideNavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('property.reservations.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('property.reservations.*'))]); ?>
                     <?php $__env->slot('icon', null, []); ?> 
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002-2h2a2 2 0 002 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                     <?php $__env->endSlot(); ?>
                    <?php echo e(__('Reservasi')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $attributes = $__attributesOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $component = $__componentOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__componentOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>

                <?php if (isset($component)) { $__componentOriginal2e340925a8bf40d3894bf118093fdd54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e340925a8bf40d3894bf118093fdd54 = $attributes; } ?>
<?php $component = App\View\Components\SideNavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('side-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SideNavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('property.income.index')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('property.income.*'))]); ?>
                      <?php $__env->slot('icon', null, []); ?> 
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                     <?php $__env->endSlot(); ?>
                    <?php echo e(__('Pendapatan')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $attributes = $__attributesOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $component = $__componentOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__componentOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>

            
            
            
            <?php elseif(Auth::user()->role === 'online_ecommerce'): ?>
                <?php if (isset($component)) { $__componentOriginal2e340925a8bf40d3894bf118093fdd54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2e340925a8bf40d3894bf118093fdd54 = $attributes; } ?>
<?php $component = App\View\Components\SideNavLink::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('side-nav-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\SideNavLink::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('ecommerce.dashboard')),'active' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request()->routeIs('ecommerce.dashboard'))]); ?>
                     <?php $__env->slot('icon', null, []); ?> 
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                     <?php $__env->endSlot(); ?>
                    <?php echo e(__('Dashboard')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $attributes = $__attributesOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__attributesOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2e340925a8bf40d3894bf118093fdd54)): ?>
<?php $component = $__componentOriginal2e340925a8bf40d3894bf118093fdd54; ?>
<?php unset($__componentOriginal2e340925a8bf40d3894bf118093fdd54); ?>
<?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
    </nav>
</aside><?php /**PATH C:\xampp\htdocs\HotelTracker\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>