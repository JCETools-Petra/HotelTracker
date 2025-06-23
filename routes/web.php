<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PricePackageController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Admin\RevenueTargetController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyIncomeController;
use App\Http\Controllers\Sales\BookingController;
use App\Http\Controllers\Sales\CalendarController;
use App\Http\Controllers\Sales\DashboardController as SalesDashboardController;
use App\Http\Controllers\Sales\DocumentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->role === 'admin' || $user->role === 'owner') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'pengguna_properti') {
            return redirect()->route('property.dashboard');
        } elseif ($user->role === 'sales') {
            return redirect()->route('sales.dashboard');
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
        if ($user->role === 'admin' || $user->role === 'owner') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'pengguna_properti') {
            return redirect()->route('property.dashboard');
        } elseif ($user->role === 'sales') {
            return redirect()->route('sales.dashboard');
        }
        abort(403, 'Tidak ada dashboard yang sesuai untuk peran Anda.');
    })->name('dashboard');

    // Rute untuk pengguna properti
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

    // Rute untuk Admin dan Owner
    Route::middleware(['role:admin,owner'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            Route::get('/dashboard/export/excel', [AdminDashboardController::class, 'exportPropertiesSummaryExcel'])->name('dashboard.export.excel');
            Route::get('/dashboard/export/csv', [AdminDashboardController::class, 'exportPropertiesSummaryCsv'])->name('dashboard.export.csv');
            Route::get('/sales-analytics', [AdminDashboardController::class, 'salesAnalytics'])->name('sales.analytics');
            Route::get('/kpi-analysis', [AdminDashboardController::class, 'kpiAnalysis'])->name('kpi.analysis');
            
            Route::resource('users', AdminUserController::class)->except(['show']);
            Route::get('users/trashed', [AdminUserController::class, 'trashedIndex'])->name('users.trashed');
            Route::put('users/{id}/restore', [AdminUserController::class, 'restore'])->name('users.restore');  
            Route::delete('users/{id}/force-delete', [AdminUserController::class, 'forceDelete'])->name('users.forceDelete');
            
            // [FIX] Pindahkan route spesifik SEBELUM route resource
            Route::get('/properties/compare', [AdminPropertyController::class, 'showComparisonForm'])->name('properties.compare.form');
            Route::get('/properties/compare/results', [AdminPropertyController::class, 'showComparisonResults'])->name('properties.compare.results');
            Route::resource('properties', AdminPropertyController::class);
            
            Route::resource('revenue-targets', RevenueTargetController::class);
            Route::resource('price-packages', PricePackageController::class);
            Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
            Route::post('settings', [SettingController::class, 'store'])->name('settings.store');
            Route::resource('mice-categories', App\Http\Controllers\Admin\MiceCategoryController::class);
            Route::resource('properties.rooms', App\Http\Controllers\Admin\RoomController::class)->shallow();
        });
    
    // Grup route untuk Sales
    Route::middleware(['role:sales'])
        ->prefix('sales')
        ->name('sales.')
        ->group(function() {
            Route::get('/dashboard', [SalesDashboardController::class, 'index'])->name('dashboard');
            
            Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');
            Route::get('calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
            
            // [FIX] Pindahkan route spesifik SEBELUM route resource
            Route::get('bookings/{booking}/beo', [BookingController::class, 'beo'])->name('bookings.beo');
            Route::post('bookings/{booking}/beo', [BookingController::class, 'storeBeo'])->name('bookings.storeBeo');
            Route::get('bookings/{booking}/beo/show', [BookingController::class, 'showBeo'])->name('bookings.showBeo');
            Route::get('bookings/{booking}/beo/print', [BookingController::class, 'printBeo'])->name('bookings.printBeo');
            Route::get('bookings/{booking}/quotation', [DocumentController::class, 'generateQuotation'])->name('documents.quotation');
            Route::get('bookings/{booking}/invoice', [DocumentController::class, 'generateInvoice'])->name('documents.invoice');
            Route::get('bookings/{booking}/beo/pdf', [DocumentController::class, 'generateBeo'])->name('documents.beo');
            
            Route::resource('bookings', BookingController::class);
    });
});

require __DIR__.'/auth.php';