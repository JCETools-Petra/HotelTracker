<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\DailyIncome;
use App\Models\RevenueTarget;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Validation\Rule;
use Illuminate\Pagination\LengthAwarePaginator;

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
        $this->authorize('manage-data');
        return view('admin.properties.create');
    }

    /**
     * Menyimpan properti baru ke database.
     */
    public function store(Request $request)
    {
        $this->authorize('manage-data');
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:properties,name',
            'total_rooms' => 'required|integer|min:1',
            'chart_color' => 'nullable|string|size:7|starts_with:#',
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
    
        $displayStartDate = $startDate ?: Carbon::now()->startOfMonth();
        $displayEndDate = $endDate ?: Carbon::now()->endOfMonth();
    
        $incomeCategories = [
            'offline_room_income' => 'Walk In', 'online_room_income' => 'OTA', 'ta_income' => 'TA/Travel Agent',
            'gov_income' => 'Gov/Government', 'corp_income' => 'Corp/Corporation', 'compliment_income' => 'Compliment',
            'house_use_income' => 'House Use', 'mice_room_income' => 'MICE Room', 'mice_income' => 'MICE (Non-Kamar)',
            'fnb_income' => 'F&B', 'others_income' => 'Lainnya',
        ];

        $dailyIncomesData = DailyIncome::where('property_id', $property->id)
            ->whereBetween('date', [$displayStartDate, $displayEndDate])
            ->get()->keyBy(fn($item) => Carbon::parse($item->date)->toDateString());

        $dailyMiceFromBookings = Booking::where('property_id', $property->id)
            ->where('status', 'Booking Pasti')
            ->whereBetween('event_date', [$displayStartDate, $displayEndDate])
            ->select(DB::raw('DATE(event_date) as date'), DB::raw('SUM(total_price) as total_mice'))
            ->groupBy('date')->get()->keyBy(fn($item) => Carbon::parse($item->date)->toDateString());

        $period = CarbonPeriod::create($displayStartDate, $displayEndDate);
        
        $fullDateRangeData = collect($period)->map(function ($date) use ($dailyIncomesData, $dailyMiceFromBookings) {
            $dateString = $date->toDateString();
            $income = $dailyIncomesData->get($dateString);
            $mice = $dailyMiceFromBookings->get($dateString);

            $dayData = $income ? $income->toArray() : ['date' => $date->toDateTimeString(), 'id' => null];
            $dayData['mice_booking_total'] = $mice->total_mice ?? 0;
            
            $dayData['total_revenue'] = ($income->total_revenue ?? 0) + ($dayData['mice_booking_total']);
            
            return (object) $dayData;
        })->sortByDesc('date');

        $totalPropertyRevenueFiltered = $fullDateRangeData->sum('total_revenue');

        $sourceDistribution = (object)[
            'total_offline_room_income' => $fullDateRangeData->sum('offline_room_income'),
            'total_online_room_income' => $fullDateRangeData->sum('online_room_income'),
            'total_ta_income' => $fullDateRangeData->sum('ta_income'),
            'total_gov_income' => $fullDateRangeData->sum('gov_income'),
            'total_corp_income' => $fullDateRangeData->sum('corp_income'),
            'total_compliment_income' => $fullDateRangeData->sum('compliment_income'),
            'total_house_use_income' => $fullDateRangeData->sum('house_use_income'),
            'total_mice_room_income' => $fullDateRangeData->sum('mice_room_income'),
            'total_mice_income' => $fullDateRangeData->sum('mice_income') + $fullDateRangeData->sum('mice_booking_total'),
            'total_fnb_income' => $fullDateRangeData->sum(fn($day) => ($day->breakfast_income ?? 0) + ($day->lunch_income ?? 0) + ($day->dinner_income ?? 0)),
            'total_others_income' => $fullDateRangeData->sum('others_income'),
        ];

        $dailyTrend = $fullDateRangeData->map(function($day) {
            return ['date' => $day->date, 'total_daily_income' => $day->total_revenue];
        })->reverse()->values();

        $lastDayIncomeData = $fullDateRangeData->first(fn($item) => Carbon::parse($item->date)->isSameDay($displayEndDate));
        $lastDayIncome = $lastDayIncomeData->total_revenue ?? 0;
        
        $dailyTarget = RevenueTarget::getTargetForDate($property->id, $displayEndDate);
        $dailyTargetAchievement = ($dailyTarget > 0) ? ($lastDayIncome / $dailyTarget) * 100 : 0;

        $perPage = 15;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $fullDateRangeData->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $incomes = new LengthAwarePaginator($currentPageItems, $fullDateRangeData->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => $request->query(),
        ]);

        return view('admin.properties.show', compact(
            'property', 'incomes', 'dailyTrend', 'sourceDistribution', 'totalPropertyRevenueFiltered',
            'displayStartDate', 'displayEndDate', 'incomeCategories',
            'dailyTarget', 'lastDayIncome', 'dailyTargetAchievement'
        ));
    }
    
    public function edit(Property $property)
    {
        $this->authorize('manage-data');
        return view('admin.properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $this->authorize('manage-data');
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('properties')->ignore($property->id)],
            'total_rooms' => 'required|integer|min:1',
            'chart_color' => 'nullable|string|size:7|starts_with:#',
        ]);

        $property->update($validatedData);
        return redirect()->route('admin.properties.index')->with('success', 'Data properti berhasil diperbarui.');
    }

    public function destroy(Property $property)
    {
        $this->authorize('manage-data');
        if ($property->dailyIncomes()->exists()) {
            return redirect()->route('admin.properties.index')
                ->with('error', 'Properti tidak dapat dihapus karena memiliki data pendapatan terkait.');
        }
        $property->delete();
        return redirect()->route('admin.properties.index')
            ->with('success', 'Properti berhasil dihapus.');
    }

    public function showComparisonForm()
    {
        $properties = Property::orderBy('name')->get();
        if ($properties->count() < 2) {
            return redirect()->route('admin.dashboard')->with('info', 'Minimal perlu ada 2 properti untuk dapat dibandingkan.');
        }
        return view('admin.properties.compare_form', compact('properties'));
    }
    
    public function showComparisonResults(Request $request)
    {
        $validated = $request->validate([
            'properties_ids'     => 'required|array|min:2',
            'properties_ids.*'   => 'integer|exists:properties,id',
            'start_date'         => 'required|date',
            'end_date'           => 'required|date|after_or_equal:start_date',
        ]);

        $propertyIds = $validated['properties_ids'];
        $startDate = Carbon::parse($validated['start_date'])->startOfDay();
        $endDate = Carbon::parse($validated['end_date'])->endOfDay();

        $incomeCategories = [
            'offline_room_income' => 'Walk In', 'online_room_income' => 'OTA', 'ta_income' => 'TA/Travel Agent',
            'gov_income' => 'Gov/Government', 'corp_income' => 'Corp/Corporation', 'compliment_income' => 'Compliment',
            'house_use_income' => 'House Use', 'mice_room_income' => 'MICE Room', 'mice_income' => 'MICE (Non-Kamar)',
            'fnb_income' => 'F&B', 'others_income' => 'Lainnya',
        ];
        
        $selectedPropertiesModels = Property::whereIn('id', $propertyIds)->get();
        $comparisonData = [];

        foreach ($selectedPropertiesModels as $property) {
            $dailyIncomes = DailyIncome::where('property_id', $property->id)
                ->whereBetween('date', [$startDate, $endDate]);

            $miceBookings = Booking::where('property_id', $property->id)
                ->where('status', 'Booking Pasti')
                ->whereBetween('event_date', [$startDate, $endDate]);

            $dataPoint = [
                'name' => $property->name,
                'offline_room_income' => $dailyIncomes->sum('offline_room_income'),
                'online_room_income' => $dailyIncomes->sum('online_room_income'),
                'ta_income' => $dailyIncomes->sum('ta_income'),
                'gov_income' => $dailyIncomes->sum('gov_income'),
                'corp_income' => $dailyIncomes->sum('corp_income'),
                'compliment_income' => $dailyIncomes->sum('compliment_income'),
                'house_use_income' => $dailyIncomes->sum('house_use_income'),
                'mice_room_income' => $dailyIncomes->sum('mice_room_income'),
                'mice_income' => $dailyIncomes->sum('mice_income') + $miceBookings->sum('total_price'),
                'fnb_income' => $dailyIncomes->sum(DB::raw('breakfast_income + lunch_income + dinner_income')),
                'others_income' => $dailyIncomes->sum('others_income'),
            ];
            $dataPoint['total_revenue'] = array_sum(array_slice($dataPoint, 1));
            $comparisonData[] = $dataPoint;
        }

        $categoryLabels = array_values($incomeCategories);
        $categoryKeys = array_keys($incomeCategories);
        
        $datasetsForGroupedBar = [];
        $colors = ['#4363d8', '#f58231', '#3cb44b', '#e6194B', '#911eb4', '#f032e6'];
        foreach ($selectedPropertiesModels as $index => $property) {
            $propertyData = collect($comparisonData)->firstWhere('name', $property->name);
            $dataValues = [];
            if ($propertyData) {
                foreach($categoryKeys as $key){
                    $dataValues[] = $propertyData[$key];
                }
            }
            $datasetsForGroupedBar[] = ['label' => $property->name, 'data' => $dataValues, 'backgroundColor' => $property->chart_color ?? $colors[$index % count($colors)]];
        }
        $chartDataGroupedBar = ['labels' => $categoryLabels, 'datasets' => $datasetsForGroupedBar];

        $period = CarbonPeriod::create($startDate, '1 day', $endDate);
        $dateLabels = collect($period)->map(fn($date) => $date->isoFormat('D MMM'));
        $datasetsForTrend = [];
        
        foreach ($selectedPropertiesModels as $index => $property) {
            $dailyIncomes = DailyIncome::where('property_id', $property->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->select('date', 'total_revenue')
                ->orderBy('date', 'asc')
                ->get()->keyBy(fn($item) => Carbon::parse($item->date)->isoFormat('D MMM'));

            $dailyMice = Booking::where('property_id', $property->id)
                ->where('status', 'Booking Pasti')
                ->whereBetween('event_date', [$startDate, $endDate])
                ->select(DB::raw('DATE(event_date) as date'), DB::raw('SUM(total_price) as daily_mice_revenue'))
                ->groupBy('date')->get()->keyBy(fn($item) => Carbon::parse($item->date)->isoFormat('D MMM'));

            $trendDataPoints = $dateLabels->map(function($label) use ($dailyIncomes, $dailyMice) {
                return ($dailyIncomes->get($label)->total_revenue ?? 0) + ($dailyMice->get($label)->daily_mice_revenue ?? 0);
            });

            $datasetsForTrend[] = [
                'label' => $property->name, 'data' => $trendDataPoints, 
                'borderColor' => $property->chart_color ?? $colors[$index % count($colors)], 
                'fill' => false, 'tension' => 0.1
            ];
        }
        $trendChartData = ['labels' => $dateLabels, 'datasets' => $datasetsForTrend];

        return view('admin.properties.compare_results', [
            'selectedPropertiesModels' => $selectedPropertiesModels,
            'startDateFormatted' => $startDate->isoFormat('D MMMM YYYY'),
            'endDateFormatted' => $endDate->isoFormat('D MMMM YYYY'),
            'comparisonData' => $comparisonData,
            'chartDataGroupedBar' => $chartDataGroupedBar,
            'trendChartData' => $trendChartData,
            'incomeCategories' => $incomeCategories,
        ]);
    }
}
