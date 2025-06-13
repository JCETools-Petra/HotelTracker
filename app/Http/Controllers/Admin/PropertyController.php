<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\DailyIncome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Exports\PropertyIncomesExport;
use Illuminate\Validation\Rule;

class PropertyController extends Controller
{
    /**
     * Menampilkan daftar semua properti.
     */
    public function index(Request $request)
    {
        $query = Property::orderBy('id', 'asc');
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $properties = $query->paginate(15);
        return view('admin.properties.index', compact('properties'));
    }

    /**
     * Menampilkan form untuk membuat properti baru.
     */
    public function create()
    {
        return view('admin.properties.create');
    }

    /**
     * Menyimpan properti baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:properties,name',
        ]);
        Property::create($validatedData);
        return redirect()->route('admin.properties.index')->with('success', 'Properti baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail sebuah properti beserta data pendapatannya.
     */
    /**
     * Menampilkan detail properti beserta riwayat pendapatan, tren, dan distribusi sumber.
     */
    /**
     * Menampilkan detail sebuah properti beserta data pendapatannya.
     */
    public function show(Property $property, Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;

        $periodIsOneMonth = false;
        $incomesQuery = DailyIncome::where('property_id', $property->id);
        
        $displayStartDate = $startDate;
        $displayEndDate = $endDate;

        if ($startDate && $endDate) {
            $incomesQuery->whereBetween('date', [$startDate, $endDate]);
        } else {
            $defaultStartDate = Carbon::now()->startOfMonth(); // Default ke awal bulan ini
            $defaultEndDate = Carbon::now()->endOfMonth(); // Default ke akhir bulan ini
            $incomesQuery->whereBetween('date', [$defaultStartDate, $defaultEndDate]);
            $displayStartDate = $defaultStartDate;
            $displayEndDate = $defaultEndDate;
        }

        $incomes = $incomesQuery->orderBy('date', 'desc')->paginate(30);

        $incomeCategories = [
            'offline_room_income' => 'Walk In Guest', 'online_room_income'  => 'OTA',
            'ta_income' => 'TA/Travel Agent', 'gov_income' => 'Gov/Government',
            'corp_income' => 'Corp/Corporation', 'compliment_income' => 'Compliment',
            'house_use_income' => 'House Use', 'mice_income' => 'MICE',
            'fnb_income' => 'F&B', 'others_income' => 'Lainnya',
        ];
        $categoryColumns = array_keys($incomeCategories);
        $totalRevenueRaw = implode(' + ', array_map(fn($col) => "IFNULL(`$col`, 0)", $categoryColumns));
        
        // Query untuk data yang difilter
        $filteredQuery = DailyIncome::where('property_id', $property->id);
        if($displayStartDate && $displayEndDate) {
            $filteredQuery->whereBetween('date', [$displayStartDate, $displayEndDate]);
        }

        // Kalkulasi Total Pendapatan Terfilter
        $totalPropertyRevenueFiltered = (clone $filteredQuery)->sum(DB::raw($totalRevenueRaw));

        // Kalkulasi untuk Chart
        $selectSums = [];
        foreach ($categoryColumns as $column) {
            $selectSums[] = DB::raw("SUM(IFNULL(`{$column}`, 0)) as total_{$column}");
        }
        $sourceDistribution = (clone $filteredQuery)->select($selectSums)->first();
        $dailyTrend = (clone $filteredQuery)->select('date', DB::raw("SUM({$totalRevenueRaw}) as total_daily_income"))
            ->groupBy('date')->orderBy('date', 'asc')->get();

        // ================== LOGIKA BARU UNTUK TARGET HARIAN ==================
        // Ambil target bulanan untuk bulan saat ini (berdasarkan tanggal awal filter)
        $targetMonth = $displayStartDate->copy()->startOfMonth();
        $revenueTarget = RevenueTarget::where('property_id', $property->id)
                                      ->where('month_year', $targetMonth->format('Y-m-d'))
                                      ->first();

        $monthlyTarget = $revenueTarget->target_amount ?? 0;
        $dailyTarget = $monthlyTarget > 0 ? $monthlyTarget / $displayStartDate->daysInMonth : 0;

        // Ambil total pendapatan aktual untuk hari ini (atau hari terakhir dari filter)
        $lastDayIncome = (clone $filteredQuery)->whereDate('date', $displayEndDate->toDateString())->sum(DB::raw($totalRevenueRaw));

        $dailyTargetAchievement = $dailyTarget > 0 ? ($lastDayIncome / $dailyTarget) * 100 : 0;
        // ================== AKHIR LOGIKA BARU ==================


        return view('admin.properties.show', compact(
            'property', 'incomes', 'dailyTrend', 'sourceDistribution', 'totalPropertyRevenueFiltered',
            'startDate', 'endDate', 'displayStartDate', 'displayEndDate', 'incomeCategories',
            'dailyTarget', 'lastDayIncome', 'dailyTargetAchievement' // Kirim variabel baru ke view
        ));
    }

    /**
     * Menampilkan form untuk mengedit properti.
     */
    public function edit(Property $property)
    {
        return view('admin.properties.edit', compact('property'));
    }

    /**
     * Memperbarui data properti di database.
     */
    public function update(Request $request, Property $property)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('properties')->ignore($property->id)],
        ]);
        $property->update($validatedData);
        return redirect()->route('admin.properties.index')->with('success', 'Data properti berhasil diperbarui.');
    }

    /**
     * Menghapus properti dari database.
     */
    public function destroy(Property $property)
    {
        if ($property->dailyIncomes()->exists()) {
            return redirect()->route('admin.properties.index')
                ->with('error', 'Properti tidak dapat dihapus karena memiliki data pendapatan terkait.');
        }
        $property->delete();
        return redirect()->route('admin.properties.index')
            ->with('success', 'Properti berhasil dihapus.');
    }

    /**
     * Menampilkan form untuk memilih properti yang akan dibandingkan.
     */
    public function showComparisonForm() // Nama metode yang benar sesuai rute Anda
    {
        $properties = Property::orderBy('name')->get();
        if ($properties->count() < 2) {
            return redirect()->route('admin.dashboard')->with('info', 'Minimal perlu ada 2 properti untuk dapat dibandingkan.');
        }
        return view('admin.properties.compare_form', compact('properties'));
    }

    /**
     * Memvalidasi form perbandingan dan me-redirect ke halaman hasil.
     */
    public function handleCompareForm(Request $request)
    {
        $request->validate([
            'property_ids'   => 'required|array|min:2',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ], [
            'property_ids.required' => 'Pilih setidaknya 2 properti untuk dibandingkan.',
            'property_ids.min'      => 'Pilih setidaknya 2 properti untuk dibandingkan.',
        ]);

        return redirect()->route('admin.properties.compare.results', [
            'properties_ids' => $request->input('property_ids'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ]);
    }

    /**
     * Menampilkan hasil perbandingan properti berdasarkan data dari URL.
     */
    public function showComparisonResults(Request $request)
    {
        $validated = $request->validate([
            'properties_ids'   => 'required|array|min:2',
            'properties_ids.*' => 'integer|exists:properties,id',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
        ]);

        $propertyIds = $validated['properties_ids'];
        $startDate = Carbon::parse($validated['start_date'])->startOfDay();
        $endDate = Carbon::parse($validated['end_date'])->endOfDay();

        $incomeCategories = [
            'offline_room_income' => 'Walk In Guest', 'online_room_income'  => 'OTA',
            'ta_income'           => 'TA/Travel Agent', 'gov_income'          => 'Gov/Government',
            'corp_income'         => 'Corp/Corporation', 'compliment_income'   => 'Compliment',
            'house_use_income'    => 'House Use', 'mice_income'         => 'MICE',
            'fnb_income'          => 'F&B', 'others_income'       => 'Lainnya',
        ];
        $categoryColumns = array_keys($incomeCategories);
        $categoryLabels = array_values($incomeCategories);

        $comparisonData = [];
        $totalRevenueRaw = implode(' + ', array_map(fn($col) => "IFNULL(`$col`, 0)", $categoryColumns));
        $selectedPropertiesModels = Property::whereIn('id', $propertyIds)->get();

        foreach ($selectedPropertiesModels as $property) {
            $incomeDetails = DailyIncome::where('property_id', $property->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->select(DB::raw("SUM({$totalRevenueRaw}) as total_revenue, " . implode(', ', array_map(fn($col) => "SUM(IFNULL(`{$col}`, 0)) as `{$col}`", $categoryColumns))))
                ->first();

            $dataPoint = ['name' => $property->name];
            foreach ($categoryColumns as $column) {
                $dataPoint[$column] = $incomeDetails->{$column} ?? 0;
            }
            $dataPoint['total_revenue'] = $incomeDetails->total_revenue ?? 0;
            $comparisonData[] = $dataPoint;
        }

        $datasetsForGroupedBar = [];
        $colors = ['rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)', 'rgba(153, 102, 255, 0.7)', 'rgba(255, 159, 64, 0.7)'];
        foreach ($selectedPropertiesModels as $index => $property) {
            $propertyData = collect($comparisonData)->firstWhere('name', $property->name);
            $dataValues = [];
            if ($propertyData) {
                foreach($categoryColumns as $column){
                     $dataValues[] = $propertyData[$column];
                }
            }
            $datasetsForGroupedBar[] = ['label' => $property->name, 'data' => $dataValues, 'backgroundColor' => $colors[$index % count($colors)]];
        }
        $chartDataGroupedBar = ['labels' => $categoryLabels, 'datasets' => $datasetsForGroupedBar];

        $period = CarbonPeriod::create($startDate, '1 day', $endDate);
        $dateLabels = collect($period)->map(fn($date) => $date->isoFormat('D MMM'));
        $datasetsForTrend = [];
        foreach ($selectedPropertiesModels as $index => $property) {
            $dailyIncomes = DailyIncome::where('property_id', $property->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->select('date', DB::raw("SUM({$totalRevenueRaw}) as daily_total_revenue"))
                ->groupBy('date')->orderBy('date', 'asc')->get()->keyBy(fn($item) => Carbon::parse($item->date)->isoFormat('D MMM'));
            
            $trendDataPoints = $dateLabels->map(fn($label) => $dailyIncomes->get($label)->daily_total_revenue ?? 0);
            $datasetsForTrend[] = ['label' => $property->name, 'data' => $trendDataPoints, 'borderColor' => $colors[$index % count($colors)], 'fill' => false];
        }
        $trendChartData = ['labels' => $dateLabels, 'datasets' => $datasetsForTrend];

        return view('admin.properties.compare_results', [
            'selectedPropertiesModels' => $selectedPropertiesModels,
            'startDateFormatted' => $startDate->isoFormat('D MMMM'),
            'endDateFormatted' => $endDate->isoFormat('D MMMM'),
            'comparisonData' => $comparisonData,
            'chartDataGroupedBar' => $chartDataGroupedBar,
            'trendChartData' => $trendChartData,
            'incomeCategories' => $incomeCategories,
        ]);
    }
    
    /**
     * Menangani permintaan ekspor detail pendapatan properti ke Excel oleh Admin.
     */
    public function exportPropertyDetailsExcel(Property $property, Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $fileName = 'detail_pendapatan_' . str_replace(' ', '_', $property->name) . '_' . Carbon::now()->format('Ymd_His') . '.xlsx';
        // Pastikan Anda memiliki App\Exports\PropertyIncomesExport
        // return Excel::download(new PropertyIncomesExport($property->id, $startDate, $endDate), $fileName);
        return redirect()->back()->with('info', 'Fitur export sedang dalam pengembangan.');
    }

    /**
     * Menangani permintaan ekspor detail pendapatan properti ke CSV oleh Admin.
     */
    public function exportPropertyDetailsCsv(Property $property, Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $fileName = 'detail_pendapatan_' . str_replace(' ', '_', $property->name) . '_' . Carbon::now()->format('Ymd_His') . '.csv';
        // Pastikan Anda memiliki App\Exports\PropertyIncomesExport
        // return Excel::download(new PropertyIncomesExport($property->id, $startDate, $endDate), \Maatwebsite\Excel\Excel::CSV);
        return redirect()->back()->with('info', 'Fitur export sedang dalam pengembangan.');
    }
}