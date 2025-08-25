<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        
        
        
        <title><?php echo e(setting('app_name', config('app.name', 'Laravel'))); ?></title>
        
        <?php if(setting('favicon_path')): ?>
            <link rel="icon" href="<?php echo e(asset('storage/' . setting('favicon_path'))); ?>" type="image/png">
        <?php else: ?>
            
            <link rel="icon" href="<?php echo e(asset('favicon.ico')); ?>" type="image/x-icon">
        <?php endif; ?>
        
        
        

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div class="mb-4">
                <a href="/">
                    <?php
                        // Menggunakan helper untuk mendapatkan pengaturan logo
                        $logoPath = setting('logo_path');
                        $logoSize = setting('logo_size', 100); // Default 100px
                        $appName = setting('app_name', config('app.name', 'Laravel'));
                    ?>
                    <?php if($logoPath): ?>
                        
                        <img src="<?php echo e(asset('storage/' . $logoPath)); ?>" alt="<?php echo e($appName); ?>" style="height: <?php echo e($logoSize); ?>px;" class="w-auto">
                    <?php else: ?>
                        
                        <span class="text-2xl font-bold text-gray-800 dark:text-gray-200"><?php echo e($appName); ?></span>
                    <?php endif; ?>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                <?php echo e($slot); ?>

            </div>
        </div>
    </body>
</html><?php /**PATH C:\xampp\htdocs\HotelTracker\resources\views/layouts/guest.blade.php ENDPATH**/ ?>