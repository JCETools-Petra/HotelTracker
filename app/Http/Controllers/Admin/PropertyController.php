<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\DailyIncome;
use App\Models\RevenueTarget;
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
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya admin yang dapat melakukan aksi ini.');
        }
        return view('admin.properties.create');
    }

    /**
     * Menyimpan properti baru ke database.
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya admin yang dapat melakukan aksi ini.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:properties,name',
            'chart_color' => 'nullable|string|size:7',
        ]);

        Property::create($validatedData);
        return redirect()->route('admin.properties.index')->with('success', 'Properti baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail sebuah properti.
     */
    public function show(Property $property, Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;

        $incomesQuery = DailyIncome::where('property_id', $property->id);
        
        $displayStartDate = $startDate;
        $displayEndDate = $endDate;

        if ($startDate && $endDate) {
            $incomesQuery->whereBetween('date', [$startDate, $endDate]);
        } else {
            $defaultStartDate = Carbon::now()->startOfMonth();
            $defaultEndDate = Carbon::now()->endOfMonth();
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
        
        $filteredQuery = DailyIncome::where('property_id', $property->id);
        if($displayStartDate && $displayEndDate) {
            $filteredQuery->whereBetween('date', [$displayStartDate, $displayEndDate]);
        }

        $totalPropertyRevenueFiltered = (clone $filteredQuery)->sum(DB::raw($totalRevenueRaw));

        $selectSums = [];
        foreach ($categoryColumns as $column) {
            $selectSums[] = DB::raw("SUM(IFNULL(`{$column}`, 0)) as total_{$column}");
        }
        $sourceDistribution = (clone $filteredQuery)->select($selectSums)->first();
        $dailyTrend = (clone $filteredQuery)->select('date', DB::raw("SUM({$totalRevenueRaw}) as total_daily_income"))
            ->groupBy('date')->orderBy('date', 'asc')->get();

        $targetMonth = $displayStartDate->copy()->startOfMonth();
        $revenueTarget = RevenueTarget::where('property_id', $property->id)
            ->where('month_year', $targetMonth->format('Y-m-d'))
            ->first();

        $monthlyTarget = $revenueTarget->target_amount ?? 0;
        $dailyTarget = $monthlyTarget > 0 ? $monthlyTarget / $displayStartDate->daysInMonth : 0;
        $lastDayIncome = (clone $filteredQuery)->whereDate('date', $displayEndDate->toDateString())->sum(DB::raw($totalRevenueRaw));
        $dailyTargetAchievement = $dailyTarget > 0 ? ($lastDayIncome / $dailyTarget) * 100 : 0;

        return view('admin.properties.show', compact(
            'property', 'incomes', 'dailyTrend', 'sourceDistribution', 'totalPropertyRevenueFiltered',
            'startDate', 'endDate', 'displayStartDate', 'displayEndDate', 'incomeCategories',
            'dailyTarget', 'lastDayIncome', 'dailyTargetAchievement'
        ));
    }

    /**
     * Menampilkan form untuk mengedit properti.
     */
    public function edit(Property $property)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya admin yang dapat melakukan aksi ini.');
        }
        return view('admin.properties.edit', compact('property'));
    }

    /**
     * Memperbarui data properti di database.
     */
    public function update(Request $request, Property $property)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya admin yang dapat melakukan aksi ini.');
        }
        
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('properties')->ignore($property->id)],
            'chart_color' => 'nullable|string|size:7',
        ]);

        $property->update($validatedData);
        return redirect()->route('admin.properties.index')->with('success', 'Data properti berhasil diperbarui.');
    }

    /**
     * Menghapus properti dari database.
     */
    public function destroy(Property $property)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Hanya admin yang dapat melakukan aksi ini.');
        }
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
    public function showComparisonForm()
    {
        $properties = Property::orderBy('name')->get();
        if ($properties->count() < 2) {
            return redirect()->route('admin.dashboard')->with('info', 'Minimal perlu ada 2 properti untuk dapat dibandingkan.');
        }
        return view('admin.properties.compare_form', compact('properties'));
    }
    
    /**
     * Menampilkan hasil perbandingan properti.
     */
    public function showComparisonResults(Request $request)
    {
        $validated = $request->validate([
            'properties_ids'   => 'required|array|min:2',
            'properties_ids.*' => 'integer|exists:properties,id',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
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
     * Menangani permintaan ekspor detail pendapatan properti.
     */
    public function exportPropertyDetailsExcel(Property $property, Request $request)
    {
        return redirect()->back()->with('info', 'Fitur export sedang dalam pengembangan.');
    }

    public function exportPropertyDetailsCsv(Property $property, Request $request)
    {
        return redirect()->back()->with('info', 'Fitur export sedang dalam pengembangan.');
    }
}