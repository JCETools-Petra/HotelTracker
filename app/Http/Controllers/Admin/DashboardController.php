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
}
