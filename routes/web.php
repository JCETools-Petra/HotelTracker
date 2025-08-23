<?php

use Illuminate\Support\Facades\Artisan;
// Pastikan untuk menambahkan use statement untuk IncomeController yang baru
use App\Http\Controllers\Admin\IncomeController;
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
<<<<<<< HEAD
use App\Http\Controllers\Admin\PricingRuleController;
use App\Http\Controllers\Ecommerce\DashboardController;
use App\Http\Controllers\Ecommerce\ReservationController;
use App\Http\Controllers\Ecommerce\BarDisplayController;
=======
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9

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
<<<<<<< HEAD
        } elseif ($user->role === 'online_ecommerce') {
            return redirect()->route('ecommerce.dashboard');
=======
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
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
<<<<<<< HEAD
        } elseif ($user->role === 'online_ecommerce') {
            return redirect()->route('ecommerce.dashboard');
=======
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
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
<<<<<<< HEAD
            
            // Logika Kalender & Reservasi sekarang di sini
            Route::get('calendar', [PropertyIncomeController::class, 'calendar'])->name('calendar.index');
            Route::get('/calendar-data', [PropertyIncomeController::class, 'getCalendarData'])->name('calendar.data');
            
            // API untuk data dinamis
            Route::get('properties/{property}/room-types', [ReservationController::class, 'getRoomTypesByProperty'])->name('properties.room-types');
            Route::get('room-types/{roomType}/active-price', [ReservationController::class, 'getActiveBarPrice'])->name('room-types.active-price');

            Route::resource('reservations', ReservationController::class);

            // Rute-rute pendapatan yang sudah ada
=======
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
            Route::get('/income/create', [PropertyIncomeController::class, 'create'])->name('income.create');
            Route::post('/income', [PropertyIncomeController::class, 'store'])->name('income.store');
            Route::get('/income', [PropertyIncomeController::class, 'index'])->name('income.index');
            Route::get('/income/{dailyIncome}/edit', [PropertyIncomeController::class, 'edit'])->name('income.edit');
            Route::put('/income/{dailyIncome}', [PropertyIncomeController::class, 'update'])->name('income.update');
            Route::delete('/income/{dailyIncome}', [PropertyIncomeController::class, 'destroy'])->name('income.destroy');
<<<<<<< HEAD
            Route::post('/occupancy/update', [PropertyIncomeController::class, 'updateOccupancy'])->name('occupancy.update');
=======
            Route::get('/income/export/excel', [PropertyIncomeController::class, 'exportIncomesExcel'])->name('income.export.excel');
            Route::get('/income/export/csv', [PropertyIncomeController::class, 'exportIncomesCsv'])->name('income.export.csv');
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
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
<<<<<<< HEAD
            Route::put('users/{id}/restore', [AdminUserController::class, 'restore'])->name('users.restore');
            Route::delete('users/{id}/force-delete', [AdminUserController::class, 'forceDelete'])->name('users.forceDelete');
            
            Route::get('/properties/compare', [AdminPropertyController::class, 'showComparisonForm'])->name('properties.compare_page');
            Route::get('/properties/compare/results', [AdminPropertyController::class, 'showComparisonResults'])->name('properties.compare.results');
            
            Route::resource('properties', AdminPropertyController::class);
            Route::resource('properties.incomes', IncomeController::class)->shallow()->except(['index', 'show']);
=======
            Route::put('users/{id}/restore', [AdminUserController::class, 'restore'])->name('users.restore');  
            Route::delete('users/{id}/force-delete', [AdminUserController::class, 'forceDelete'])->name('users.forceDelete');
            
            // [FIX] Pindahkan route spesifik SEBELUM route resource
            Route::get('/properties/compare', [AdminPropertyController::class, 'showComparisonForm'])->name('properties.compare_page');
            Route::get('/properties/compare/results', [AdminPropertyController::class, 'showComparisonResults'])->name('properties.compare.results');
            
            // ==========================================================
            // >> AWAL PERUBAHAN <<
            // ==========================================================
            Route::resource('properties', AdminPropertyController::class);
            // Daftarkan route untuk Incomes di dalam Properties
            Route::resource('properties.incomes', IncomeController::class)->shallow()->except(['index', 'show']);
            // ==========================================================
            // >> AKHIR PERUBAHAN <<
            // ==========================================================
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
            
            Route::resource('revenue-targets', RevenueTargetController::class);
            Route::resource('price-packages', PricePackageController::class);
            Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
            Route::post('settings', [SettingController::class, 'store'])->name('settings.store');
            Route::resource('mice-categories', App\Http\Controllers\Admin\MiceCategoryController::class);
            Route::resource('properties.rooms', App\Http\Controllers\Admin\RoomController::class)->shallow();
            Route::get('/activity-log', [App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity_log.index');
            Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');
            Route::get('calendar/events', [CalendarController::class, 'events'])->name('calendar.events');

<<<<<<< HEAD
            // === AWAL BLOK YANG DIPERBAIKI ===
            // Hapus route lama yang konflik
            // Route::get('properties/{property}/pricing-rule', [PricingRuleController::class, 'edit'])->name('pricing-rules.edit');
            
            // Gunakan grup route baru ini
            Route::prefix('properties/{property}/pricing-rule')->name('pricing-rules.')->group(function () {
                Route::get('/', [PricingRuleController::class, 'index'])->name('index');
                Route::post('/store-room-type', [PricingRuleController::class, 'storeRoomType'])->name('room-type.store');
                Route::put('/update-pricing-rule/{roomType}', [PricingRuleController::class, 'updatePricingRule'])->name('rule.update');
                Route::delete('/destroy-room-type/{roomType}', [PricingRuleController::class, 'destroyRoomType'])->name('room-type.destroy');
            Route::put('/update-property-bars', [PricingRuleController::class, 'updatePropertyBars'])->name('property-bars.update');
            });
            Route::post('properties/{property}/occupancy', [AdminPropertyController::class, 'updateOccupancy'])->name('properties.occupancy.update');
            Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');
            Route::get('calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
            Route::get('unified-calendar', [AdminDashboardController::class, 'unifiedCalendar'])->name('calendar.unified');
            Route::get('unified-calendar/events', [AdminDashboardController::class, 'getUnifiedCalendarEvents'])->name('calendar.unified.events');        
            Route::get('bar-prices', [BarDisplayController::class, 'index'])->name('bar-prices.index');
=======
            // Hapus baris lama yang menyebabkan kebingungan
            // Route::resource('incomes', IncomeController::class)->except(['show']);

>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
        });
    
    // Grup route untuk Sales
    Route::middleware(['role:sales'])
        ->prefix('sales')
        ->name('sales.')
        ->group(function() {
            Route::get('/dashboard', [SalesDashboardController::class, 'index'])->name('dashboard');
            
            Route::get('calendar', [CalendarController::class, 'index'])->name('calendar.index');
            Route::get('calendar/events', [CalendarController::class, 'events'])->name('calendar.events');
            
<<<<<<< HEAD
=======
            // [FIX] Pindahkan route spesifik SEBELUM route resource
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
            Route::get('bookings/{booking}/beo', [BookingController::class, 'beo'])->name('bookings.beo');
            Route::post('bookings/{booking}/beo', [BookingController::class, 'storeBeo'])->name('bookings.storeBeo');
            Route::get('bookings/{booking}/beo/show', [BookingController::class, 'showBeo'])->name('bookings.showBeo');
            Route::get('bookings/{booking}/beo/print', [BookingController::class, 'printBeo'])->name('bookings.printBeo');
            Route::get('bookings/{booking}/quotation', [DocumentController::class, 'generateQuotation'])->name('documents.quotation');
            Route::get('bookings/{booking}/invoice', [DocumentController::class, 'generateInvoice'])->name('documents.invoice');
            Route::get('bookings/{booking}/beo/pdf', [DocumentController::class, 'generateBeo'])->name('documents.beo');
            
            Route::resource('bookings', BookingController::class);
<<<<<<< HEAD
        });

    // === PERUBAHAN DI BLOK INI ===
    // Sekarang kedua peran bisa mengakses dasbor kalender dan reservasi
    Route::middleware(['role:online_ecommerce'])
        ->prefix('ecommerce')
        ->name('ecommerce.')
        ->group(function () {
            Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        });
=======
    });
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
});

require __DIR__.'/auth.php';