<?php

// routes/web.php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyIncomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController; // Pastikan ini di-import
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log; // Opsional
use App\Http\Controllers\Admin\UserController as AdminUserController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute halaman utama
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        // Log::info('[ROOT /] User accessing. Role: ' . ($user->role ?? 'N/A')); // Baris Log dihapus untuk keringkasan
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'pengguna_properti') {
            return redirect()->route('property.dashboard');
        }
        // Log::warning('[ROOT /] User ID: ' . $user->id . ' with role: "' . $user->role . '" - role not matched for specific dashboard. Redirecting to general dashboard route.'); // Baris Log dihapus
        return redirect()->route('dashboard');
    }
    return view('auth.login'); // Mengarahkan ke login jika belum terautentikasi dari root
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', function () {
        $user = Auth::user();
        // Log::info('[/dashboard GENERAL] User accessing. Role: ' . ($user->role ?? 'N/A')); // Baris Log dihapus
        if ($user->role === 'admin') {
            // Log::info('[/dashboard GENERAL] Redirecting admin user to admin.dashboard.'); // Baris Log dihapus
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'pengguna_properti') {
            // Log::info('[/dashboard GENERAL] Redirecting property user to property.dashboard.'); // Baris Log dihapus
            return redirect()->route('property.dashboard');
        }
        // Log::warning('[/dashboard GENERAL] User ID: ' . $user->id . ' with role: "' . $user->role . '" - no matching dashboard redirect. Aborting.'); // Baris Log dihapus
        abort(403, 'Tidak ada dashboard yang sesuai untuk peran Anda atau peran tidak dikenali dari /dashboard.');
    })->name('dashboard');

    Route::middleware(['role:pengguna_properti'])
          ->prefix('property')
          ->name('property.')
          ->group(function () {
        Route::get('/dashboard', [PropertyIncomeController::class, 'dashboard'])->name('dashboard');
        Route::get('/income/create', [PropertyIncomeController::class, 'create'])->name('income.create');
        Route::post('/income', [PropertyIncomeController::class, 'store'])->name('income.store');
        Route::get('/income', [PropertyIncomeController::class, 'index'])->name('income.index');
        Route::get('/income/{dailyIncome}/edit', [PropertyIncomeController::class, 'edit'])->name('income.edit');
        Route::put('/income/{dailyIncome}', [PropertyIncomeController::class, 'update'])->name('income.update');
        Route::delete('/income/{dailyIncome}', [PropertyIncomeController::class, 'destroy'])->name('income.destroy');
        Route::get('/income/export/excel', [PropertyIncomeController::class, 'exportIncomesExcel'])->name('income.export.excel');
        Route::get('/income/export/csv', [PropertyIncomeController::class, 'exportIncomesCsv'])->name('income.export.csv');
    });

    Route::middleware(['role:admin'])
          ->prefix('admin')
          ->name('admin.')
          ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/export/excel', [AdminDashboardController::class, 'exportPropertiesSummaryExcel'])->name('dashboard.export.excel');
        Route::get('/dashboard/export/csv', [AdminDashboardController::class, 'exportPropertiesSummaryCsv'])->name('dashboard.export.csv');

        // Rute kustom untuk Properti (ini sudah ada dan sebaiknya dipertahankan jika berbeda dari resource)
        Route::get('/properties/compare', [AdminPropertyController::class, 'showComparisonForm'])->name('properties.compare.form');
        Route::get('/properties/compare/results', [AdminPropertyController::class, 'showComparisonResults'])->name('properties.compare.results');
        // Rute detail dan ekspor per properti sekarang akan ditangani oleh resource atau didefinisikan setelahnya jika perlu kustomisasi
        // Route::get('/properties/{property}', [AdminPropertyController::class, 'show'])->name('properties.show'); // Akan dibuat oleh resource
        Route::get('/properties/{property}/export/excel', [AdminPropertyController::class, 'exportPropertyDetailsExcel'])->name('properties.export.excel');
        Route::get('/properties/{property}/export/csv', [AdminPropertyController::class, 'exportPropertyDetailsCsv'])->name('properties.export.csv');
        
        Route::get('/chart-data/overall', [AdminDashboardController::class, 'overallChartData'])->name('chart.overall');
        Route::get('/chart-data/property/{property}', [AdminDashboardController::class, 'propertyChartData'])->name('chart.property');
        
        Route::resource('users', AdminUserController::class)->except(['show']);
        Route::get('users/trashed', [AdminUserController::class, 'trashedIndex'])->name('users.trashed');
        Route::put('users/{id}/restore', [AdminUserController::class, 'restore'])->name('users.restore');  
        Route::delete('users/{id}/force-delete', [AdminUserController::class, 'forceDelete'])->name('users.forceDelete'); // Ditambahkan dari respons sebelumnya

        // --- HANYA SATU DEFINISI ROUTE::RESOURCE UNTUK PROPERTIES ---
        Route::resource('properties', AdminPropertyController::class)->except([
            // 'destroy' // Aktifkan jika Anda sudah siap dengan logika destroy
        ]);
        // Baris Route::get('/properties/create', ...) yang duplikat sudah dihapus karena dicakup oleh Route::resource
    });

});

require __DIR__.'/auth.php';