<<<<<<< HEAD
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
    public function __invoke(Request $request)
    {
        return $this->index($request);
    }
    
    public function salesAnalytics()
    {
        $totalEventRevenue = Booking::where('status', 'Booking Pasti')->sum('total_price');
        $totalBookings = Booking::count();
        $totalConfirmedBookings = Booking::where('status', 'Booking Pasti')->count();
        $totalActivePackages = PricePackage::where('is_active', true)->count();

        $bookingStatusData = Booking::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');
            
        $pieChartData = [
            'labels' => $bookingStatusData->keys(),
            'data' => $bookingStatusData->values(),
        ];
        
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
        // 1. Pengaturan Filter Tanggal
        $propertyId = $request->input('property_id');
        $period = $request->input('period', 'year');

        if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            $period = 'custom';
        } else {
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
                default:
                    $startDate = Carbon::now()->startOfYear();
                    $endDate = Carbon::now()->endOfYear();
                    break;
            }
        }

        // 2. Definisi Kategori Pendapatan
        $incomeCategories = [
            'offline_room_income' => 'Walk In', 'online_room_income' => 'OTA', 'ta_income' => 'Travel Agent',
            'gov_income' => 'Government', 'corp_income' => 'Corporation', 'compliment_income' => 'Compliment',
            'house_use_income' => 'House Use', 'afiliasi_room_income' => 'Afiliasi',
            'breakfast_income' => 'Breakfast', 'lunch_income' => 'Lunch', 'dinner_income' => 'Dinner',
            'others_income' => 'Lain-lain',
        ];
        $incomeColumns = array_keys($incomeCategories);
        $roomCountColumns = ['offline_rooms', 'online_rooms', 'ta_rooms', 'gov_rooms', 'corp_rooms', 'compliment_rooms', 'house_use_rooms', 'afiliasi_rooms'];
        $dateFilter = fn ($query) => $query->whereBetween('date', [$startDate, $endDate]);

        // 3. Mengambil Data Properti dengan Semua Kalkulasi
        $propertiesQuery = Property::when($propertyId, fn ($q) => $q->where('id', $propertyId))->orderBy('id', 'asc');

        foreach ($incomeColumns as $column) {
            $propertiesQuery->withSum(['dailyIncomes as total_' . $column => $dateFilter], $column);
        }
        foreach ($roomCountColumns as $column) {
            $propertiesQuery->withSum(['dailyIncomes as total_' . $column => $dateFilter], $column);
        }
        $properties = $propertiesQuery->get();

        $miceRevenues = Booking::where('status', 'Booking Pasti')
            ->whereBetween('event_date', [$startDate, $endDate])
            ->when($propertyId, fn ($q) => $q->where('property_id', $propertyId))
            ->select('property_id', 'mice_category_id', DB::raw('SUM(total_price) as total_mice_revenue'))
            ->groupBy('property_id', 'mice_category_id')
            ->with('miceCategory:id,name')
            ->get()
            ->groupBy('property_id');

        $totalOverallRevenue = 0;

        foreach ($properties as $property) {
            $dailyRevenue = collect($incomeColumns)->reduce(fn ($carry, $col) => $carry + ($property->{'total_' . $col} ?? 0), 0);

            $propertyMiceRevenues = $miceRevenues->get($property->id);
            if ($propertyMiceRevenues) {
                $miceTotalForProperty = $propertyMiceRevenues->sum('total_mice_revenue');
                $dailyRevenue += $miceTotalForProperty;
                $property->mice_revenue_breakdown = $propertyMiceRevenues;
            } else {
                $property->mice_revenue_breakdown = collect();
            }

            $property->dailyRevenue = $dailyRevenue;
            $totalOverallRevenue += $dailyRevenue;

            $totalArrRevenue = 0;
            $totalArrRoomsSold = 0;
            $arrRevenueCategories = ['offline_room_income', 'online_room_income', 'ta_income', 'gov_income', 'corp_income'];
            $arrRoomsCategories = ['offline_rooms', 'online_rooms', 'ta_rooms', 'gov_rooms', 'corp_rooms'];
            foreach ($arrRevenueCategories as $cat) {
                $totalArrRevenue += $property->{'total_' . $cat} ?? 0;
            }
            foreach ($arrRoomsCategories as $cat) {
                $totalArrRoomsSold += $property->{'total_' . $cat} ?? 0;
            }
            $property->averageRoomRate = ($totalArrRoomsSold > 0) ? ($totalArrRevenue / $totalArrRoomsSold) : 0;
        }
        
        // 4. Menyiapkan Data untuk Chart
        $pieChartCategories = [
            'offline_room_income' => 'Walk In', 'online_room_income' => 'OTA', 'ta_income' => 'Travel Agent',
            'gov_income' => 'Government', 'corp_income' => 'Corporation', 'afiliasi_room_income' => 'Afiliasi',
            'mice_income' => 'MICE', 'fnb_income' => 'F&B', 'others_income' => 'Lain-lain',
        ];

        $pieChartDataSource = new \stdClass();
        foreach ($pieChartCategories as $key => $label) {
            $totalKey = 'total_' . $key;
            if ($key === 'mice_income') {
                $pieChartDataSource->$totalKey = $miceRevenues->flatten()->sum('total_mice_revenue');
            } else if ($key === 'fnb_income') {
                $pieChartDataSource->$totalKey = $properties->sum('total_breakfast_income') + $properties->sum('total_lunch_income') + $properties->sum('total_dinner_income');
            } else {
                $pieChartDataSource->$totalKey = $properties->sum($totalKey);
            }
        }

        $recentMiceBookings = Booking::with(['property', 'miceCategory'])
            ->where('status', 'Booking Pasti')
            ->whereBetween('event_date', [$startDate, $endDate])
            ->when($propertyId, fn ($q) => $q->where('property_id', $propertyId))
            ->latest('event_date')->take(10)->get();

        $allPropertiesForFilter = Property::orderBy('name')->get();

        $overallIncomeByProperty = $properties->map(function ($property) {
            return (object)[
                'name' => $property->name,
                'total_revenue' => $property->dailyRevenue,
                'chart_color' => $property->chart_color,
            ];
        });

        // 5. Mengirim Data ke View (tanpa variabel total OTA/Afiliasi)
        return view('admin.dashboard', [
            'properties' => $properties,
            'totalOverallRevenue' => $totalOverallRevenue,
            'allPropertiesForFilter' => $allPropertiesForFilter,
            'propertyId' => $propertyId, 'period' => $period,
            'startDate' => $startDate, 'endDate' => $endDate,
            'incomeCategories' => $incomeCategories,
            'recentMiceBookings' => $recentMiceBookings,
            'pieChartDataSource' => $pieChartDataSource,
            'pieChartCategories' => $pieChartCategories,
            'overallIncomeByProperty' => $overallIncomeByProperty,
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
    
        // PERBAIKAN 1: Hitung total hari sebagai integer
        $totalDaysInPeriod = (int) $filterStartDate->diffInDays($filterEndDate) + 1;
    
        $propertyMomFilterId = $request->input('property_mom_filter_id');
        $allPropertiesForFilter = Property::orderBy('name')->get();
    
        $dailyIncomeColumns = [
            'offline_room_income', 'online_room_income', 'ta_income', 'gov_income', 'corp_income', 'compliment_income',
            'house_use_income', 'afiliasi_room_income', 'breakfast_income', 'lunch_income', 'dinner_income', 'others_income'
        ];
        $dailyIncomeSumRaw = implode(' + ', array_map(fn($col) => "IFNULL(`$col`, 0)", $dailyIncomeColumns));
    
        $categories = [
            'offline_room_income' => 'Walk In', 'online_room_income' => 'OTA', 'ta_income' => 'Travel Agent',
            'gov_income' => 'Government', 'corp_income' => 'Corporation', 'compliment_income' => 'Compliment',
            'house_use_income' => 'House Use', 'afiliasi_room_income' => 'Afiliasi',
            'mice_income' => 'MICE', 'fnb_income' => 'F&B', 'others_income' => 'Lain-lain',
        ];
    
        // --- Bagian 1: Data untuk KPI Ringkasan Keseluruhan ---
        $overallIncomeQuery = DailyIncome::query()->whereBetween('date', [$filterStartDate, $filterEndDate]);
        $totalFromDailyIncomes = (clone $overallIncomeQuery)->sum(DB::raw($dailyIncomeSumRaw));
        $totalMiceRevenue = Booking::where('status', 'Booking Pasti')->whereBetween('event_date', [$filterStartDate, $filterEndDate])->sum('total_price');
        $totalOverallRevenue = $totalFromDailyIncomes + $totalMiceRevenue;
        $uniqueDaysWithIncome = (clone $overallIncomeQuery)->select(DB::raw('COUNT(DISTINCT date) as count'))->first()->count;
        $averageDailyOverallRevenue = ($uniqueDaysWithIncome > 0) ? $totalOverallRevenue / $uniqueDaysWithIncome : 0;
        $activePropertiesCount = Property::count();
        $activePropertyUsersCount = User::where('role', 'pengguna_properti')->whereNull('deleted_at')->count();
        $averageRevenuePerProperty = ($activePropertiesCount > 0) ? $totalOverallRevenue / $activePropertiesCount : 0;
        
        $overallIncomeSource = (clone $overallIncomeQuery)
            ->selectRaw("SUM(IFNULL(offline_room_income, 0)) as total_offline_room_income, SUM(IFNULL(online_room_income, 0)) as total_online_room_income, SUM(IFNULL(ta_income, 0)) as total_ta_income, SUM(IFNULL(gov_income, 0)) as total_gov_income, SUM(IFNULL(corp_income, 0)) as total_corp_income, SUM(IFNULL(compliment_income, 0)) as total_compliment_income, SUM(IFNULL(house_use_income, 0)) as total_house_use_income, SUM(IFNULL(afiliasi_room_income, 0)) as total_afiliasi_room_income, SUM(IFNULL(breakfast_income, 0) + IFNULL(lunch_income, 0) + IFNULL(dinner_income, 0)) as total_fnb_income, SUM(IFNULL(others_income, 0)) as total_others_income")
            ->first();
        $overallIncomeSource->total_mice_income = $totalMiceRevenue;
        
        $overallIncomeByProperty = Property::query()
            ->leftJoin('daily_incomes as di', fn($j) => $j->on('properties.id', '=', 'di.property_id')->whereBetween('di.date', [$filterStartDate, $filterEndDate]))
            ->leftJoin('bookings as b', fn($j) => $j->on('properties.id', '=', 'b.property_id')->where('b.status', 'Booking Pasti')->whereBetween('b.event_date', [$filterStartDate, $filterEndDate]))
            ->select('properties.name', 'properties.id', 'properties.chart_color', DB::raw("SUM({$dailyIncomeSumRaw}) + SUM(IFNULL(b.total_price, 0)) as total_revenue"))
            ->groupBy('properties.id', 'properties.name', 'properties.chart_color')->orderBy('properties.id', 'asc')->get();
    
        // --- Bagian 2: Data untuk Analisis Detail per Kategori ---
        $useDailyView = $totalDaysInPeriod <= 62;
        // PERBAIKAN 2: Ubah format tanggal untuk chart harian
        $periodFormat = $useDailyView ? 'D MMM' : 'MMMM YYYY'; 
        $dateFormat = $useDailyView ? '%Y-%m-%d' : '%Y-%m';
        $periodIteratorUnit = $useDailyView ? '1 day' : '1 month';
    
        $categoryQueryBase = DailyIncome::query()->whereBetween('date', [$filterStartDate, $filterEndDate]);
        if ($propertyMomFilterId && $propertyMomFilterId != 'all') {
            $categoryQueryBase->where('property_id', $propertyMomFilterId);
        }
        
        $selectCategorySums = [DB::raw("DATE_FORMAT(date, '{$dateFormat}') as period_key")];
        foreach ($dailyIncomeColumns as $column) {
            $selectCategorySums[] = DB::raw("SUM(IFNULL(`{$column}`, 0)) as total_{$column}");
        }
        $categoryIncome = (clone $categoryQueryBase)->select($selectCategorySums)->groupBy('period_key')->orderBy('period_key', 'asc')->get()->keyBy('period_key');
    
        $miceIncomeGrouped = Booking::query()->where('status', 'Booking Pasti')->whereBetween('event_date', [$filterStartDate, $filterEndDate])
            ->when($propertyMomFilterId && $propertyMomFilterId != 'all', fn($q) => $q->where('property_id', $propertyMomFilterId))
            ->select(DB::raw("DATE_FORMAT(event_date, '{$dateFormat}') as period_key"), DB::raw('SUM(total_price) as total_mice_income'))
            ->groupBy('period_key')->get()->keyBy('period_key');
    
        $periodIterator = CarbonPeriod::create($filterStartDate, $periodIteratorUnit, $filterEndDate);
        $trendKontribusiData = ['labels' => [], 'datasets' => []];
        $categoryDatasets = [];
        foreach ($categories as $key => $label) {
            $categoryDatasets[$key] = [];
        }
        
        foreach ($periodIterator as $date) {
            $key = $date->format($useDailyView ? 'Y-m-d' : 'Y-m');
            $trendKontribusiData['labels'][] = $date->isoFormat($periodFormat);
            
            $dailyData = $categoryIncome->get($key);
            $miceData = $miceIncomeGrouped->get($key);
    
            foreach ($categories as $catKey => $label) {
                $value = 0;
                if ($catKey === 'mice_income') {
                    $value = $miceData->total_mice_income ?? 0;
                } else if ($catKey === 'fnb_income') {
                    $value = ($dailyData->total_breakfast_income ?? 0) + ($dailyData->total_lunch_income ?? 0) + ($dailyData->total_dinner_income ?? 0);
                } else if($dailyData) {
                    $value = $dailyData->{'total_'.$catKey} ?? 0;
                }
                $categoryDatasets[$catKey][] = $value;
            }
        }
        
        $categoryColors = ['#e6194B', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#42d4f4', '#f032e6', '#bfef45', '#808000'];
        $colorIndex = 0;
        foreach ($categories as $key => $label) {
            if (collect($categoryDatasets[$key])->sum() > 0) {
                $trendKontribusiData['datasets'][] = [
                    'label' => $label, 'data' => $categoryDatasets[$key],
                    'borderColor' => $categoryColors[$colorIndex % count($categoryColors)],
                    'backgroundColor' => $categoryColors[$colorIndex % count($categoryColors)],
                    'fill' => false, 'tension' => 0.1,
                ];
                $colorIndex++;
            }
        }
        
        $multiMonthCategoryGrowth = [];
    
        // --- Bagian 3 & 4 (Target dan Kepatuhan) ---
        $allPropertiesForTargetAnalysis = Property::get();
        $propertyTargetAchievements = [];
        $totalAchievementSum = 0;
        $propertiesWithTargetsCount = 0;
        $propertiesAchievedTargetCount = 0;
    
        foreach ($allPropertiesForTargetAnalysis as $property) {
            $targetsInPeriod = RevenueTarget::where('property_id', $property->id)->whereBetween('month_year', [$filterStartDate->copy()->startOfMonth()->toDateString(), $filterEndDate->copy()->endOfMonth()->toDateString()])->get();
            $totalTargetAmountForPeriod = $targetsInPeriod->sum('target_amount');
            
            $totalActualRevenueForPeriod = DailyIncome::where('property_id', $property->id)->whereBetween('date', [$filterStartDate, $filterEndDate])->sum(DB::raw($dailyIncomeSumRaw));
            $totalActualRevenueForPeriod += Booking::where('property_id', $property->id)->where('status', 'Booking Pasti')->whereBetween('event_date', [$filterStartDate, $filterEndDate])->sum('total_price');
    
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
        $percentagePropertiesAchieved = ($propertiesWithTargetsCount > 0) ? round(($propertiesWithTargetsCount / $propertiesWithTargetsCount) * 100, 2) : 0;
        $sortableAchievements = array_filter($propertyTargetAchievements, fn($item) => $item['has_valid_target']);
        usort($sortableAchievements, fn($a, $b) => $b['achievement_percentage'] <=> $a['achievement_percentage']);
        $topPropertyTarget = !empty($sortableAchievements) ? $sortableAchievements[0] : null;
        $bottomPropertyTarget = !empty($sortableAchievements) ? end($sortableAchievements) : null;
        $targetAnalysis = ['properties_achieved_count' => $propertiesAchievedTargetCount, 'properties_achieved_percentage' => $percentagePropertiesAchieved, 'average_achievement_percentage' => $averageOverallAchievement, 'top_property_target' => $topPropertyTarget, 'bottom_property_target' => $bottomPropertyTarget, 'details' => $propertyTargetAchievements];
    
        // PERBAIKAN 3: Tambahkan $useDailyView ke compact()
        return view('admin.kpi_analysis', compact(
            'overallIncomeSource', 'overallIncomeByProperty', 'totalOverallRevenue',
            'averageDailyOverallRevenue', 'activePropertiesCount', 'activePropertyUsersCount',
            'averageRevenuePerProperty', 'targetAnalysis', 'filterStartDate', 'filterEndDate',
            'allPropertiesForFilter', 'propertyMomFilterId', 'categories', 'totalDaysInPeriod',
            'trendKontribusiData', 'multiMonthCategoryGrowth', 'useDailyView'
        ));
    }
    public function exportPropertiesSummaryExcel(Request $request)
    {
        // Logika untuk menentukan filter tanggal (sama seperti di method index)
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
        } else {
            $period = $request->input('period', 'year'); // Default 'year' jika tidak ada
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
                default:
                    $startDate = Carbon::now()->startOfYear();
                    $endDate = Carbon::now()->endOfYear();
                    break;
            }
        }
        
        $propertyId = $request->input('property_id');
        
        // Siapkan nama file
        $fileName = 'Laporan_Pendapatan_Properti_' . now()->format('d-m-Y_H-i') . '.xlsx';
        
        // Panggil class export yang sudah ada dengan filter yang sesuai
        return Excel::download(new AdminPropertiesSummaryExport($startDate, $endDate, $propertyId), $fileName);
    }

    /**
     * Menangani ekspor data ringkasan properti ke CSV.
     */
    public function exportPropertiesSummaryCsv(Request $request)
    {
        return Excel::download(new AdminPropertiesSummaryExport($request), 'properties-summary-'.now()->format('Ymd').'.csv');
    }
}
=======
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
<<<<<<< HEAD
use App\Models\Reservation;
use App\Models\DailyOccupancy;
=======
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Booking;
use App\Models\PricePackage;
use App\Exports\AdminPropertiesSummaryExport;

class DashboardController extends Controller
{
<<<<<<< HEAD
    public function __invoke(Request $request)
    {
        return $this->index($request);
    }

    public function index(Request $request)
    {
        // 1. Pengaturan Filter Tanggal
        $propertyId = $request->input('property_id');
        $period = $request->input('period', 'month');

        if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            $period = 'custom';
        } else {
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
                default:
                    $startDate = Carbon::now()->startOfYear();
                    $endDate = Carbon::now()->endOfYear();
                    break;
            }
        }

        // 2. Definisi Kategori Pendapatan
        $incomeCategories = [
            'offline_room_income' => 'Walk In', 'online_room_income' => 'OTA', 'ta_income' => 'Travel Agent',
            'gov_income' => 'Government', 'corp_income' => 'Corporation', 'compliment_income' => 'Compliment',
            'house_use_income' => 'House Use', 'afiliasi_room_income' => 'Afiliasi',
            'breakfast_income' => 'Breakfast', 'lunch_income' => 'Lunch', 'dinner_income' => 'Dinner',
            'others_income' => 'Lain-lain',
        ];
        $incomeColumns = array_keys($incomeCategories);
        $roomCountColumns = ['offline_rooms', 'online_rooms', 'ta_rooms', 'gov_rooms', 'corp_rooms', 'compliment_rooms', 'house_use_rooms', 'afiliasi_rooms'];
        $dateFilter = fn ($query) => $query->whereBetween('date', [$startDate, $endDate]);

        // 3. Mengambil Data Properti dengan Semua Kalkulasi
        $propertiesQuery = Property::when($propertyId, fn ($q) => $q->where('id', $propertyId))->orderBy('id', 'asc');

        foreach ($incomeColumns as $column) {
            $propertiesQuery->withSum(['dailyIncomes as total_' . $column => $dateFilter], $column);
        }
        foreach ($roomCountColumns as $column) {
            $propertiesQuery->withSum(['dailyIncomes as total_' . $column => $dateFilter], $column);
        }
        $properties = $propertiesQuery->get();

        $miceRevenues = Booking::where('status', 'Booking Pasti')
            ->whereBetween('event_date', [$startDate, $endDate])
            ->when($propertyId, fn ($q) => $q->where('property_id', $propertyId))
            ->select('property_id', 'mice_category_id', DB::raw('SUM(total_price) as total_mice_revenue'))
            ->groupBy('property_id', 'mice_category_id')
            ->with('miceCategory:id,name')
            ->get()
            ->groupBy('property_id');

        $totalOverallRevenue = 0;

        foreach ($properties as $property) {
            $dailyRevenue = collect($incomeColumns)->reduce(fn ($carry, $col) => $carry + ($property->{'total_' . $col} ?? 0), 0);

            $propertyMiceRevenues = $miceRevenues->get($property->id);
            if ($propertyMiceRevenues) {
                $miceTotalForProperty = $propertyMiceRevenues->sum('total_mice_revenue');
                $dailyRevenue += $miceTotalForProperty;
                $property->mice_revenue_breakdown = $propertyMiceRevenues;
            } else {
                $property->mice_revenue_breakdown = collect();
            }

            $property->dailyRevenue = $dailyRevenue;
            $totalOverallRevenue += $dailyRevenue;

            $totalArrRevenue = 0;
            $totalArrRoomsSold = 0;
            $arrRevenueCategories = ['offline_room_income', 'online_room_income', 'ta_income', 'gov_income', 'corp_income'];
            $arrRoomsCategories = ['offline_rooms', 'online_rooms', 'ta_rooms', 'gov_rooms', 'corp_rooms'];
            foreach ($arrRevenueCategories as $cat) {
                $totalArrRevenue += $property->{'total_' . $cat} ?? 0;
            }
            foreach ($arrRoomsCategories as $cat) {
                $totalArrRoomsSold += $property->{'total_' . $cat} ?? 0;
            }
            $property->averageRoomRate = ($totalArrRoomsSold > 0) ? ($totalArrRevenue / $totalArrRoomsSold) : 0;
        }
        
        // 4. Menyiapkan Data untuk Chart
        $pieChartCategories = [
            'offline_room_income' => 'Walk In', 'online_room_income' => 'OTA', 'ta_income' => 'Travel Agent',
            'gov_income' => 'Government', 'corp_income' => 'Corporation', 'afiliasi_room_income' => 'Afiliasi',
            'mice_income' => 'MICE', 'fnb_income' => 'F&B', 'others_income' => 'Lain-lain',
        ];

        $pieChartDataSource = new \stdClass();
        foreach ($pieChartCategories as $key => $label) {
            $totalKey = 'total_' . $key;
            if ($key === 'mice_income') {
                $pieChartDataSource->$totalKey = $miceRevenues->flatten()->sum('total_mice_revenue');
            } else if ($key === 'fnb_income') {
                $pieChartDataSource->$totalKey = $properties->sum('total_breakfast_income') + $properties->sum('total_lunch_income') + $properties->sum('total_dinner_income');
            } else {
                $pieChartDataSource->$totalKey = $properties->sum($totalKey);
            }
        }

        $recentMiceBookings = Booking::with(['property', 'miceCategory'])
            ->where('status', 'Booking Pasti')
            ->whereBetween('event_date', [$startDate, $endDate])
            ->when($propertyId, fn ($q) => $q->where('property_id', $propertyId))
            ->latest('event_date')->take(10)->get();

        $allPropertiesForFilter = Property::orderBy('name')->get();

        $overallIncomeByProperty = $properties->map(function ($property) {
            return (object)[
                'name' => $property->name,
                'total_revenue' => $property->dailyRevenue,
                'chart_color' => $property->chart_color,
            ];
        });

        // 5. Mengirim Data ke View
        return view('admin.dashboard', [
            'properties' => $properties,
            'totalOverallRevenue' => $totalOverallRevenue,
            'allPropertiesForFilter' => $allPropertiesForFilter,
            'propertyId' => $propertyId, 'period' => $period,
            'startDate' => $startDate, 'endDate' => $endDate,
            'incomeCategories' => $incomeCategories,
            'recentMiceBookings' => $recentMiceBookings,
            'pieChartDataSource' => $pieChartDataSource,
            'pieChartCategories' => $pieChartCategories,
            'overallIncomeByProperty' => $overallIncomeByProperty,
        ]);
    }

    public function salesAnalytics()
    {
=======
    /**
     * Menampilkan dashboard admin dengan data ringkasan, KPI, dan chart yang telah difilter.
     * NOTE: Logika utama tampaknya berada di metode index(). Metode __invoke() ini mungkin tidak digunakan.
     */
    public function __invoke(Request $request)
    {
        // Logika di sini tampaknya duplikat dari metode index().
        // Merekomendasikan untuk mengarahkan rute ke metode index() untuk konsistensi.
        return $this->index($request);
    }
    
    public function salesAnalytics()
    {
        // ======================================================
        // Mengambil semua data yang berhubungan dengan event
        // ======================================================

        // 1. Data untuk kartu statistik
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
        $totalEventRevenue = Booking::where('status', 'Booking Pasti')->sum('total_price');
        $totalBookings = Booking::count();
        $totalConfirmedBookings = Booking::where('status', 'Booking Pasti')->count();
        $totalActivePackages = PricePackage::where('is_active', true)->count();

<<<<<<< HEAD
=======
        // 2. Data untuk Grafik Pie Status Booking
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
        $bookingStatusData = Booking::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');
            
        $pieChartData = [
            'labels' => $bookingStatusData->keys(),
            'data' => $bookingStatusData->values(),
        ];
        
<<<<<<< HEAD
=======
        // 3. Data untuk Grafik Batang Pendapatan Event 12 Bulan Terakhir
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
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
<<<<<<< HEAD
=======
    
    // app/Http/Controllers/Admin/DashboardController.php

public function index(Request $request)
{
    $propertyId = $request->input('property_id');
    $period = $request->input('period', 'year');

    // =========================================================================
    // >> AWAL PERUBAHAN: Logika untuk menangani filter bulan custom <<
    // =========================================================================
    // Jika start_date dan end_date ada di URL, gunakan itu sebagai filter utama.
    if ($request->has('start_date') && $request->has('end_date')) {
        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
        // Set $period ke 'custom' untuk menandai bahwa ini bukan filter default.
        $period = 'custom';
    } else {
        // Jika tidak ada, gunakan logika filter periode yang sudah ada.
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
            default:
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
        }
    }
    // =========================================================================
    // >> AKHIR PERUBAHAN <<
    // =========================================================================

    $incomeCategories = [
        'offline_room_income' => 'Walk In', 'online_room_income' => 'OTA', 'ta_income' => 'Travel Agent',
        'gov_income' => 'Government', 'corp_income' => 'Corporation', 'compliment_income' => 'Compliment',
        'house_use_income' => 'House Use', 'mice_income' => 'MICE', 'breakfast_income' => 'Breakfast',
        'lunch_income' => 'Lunch', 'dinner_income' => 'Dinner', 'others_income' => 'Lain-lain',
    ];
    $incomeColumns = array_keys($incomeCategories);

    $dateFilter = function ($query) use ($startDate, $endDate) {
        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }
    };

    $roomCountColumns = ['offline_rooms', 'online_rooms', 'ta_rooms', 'gov_rooms', 'corp_rooms', 'compliment_rooms', 'house_use_rooms'];
    $propertiesQuery = Property::when($propertyId, fn($q) => $q->where('id', $propertyId))->orderBy('id', 'asc');

    foreach ($incomeColumns as $column) {
        $propertiesQuery->withSum(['dailyIncomes as total_' . $column => $dateFilter], $column);
    }
    foreach ($roomCountColumns as $column) {
        $propertiesQuery->withSum(['dailyIncomes as total_' . $column => $dateFilter], $column);
    }
    
    $properties = $propertiesQuery->get();

    $miceRevenues = Booking::where('status', 'Booking Pasti')
        ->whereBetween('event_date', [$startDate, $endDate])
        ->when($propertyId, fn($q) => $q->where('property_id', $propertyId))
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

    $pieChartCategories = [];
    $pieChartDataSource = new \stdClass();

    $totalMiceFromBookings = Booking::where('status', 'Booking Pasti')
        ->whereBetween('event_date', [$startDate, $endDate])
        ->when($propertyId, fn($q) => $q->where('property_id', $propertyId))
        ->sum('total_price');

    $totalFnbIncome = 0;
    if ($overallIncomeSource) {
        $totalFnbIncome = ($overallIncomeSource->total_breakfast_income ?? 0) +
                            ($overallIncomeSource->total_lunch_income ?? 0) +
                            ($overallIncomeSource->total_dinner_income ?? 0);
    }

    $pieChartCategories = [
        'offline_room_income' => 'Walk In', 'online_room_income' => 'OTA', 'ta_income' => 'Travel Agent',
        'gov_income' => 'Government', 'corp_income' => 'Corporation', 'compliment_income' => 'Compliment',
        'house_use_income' => 'House Use', 'mice_income' => 'MICE', 'fnb_income' => 'F&B',
        'others_income' => 'Lain-lain',
    ];
    
    foreach ($pieChartCategories as $key => $label) {
        if ($key === 'fnb_income') {
            $pieChartDataSource->{'total_' . $key} = $totalFnbIncome;
        } elseif ($key === 'mice_income') {
            $pieChartDataSource->{'total_' . $key} = $totalMiceFromBookings;
        } else {
            $sourceKey = 'total_' . $key;
            $pieChartDataSource->{$sourceKey} = $overallIncomeSource->{$sourceKey} ?? 0;
        }
    }
    
    $incomeSumRaw = implode(' + ', array_map(fn($col) => "IFNULL(`$col`, 0)", $incomeColumns));
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
    
    $miceDateFilter = function ($query) use ($startDate, $endDate) {
        if ($startDate && $endDate) {
            $query->whereBetween('event_date', [$startDate, $endDate]);
        }
    };

    $recentMiceBookings = Booking::with(['property', 'miceCategory'])
        ->where('status', 'Booking Pasti')
        ->when($propertyId, fn($q) => $q->where('property_id', $propertyId))
        ->where($miceDateFilter)
        ->latest('event_date')
        ->take(10)
        ->get();
    
    $allPropertiesForFilter = Property::orderBy('name')->get();

    return view('admin.dashboard', [
        'properties' => $properties,
        'allPropertiesForFilter' => $allPropertiesForFilter,
        'propertyId' => $propertyId,
        'period' => $period,
        'startDate' => $startDate, // Kirim startDate ke view
        'endDate' => $endDate,     // Kirim endDate ke view
        'overallIncomeSource' => $overallIncomeSource,
        'overallIncomeByProperty' => $overallIncomeByProperty,
        'incomeCategories' => $incomeCategories,
        'recentMiceBookings' => $recentMiceBookings,
        'pieChartDataSource' => $pieChartDataSource,
        'pieChartCategories' => $pieChartCategories,
    ]);
}
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9

    public function kpiAnalysis(Request $request)
    {
        // --- 1. SETUP FILTER & DEFINISI KATEGORI ---
        $filterStartDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
        $filterEndDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();
<<<<<<< HEAD
    
=======

>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
        if ($filterStartDate->gt($filterEndDate)) {
            $filterStartDate = Carbon::now()->subDays(29)->startOfDay();
            $filterEndDate = Carbon::now()->endOfDay();
        }
<<<<<<< HEAD
    
        // PERBAIKAN 1: Hitung total hari sebagai integer
        $totalDaysInPeriod = (int) $filterStartDate->diffInDays($filterEndDate) + 1;
    
        $propertyMomFilterId = $request->input('property_mom_filter_id');
        $allPropertiesForFilter = Property::orderBy('name')->get();
    
        $dailyIncomeColumns = [
            'offline_room_income', 'online_room_income', 'ta_income', 'gov_income', 'corp_income', 'compliment_income',
            'house_use_income', 'afiliasi_room_income', 'breakfast_income', 'lunch_income', 'dinner_income', 'others_income'
        ];
        $dailyIncomeSumRaw = implode(' + ', array_map(fn($col) => "IFNULL(`$col`, 0)", $dailyIncomeColumns));
    
        $categories = [
            'offline_room_income' => 'Walk In', 'online_room_income' => 'OTA', 'ta_income' => 'Travel Agent',
            'gov_income' => 'Government', 'corp_income' => 'Corporation', 'compliment_income' => 'Compliment',
            'house_use_income' => 'House Use', 'afiliasi_room_income' => 'Afiliasi',
            'mice_income' => 'MICE', 'fnb_income' => 'F&B', 'others_income' => 'Lain-lain',
        ];
    
        // --- Bagian 1: Data untuk KPI Ringkasan Keseluruhan ---
        $overallIncomeQuery = DailyIncome::query()->whereBetween('date', [$filterStartDate, $filterEndDate]);
        $totalFromDailyIncomes = (clone $overallIncomeQuery)->sum(DB::raw($dailyIncomeSumRaw));
        $totalMiceRevenue = Booking::where('status', 'Booking Pasti')->whereBetween('event_date', [$filterStartDate, $filterEndDate])->sum('total_price');
        $totalOverallRevenue = $totalFromDailyIncomes + $totalMiceRevenue;
=======

        $propertyMomFilterId = $request->input('property_mom_filter_id');
        $allPropertiesForFilter = Property::orderBy('name')->get();

        // KATEGORI PENDAPATAN (F&B DIGABUNGKAN)
        $categories = [
            'offline_room_income' => 'Walk In',
            'online_room_income' => 'OTA',
            'ta_income' => 'Travel Agent',
            'gov_income' => 'Government',
            'corp_income' => 'Corporation',
            'compliment_income' => 'Compliment',
            'house_use_income' => 'House Use',
            'mice_income' => 'MICE',
            'total_fb_revenue' => 'F&B',
            'others_income' => 'Lain-lain',
        ];
        $categoryColumns = array_keys($categories);
        $categoryLabels = array_values($categories);
        $categoryColors = ['#e6194B', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#42d4f4', '#f032e6', '#bfef45', '#808000', '#000075', '#a9a9a9'];
        $incomeSumRaw = implode(' + ', array_map(fn($col) => "IFNULL(`$col`, 0)", $categoryColumns));

        // --- Inisialisasi variabel ---
        $trendKontribusiData = ['labels' => [], 'datasets' => []];
        $multiMonthCategoryGrowth = [];
        $targetAnalysis = ['details' => []];
        $dataCompliance = ['days_without_entry' => []];

        // --- Bagian 1: Data untuk KPI Ringkasan Keseluruhan ---
        $overallIncomeQuery = DailyIncome::query()->whereBetween('date', [$filterStartDate, $filterEndDate]);
        $totalOverallRevenue = (clone $overallIncomeQuery)->sum(DB::raw($incomeSumRaw));
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
        $uniqueDaysWithIncome = (clone $overallIncomeQuery)->select(DB::raw('COUNT(DISTINCT date) as count'))->first()->count;
        $averageDailyOverallRevenue = ($uniqueDaysWithIncome > 0) ? $totalOverallRevenue / $uniqueDaysWithIncome : 0;
        $activePropertiesCount = Property::count();
        $activePropertyUsersCount = User::where('role', 'pengguna_properti')->whereNull('deleted_at')->count();
        $averageRevenuePerProperty = ($activePropertiesCount > 0) ? $totalOverallRevenue / $activePropertiesCount : 0;
<<<<<<< HEAD
        
        $overallIncomeSource = (clone $overallIncomeQuery)
            ->selectRaw("SUM(IFNULL(offline_room_income, 0)) as total_offline_room_income, SUM(IFNULL(online_room_income, 0)) as total_online_room_income, SUM(IFNULL(ta_income, 0)) as total_ta_income, SUM(IFNULL(gov_income, 0)) as total_gov_income, SUM(IFNULL(corp_income, 0)) as total_corp_income, SUM(IFNULL(compliment_income, 0)) as total_compliment_income, SUM(IFNULL(house_use_income, 0)) as total_house_use_income, SUM(IFNULL(afiliasi_room_income, 0)) as total_afiliasi_room_income, SUM(IFNULL(breakfast_income, 0) + IFNULL(lunch_income, 0) + IFNULL(dinner_income, 0)) as total_fnb_income, SUM(IFNULL(others_income, 0)) as total_others_income")
            ->first();
        $overallIncomeSource->total_mice_income = $totalMiceRevenue;
        
        $overallIncomeByProperty = Property::query()
            ->leftJoin('daily_incomes as di', fn($j) => $j->on('properties.id', '=', 'di.property_id')->whereBetween('di.date', [$filterStartDate, $filterEndDate]))
            ->leftJoin('bookings as b', fn($j) => $j->on('properties.id', '=', 'b.property_id')->where('b.status', 'Booking Pasti')->whereBetween('b.event_date', [$filterStartDate, $filterEndDate]))
            ->select('properties.name', 'properties.id', 'properties.chart_color', DB::raw("SUM({$dailyIncomeSumRaw}) + SUM(IFNULL(b.total_price, 0)) as total_revenue"))
            ->groupBy('properties.id', 'properties.name', 'properties.chart_color')->orderBy('properties.id', 'asc')->get();
    
        // --- Bagian 2: Data untuk Analisis Detail per Kategori ---
        $useDailyView = $totalDaysInPeriod <= 62;
        // PERBAIKAN 2: Ubah format tanggal untuk chart harian
        $periodFormat = $useDailyView ? 'D MMM' : 'MMMM YYYY'; 
        $dateFormat = $useDailyView ? '%Y-%m-%d' : '%Y-%m';
        $periodIteratorUnit = $useDailyView ? '1 day' : '1 month';
    
        $categoryQueryBase = DailyIncome::query()->whereBetween('date', [$filterStartDate, $filterEndDate]);
        if ($propertyMomFilterId && $propertyMomFilterId != 'all') {
            $categoryQueryBase->where('property_id', $propertyMomFilterId);
        }
        
        $selectCategorySums = [DB::raw("DATE_FORMAT(date, '{$dateFormat}') as period_key")];
        foreach ($dailyIncomeColumns as $column) {
            $selectCategorySums[] = DB::raw("SUM(IFNULL(`{$column}`, 0)) as total_{$column}");
        }
        $categoryIncome = (clone $categoryQueryBase)->select($selectCategorySums)->groupBy('period_key')->orderBy('period_key', 'asc')->get()->keyBy('period_key');
    
        $miceIncomeGrouped = Booking::query()->where('status', 'Booking Pasti')->whereBetween('event_date', [$filterStartDate, $filterEndDate])
            ->when($propertyMomFilterId && $propertyMomFilterId != 'all', fn($q) => $q->where('property_id', $propertyMomFilterId))
            ->select(DB::raw("DATE_FORMAT(event_date, '{$dateFormat}') as period_key"), DB::raw('SUM(total_price) as total_mice_income'))
            ->groupBy('period_key')->get()->keyBy('period_key');
    
        $periodIterator = CarbonPeriod::create($filterStartDate, $periodIteratorUnit, $filterEndDate);
        $trendKontribusiData = ['labels' => [], 'datasets' => []];
        $categoryDatasets = [];
        foreach ($categories as $key => $label) {
            $categoryDatasets[$key] = [];
        }
        
        foreach ($periodIterator as $date) {
            $key = $date->format($useDailyView ? 'Y-m-d' : 'Y-m');
            $trendKontribusiData['labels'][] = $date->isoFormat($periodFormat);
            
            $dailyData = $categoryIncome->get($key);
            $miceData = $miceIncomeGrouped->get($key);
    
            foreach ($categories as $catKey => $label) {
                $value = 0;
                if ($catKey === 'mice_income') {
                    $value = $miceData->total_mice_income ?? 0;
                } else if ($catKey === 'fnb_income') {
                    $value = ($dailyData->total_breakfast_income ?? 0) + ($dailyData->total_lunch_income ?? 0) + ($dailyData->total_dinner_income ?? 0);
                } else if($dailyData) {
                    $value = $dailyData->{'total_'.$catKey} ?? 0;
                }
                $categoryDatasets[$catKey][] = $value;
            }
        }
        
        $categoryColors = ['#e6194B', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#42d4f4', '#f032e6', '#bfef45', '#808000'];
        $colorIndex = 0;
        foreach ($categories as $key => $label) {
            if (collect($categoryDatasets[$key])->sum() > 0) {
                $trendKontribusiData['datasets'][] = [
                    'label' => $label, 'data' => $categoryDatasets[$key],
                    'borderColor' => $categoryColors[$colorIndex % count($categoryColors)],
                    'backgroundColor' => $categoryColors[$colorIndex % count($categoryColors)],
                    'fill' => false, 'tension' => 0.1,
                ];
                $colorIndex++;
            }
        }
        
        $multiMonthCategoryGrowth = [];
    
=======

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
                    'borderColor' => $categoryColors[$key % count($categoryColors)], 'backgroundColor' => $categoryColors[$key % count($categoryColors)],
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
                $multiMonthCategoryGrowth[$currentMonthCarbon->isoFormat('MMMMfindIndex')] = $growthForCurrentMonth;
            }
        }

>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
        // --- Bagian 3 & 4 (Target dan Kepatuhan) ---
        $allPropertiesForTargetAnalysis = Property::get();
        $propertyTargetAchievements = [];
        $totalAchievementSum = 0;
        $propertiesWithTargetsCount = 0;
        $propertiesAchievedTargetCount = 0;
<<<<<<< HEAD
    
        foreach ($allPropertiesForTargetAnalysis as $property) {
            $targetsInPeriod = RevenueTarget::where('property_id', $property->id)->whereBetween('month_year', [$filterStartDate->copy()->startOfMonth()->toDateString(), $filterEndDate->copy()->endOfMonth()->toDateString()])->get();
            $totalTargetAmountForPeriod = $targetsInPeriod->sum('target_amount');
            
            $totalActualRevenueForPeriod = DailyIncome::where('property_id', $property->id)->whereBetween('date', [$filterStartDate, $filterEndDate])->sum(DB::raw($dailyIncomeSumRaw));
            $totalActualRevenueForPeriod += Booking::where('property_id', $property->id)->where('status', 'Booking Pasti')->whereBetween('event_date', [$filterStartDate, $filterEndDate])->sum('total_price');
    
=======

        foreach ($allPropertiesForTargetAnalysis as $property) {
            $targetsInPeriod = RevenueTarget::where('property_id', $property->id)->whereBetween('month_year', [$filterStartDate->copy()->startOfMonth()->toDateString(), $filterEndDate->copy()->endOfMonth()->toDateString()])->get();
            $totalTargetAmountForPeriod = $targetsInPeriod->sum('target_amount');
            $totalActualRevenueForPeriod = DailyIncome::where('property_id', $property->id)->whereBetween('date', [$filterStartDate, $filterEndDate])->sum(DB::raw($incomeSumRaw));
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
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
<<<<<<< HEAD
        $percentagePropertiesAchieved = ($propertiesWithTargetsCount > 0) ? round(($propertiesWithTargetsCount / $propertiesWithTargetsCount) * 100, 2) : 0;
=======
        $percentagePropertiesAchieved = ($propertiesWithTargetsCount > 0) ? round(($propertiesAchievedTargetCount / $propertiesWithTargetsCount) * 100, 2) : 0;
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
        $sortableAchievements = array_filter($propertyTargetAchievements, fn($item) => $item['has_valid_target']);
        usort($sortableAchievements, fn($a, $b) => $b['achievement_percentage'] <=> $a['achievement_percentage']);
        $topPropertyTarget = !empty($sortableAchievements) ? $sortableAchievements[0] : null;
        $bottomPropertyTarget = !empty($sortableAchievements) ? end($sortableAchievements) : null;
        $targetAnalysis = ['properties_achieved_count' => $propertiesAchievedTargetCount, 'properties_achieved_percentage' => $percentagePropertiesAchieved, 'average_achievement_percentage' => $averageOverallAchievement, 'top_property_target' => $topPropertyTarget, 'bottom_property_target' => $bottomPropertyTarget, 'details' => $propertyTargetAchievements];
<<<<<<< HEAD
    
        // PERBAIKAN 3: Tambahkan $useDailyView ke compact()
        return view('admin.kpi_analysis', compact(
            'overallIncomeSource', 'overallIncomeByProperty', 'totalOverallRevenue',
            'averageDailyOverallRevenue', 'activePropertiesCount', 'activePropertyUsersCount',
            'averageRevenuePerProperty', 'targetAnalysis', 'filterStartDate', 'filterEndDate',
            'allPropertiesForFilter', 'propertyMomFilterId', 'categories', 'totalDaysInPeriod',
            'trendKontribusiData', 'multiMonthCategoryGrowth', 'useDailyView'
=======

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
            'monthName' => $pacingMonth->isoFormat('MMMMfindIndex'),
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
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
        ));
    }
    public function exportPropertiesSummaryExcel(Request $request)
    {
        // Logika untuk menentukan filter tanggal (sama seperti di method index)
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
        } else {
            $period = $request->input('period', 'year'); // Default 'year' jika tidak ada
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
                default:
                    $startDate = Carbon::now()->startOfYear();
                    $endDate = Carbon::now()->endOfYear();
                    break;
            }
        }
        
        $propertyId = $request->input('property_id');
        
        // Siapkan nama file
        $fileName = 'Laporan_Pendapatan_Properti_' . now()->format('d-m-Y_H-i') . '.xlsx';
        
        // Panggil class export yang sudah ada dengan filter yang sesuai
        return Excel::download(new AdminPropertiesSummaryExport($startDate, $endDate, $propertyId), $fileName);
    }

    /**
     * Menangani ekspor data ringkasan properti ke CSV.
     */
    public function exportPropertiesSummaryCsv(Request $request)
    {
        return Excel::download(new AdminPropertiesSummaryExport($request), 'properties-summary-'.now()->format('Ymd').'.csv');
    }
<<<<<<< HEAD
    public function unifiedCalendar()
    {
        // Ambil semua properti untuk ditampilkan di filter dropdown
        $properties = Property::orderBy('name')->get();

        return view('admin.calendar.unified_index', compact('properties'));
    }

    /**
     * Menyediakan data event untuk kalender terpusat (Ecommerce atau Sales).
     */
    public function getUnifiedCalendarEvents(Request $request)
    {
        $source = $request->query('source', 'ecommerce');
        $propertyId = $request->query('property_id'); // Ambil ID properti dari request
        $response = [];

        if ($source === 'sales') {
            $eventsQuery = Booking::query();
            if ($propertyId && $propertyId !== 'all') {
                $eventsQuery->where('property_id', $propertyId);
            }
            $events = $eventsQuery->select(
                'client_name as title',
                'event_date as start',
                DB::raw('DATE_ADD(event_date, INTERVAL 1 DAY) as end'),
                DB::raw("'#3B82F6' as color")
            )->get();
            $response['events'] = $events;
        } else { // ecommerce
            $eventsQuery = Reservation::query();
            if ($propertyId && $propertyId !== 'all') {
                $eventsQuery->where('property_id', $propertyId);
            }
            $events = $eventsQuery->select(
                'guest_name as title',
                'checkin_date as start',
                'checkout_date as end',
                DB::raw("'#10B981' as color")
            )->get();
            $response['events'] = $events;

            // === LOGIKA CHART DENGAN FILTER PROPERTI ===
            $startDate = Carbon::now()->subDays(30);
            
            $chartQuery = DailyOccupancy::query()
                ->where('date', '>=', $startDate);

            // Terapkan filter jika ada properti yang dipilih
            if ($propertyId && $propertyId !== 'all') {
                $chartQuery->where('property_id', $propertyId);
            }

            $chartOccupancyData = $chartQuery->select(
                    'date',
                    // Gunakan SUM karena jika tidak ada filter, kita menjumlahkan semua properti
                    DB::raw('SUM(occupied_rooms) as total_occupied')
                )
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get();

            $response['chartData'] = [
                'labels' => $chartOccupancyData->pluck('date')->map(fn ($date) => Carbon::parse($date)->format('d M')),
                'data' => $chartOccupancyData->pluck('total_occupied'),
            ];
            // ===============================================
        }

        return response()->json($response);
    }
=======
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
}
>>>>>>> origin/master
