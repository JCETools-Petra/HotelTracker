<?php
// app/Providers/AppServiceProvider.php
namespace App\Providers;

use Illuminate\Support\Facades\View; // <-- Tambahkan ini
use Illuminate\Support\Facades\Cache; // <-- Tambahkan ini
use App\Models\Setting; // <-- Tambahkan ini
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void { /* ... */ }

    public function boot(): void
    {
        // [BARU] Kirim data pengaturan logo ke sidebar setiap kali view-nya dimuat
        View::composer('layouts.sidebar', function ($view) {
            $settings = Cache::remember('app_settings', 60, function () {
                return Setting::pluck('value', 'key');
            });
            $view->with('appSettings', $settings);
        });
    }
}