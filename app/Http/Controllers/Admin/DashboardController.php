<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\DailyIncome;
use App\Models\RevenueTarget;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Booking;
use App\Models\PricePackage;
use App\Exports\AdminPropertiesSummaryExport;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin dengan data ringkasan, KPI, dan chart yang telah difilter.
     */
    public function __invoke(Request $request)
    {
        // 1. Pengaturan Filter
        $allPropertiesForFilter = Property::orderBy('name')->get();
        $propertyId = $request->input('property_id');
        $period = $request->input('period', 'month');
        
        $startDate = null;
        $endDate = null;

        // Logika untuk menentukan rentang tanggal berdasarkan periode
        switch ($period) {
            case 'today':
                $startDate = Carbon::today()->startOfDay();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'month':
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
        }

        $dateFilter = function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        };
        
        // 2. Definisi Kategori Pendapatan (TANPA fnb_income)
        $incomeCategories = [
            'offline_room_income' => 'Walk In',
            'online_room_income' => 'OTA',
            'ta_income' => 'Travel Agent',
            'gov_income' => 'Government',
            'corp_income' => 'Corporation',
            'compliment_income' => 'Compliment',
            'house_use_income' => 'House Use',
            'mice_income' => 'MICE',
            'breakfast_income' => 'Breakfast',
            'lunch_income' => 'Lunch',
            'dinner_income' => 'Dinner',
            'others_income' => 'Lain-lain',
        ];

        $incomeCategoryKeys = array_keys($incomeCategories);

        // 3. Query untuk Pie Chart dan Legenda
        $selectsForPie = array_map(fn($key) => DB::raw("SUM(COALESCE({$key}, 0)) as total_{$key}"), $incomeCategoryKeys);
        $overallIncomeSource = DailyIncome::query()
            ->when($propertyId, fn($q) => $q->where('property_id', $propertyId))
            ->where($dateFilter)
            ->selectRaw(implode(', ', $selectsForPie))
            ->first();

        // 4. Query untuk Bar Chart Pendapatan per Properti
        $overallIncomeByProperty = DailyIncome::query()
            ->join('properties', 'daily_incomes.property_id', '=', 'properties.id')
            ->select('properties.name', 'properties.chart_color', DB::raw('SUM(total_revenue) as total_revenue'))
            ->when($propertyId, fn($q) => $q->where('property_id', $propertyId))
            ->where($dateFilter)
            ->groupBy('properties.name', 'properties.chart_color')
            ->orderBy('total_revenue', 'desc')
            ->get();

        // 5. Query untuk Kartu Properti Individual (Sumber Eror)
        $propertiesQuery = Property::query()->when($propertyId, fn($q) => $q->where('id', $propertyId));
        
        // Loop dinamis untuk menjumlahkan setiap kategori pendapatan yang BENAR
        foreach ($incomeCategoryKeys as $column) {
            $propertiesQuery->withSum(['incomes as total_' . $column => $dateFilter], $column);
        }
        
        $roomCountColumns = ['offline_rooms', 'online_rooms', 'ta_rooms', 'gov_rooms', 'corp_rooms', 'compliment_rooms', 'house_use_rooms'];
        foreach ($roomCountColumns as $column) {
            $propertiesQuery->withSum(['incomes as total_' . $column => $dateFilter], $column);
        }
        
        $properties = $propertiesQuery->get();

        // 6. Ambil dan distribusikan data MICE dan kalkulasi
        $miceDateFilter = function ($query) use ($startDate, $endDate) {
            $query->whereBetween('event_date', [$startDate, $endDate]);
        };

        $miceRevenues = Booking::where('status', 'Booking Pasti')
            ->where($miceDateFilter)
            ->when($propertyId, fn($q) => $q->where('property_id', $propertyId))
            ->select('property_id', 'mice_category_id', DB::raw('SUM(total_price) as total_mice_revenue'))
            ->groupBy('property_id', 'mice_category_id')
            ->with('miceCategory:id,name')
            ->get()
            ->groupBy('property_id');
            
        foreach ($properties as $property) {
            $property->mice_revenue_breakdown = $miceRevenues->get($property->id) ?? collect();
            
            $totalMiceRevenue = $property->mice_revenue_breakdown->sum('total_mice_revenue');
            
            $totalDailyIncome = 0;
            foreach($incomeCategoryKeys as $key) {
                $totalDailyIncome += $property->{'total_'.$key} ?? 0;
            }
            $property->dailyRevenue = $totalDailyIncome + $totalMiceRevenue;

            $totalRoomsSold = 0;
            foreach($roomCountColumns as $key) {
                $totalRoomsSold += $property->{'total_'.$key} ?? 0;
            }
            
            $totalRoomRevenue = 0;
            foreach (['offline_room_income', 'online_room_income', 'ta_income', 'gov_income', 'corp_income', 'compliment_income', 'house_use_income'] as $key) {
                $totalRoomRevenue += $property->{'total_'.$key} ?? 0;
            }

            $property->averageRoomRate = $totalRoomsSold > 0 ? $totalRoomRevenue / $totalRoomsSold : 0;
        }

        // 7. Query untuk Laporan Event MICE Lunas Terbaru
        $completedMiceEvents = Booking::where('status', 'Booking Pasti')
            ->where('payment_status', 'Lunas')
            ->when($propertyId, fn($q) => $q->where('property_id', $propertyId))
            ->where($miceDateFilter)
            ->with('property', 'miceCategory')
            ->latest('event_date')
            ->limit(5)
            ->get();
            
        return view('admin.dashboard', compact(
            'allPropertiesForFilter', 'propertyId', 'period',
            'overallIncomeSource', 'incomeCategories',
            'overallIncomeByProperty', 'properties', 'completedMiceEvents'
        ));
    }
    
    public function salesAnalytics()
    {
        // ======================================================
        // Mengambil semua data yang berhubungan dengan event
        // ======================================================

        // 1. Data untuk kartu statistik
        $totalEventRevenue = Booking::where('status', 'Booking Pasti')->sum('total_price');
        $totalBookings = Booking::count();
        $totalConfirmedBookings = Booking::where('status', 'Booking Pasti')->count();
        $totalActivePackages = PricePackage::where('is_active', true)->count();

        // 2. Data untuk Grafik Pie Status Booking
        $bookingStatusData = Booking::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');
            
        $pieChartData = [
            'labels' => $bookingStatusData->keys(),
            'data' => $bookingStatusData->values(),
        ];
        
        // 3. Data untuk Grafik Batang Pendapatan Event 12 Bulan Terakhir
        $revenueData = Booking::select(
                DB::raw('YEAR(event_date) as year, MONTH(event_date) as month'),
                DB::raw('sum(total_price) as total')
            )
            ->where('status', 'Booking Pasti')
            ->where('event_date', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')->orderBy('month', 'asc')
            ->get();
        
        $barChartLabels = [];
        $barChartData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->format('M Y');
            $barChartLabels[] = $monthName;
            $found = $revenueData->first(fn($item) => $item->year == $date->year && $item->month == $date->month);
            $barChartData[] = $found ? $found->total : 0;
        }
        
        $revenueChartData = [
            'labels' => $barChartLabels,
            'data' => $barChartData,
        ];

        return view('admin.sales_analytics', compact(
            'totalEventRevenue',
            'totalBookings',
            'totalConfirmedBookings',
            'totalActivePackages',
            'pieChartData',
            'revenueChartData'
        ));
    }
    
    public function index(Request $request)
    {
        $propertyId = $request->input('property_id');
        $period = $request->input('period', 'year');

        $startDate = null;
        $endDate = null;
        switch ($period) {
            case 'today':
                $startDate = Carbon::today()->startOfDay();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
        }

        $incomeCategories = [
            'offline_room_income' => 'Walk In Guest',
            'online_room_income'  => 'OTA',
            'ta_income'           => 'TA/Travel Agent',
            'gov_income'          => 'Gov/Government',
            'corp_income'         => 'Corp/Corporation',
            'compliment_income'   => 'Compliment',
            'house_use_income'    => 'House Use',
            'mice_income'         => 'MICE',
            'fnb_income'          => 'F&B',
            'others_income'       => 'Lainnya',
        ];
        $incomeColumns = array_keys($incomeCategories);
        $incomeSumRaw = implode(' + ', array_map(fn($col) => "IFNULL(`$col`, 0)", $incomeColumns));

        $propertyQuery = Property::orderBy('id', 'asc');
        
        if ($propertyId) {
            $propertyQuery->where('id', $propertyId);
        }

        $dateFilter = function ($query) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }
        };

        $roomCountColumns = ['offline_rooms', 'online_rooms', 'ta_rooms', 'gov_rooms', 'corp_rooms', 'compliment_rooms', 'house_use_rooms'];
        $propertiesQuery = (clone $propertyQuery);
        foreach ($incomeColumns as $column) {
            $propertiesQuery->withSum(['dailyIncomes as total_' . $column => $dateFilter], $column);
        }
        foreach ($roomCountColumns as $column) {
            $propertiesQuery->withSum(['dailyIncomes as total_' . $column => $dateFilter], $column);
        }
        
        $properties = $propertiesQuery->get();

        $miceRevenues = Booking::where('status', 'Booking Pasti')
            ->whereBetween('event_date', [$startDate, $endDate])
            ->select('property_id', 'mice_category_id', DB::raw('SUM(total_price) as total_mice_revenue'))
            ->groupBy('property_id', 'mice_category_id')
            ->with('miceCategory:id,name')
            ->get()
            ->groupBy('property_id');

        foreach ($properties as $property) {
            $dailyRevenue = 0;
            foreach ($incomeColumns as $col) {
                $key = 'total_' . $col;
                $dailyRevenue += $property->$key ?? 0;
            }

            $propertyMiceRevenues = $miceRevenues->get($property->id);
            if ($propertyMiceRevenues) {
                $dailyRevenue += $propertyMiceRevenues->sum('total_mice_revenue');
                $property->mice_revenue_breakdown = $propertyMiceRevenues;
            } else {
                $property->mice_revenue_breakdown = collect();
            }
            
            $property->dailyRevenue = $dailyRevenue;

            $totalArrRevenue = 0;
            $totalArrRoomsSold = 0;
            $arrRevenueCategories = ['offline_room_income', 'online_room_income', 'ta_income', 'gov_income', 'corp_income'];
            $arrRoomsCategories = ['offline_rooms', 'online_rooms', 'ta_rooms', 'gov_rooms', 'corp_rooms'];

            foreach($arrRevenueCategories as $cat) {
                $totalArrRevenue += $property->{'total_' . $cat} ?? 0;
            }
            foreach($arrRoomsCategories as $cat) {
                $totalArrRoomsSold += $property->{'total_' . $cat} ?? 0;
            }
            $property->averageRoomRate = ($totalArrRoomsSold > 0) ? ($totalArrRevenue / $totalArrRoomsSold) : 0;
        }

        $dailyIncomeQuery = DailyIncome::query();
        if ($propertyId) $dailyIncomeQuery->where('property_id', $propertyId);
        if ($startDate && $endDate) $dailyIncomeQuery->whereBetween('date', [$startDate, $endDate]);

        $selectSums = [];
        foreach ($incomeColumns as $column) {
            $selectSums[] = DB::raw("SUM(IFNULL(`{$column}`, 0)) as total_{$column}");
        }
        $overallIncomeSource = (clone $dailyIncomeQuery)->select($selectSums)->first();

        // ==========================================================
        // >> PERUBAHAN DIMULAI DI SINI <<
        // ==========================================================

        // 1. Ambil total pendapatan MICE dari tabel Bookings
        $miceBookingQuery = Booking::where('status', 'Booking Pasti');
        if ($propertyId) {
            $miceBookingQuery->where('property_id', $propertyId);
        }
        if ($startDate && $endDate) {
            $miceBookingQuery->whereBetween('event_date', [$startDate, $endDate]);
        }
        $miceRevenueFromSales = $miceBookingQuery->sum('total_price');

        // 2. Jika tidak ada data pendapatan harian sama sekali, buat objek kosong
        if (!$overallIncomeSource) {
            $overallIncomeSource = new \stdClass();
            foreach($incomeColumns as $col) {
                $overallIncomeSource->{'total_'.$col} = 0;
            }
        }
        
        // 3. Tambahkan pendapatan MICE dari Sales ke total pendapatan MICE
        //    (Ini akan menjumlahkan MICE dari daily_incomes dan MICE dari bookings)
        $overallIncomeSource->total_mice_income = ($overallIncomeSource->total_mice_income ?? 0) + $miceRevenueFromSales;

        // ==========================================================
        // >> PERUBAHAN SELESAI DI SINI <<
        // ==========================================================
        
        $overallIncomeByProperty = Property::query()
            ->when($propertyId, fn($q) => $q->where('properties.id', $propertyId))
            ->leftJoin('daily_incomes', function ($join) use ($startDate, $endDate) {
                $join->on('properties.id', '=', 'daily_incomes.property_id');
                if ($startDate && $endDate) {
                    $join->whereBetween('daily_incomes.date', [$startDate, $endDate]);
                }
            })
            ->select('properties.name', 'properties.id', 'properties.chart_color', DB::raw("SUM({$incomeSumRaw}) as total_revenue"))
            ->groupBy('properties.id', 'properties.name', 'properties.chart_color')
            ->get();

        $completedMiceEvents = Booking::with(['property', 'miceCategory'])
            ->where('payment_status', 'Paid')
            ->latest('event_date')
            ->take(10)
            ->get();

        $allPropertiesForFilter = Property::orderBy('name')->get();

        return view('admin.dashboard', [
            'properties' => $properties,
            'allPropertiesForFilter' => $allPropertiesForFilter,
            'propertyId' => $propertyId,
            'period' => $period,
            'overallIncomeSource' => $overallIncomeSource,
            'overallIncomeByProperty' => $overallIncomeByProperty,
            'incomeCategories' => $incomeCategories,
            'completedMiceEvents' => $completedMiceEvents,
        ]);
    }

    public function kpiAnalysis(Request $request)
    {
        // --- 1. SETUP FILTER & DEFINISI KATEGORI ---
        $filterStartDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
        $filterEndDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();

        if ($filterStartDate->gt($filterEndDate)) {
            $filterStartDate = Carbon::now()->subDays(29)->startOfDay();
            $filterEndDate = Carbon::now()->endOfDay();
        }

        $propertyMomFilterId = $request->input('property_mom_filter_id');
        $allPropertiesForFilter = Property::orderBy('name')->get();

        $categories = [
            'offline_room_income' => 'Walk In Guest', 'online_room_income'  => 'OTA',
            'ta_income'           => 'TA/Travel Agent', 'gov_income'          => 'Gov/Government',
            'corp_income'         => 'Corp/Corporation', 'compliment_income'   => 'Compliment',
            'house_use_income'    => 'House Use', 'mice_income'         => 'MICE',
            'fnb_income'          => 'F&B', 'others_income'       => 'Lainnya',
        ];
        $categoryColumns = array_keys($categories);
        $categoryLabels = array_values($categories);
        $categoryColors = ['#e6194B', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#42d4f4', '#f032e6', '#bfef45', '#808000'];
        $incomeSumRaw = implode(' + ', array_map(fn($col) => "IFNULL(`$col`, 0)", $categoryColumns));

        // --- Inisialisasi variabel ---
        $trendKontribusiData = ['labels' => [], 'datasets' => []];
        $multiMonthCategoryGrowth = [];
        $targetAnalysis = ['details' => []];
        $dataCompliance = ['days_without_entry' => []];

        // --- Bagian 1: Data untuk KPI Ringkasan Keseluruhan ---
        $overallIncomeQuery = DailyIncome::query()->whereBetween('date', [$filterStartDate, $filterEndDate]);
        $totalOverallRevenue = (clone $overallIncomeQuery)->sum(DB::raw($incomeSumRaw));
        $uniqueDaysWithIncome = (clone $overallIncomeQuery)->select(DB::raw('COUNT(DISTINCT date) as count'))->first()->count;
        $averageDailyOverallRevenue = ($uniqueDaysWithIncome > 0) ? $totalOverallRevenue / $uniqueDaysWithIncome : 0;
        $activePropertiesCount = Property::count();
        $activePropertyUsersCount = User::where('role', 'pengguna_properti')->whereNull('deleted_at')->count();
        $averageRevenuePerProperty = ($activePropertiesCount > 0) ? $totalOverallRevenue / $activePropertiesCount : 0;

        $selectOverallSums = [];
        foreach ($categoryColumns as $column) {
            $selectOverallSums[] = DB::raw("SUM(IFNULL(`{$column}`, 0)) as total_{$column}");
        }
        $overallIncomeSource = (clone $overallIncomeQuery)->select($selectOverallSums)->first();

        $overallIncomeByProperty = Property::query()
            ->leftJoin('daily_incomes as di', function($join) use ($filterStartDate, $filterEndDate) {
                $join->on('properties.id', '=', 'di.property_id')->whereBetween('di.date', [$filterStartDate, $filterEndDate]);
            })
            ->select('properties.name', 'properties.id', DB::raw("SUM({$incomeSumRaw}) as total_revenue"))
            ->groupBy('properties.id', 'properties.name')->orderBy('properties.id', 'asc')->get();

        // --- Bagian 2: Data untuk Analisis Kinerja per Kategori Pendapatan ---
        $monthlyCategoryIncomeQueryBase = DailyIncome::query()->whereBetween('date', [$filterStartDate, $filterEndDate]);
        if ($propertyMomFilterId && $propertyMomFilterId != 'all') {
            $monthlyCategoryIncomeQueryBase->where('property_id', $propertyMomFilterId);
        }

        $selectMonthlySums = [DB::raw("DATE_FORMAT(date, '%Y-%m') as month_year_formatted")];
        foreach ($categoryColumns as $column) {
            $selectMonthlySums[] = DB::raw("SUM(IFNULL(`{$column}`, 0)) as total_{$column}_monthly");
        }
        $monthlyCategoryIncome = (clone $monthlyCategoryIncomeQueryBase)->select($selectMonthlySums)->groupBy('month_year_formatted')->orderBy('month_year_formatted', 'asc')->get();

        if(!$monthlyCategoryIncome->isEmpty()){
            $trendKontribusiData['labels'] = $monthlyCategoryIncome->pluck('month_year_formatted')->map(fn($d) => Carbon::parse($d.'-01')->isoFormat('MMM YY'))->all();
            foreach ($categoryColumns as $key => $column) {
                $trendKontribusiData['datasets'][] = [
                    'label' => $categoryLabels[$key],
                    'data' => $monthlyCategoryIncome->pluck('total_'.$column.'_monthly')->all(),
                    'borderColor' => $categoryColors[$key], 'backgroundColor' => $categoryColors[$key],
                    'fill' => false, 'tension' => 0.1
                ];
            }
        }

        $period = CarbonPeriod::create($filterStartDate->copy()->startOfMonth(), '1 month', $filterEndDate->copy()->endOfMonth());
        $allMonthlyTotals = (clone $monthlyCategoryIncomeQueryBase)->select($selectMonthlySums)->groupBy('month_year_formatted')->get()->keyBy('month_year_formatted');

        foreach ($period as $currentMonthCarbon) {
            $currentMonthKey = $currentMonthCarbon->format('Y-m');
            $previousMonthKey = $currentMonthCarbon->copy()->subMonthNoOverflow()->format('Y-m');
            $currentMonthTotals = $allMonthlyTotals->get($currentMonthKey);
            $previousMonthTotals = $allMonthlyTotals->get($previousMonthKey);

            $growthForCurrentMonth = [];
            foreach ($categoryColumns as $catKey => $column) {
                $currentValue = $currentMonthTotals->{'total_'.$column.'_monthly'} ?? 0;
                $previousValue = $previousMonthTotals->{'total_'.$column.'_monthly'} ?? 0;
                $growthDisplay = "N/A";

                if ($previousMonthTotals === null && $currentMonthTotals !== null) {
                    $growthDisplay = ($currentValue > 0) ? "Baru" : "0.00%";
                } elseif ($previousMonthTotals !== null) {
                    if ($previousValue > 0) {
                        $growthNumeric = (($currentValue - $previousValue) / $previousValue) * 100;
                        $growthDisplay = sprintf("%+.2f%%", $growthNumeric);
                    } elseif ($currentValue > 0) {
                        $growthDisplay = "Baru";
                    } else {
                        $growthDisplay = "0.00%";
                    }
                }

                $growthForCurrentMonth[] = [
                    'category' => $categoryLabels[$catKey],
                    'growth' => $growthDisplay,
                    'current_value' => $currentValue,
                    'previous_value' => $previousValue
                ];
            }

            if ($currentMonthTotals) {
                $multiMonthCategoryGrowth[$currentMonthCarbon->isoFormat('MMMM YYYY')] = $growthForCurrentMonth;
            }
        }

        // --- Bagian 3 & 4 (Target dan Kepatuhan) ---
        $allPropertiesForTargetAnalysis = Property::get();
        $propertyTargetAchievements = [];
        $totalAchievementSum = 0;
        $propertiesWithTargetsCount = 0;
        $propertiesAchievedTargetCount = 0;

        foreach ($allPropertiesForTargetAnalysis as $property) {
            $targetsInPeriod = RevenueTarget::where('property_id', $property->id)->whereBetween('month_year', [$filterStartDate->copy()->startOfMonth()->toDateString(), $filterEndDate->copy()->endOfMonth()->toDateString()])->get();
            $totalTargetAmountForPeriod = $targetsInPeriod->sum('target_amount');
            $totalActualRevenueForPeriod = DailyIncome::where('property_id', $property->id)->whereBetween('date', [$filterStartDate, $filterEndDate])->sum(DB::raw($incomeSumRaw));
            $achievementPercentage = 0;
            $hasValidTarget = $totalTargetAmountForPeriod > 0;
            if ($hasValidTarget) {
                $achievementPercentage = ($totalActualRevenueForPeriod / $totalTargetAmountForPeriod) * 100;
                $totalAchievementSum += $achievementPercentage;
                $propertiesWithTargetsCount++;
                if ($achievementPercentage >= 100) $propertiesAchievedTargetCount++;
            }
            $propertyTargetAchievements[] = ['id' => $property->id, 'name' => $property->name, 'total_target' => $totalTargetAmountForPeriod, 'total_actual' => $totalActualRevenueForPeriod, 'achievement_percentage' => $hasValidTarget ? round($achievementPercentage, 2) : null, 'has_valid_target' => $hasValidTarget];
        }
        $averageOverallAchievement = ($propertiesWithTargetsCount > 0) ? round($totalAchievementSum / $propertiesWithTargetsCount, 2) : null;
        $percentagePropertiesAchieved = ($propertiesWithTargetsCount > 0) ? round(($propertiesAchievedTargetCount / $propertiesWithTargetsCount) * 100, 2) : 0;
        $sortableAchievements = array_filter($propertyTargetAchievements, fn($item) => $item['has_valid_target']);
        usort($sortableAchievements, fn($a, $b) => $b['achievement_percentage'] <=> $a['achievement_percentage']);
        $topPropertyTarget = !empty($sortableAchievements) ? $sortableAchievements[0] : null;
        $bottomPropertyTarget = !empty($sortableAchievements) ? end($sortableAchievements) : null;
        $targetAnalysis = ['properties_achieved_count' => $propertiesAchievedTargetCount, 'properties_achieved_percentage' => $percentagePropertiesAchieved, 'average_achievement_percentage' => $averageOverallAchievement, 'top_property_target' => $topPropertyTarget, 'bottom_property_target' => $bottomPropertyTarget, 'details' => $propertyTargetAchievements];

        $periodForCompliance = CarbonPeriod::create($filterStartDate, '1 day', $filterEndDate);
        $allPropertiesForCompliance = Property::orderBy('name')->get();
        $totalDaysInPeriod = $periodForCompliance->count();

        foreach ($allPropertiesForCompliance as $property) {
            $datesWithIncome = DailyIncome::where('property_id', $property->id)->whereBetween('date', [$filterStartDate, $filterEndDate])->pluck('date')->map(fn($date) => Carbon::parse($date)->toDateString())->toArray();
            $daysWithoutEntryCount = 0;
            foreach (clone $periodForCompliance as $dateInPeriod) {
                if (!in_array($dateInPeriod->toDateString(), $datesWithIncome)) $daysWithoutEntryCount++;
            }
            $dataCompliance['days_without_entry'][] = ['property_name' => $property->name, 'days' => $daysWithoutEntryCount, 'total_days_in_period' => $totalDaysInPeriod, 'compliance_percentage' => $totalDaysInPeriod > 0 ? round((($totalDaysInPeriod - $daysWithoutEntryCount) / $totalDaysInPeriod) * 100, 2) : 0];
        }

        // --- Bagian 5: Data untuk Analisis Pencapaian Harian vs Target (Tabel Rincian) ---
        $dailyActualsQuery = DailyIncome::query()->whereBetween('date', [$filterStartDate, $filterEndDate]);
        if ($propertyMomFilterId && $propertyMomFilterId != 'all') {
            $dailyActualsQuery->where('property_id', $propertyMomFilterId);
        }
        $dailyActuals = $dailyActualsQuery
            ->select(DB::raw('DATE(date) as day'), DB::raw("SUM({$incomeSumRaw}) as total_income"))
            ->groupBy('day')->get()->keyBy('day');

        $monthlyTargetsQuery = RevenueTarget::query()->whereBetween('month_year', [$filterStartDate->copy()->startOfMonth(), $filterEndDate->copy()->endOfMonth()]);
        if ($propertyMomFilterId && $propertyMomFilterId != 'all') {
            $monthlyTargetsQuery->where('property_id', $propertyMomFilterId);
        }
        $monthlyTargets = $monthlyTargetsQuery
            ->select('month_year', DB::raw('SUM(target_amount) as monthly_target'))
            ->groupBy('month_year')->get()->keyBy(fn($item) => Carbon::parse($item->month_year)->format('Y-m'));

        $dailyPerformanceData = [];
        foreach (clone $periodForCompliance as $date) {
            $dateString = $date->toDateString();
            $monthKey = $date->format('Y-m');
            $monthlyTargetData = $monthlyTargets->get($monthKey);
            $monthlyTargetAmount = $monthlyTargetData ? $monthlyTargetData->monthly_target : 0;
            $daysInMonth = $date->daysInMonth;
            $dailyTarget = ($daysInMonth > 0) ? $monthlyTargetAmount / $daysInMonth : 0;
            $actualIncome = $dailyActuals->get($dateString) ? $dailyActuals->get($dateString)->total_income : 0;
            $achievementPercentage = ($dailyTarget > 0) ? ($actualIncome / $dailyTarget) * 100 : 0;
            $dailyPerformanceData[] = [
                'date' => $date,
                'actual_income' => $actualIncome,
                'daily_target' => $dailyTarget,
                'achievement_percentage' => $achievementPercentage,
            ];
        }
        $dailyPerformanceData = array_reverse($dailyPerformanceData);

        // --- Bagian 6: Data untuk Pacing Target Bulanan ---
        $pacingMonth = $filterEndDate->copy();
        $pacingMonthStart = $pacingMonth->copy()->startOfMonth();
        $daysInPacingMonth = $pacingMonth->daysInMonth;
        $daysPassedInPacingMonth = $pacingMonth->day;

        $pacingMonthlyTargetQuery = RevenueTarget::query()
            ->whereYear('month_year', $pacingMonth->year)
            ->whereMonth('month_year', $pacingMonth->month);
        if ($propertyMomFilterId && $propertyMomFilterId != 'all') {
            $pacingMonthlyTargetQuery->where('property_id', $propertyMomFilterId);
        }
        $totalMonthlyTarget = $pacingMonthlyTargetQuery->sum('target_amount');

        $monthToDateActualQuery = DailyIncome::query()->whereBetween('date', [$pacingMonthStart, $filterEndDate]);
        if ($propertyMomFilterId && $propertyMomFilterId != 'all') {
            $monthToDateActualQuery->where('property_id', $propertyMomFilterId);
        }
        $monthToDateActual = $monthToDateActualQuery->sum(DB::raw($incomeSumRaw));

        $dailyTargetForPacing = ($daysInPacingMonth > 0) ? $totalMonthlyTarget / $daysInPacingMonth : 0;
        $pacingTarget = $dailyTargetForPacing * $daysPassedInPacingMonth;
        $totalRemainingTarget = $totalMonthlyTarget - $monthToDateActual;
        $variance = $monthToDateActual - $pacingTarget;
        $monthlyAchievementPercentage = ($totalMonthlyTarget > 0) ? ($monthToDateActual / $totalMonthlyTarget) * 100 : 0;

        $monthlyPacingData = [
            'monthName' => $pacingMonth->isoFormat('MMMM YYYY'),
            'totalMonthlyTarget' => $totalMonthlyTarget,
            'monthToDateActual' => $monthToDateActual,
            'totalRemainingTarget' => $totalRemainingTarget,
            'pacingTarget' => $pacingTarget,
            'variance' => $variance,
            'dailyTarget' => $dailyTargetForPacing,
            'achievementPercentage' => $monthlyAchievementPercentage
        ];

        return view('admin.kpi_analysis', compact(
            'overallIncomeSource', 'overallIncomeByProperty', 'totalOverallRevenue',
            'averageDailyOverallRevenue', 'activePropertiesCount', 'activePropertyUsersCount',
            'averageRevenuePerProperty', 'trendKontribusiData', 'multiMonthCategoryGrowth',
            'targetAnalysis', 'dataCompliance', 'filterStartDate', 'filterEndDate',
            'totalDaysInPeriod', 'allPropertiesForFilter', 'propertyMomFilterId', 'categories',
            'dailyPerformanceData', 'monthlyPacingData'
        ));
    }
}