<?php

namespace App\Http\Controllers;

use App\Models\DailyIncome;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PropertyIncomeController extends Controller
{
    /**
     * Menampilkan dashboard untuk pengguna properti.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $property = $user->property;

        if (!$property) {
            return redirect('/')->with('error', 'Anda tidak terkait dengan properti manapun.');
        }

        $todayIncome = DailyIncome::where('property_id', $property->id)
            ->whereDate('date', Carbon::today())
            ->first();

        return view('property.dashboard', compact('property', 'todayIncome'));
    }

    /**
     * Menampilkan riwayat pendapatan untuk properti pengguna.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $property = $user->property;

        $query = DailyIncome::where('property_id', $property->id);

        if ($request->filled('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        $incomes = $query->orderBy('date', 'desc')->paginate(15);

        return view('property.income.index', compact('incomes', 'property'));
    }

    /**
     * Menampilkan form untuk mencatat pendapatan baru.
     */
    public function create()
    {
        $user = Auth::user();
        $property = $user->property;
        $date = Carbon::today()->toDateString();
        return view('property.income.create', compact('property', 'date'));
    }

    /**
     * Menyimpan data pendapatan baru.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $property = $user->property;

        $validatedData = $request->validate([
            'date' => 'required|date|unique:daily_incomes,date,NULL,id,property_id,'.$property->id,
            'offline_rooms' => 'required|integer|min:0',
            'offline_room_income' => 'required|numeric|min:0',
            'online_rooms' => 'required|integer|min:0',
            'online_room_income' => 'required|numeric|min:0',
            'ta_rooms' => 'required|integer|min:0',
            'ta_income' => 'required|numeric|min:0',
            'gov_rooms' => 'required|integer|min:0',
            'gov_income' => 'required|numeric|min:0',
            'corp_rooms' => 'required|integer|min:0',
            'corp_income' => 'required|numeric|min:0',
            'compliment_rooms' => 'required|integer|min:0',
            'compliment_income' => 'required|numeric|min:0',
            'house_use_rooms' => 'required|integer|min:0',
            'house_use_income' => 'required|numeric|min:0',
            'afiliasi_rooms' => 'required|integer|min:0',
            'afiliasi_room_income' => 'required|numeric|min:0',
            'breakfast_income' => 'required|numeric|min:0',
            'lunch_income' => 'required|numeric|min:0',
            'dinner_income' => 'required|numeric|min:0',
            'others_income' => 'required|numeric|min:0',
        ], ['date.unique' => 'Pendapatan untuk tanggal ini sudah pernah dicatat.']);

        $total_rooms_sold = $validatedData['offline_rooms'] + $validatedData['online_rooms'] + $validatedData['ta_rooms'] + $validatedData['gov_rooms'] + $validatedData['corp_rooms'] + $validatedData['compliment_rooms'] + $validatedData['house_use_rooms'] + $validatedData['afiliasi_rooms'];
        $total_rooms_revenue = $validatedData['offline_room_income'] + $validatedData['online_room_income'] + $validatedData['ta_income'] + $validatedData['gov_income'] + $validatedData['corp_income'] + $validatedData['compliment_income'] + $validatedData['house_use_income'] + $validatedData['afiliasi_room_income'];
        $total_fb_revenue = $validatedData['breakfast_income'] + $validatedData['lunch_income'] + $validatedData['dinner_income'];
        $total_revenue = $total_rooms_revenue + $total_fb_revenue + ($validatedData['afiliasi_income'] ?? 0) + $validatedData['others_income'];
        $arr = ($total_rooms_sold > 0) ? ($total_rooms_revenue / $total_rooms_sold) : 0;
        $occupancy = ($property->total_rooms > 0) ? ($total_rooms_sold / $property->total_rooms) * 100 : 0;

        DailyIncome::create(array_merge($validatedData, [
            'property_id' => $property->id,
            'user_id' => $user->id,
            'total_rooms_sold' => $total_rooms_sold,
            'total_rooms_revenue' => $total_rooms_revenue,
            'total_fb_revenue' => $total_fb_revenue,
            'total_revenue' => $total_revenue,
            'arr' => $arr,
            'occupancy' => $occupancy,
        ]));

        return redirect()->route('property.income.index')->with('success', 'Pendapatan harian berhasil dicatat.');
    }

    /**
     * Menampilkan form untuk mengedit data pendapatan.
     */
    public function edit(DailyIncome $dailyIncome)
    {
        $this->authorize('update', $dailyIncome);
        return view('property.income.edit', compact('dailyIncome'));
    }

    /**
     * Memperbarui data pendapatan.
     */
    public function update(Request $request, DailyIncome $dailyIncome)
    {
        $this->authorize('update', $dailyIncome);
        $property = $dailyIncome->property;

        $validatedData = $request->validate([
            'date' => 'required|date|unique:daily_incomes,date,' . $dailyIncome->id . ',id,property_id,' . $property->id,
            'offline_rooms' => 'required|integer|min:0',
            'offline_room_income' => 'required|numeric|min:0',
            'online_rooms' => 'required|integer|min:0',
            'online_room_income' => 'required|numeric|min:0',
            'ta_rooms' => 'required|integer|min:0',
            'ta_income' => 'required|numeric|min:0',
            'gov_rooms' => 'required|integer|min:0',
            'gov_income' => 'required|numeric|min:0',
            'corp_rooms' => 'required|integer|min:0',
            'corp_income' => 'required|numeric|min:0',
            'compliment_rooms' => 'required|integer|min:0',
            'compliment_income' => 'required|numeric|min:0',
            'house_use_rooms' => 'required|integer|min:0',
            'house_use_income' => 'required|numeric|min:0',
            'afiliasi_rooms' => 'required|integer|min:0',
            'afiliasi_room_income' => 'required|numeric|min:0',
            'breakfast_income' => 'required|numeric|min:0',
            'lunch_income' => 'required|numeric|min:0',
            'dinner_income' => 'required|numeric|min:0',
            'others_income' => 'required|numeric|min:0',
        ], ['date.unique' => 'Pendapatan untuk tanggal ini sudah ada.']);

        $total_rooms_sold = $validatedData['offline_rooms'] + $validatedData['online_rooms'] + $validatedData['ta_rooms'] + $validatedData['gov_rooms'] + $validatedData['corp_rooms'] + $validatedData['compliment_rooms'] + $validatedData['house_use_rooms'] + $validatedData['afiliasi_rooms'];
        $total_rooms_revenue = $validatedData['offline_room_income'] + $validatedData['online_room_income'] + $validatedData['ta_income'] + $validatedData['gov_income'] + $validatedData['corp_income'] + $validatedData['compliment_income'] + $validatedData['house_use_income'] + $validatedData['afiliasi_room_income'];
        $total_fb_revenue = $validatedData['breakfast_income'] + $validatedData['lunch_income'] + $validatedData['dinner_income'];
        $total_revenue = $total_rooms_revenue + $total_fb_revenue + ($validatedData['afiliasi_income'] ?? 0) + $validatedData['others_income'];
        $arr = ($total_rooms_sold > 0) ? ($total_rooms_revenue / $total_rooms_sold) : 0;
        $occupancy = ($property->total_rooms > 0) ? ($total_rooms_sold / $property->total_rooms) * 100 : 0;

        $dailyIncome->update(array_merge($validatedData, [
            'total_rooms_sold' => $total_rooms_sold,
            'total_rooms_revenue' => $total_rooms_revenue,
            'total_fb_revenue' => $total_fb_revenue,
            'total_revenue' => $total_revenue,
            'arr' => $arr,
            'occupancy' => $occupancy,
        ]));

        return redirect()->route('property.income.index')->with('success', 'Pendapatan harian berhasil diperbarui.');
    }

    /**
     * Menghapus data pendapatan.
     */
    public function destroy(DailyIncome $dailyIncome)
    {
        $this->authorize('delete', $dailyIncome);
        $dailyIncome->delete();
        return redirect()->route('property.income.index')->with('success', 'Data pendapatan berhasil dihapus.');
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
