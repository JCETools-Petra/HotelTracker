<?php

// app/Http/Controllers/Admin/DashboardController.php
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
use App\Exports\AdminPropertiesSummaryExport;

class DashboardController extends Controller
{
    // Method index(), exportPropertiesSummaryExcel(), exportPropertiesSummaryCsv(),
    // overallChartData(), propertyChartData() tetap sama.
    // Hanya method kpiAnalysis() yang akan dimodifikasi signifikan.

    public function index()
    {
        $properties = Property::withCount(['dailyIncomes as total_income_records'])
                                ->withSum('dailyIncomes as total_mice_income', 'mice_income')
                                ->withSum('dailyIncomes as total_fnb_income', 'fnb_income')
                                ->withSum('dailyIncomes as total_offline_room_income', 'offline_room_income')
                                ->withSum('dailyIncomes as total_online_room_income', 'online_room_income')
                                ->withSum('dailyIncomes as total_others_income', 'others_income')
                                ->get();

        $overallIncomeByProperty = Property::select('properties.name', 'properties.id')
            ->leftJoin('daily_incomes as di', 'properties.id', '=', 'di.property_id')
            ->selectRaw('properties.name, SUM(
                IFNULL(di.mice_income, 0) +
                IFNULL(di.fnb_income, 0) +
                IFNULL(di.offline_room_income, 0) +
                IFNULL(di.online_room_income, 0) +
                IFNULL(di.others_income, 0)
            ) as total_revenue')
            ->groupBy('properties.id', 'properties.name')
            ->orderByDesc('total_revenue')
            ->get();

        $overallIncomeSource = DailyIncome::select(
                DB::raw('SUM(IFNULL(mice_income,0)) as total_mice'),
                DB::raw('SUM(IFNULL(fnb_income,0)) as total_fnb'),
                DB::raw('SUM(IFNULL(offline_room_income,0)) as total_offline_room'),
                DB::raw('SUM(IFNULL(online_room_income,0)) as total_online_room'),
                DB::raw('SUM(IFNULL(others_income,0)) as total_others')
            )->first();

        return view('admin.dashboard', compact('properties', 'overallIncomeByProperty', 'overallIncomeSource'));
    }


    public function exportPropertiesSummaryExcel()
    {
        $propertiesData = Property::withCount(['dailyIncomes as total_income_records'])
                                ->withSum('dailyIncomes as total_mice_income', 'mice_income')
                                ->withSum('dailyIncomes as total_fnb_income', 'fnb_income')
                                ->withSum('dailyIncomes as total_offline_room_income', 'offline_room_income')
                                ->withSum('dailyIncomes as total_online_room_income', 'online_room_income')
                                ->withSum('dailyIncomes as total_others_income', 'others_income')
                                ->orderBy('name', 'asc')
                                ->get();

        $fileName = 'ringkasan_semua_properti_' . Carbon::now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new AdminPropertiesSummaryExport($propertiesData), $fileName);
    }

    public function exportPropertiesSummaryCsv()
    {
        $propertiesData = Property::withCount(['dailyIncomes as total_income_records'])
                                ->withSum('dailyIncomes as total_mice_income', 'mice_income')
                                ->withSum('dailyIncomes as total_fnb_income', 'fnb_income')
                                ->withSum('dailyIncomes as total_offline_room_income', 'offline_room_income')
                                ->withSum('dailyIncomes as total_online_room_income', 'online_room_income')
                                ->withSum('dailyIncomes as total_others_income', 'others_income')
                                ->orderBy('name', 'asc')
                                ->get();

        $fileName = 'ringkasan_semua_properti_' . Carbon::now()->format('Ymd_His') . '.csv';
        return Excel::download(new AdminPropertiesSummaryExport($propertiesData), $fileName, \Maatwebsite\Excel\Excel::CSV);
    }

    public function overallChartData()
    {
        // Logika ini mungkin perlu disesuaikan jika filter tanggal juga diterapkan di sini
        // Untuk saat ini, saya biarkan seperti aslinya yang mengambil semua data
        $data = DailyIncome::select(
            DB::raw('SUM(mice_income) as mice'),
            DB::raw('SUM(fnb_income) as fnb'),
            DB::raw('SUM(offline_room_income) as offline_room'),
            DB::raw('SUM(online_room_income) as online_room'),
            DB::raw('SUM(others_income) as others')
        )->first();

        return response()->json([
            'labels' => ['MICE', 'F&B', 'Room Offline', 'Room Online', 'Others'],
            'datasets' => [
                [
                    'label' => 'Total Pendapatan',
                    'data' => [
                        $data->mice ?? 0,
                        $data->fnb ?? 0,
                        $data->offline_room ?? 0,
                        $data->online_room ?? 0,
                        $data->others ?? 0,
                    ],
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                ]
            ]
        ]);
    }

    public function propertyChartData(Property $property)
    {
        // Logika ini mungkin perlu disesuaikan jika filter tanggal juga diterapkan di sini
        $data = DailyIncome::where('property_id', $property->id)
            ->select(
                DB::raw('SUM(mice_income) as mice'),
                DB::raw('SUM(fnb_income) as fnb'),
                DB::raw('SUM(offline_room_income) as offline_room'),
                DB::raw('SUM(online_room_income) as online_room'),
                DB::raw('SUM(others_income) as others')
            )->first();

          return response()->json([
            'labels' => ['MICE', 'F&B', 'Room Offline', 'Room Online', 'Others'],
            'datasets' => [
                [
                    'label' => 'Total Pendapatan ' . $property->name,
                    'data' => [
                        $data->mice ?? 0,
                        $data->fnb ?? 0,
                        $data->offline_room ?? 0,
                        $data->online_room ?? 0,
                        $data->others ?? 0,
                    ],
                    'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                ]
            ]
        ]);
    }

    public function kpiAnalysis(Request $request)
    {
        $filterStartDate = $request->filled('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $filterEndDate = $request->filled('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;

        // Jika salah satu tanggal diisi, tapi yang lain tidak, set default yang lain (misalnya, 30 hari dari yang diisi)
        // Atau, bisa juga mewajibkan kedua tanggal jika salah satu diisi. Untuk saat ini, kita biarkan null jika tidak diisi.
        if ($filterStartDate && !$filterEndDate) {
            // $filterEndDate = $filterStartDate->copy()->addDays(29)->endOfDay(); // Contoh: default 30 hari
            // Atau, bisa juga dikosongkan agar user harus mengisi keduanya
             $filterEndDate = null; // Atau biarkan null, dan view akan menampilkan pesan untuk mengisi
        } elseif (!$filterStartDate && $filterEndDate) {
            // $filterStartDate = $filterEndDate->copy()->subDays(29)->startOfDay(); // Contoh
             $filterStartDate = null;
        }

        // Jika kedua tanggal ada dan start > end, reset keduanya ke null atau tampilkan error
        if ($filterStartDate && $filterEndDate && $filterStartDate->gt($filterEndDate)) {
            // Opsi 1: Reset ke null
            $filterStartDate = null;
            $filterEndDate = null;
            // Opsi 2: Redirect dengan error (lebih baik untuk UX)
            // return redirect()->route('admin.kpi.analysis')->with('error', 'Tanggal mulai tidak boleh melebihi tanggal akhir.');
        }

        $propertyMomFilterId = $request->input('property_mom_filter_id');
        $allPropertiesForFilter = Property::orderBy('name')->get();

        // Inisialisasi semua variabel data ke nilai default (kosong atau 0)
        $overallIncomeSource = null;
        $totalOverallRevenue = 0;
        $averageDailyOverallRevenue = 0;
        $uniqueDaysWithIncome = 0;
        $overallIncomeByProperty = collect(); // Koleksi kosong
        $trendKontribusiData = ['labels' => [], 'datasets' => []];
        $multiMonthCategoryGrowth = [];
        $targetAnalysis = [
            'properties_achieved_count' => 0,
            'properties_achieved_percentage' => 0,
            'average_achievement_percentage' => null,
            'top_property_target' => null,
            'bottom_property_target' => null,
            'details' => []
        ];
        $dataCompliance = ['days_without_entry' => []];
        $totalDaysInPeriod = 0;

        // Hanya lakukan kalkulasi jika kedua filter tanggal valid dan telah diisi
        if ($filterStartDate && $filterEndDate) {
            // == Bagian 1: Data untuk KPI Ringkasan Keseluruhan ==
            $overallIncomeQuery = DailyIncome::query();
            $overallIncomeQuery->whereBetween('date', [$filterStartDate, $filterEndDate]);
            $filteredOverallIncomeQuery = clone $overallIncomeQuery;

            $overallIncomeSource = $filteredOverallIncomeQuery->select(
                    DB::raw('SUM(IFNULL(mice_income,0)) as total_mice'),
                    DB::raw('SUM(IFNULL(fnb_income,0)) as total_fnb'),
                    DB::raw('SUM(IFNULL(offline_room_income,0)) as total_offline_room'),
                    DB::raw('SUM(IFNULL(online_room_income,0)) as total_online_room'),
                    DB::raw('SUM(IFNULL(others_income,0)) as total_others')
                )->first();

            $totalOverallRevenue = ($overallIncomeSource->total_mice ?? 0) +
                                   ($overallIncomeSource->total_fnb ?? 0) +
                                   ($overallIncomeSource->total_offline_room ?? 0) +
                                   ($overallIncomeSource->total_online_room ?? 0) +
                                   ($overallIncomeSource->total_others ?? 0);

            $uniqueDaysQuery = DailyIncome::query();
            $uniqueDaysQuery->whereBetween('date', [$filterStartDate, $filterEndDate]);
            $uniqueDaysWithIncome = $uniqueDaysQuery->select(DB::raw('COUNT(DISTINCT date) as count'))->first()->count;

            $averageDailyOverallRevenue = ($uniqueDaysWithIncome > 0) ? $totalOverallRevenue / $uniqueDaysWithIncome : 0;
            
            $overallIncomeByPropertyQuery = Property::select('properties.name', 'properties.id')
                ->leftJoin('daily_incomes as di', 'properties.id', '=', 'di.property_id')
                ->selectRaw('properties.name, properties.id, SUM(
                    IFNULL(di.mice_income, 0) +
                    IFNULL(di.fnb_income, 0) +
                    IFNULL(di.offline_room_income, 0) +
                    IFNULL(di.online_room_income, 0) +
                    IFNULL(di.others_income, 0)
                ) as total_revenue')
                ->whereBetween('di.date', [$filterStartDate, $filterEndDate]); // Filter tanggal di sini
            $overallIncomeByProperty = $overallIncomeByPropertyQuery
                ->groupBy('properties.id', 'properties.name')
                ->orderByDesc('total_revenue')
                ->get();

            // == Bagian 2: Data untuk Analisis Kinerja per Kategori Pendapatan ==
            $categories = ['mice', 'fnb', 'offline_room', 'online_room', 'others'];
            $categoryLabels = ['MICE', 'F&B', 'Kamar Offline', 'Kamar Online', 'Lainnya'];
            $categoryColors = [
                'rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)', 'rgba(153, 102, 255, 0.7)'
            ];

            $monthlyCategoryIncomeQueryBase = DailyIncome::query();
            if ($propertyMomFilterId && $propertyMomFilterId != 'all') {
                $monthlyCategoryIncomeQueryBase->where('property_id', $propertyMomFilterId);
            }

            $monthlyCategoryIncomeQuery = clone $monthlyCategoryIncomeQueryBase;
            $monthlyCategoryIncomeQuery->select(
                    DB::raw("DATE_FORMAT(date, '%Y-%m') as month_year_formatted"),
                    DB::raw('SUM(IFNULL(mice_income,0)) as total_mice_monthly'),
                    DB::raw('SUM(IFNULL(fnb_income,0)) as total_fnb_monthly'),
                    DB::raw('SUM(IFNULL(offline_room_income,0)) as total_offline_room_monthly'),
                    DB::raw('SUM(IFNULL(online_room_income,0)) as total_online_room_monthly'),
                    DB::raw('SUM(IFNULL(others_income,0)) as total_others_monthly')
                )
                ->whereBetween('date', [$filterStartDate, $filterEndDate])
                ->groupBy('month_year_formatted')
                ->orderBy('month_year_formatted', 'asc');
            $monthlyCategoryIncome = $monthlyCategoryIncomeQuery->get();

            if(!$monthlyCategoryIncome->isEmpty()){
                $trendKontribusiData['labels'] = $monthlyCategoryIncome->pluck('month_year_formatted')->map(function($dateStr){
                    return Carbon::parse($dateStr . '-01')->isoFormat('MMM YY');
                })->all();
                foreach ($categories as $key => $category) {
                    $trendKontribusiData['datasets'][] = [
                        'label' => $categoryLabels[$key],
                        'data' => $monthlyCategoryIncome->pluck('total_' . $category . '_monthly')->all(),
                        'borderColor' => $categoryColors[$key],
                        'backgroundColor' => $categoryColors[$key],
                        'fill' => false,
                        'tension' => 0.1
                    ];
                }
            }

            if ($filterStartDate->diffInMonths($filterEndDate) >= 0) { // Memastikan ada rentang untuk MoM
                $period = CarbonPeriod::create($filterStartDate->copy()->startOfMonth(), '1 month', $filterEndDate->copy()->endOfMonth());
                foreach ($period as $currentMonthCarbon) {
                    $currentMonthStart = $currentMonthCarbon->copy()->startOfMonth();
                    $currentMonthEndLoop = $currentMonthCarbon->copy()->endOfMonth(); // Akhir bulan penuh untuk loop

                    // Batasi $currentMonthEndLoop dengan $filterEndDate
                    $currentMonthEndForQuery = $currentMonthEndLoop->gt($filterEndDate) ? $filterEndDate->copy() : $currentMonthEndLoop->copy();
                    
                    // Pastikan bulan saat ini relevan dengan rentang filter
                    if ($currentMonthStart->gt($filterEndDate) || $currentMonthEndForQuery->lt($filterStartDate)) {
                        continue;
                    }


                    $currentMonthTotalsQuery = DailyIncome::query();
                    if ($propertyMomFilterId && $propertyMomFilterId != 'all') {
                        $currentMonthTotalsQuery->where('property_id', $propertyMomFilterId);
                    }
                    $currentMonthTotals = $currentMonthTotalsQuery->whereBetween('date', [$currentMonthStart, $currentMonthEndForQuery])
                        ->select(
                            DB::raw('SUM(IFNULL(mice_income,0)) as mice'),
                            DB::raw('SUM(IFNULL(fnb_income,0)) as fnb'),
                            DB::raw('SUM(IFNULL(offline_room_income,0)) as offline_room'),
                            DB::raw('SUM(IFNULL(online_room_income,0)) as online_room'),
                            DB::raw('SUM(IFNULL(others_income,0)) as others')
                        )->first();

                    $previousMonthStart = $currentMonthStart->copy()->subMonthNoOverflow()->startOfMonth();
                    $previousMonthEnd = $currentMonthStart->copy()->subMonthNoOverflow()->endOfMonth();
                    
                    $hasPreviousMonthDataQuery = DailyIncome::query();
                    if ($propertyMomFilterId && $propertyMomFilterId != 'all') {
                        $hasPreviousMonthDataQuery->where('property_id', $propertyMomFilterId);
                    }
                    $hasPreviousMonthData = $hasPreviousMonthDataQuery->whereBetween('date', [$previousMonthStart, $previousMonthEnd])->exists();
                    
                    $previousMonthTotals = null;
                    if ($hasPreviousMonthData) {
                        $previousMonthTotalsQuery = DailyIncome::query();
                        if ($propertyMomFilterId && $propertyMomFilterId != 'all') {
                            $previousMonthTotalsQuery->where('property_id', $propertyMomFilterId);
                        }
                        $previousMonthTotals = $previousMonthTotalsQuery->whereBetween('date', [$previousMonthStart, $previousMonthEnd])
                            ->select(
                                DB::raw('SUM(IFNULL(mice_income,0)) as mice'),
                                DB::raw('SUM(IFNULL(fnb_income,0)) as fnb'),
                                DB::raw('SUM(IFNULL(offline_room_income,0)) as offline_room'),
                                DB::raw('SUM(IFNULL(online_room_income,0)) as online_room'),
                                DB::raw('SUM(IFNULL(others_income,0)) as others')
                            )->first();
                    }

                    $growthForCurrentMonth = [];
                    if ($currentMonthTotals) {
                        foreach ($categories as $catKey => $categorySlug) {
                            $currentValue = $currentMonthTotals->$categorySlug ?? 0;
                            $previousValue = $hasPreviousMonthData && $previousMonthTotals ? ($previousMonthTotals->$categorySlug ?? 0) : 0;
                            $growthDisplay = "N/A";

                            if (!$hasPreviousMonthData) {
                                $growthDisplay = ($currentValue > 0) ? "Data pembanding tidak cukup" : "N/A";
                            } elseif ($previousValue > 0) {
                                $growthNumeric = (($currentValue - $previousValue) / $previousValue) * 100;
                                $growthDisplay = sprintf("%+.2f%%", $growthNumeric);
                            } elseif ($currentValue > 0 && $previousValue == 0) {
                                $growthDisplay = "Baru";
                            } elseif ($currentValue == 0 && $previousValue == 0) {
                                $growthDisplay = "0.00%";
                            } else { 
                                 $growthDisplay = "-100.00%";
                            }
                            $growthForCurrentMonth[] = [
                                'category' => $categoryLabels[$catKey],
                                'growth' => $growthDisplay,
                                'current_value' => $currentValue,
                                'previous_value' => $previousValue
                            ];
                        }
                    }
                    $multiMonthCategoryGrowth[$currentMonthCarbon->isoFormat('MMMM YYYY')] = $growthForCurrentMonth;
                }
            }

            // == Bagian 3: Analisis Pencapaian Target Pendapatan ==
            $allPropertiesForTargetAnalysis = Property::get();
            $propertyTargetAchievements = [];
            $totalAchievementSum = 0;
            $propertiesWithTargetsCount = 0;
            $propertiesAchievedTargetCount = 0;

            foreach ($allPropertiesForTargetAnalysis as $property) {
                $targetsInPeriod = RevenueTarget::where('property_id', $property->id)
                    ->where('month_year', '>=', $filterStartDate->copy()->startOfMonth()->toDateString())
                    ->where('month_year', '<=', $filterEndDate->copy()->endOfMonth()->toDateString())
                    ->get();
                $totalTargetAmountForPeriod = $targetsInPeriod->sum('target_amount');

                $totalActualRevenueForPeriod = DailyIncome::where('property_id', $property->id)
                    ->whereBetween('date', [$filterStartDate, $filterEndDate])
                    ->sum(DB::raw('IFNULL(mice_income,0) + IFNULL(fnb_income,0) + IFNULL(offline_room_income,0) + IFNULL(online_room_income,0) + IFNULL(others_income,0)'));

                $achievementPercentage = 0;
                $hasValidTarget = $totalTargetAmountForPeriod > 0;

                if ($hasValidTarget) {
                    $achievementPercentage = ($totalActualRevenueForPeriod / $totalTargetAmountForPeriod) * 100;
                    $totalAchievementSum += $achievementPercentage;
                    $propertiesWithTargetsCount++;
                    if ($achievementPercentage >= 100) {
                        $propertiesAchievedTargetCount++;
                    }
                } elseif ($totalActualRevenueForPeriod > 0 && $totalTargetAmountForPeriod == 0) {
                    $achievementPercentage = null; 
                }
                
                $propertyTargetAchievements[] = [
                    'id' => $property->id,
                    'name' => $property->name,
                    'total_target' => $totalTargetAmountForPeriod,
                    'total_actual' => $totalActualRevenueForPeriod,
                    'achievement_percentage' => $achievementPercentage !== null ? round($achievementPercentage, 2) : null,
                    'has_valid_target' => $hasValidTarget
                ];
            }

            $averageOverallAchievement = ($propertiesWithTargetsCount > 0) ? round($totalAchievementSum / $propertiesWithTargetsCount, 2) : null;
            $percentagePropertiesAchieved = ($propertiesWithTargetsCount > 0) ? round(($propertiesAchievedTargetCount / $propertiesWithTargetsCount) * 100, 2) : 0;

            $sortableAchievements = array_filter($propertyTargetAchievements, function($item) {
                return $item['has_valid_target'] && $item['achievement_percentage'] !== null;
            });

            usort($sortableAchievements, function ($a, $b) {
                if ($a['achievement_percentage'] === null) return 1;
                if ($b['achievement_percentage'] === null) return -1;
                return $b['achievement_percentage'] <=> $a['achievement_percentage'];
            });
            
            $topPropertyTarget = !empty($sortableAchievements) ? $sortableAchievements[0] : null;
            $bottomPropertyTarget = !empty($sortableAchievements) ? end($sortableAchievements) : null;

            $targetAnalysis = [
                'properties_achieved_count' => $propertiesAchievedTargetCount,
                'properties_achieved_percentage' => $percentagePropertiesAchieved,
                'average_achievement_percentage' => $averageOverallAchievement,
                'top_property_target' => $topPropertyTarget,
                'bottom_property_target' => $bottomPropertyTarget,
                'details' => $propertyTargetAchievements
            ];
            
            // == Bagian 4: Analisis Kepatuhan Data ==
            // Perhitungan $totalDaysInPeriod dipindahkan ke atas, sebelum blok if ($filterStartDate && $filterEndDate)
            $periodForCompliance = CarbonPeriod::create($filterStartDate, '1 day', $filterEndDate);
            $allPropertiesForCompliance = Property::orderBy('name')->get(); // Bisa gunakan $allPropertiesForFilter
            foreach ($allPropertiesForCompliance as $property) {
                $datesWithIncome = DailyIncome::where('property_id', $property->id)
                                                ->whereBetween('date', [$filterStartDate, $filterEndDate])
                                                ->pluck('date')
                                                ->map(function ($date) {
                                                    return Carbon::parse($date)->toDateString();
                                                })
                                                ->toArray();
                $daysWithoutEntryCount = 0;
                foreach (clone $periodForCompliance as $dateInPeriod) {
                    if (!in_array($dateInPeriod->toDateString(), $datesWithIncome)) {
                        $daysWithoutEntryCount++;
                    }
                }
                 $dataCompliance['days_without_entry'][] = [
                    'property_name' => $property->name,
                    'days' => $daysWithoutEntryCount,
                    'total_days_in_period' => $totalDaysInPeriod, // Sudah dihitung di atas
                    'compliance_percentage' => $totalDaysInPeriod > 0 ? round((($totalDaysInPeriod - $daysWithoutEntryCount) / $totalDaysInPeriod) * 100, 2) : 0,
                ];
            }
        } // Akhir dari if ($filterStartDate && $filterEndDate)

        // Variabel ini dihitung di luar blok if karena mungkin diperlukan oleh view meskipun filter tidak aktif
        $activePropertiesCount = Property::count();
        $activePropertyUsersCount = User::where('role', 'pengguna_properti')->whereNull('deleted_at')->count();
        
        // Hitung $totalDaysInPeriod di sini, berlaku untuk semua kasus
        if ($filterStartDate && $filterEndDate && $filterStartDate->lte($filterEndDate)) {
            $periodForDayCount = CarbonPeriod::create($filterStartDate->copy()->startOfDay(), '1 day', $filterEndDate->copy()->endOfDay());
            $totalDaysInPeriod = count(iterator_to_array($periodForDayCount));
        } else {
            $totalDaysInPeriod = 0; // Atau 1 jika Anda ingin menampilkan "1 hari" jika tanggal sama
            if ($filterStartDate && $filterEndDate && $filterStartDate->isSameDay($filterEndDate)) {
                $totalDaysInPeriod = 1;
            }
        }


        return view('admin.kpi_analysis', compact(
            'overallIncomeSource', 'overallIncomeByProperty', 'totalOverallRevenue',
            'averageDailyOverallRevenue', 'activePropertiesCount', 'activePropertyUsersCount',
            'averageRevenuePerProperty',
            'trendKontribusiData',
            'multiMonthCategoryGrowth',
            'targetAnalysis',
            'dataCompliance',
            'filterStartDate', // Kirim null jika tidak di-set
            'filterEndDate',   // Kirim null jika tidak di-set
            'totalDaysInPeriod',
            'allPropertiesForFilter', 
            'propertyMomFilterId'
        ));
    }
}
