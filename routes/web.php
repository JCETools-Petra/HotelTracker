<?php

// routes/web.php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyIncomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RevenueTargetController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute halaman utama
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'admin' || $user->role === 'owner') { // Ditambahkan 'owner'
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'pengguna_properti') {
            return redirect()->route('property.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role === 'admin' || $user->role === 'owner') { // Ditambahkan 'owner'
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'pengguna_properti') {
            return redirect()->route('property.dashboard');
        }
        abort(403, 'Tidak ada dashboard yang sesuai untuk peran Anda.');
    })->name('dashboard');

    // Rute untuk pengguna properti (Tidak berubah)
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

    // ================== PERUBAHAN UTAMA DI SINI ==================
    // Middleware diubah untuk mengizinkan admin DAN owner
    Route::middleware(['role:admin,owner'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            Route::get('/dashboard/export/excel', [AdminDashboardController::class, 'exportPropertiesSummaryExcel'])->name('dashboard.export.excel');
            Route::get('/dashboard/export/csv', [AdminDashboardController::class, 'exportPropertiesSummaryCsv'])->name('dashboard.export.csv');

            Route::get('/properties/compare', [AdminPropertyController::class, 'showComparisonForm'])->name('properties.compare.form');
            Route::get('/properties/compare/results', [AdminPropertyController::class, 'showComparisonResults'])->name('properties.compare.results');
            Route::get('/properties/{property}/export/excel', [AdminPropertyController::class, 'exportPropertyDetailsExcel'])->name('properties.export.excel');
            Route::get('/properties/{property}/export/csv', [AdminPropertyController::class, 'exportPropertyDetailsCsv'])->name('properties.export.csv');
            
            Route::get('/chart-data/overall', [AdminDashboardController::class, 'overallChartData'])->name('chart.overall');
            Route::get('/chart-data/property/{property}', [AdminDashboardController::class, 'propertyChartData'])->name('chart.property');
            
            Route::resource('users', AdminUserController::class)->except(['show']);
            Route::get('users/trashed', [AdminUserController::class, 'trashedIndex'])->name('users.trashed');
            Route::put('users/{id}/restore', [AdminUserController::class, 'restore'])->name('users.restore');  
            Route::delete('users/{id}/force-delete', [AdminUserController::class, 'forceDelete'])->name('users.forceDelete');
            Route::get('/kpi-analysis', [AdminDashboardController::class, 'kpiAnalysis'])->name('kpi.analysis');
            Route::resource('revenue-targets', RevenueTargetController::class);

            // Hanya satu Route::resource untuk 'properties' agar tidak duplikat
            Route::resource('properties', AdminPropertyController::class);
        });
    // ================== AKHIR PERUBAHAN ==================
});

require __DIR__.'/auth.php';