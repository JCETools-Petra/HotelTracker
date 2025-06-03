<?php

  // app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\DailyIncome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AdminPropertiesSummaryExport;
use App\Models\User; // Pastikan ini sudah ada atau tambahkan jika belum
use Illuminate\Support\Collection;
use Carbon\CarbonPeriod; 


class DashboardController extends Controller
{
    public function index()
    {
        $properties = Property::withCount(['dailyIncomes as total_income_records'])
                                ->withSum('dailyIncomes as total_mice_income', 'mice_income')
                                ->withSum('dailyIncomes as total_fnb_income', 'fnb_income')
                                ->withSum('dailyIncomes as total_offline_room_income', 'offline_room_income')
                                ->withSum('dailyIncomes as total_online_room_income', 'online_room_income')
                                ->withSum('dailyIncomes as total_others_income', 'others_income')
                                ->get();

        // Data untuk diagram total pendapatan per properti
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

        // Data untuk diagram sumber pendapatan keseluruhan
        // Menggunakan first() akan mengembalikan null jika tabel daily_incomes kosong
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
        // Ambil data properti dengan sum yang sama seperti di metode index()
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

    /**
     * Menangani permintaan ekspor ringkasan properti ke CSV.
     */
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



    // Metode untuk menyediakan data ke Chart.js via AJAX (opsional)
    public function overallChartData()
    {
        // Contoh data untuk pie chart: Total pendapatan per kategori secara keseluruhan
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
        // Data untuk pie chart per properti
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
        $filterStartDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
        $filterEndDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();

        if ($filterStartDate->gt($filterEndDate)) {
            $filterStartDate = Carbon::now()->subDays(29)->startOfDay();
            $filterEndDate = Carbon::now()->endOfDay();
        }

        // == Bagian 1: Data untuk KPI Ringkasan Keseluruhan ==
        // ... (kode Bagian 1 tetap sama) ...
        $overallIncomeQuery = DailyIncome::query();
        if ($filterStartDate && $filterEndDate) {
            $overallIncomeQuery->whereBetween('date', [$filterStartDate, $filterEndDate]);
        }
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
        if ($filterStartDate && $filterEndDate) {
            $uniqueDaysQuery->whereBetween('date', [$filterStartDate, $filterEndDate]);
        }
        $uniqueDaysWithIncome = $uniqueDaysQuery->select(DB::raw('COUNT(DISTINCT date) as count'))->first()->count;

        $averageDailyOverallRevenue = ($uniqueDaysWithIncome > 0) ? $totalOverallRevenue / $uniqueDaysWithIncome : 0;
        $activePropertiesCount = Property::count();
        $activePropertyUsersCount = User::where('role', 'pengguna_properti')->whereNull('deleted_at')->count();
        $averageRevenuePerProperty = ($activePropertiesCount > 0 && $totalOverallRevenue > 0) ? $totalOverallRevenue / $activePropertiesCount : 0;

        $overallIncomeByPropertyQuery = Property::select('properties.name', 'properties.id')
            ->leftJoin('daily_incomes as di', 'properties.id', '=', 'di.property_id')
            ->selectRaw('properties.name, properties.id, SUM(
                IFNULL(di.mice_income, 0) +
                IFNULL(di.fnb_income, 0) +
                IFNULL(di.offline_room_income, 0) +
                IFNULL(di.online_room_income, 0) +
                IFNULL(di.others_income, 0)
            ) as total_revenue');

        if ($filterStartDate && $filterEndDate) {
            $overallIncomeByPropertyQuery->whereBetween('di.date', [$filterStartDate, $filterEndDate]);
        }
        $overallIncomeByProperty = $overallIncomeByPropertyQuery
            ->groupBy('properties.id', 'properties.name')
            ->orderByDesc('total_revenue')
            ->get();


        // == Bagian 2: Data untuk Analisis Kinerja per Kategori Pendapatan ==
        $categories = ['mice', 'fnb', 'offline_room', 'online_room', 'others'];
        $categoryLabels = ['MICE', 'F&B', 'Kamar Offline', 'Kamar Online', 'Lainnya'];
        $categoryColors = [ // Tidak digunakan di sini, tapi mungkin untuk grafik lain
            'rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)', 'rgba(153, 102, 255, 0.7)'
        ];
        $trendKontribusiData = ['labels' => [], 'datasets' => []]; // Untuk grafik tren bulanan

        $monthlyCategoryIncomeQuery = DailyIncome::query()
            ->select(
                DB::raw("DATE_FORMAT(date, '%Y-%m') as month_year"),
                DB::raw('SUM(IFNULL(mice_income,0)) as total_mice_monthly'),
                DB::raw('SUM(IFNULL(fnb_income,0)) as total_fnb_monthly'),
                DB::raw('SUM(IFNULL(offline_room_income,0)) as total_offline_room_monthly'),
                DB::raw('SUM(IFNULL(online_room_income,0)) as total_online_room_monthly'),
                DB::raw('SUM(IFNULL(others_income,0)) as total_others_monthly')
            );
        if ($filterStartDate && $filterEndDate) {
            $monthlyCategoryIncomeQuery->whereBetween('date', [$filterStartDate, $filterEndDate]);
        }
        $monthlyCategoryIncome = $monthlyCategoryIncomeQuery->groupBy('month_year')
            ->orderBy('month_year', 'asc')
            ->get();

        if(!$monthlyCategoryIncome->isEmpty()){
            $trendKontribusiData['labels'] = $monthlyCategoryIncome->pluck('month_year')->map(function($dateStr){
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

        // KPI 2.2: Pertumbuhan Kategori Pendapatan (MoM untuk setiap bulan dalam rentang)
        $multiMonthCategoryGrowth = [];
        if ($filterStartDate && $filterEndDate && $filterStartDate->diffInMonths($filterEndDate) >= 0) { // Cek jika rentang valid
            // Buat periode per bulan dari start ke end
            $period = CarbonPeriod::create($filterStartDate->copy()->startOfMonth(), '1 month', $filterEndDate->copy()->endOfMonth());

            foreach ($period as $currentMonthCarbon) {
                $currentMonthStart = $currentMonthCarbon->copy()->startOfMonth();
                $currentMonthEnd = $currentMonthCarbon->copy()->endOfMonth();

                // Pastikan bulan saat ini tidak melebihi filterEndDate
                if ($currentMonthStart->gt($filterEndDate)) continue;
                // Sesuaikan currentMonthEnd jika filterEndDate lebih awal dari akhir bulan
                if ($currentMonthEnd->gt($filterEndDate)) {
                    $currentMonthEnd = $filterEndDate->copy();
                }


                $currentMonthTotals = DailyIncome::query()
                    ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
                    ->select(
                        DB::raw('SUM(IFNULL(mice_income,0)) as mice'),
                        DB::raw('SUM(IFNULL(fnb_income,0)) as fnb'),
                        DB::raw('SUM(IFNULL(offline_room_income,0)) as offline_room'),
                        DB::raw('SUM(IFNULL(online_room_income,0)) as online_room'),
                        DB::raw('SUM(IFNULL(others_income,0)) as others')
                    )->first();

                $previousMonthStart = $currentMonthStart->copy()->subMonthNoOverflow()->startOfMonth();
                $previousMonthEnd = $currentMonthStart->copy()->subMonthNoOverflow()->endOfMonth();

                $hasPreviousMonthData = DailyIncome::query()
                                        ->whereBetween('date', [$previousMonthStart, $previousMonthEnd])
                                        ->exists();
                $previousMonthTotals = null;
                if ($hasPreviousMonthData) {
                    $previousMonthTotals = DailyIncome::query()
                        ->whereBetween('date', [$previousMonthStart, $previousMonthEnd])
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
                            $growthDisplay = "Baru"; // Atau "+100% (Baru)"
                        } elseif ($currentValue == 0 && $previousValue == 0) {
                            $growthDisplay = "0.00%";
                        } else { // currentValue == 0 && previousValue > 0
                             $growthDisplay = "-100.00%";
                        }
                        $growthForCurrentMonth[] = [
                            'category' => $categoryLabels[$catKey],
                            'growth' => $growthDisplay,
                            'current_value' => $currentValue, // Opsional: untuk ditampilkan di view
                            'previous_value' => $previousValue // Opsional: untuk ditampilkan di view
                        ];
                    }
                }
                $multiMonthCategoryGrowth[$currentMonthCarbon->isoFormat('MMMM YYYY')] = $growthForCurrentMonth;
            }
        }


        // == Bagian 3: Analisis Pencapaian Target (Placeholder) ==
        $targetAnalysis = [
            'properties_achieved_count' => 'N/A',
            'properties_achieved_percentage' => 'N/A',
            'average_achievement_percentage' => 'N/A'
        ];

        // == Bagian 4: Analisis Kepatuhan Data ==
        $dataCompliance = ['days_without_entry' => []];
        $totalDaysInPeriod = ($filterStartDate && $filterEndDate && $filterStartDate->lte($filterEndDate)) ? $filterEndDate->diffInDays($filterStartDate) + 1 : 0;

        if ($totalDaysInPeriod > 0) {
            $periodForCompliance = Carbon::parse($filterStartDate)->daysUntil($filterEndDate->copy()->addDay());
            $allPropertiesForCompliance = Property::orderBy('name')->get();
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
                    'total_days_in_period' => $totalDaysInPeriod,
                    'compliance_percentage' => $totalDaysInPeriod > 0 ? (($totalDaysInPeriod - $daysWithoutEntryCount) / $totalDaysInPeriod) * 100 : 0,
                ];
            }
        }

        return view('admin.kpi_analysis', compact(
            'overallIncomeSource', 'overallIncomeByProperty', 'totalOverallRevenue',
            'averageDailyOverallRevenue', 'activePropertiesCount', 'activePropertyUsersCount',
            'averageRevenuePerProperty',
            'trendKontribusiData',
            // 'categoryGrowth', // Digantikan oleh multiMonthCategoryGrowth
            'multiMonthCategoryGrowth', // Variabel baru untuk pertumbuhan MoM per bulan
            'targetAnalysis',
            'dataCompliance',
            'filterStartDate',
            'filterEndDate',
            'totalDaysInPeriod'
        ));
    }
}
