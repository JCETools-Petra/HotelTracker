<?php

// app/Http/Controllers/Admin/PropertyController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\DailyIncome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Pastikan ini di-import untuk DB::raw()
use Carbon\Carbon;                   // Pastikan ini di-import untuk Carbon::parse(), Carbon::now()
use Maatwebsite\Excel\Facades\Excel; // Pastikan ini di-import jika menggunakan Laravel Excel
use App\Exports\PropertyIncomesExport; // Pastikan ini di-import dan namespace-nya benar
use Illuminate\Validation\Rule;      // Untuk validasi Rule::unique

class PropertyController extends Controller
{
    /**
     * Display a listing of the properties.
     */
    public function index()
    {
        $properties = Property::orderBy('name', 'asc')->paginate(15);
        return view('admin.properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new property.
     */
    public function create()
    {
        return view('admin.properties.create');
    }

    /**
     * Store a newly created property in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:properties,name',
        ], [
            'name.required' => 'Nama properti harus diisi.',
            'name.unique' => 'Nama properti ini sudah ada.',
        ]);

        Property::create($validatedData);

        return redirect()->route('admin.properties.index')->with('success', 'Properti baru berhasil ditambahkan.');
    }

    /**
     * Menangani permintaan ekspor detail pendapatan properti ke Excel oleh Admin.
     */
    public function exportPropertyDetailsExcel(Property $property, Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $fileName = 'detail_pendapatan_' . str_replace(' ', '_', $property->name) . '_' . Carbon::now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new PropertyIncomesExport($property->id, $startDate, $endDate), $fileName);
    }

    /**
     * Menangani permintaan ekspor detail pendapatan properti ke CSV oleh Admin.
     */
    public function exportPropertyDetailsCsv(Property $property, Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $fileName = 'detail_pendapatan_' . str_replace(' ', '_', $property->name) . '_' . Carbon::now()->format('Ymd_His') . '.csv';
        return Excel::download(new PropertyIncomesExport($property->id, $startDate, $endDate), $fileName, \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * Menampilkan detail properti beserta riwayat pendapatan, tren, dan distribusi sumber.
     * Mendukung filter berdasarkan rentang tanggal.
     */
    public function show(Property $property, Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;

        $incomesQuery = DailyIncome::where('property_id', $property->id);
        $dailyTrendQuery = DailyIncome::where('property_id', $property->id);
        $sourceDistributionQuery = DailyIncome::where('property_id', $property->id);

        if ($startDate && $endDate) {
            $incomesQuery->whereBetween('date', [$startDate, $endDate]);
            $dailyTrendQuery->whereBetween('date', [$startDate, $endDate]);
            $sourceDistributionQuery->whereBetween('date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $incomesQuery->where('date', '>=', $startDate);
            $dailyTrendQuery->where('date', '>=', $startDate);
            $sourceDistributionQuery->where('date', '>=', $startDate);
        } elseif ($endDate) {
            $incomesQuery->where('date', '<=', $endDate);
            $dailyTrendQuery->where('date', '<=', $endDate);
            $sourceDistributionQuery->where('date', '<=', $endDate);
        } else {
            $defaultStartDate = Carbon::now()->subDays(29)->startOfDay();
            $defaultEndDate = Carbon::now()->endOfDay();
            $incomesQuery->whereBetween('date', [$defaultStartDate, $defaultEndDate]);
            $dailyTrendQuery->whereBetween('date', [$defaultStartDate, $defaultEndDate]);
            // Untuk sourceDistribution, jika tidak ada filter, kita ambil keseluruhan data properti tersebut
            // (perilaku default query tanpa whereBetween/whereDate)
        }

        $incomes = $incomesQuery->orderBy('date', 'desc')->get();

        $dailyTrend = $dailyTrendQuery->select(
                'date',
                DB::raw('
                    IFNULL(mice_income, 0) + IFNULL(fnb_income, 0) +
                    IFNULL(offline_room_income, 0) + IFNULL(online_room_income, 0) +
                    IFNULL(others_income, 0)
                as total_daily_income')
            )
            ->orderBy('date', 'asc')
            ->get();

        $sourceDistribution = $sourceDistributionQuery->select(
                DB::raw('SUM(IFNULL(mice_income,0)) as total_mice'),
                DB::raw('SUM(IFNULL(fnb_income,0)) as total_fnb'),
                DB::raw('SUM(IFNULL(offline_room_income,0)) as total_offline_room'),
                DB::raw('SUM(IFNULL(online_room_income,0)) as total_online_room'),
                DB::raw('SUM(IFNULL(others_income,0)) as total_others')
            )->first();

        return view('admin.properties.show', compact(
            'property', 'incomes', 'dailyTrend', 'sourceDistribution', 'startDate', 'endDate'
        ));
    }
    
    /**
     * Show the form for editing the specified property.
     */
    public function edit(Property $property)
    {
        return view('admin.properties.edit', compact('property'));
    }

    /**
     * Update the specified property in storage.
     */
    public function update(Request $request, Property $property)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('properties')->ignore($property->id)],
        ], [
            'name.required' => 'Nama properti harus diisi.',
            'name.unique' => 'Nama properti ini sudah digunakan oleh properti lain.',
        ]);

        $property->update($validatedData);

        return redirect()->route('admin.properties.index')->with('success', 'Data properti berhasil diperbarui.');
    }
    
    /**
     * Menampilkan form untuk membandingkan properti.
     */
    public function showComparisonForm()
    {
        $properties = Property::orderBy('name')->get();
        if ($properties->isEmpty() || $properties->count() < 2) {
            return redirect()->route('admin.dashboard')->with('info', $properties->isEmpty() ? 'Tidak ada properti untuk dibandingkan.' : 'Minimal perlu ada 2 properti untuk dibandingkan.');
        }
        return view('admin.properties.compare_form', compact('properties'));
    }

    /**
     * Menampilkan hasil perbandingan properti.
     * Termasuk perbandingan per kategori (grouped bar chart) dan perbandingan tren harian (multi-line chart).
     */
    public function showComparisonResults(Request $request)
    {
        $validated = $request->validate([
            'properties_ids'   => 'required|array|min:2',
            'properties_ids.*' => 'integer|exists:properties,id',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
        ], [ /* ... pesan validasi ... */ ]);

        $propertyIds = $validated['properties_ids'];
        $startDate = Carbon::parse($validated['start_date'])->startOfDay();
        $endDate = Carbon::parse($validated['end_date'])->endOfDay();

        $selectedPropertiesModels = Property::whereIn('id', $propertyIds)->orderBy('name')->get();

        // Data untuk Grouped Bar Chart (Kategori) dan Tabel Detail
        $comparisonData = []; 
        $chartLabelsGroupedBar = $selectedPropertiesModels->pluck('name')->all();
        $miceIncomes = []; $fnbIncomes = []; $offlineRoomIncomes = []; $onlineRoomIncomes = []; $othersIncomes = [];

        foreach ($selectedPropertiesModels as $property) {
            $incomeSummary = DailyIncome::where('property_id', $property->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->select(
                    DB::raw('SUM(IFNULL(mice_income, 0)) as sum_mice'),
                    DB::raw('SUM(IFNULL(fnb_income, 0)) as sum_fnb'),
                    DB::raw('SUM(IFNULL(offline_room_income, 0)) as sum_offline_room'),
                    DB::raw('SUM(IFNULL(online_room_income, 0)) as sum_online_room'),
                    DB::raw('SUM(IFNULL(others_income, 0)) as sum_others')
                )->first();
            
            $currentTotalRevenue = ($incomeSummary->sum_mice ?? 0) + ($incomeSummary->sum_fnb ?? 0) + ($incomeSummary->sum_offline_room ?? 0) + ($incomeSummary->sum_online_room ?? 0) + ($incomeSummary->sum_others ?? 0);
            $comparisonData[] = ['id' => $property->id, 'name' => $property->name, 'mice_income' => $incomeSummary->sum_mice ?? 0, 'fnb_income' => $incomeSummary->sum_fnb ?? 0, 'offline_room_income' => $incomeSummary->sum_offline_room ?? 0, 'online_room_income' => $incomeSummary->sum_online_room ?? 0, 'others_income' => $incomeSummary->sum_others ?? 0, 'total_revenue' => $currentTotalRevenue];
            $miceIncomes[] = $incomeSummary->sum_mice ?? 0; $fnbIncomes[] = $incomeSummary->sum_fnb ?? 0; $offlineRoomIncomes[] = $incomeSummary->sum_offline_room ?? 0; $onlineRoomIncomes[] = $incomeSummary->sum_online_room ?? 0; $othersIncomes[] = $incomeSummary->sum_others ?? 0;
        }
        
        $categoryColors = [ /* ... definisi warna Anda ... */ 
            'mice' => ['backgroundColor' => 'rgba(255, 99, 132, 0.7)', 'borderColor' => 'rgba(255, 99, 132, 1)'],
            'fnb' => ['backgroundColor' => 'rgba(54, 162, 235, 0.7)', 'borderColor' => 'rgba(54, 162, 235, 1)'],
            'offline_room' => ['backgroundColor' => 'rgba(255, 206, 86, 0.7)', 'borderColor' => 'rgba(255, 206, 86, 1)'],
            'online_room' => ['backgroundColor' => 'rgba(75, 192, 192, 0.7)', 'borderColor' => 'rgba(75, 192, 192, 1)'],
            'others' => ['backgroundColor' => 'rgba(153, 102, 255, 0.7)', 'borderColor' => 'rgba(153, 102, 255, 1)'],
        ];
        $chartDataGroupedBar = [
            'labels' => $chartLabelsGroupedBar,
            'datasets' => [
                ['label' => 'Pendapatan MICE (Rp)', 'data' => $miceIncomes, 'backgroundColor' => $categoryColors['mice']['backgroundColor'], 'borderColor' => $categoryColors['mice']['borderColor'], 'borderWidth' => 1],
                ['label' => 'Pendapatan F&B (Rp)', 'data' => $fnbIncomes, 'backgroundColor' => $categoryColors['fnb']['backgroundColor'], 'borderColor' => $categoryColors['fnb']['borderColor'], 'borderWidth' => 1],
                ['label' => 'Pendapatan Kamar Offline (Rp)', 'data' => $offlineRoomIncomes, 'backgroundColor' => $categoryColors['offline_room']['backgroundColor'], 'borderColor' => $categoryColors['offline_room']['borderColor'], 'borderWidth' => 1],
                ['label' => 'Pendapatan Kamar Online (Rp)', 'data' => $onlineRoomIncomes, 'backgroundColor' => $categoryColors['online_room']['backgroundColor'], 'borderColor' => $categoryColors['online_room']['borderColor'], 'borderWidth' => 1],
                ['label' => 'Pendapatan Lainnya (Rp)', 'data' => $othersIncomes, 'backgroundColor' => $categoryColors['others']['backgroundColor'], 'borderColor' => $categoryColors['others']['borderColor'], 'borderWidth' => 1],
            ]
        ];

        // --- LOGIKA BARU UNTUK PERBANDINGAN TREN PENDAPATAN HARIAN ---
        $dateLabelsForTrend = [];
        $period = $startDate->copy()->daysUntil($endDate->copy()->addDay()); // Pastikan $startDate dan $endDate adalah objek Carbon
        foreach ($period as $date) {
            $dateLabelsForTrend[] = $date->toDateString();
        }
        // Jika rentang hanya satu hari, pastikan tanggal itu ada
        if ($startDate->isSameDay($endDate) && empty($dateLabelsForTrend)) {
            $dateLabelsForTrend[] = $startDate->toDateString();
        }
        $dateLabelsForTrend = array_unique($dateLabelsForTrend);
        sort($dateLabelsForTrend);

        $trendDatasets = [];
        $lineChartColors = ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)', 'rgba(40, 167, 69, 1)', 'rgba(201, 203, 207, 1)'];
        $colorIndex = 0;

        foreach ($selectedPropertiesModels as $property) {
            $dailyTotals = DailyIncome::where('property_id', $property->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->select(
                    'date',
                    DB::raw('IFNULL(mice_income, 0) + IFNULL(fnb_income, 0) + IFNULL(offline_room_income, 0) + IFNULL(online_room_income, 0) + IFNULL(others_income, 0) as daily_total_revenue')
                )
                ->orderBy('date', 'asc')
                ->get()
                ->keyBy(function ($item) {
                    return Carbon::parse($item->date)->toDateString(); // Pastikan parsing tanggal
                });

            $propertyTrendData = [];
            foreach ($dateLabelsForTrend as $dateString) {
                $incomeRecord = $dailyTotals->get($dateString);
                $propertyTrendData[] = $incomeRecord ? $incomeRecord->daily_total_revenue : 0; 
            }

            $trendDatasets[] = [
                'label' => $property->name . ' (Total Harian)',
                'data' => $propertyTrendData,
                'borderColor' => $lineChartColors[$colorIndex % count($lineChartColors)],
                'backgroundColor' => $lineChartColors[$colorIndex % count($lineChartColors)], // Untuk titik di line chart, atau area jika fill=true
                'fill' => false,
                'tension' => 0.1
            ];
            $colorIndex++;
        }
        
        $trendChartData = [
            'labels' => array_map(function($dateStr) { return Carbon::parse($dateStr)->isoFormat('D MMM'); }, $dateLabelsForTrend),
            'datasets' => $trendDatasets
        ];
        // --- AKHIR LOGIKA BARU ---

        return view('admin.properties.compare_results', [
            'comparisonData'         => $comparisonData,
            'chartDataGroupedBar'    => $chartDataGroupedBar,
            'trendChartData'         => $trendChartData,    // Kirim data tren ke view
            'selectedPropertiesModels' => $selectedPropertiesModels,
            'startDateFormatted'     => $startDate->isoFormat('D MMMM YYYY'),
            'endDateFormatted'       => $endDate->isoFormat('D MMMM YYYY'),
        ]);
    }

    /**
     * Remove the specified property from storage.
     * KITA AKAN IMPLEMENTASIKAN INI NANTI SETELAH PERTIMBANGAN LEBIH LANJUT
     */
    // public function destroy(Property $property)
    // {
    //     // ...
    // }
}