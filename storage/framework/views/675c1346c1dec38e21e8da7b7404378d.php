<?php
// Ambil pengaturan yang sudah kita share dari AppServiceProvider
$logoPath = $appSettings['logo_path'] ?? null;
$logoSize = $appSettings['logo_size'] ?? 80; // Default ukuran 80px jika tidak ada pengaturan
$appName = $appSettings['app_name'] ?? config('app.name', 'Laravel');
?>

<?php if($logoPath): ?>
    
    <img src="<?php echo e(asset('storage/' . $logoPath)); ?>" 
         style="height: <?php echo e($logoSize); ?>px;" 
         <?php echo e($attributes->merge(['alt' => 'Logo', 'class' => 'w-auto'])); ?>>
<?php else: ?>
    
    <span <?php echo e($attributes->merge(['class' => 'text-gray-800 dark:text-gray-200 text-2xl font-extrabold'])); ?>>
        <?php echo e($appName); ?>

    </span>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\HotelTracker\resources\views/components/application-logo.blade.php ENDPATH**/ ?>