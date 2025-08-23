<<<<<<< HEAD
<?php

namespace App\Http\Controllers;

// ===== BAGIAN USE STATEMENT (PASTIKAN SEMUA INI ADA) =====
use App\Models\DailyIncome;
use App\Models\Property;
use App\Http\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PropertyIncomesExport;
use Illuminate\Support\Str;
use App\Services\ReservationPriceService;
use App\Models\DailyOccupancy;
use App\Models\Reservation;

class PropertyIncomeController extends Controller
{
    use LogActivity; // <-- 1. Gunakan Trait di sini

    protected $priceService;

    public function __construct(ReservationPriceService $priceService)
    {
        $this->priceService = $priceService;
    }

    public function dashboard()
    {
        $user = Auth::user();
        $property = $user->property;

        $occupancyToday = DailyOccupancy::firstOrCreate(
            [
                'property_id' => $property->id,
                'date' => today()->toDateString(),
            ],
            ['occupied_rooms' => 0]
        );

        // ## AWAL PERUBAHAN ##
        // Ambil data harga dinamis saat ini untuk semua tipe kamar
        $currentPrices = $this->priceService->getCurrentPricesForProperty($property->id, today()->toDateString());
        // ## AKHIR PERUBAHAN ##

        // Kirim variabel baru ke view
        return view('property.dashboard', compact('property', 'occupancyToday', 'currentPrices'));
    }

    public function updateOccupancy(Request $request)
    {
        $user = Auth::user();
        $property = $user->property;

        $validated = $request->validate([
            'occupied_rooms' => 'required|integer|min:0',
        ]);

        DailyOccupancy::updateOrCreate(
            [
                'property_id' => $property->id,
                'date' => today()->toDateString(),
            ],
            ['occupied_rooms' => $validated['occupied_rooms']]
        );

        // Tambahkan Log
        $this->logActivity('Memperbarui jumlah okupansi harian menjadi ' . $validated['occupied_rooms'] . ' kamar.', $request);

        return redirect()->route('property.dashboard')->with('success', 'Jumlah kamar terisi berhasil diperbarui.');
    }

    public function createOtaReservation()
    {
        $user = Auth::user();
        $property = $user->property;
        $sources = ['Traveloka', 'Booking.com', 'Agoda', 'Tiket.com'];

        return view('property.reservations.create', compact('property', 'sources'));
    }

    public function storeOtaReservation(Request $request)
    {
        $user = Auth::user();
        $property = $user->property;

        $validated = $request->validate([
            'source' => 'required|string',
            'guest_name' => 'required|string|max:255',
            'checkin_date' => 'required|date',
            'checkout_date' => 'required|date|after_or_equal:checkin_date',
        ]);

        $finalPrice = $this->priceService->getCurrentPricesForProperty($property->id, $validated['checkin_date'])
                                ->firstWhere('name', $request->room_type_name)['price_ota'] ?? 0; // Sesuaikan jika ada pilihan tipe kamar

        Reservation::create($validated + [
            'final_price' => $finalPrice,
            'property_id' => $property->id
        ]);

        // Tambahkan Log
        $this->logActivity('Menambahkan reservasi OTA baru untuk tamu: ' . $validated['guest_name'], $request);

        return redirect()->route('property.reservations.create')->with('success', 'Reservasi OTA berhasil ditambahkan.');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $property = $user->property;

        if (!$property) {
            return redirect('/')->with('error', 'Anda tidak terkait dengan properti manapun.');
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = DailyIncome::where('property_id', $property->id);

        if ($startDate) {
            try {
                $query->whereDate('date', '>=', Carbon::parse($startDate));
            } catch (\Exception $e) {}
        }
        if ($endDate) {
            try {
                $query->whereDate('date', '<=', Carbon::parse($endDate));
            } catch (\Exception $e) {}
        }

        $incomes = $query->orderBy('date', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('property.income.index', compact('incomes', 'property', 'startDate', 'endDate'));
    }

    public function create()
    {
        $user = Auth::user();
        $property = $user->property;

        if (!$property) {
            return redirect('/')->with('error', 'Anda tidak terkait dengan properti manapun.');
        }

        return view('property.income.create', [
            'property' => $property,
            'date' => old('date', Carbon::today()->toDateString())
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $property = $user->property;
        if (!$property) {
            abort(403);
        }
    
        $validatedData = $request->validate([
            'date' => 'required|date|unique:daily_incomes,date,NULL,id,property_id,' . $property->id,
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
        ], [
            'date.unique' => 'Pendapatan untuk tanggal ini sudah pernah dicatat.',
        ]);
    
        $total_rooms_sold =
            (int)$validatedData['offline_rooms'] + (int)$validatedData['online_rooms'] + (int)$validatedData['ta_rooms'] +
            (int)$validatedData['gov_rooms'] + (int)$validatedData['corp_rooms'] + (int)$validatedData['compliment_rooms'] +
            (int)$validatedData['house_use_rooms'] + (int)$validatedData['afiliasi_rooms'];
    
        $total_rooms_revenue =
            (float)$validatedData['offline_room_income'] + (float)$validatedData['online_room_income'] + (float)$validatedData['ta_income'] +
            (float)$validatedData['gov_income'] + (float)$validatedData['corp_income'] + (float)$validatedData['compliment_income'] +
            (float)$validatedData['house_use_income'] + (float)$validatedData['afiliasi_room_income'];
    
        $total_fb_revenue = (float)$validatedData['breakfast_income'] + (float)$validatedData['lunch_income'] + (float)$validatedData['dinner_income'];
    
        $total_revenue = $total_rooms_revenue + $total_fb_revenue + (float)$validatedData['others_income'];
    
        $arr = ($total_rooms_sold > 0) ? ($total_rooms_revenue / $total_rooms_sold) : 0;
        $occupancy = ($property->total_rooms > 0) ? ($total_rooms_sold / $property->total_rooms) * 100 : 0;
    
        $incomeData = array_merge($validatedData, [
            'property_id' => $property->id,
            'user_id' => $user->id,
            'total_rooms_sold' => $total_rooms_sold,
            'total_rooms_revenue' => $total_rooms_revenue,
            'total_fb_revenue' => $total_fb_revenue,
            'total_revenue' => $total_revenue,
            'arr' => $arr,
            'occupancy' => $occupancy,
        ]);
    
        DailyIncome::create($incomeData);
    
        // Tambahkan Log
        $formattedDate = Carbon::parse($incomeData['date'])->isoFormat('D MMMM YYYY');
        $this->logActivity('Mencatat pendapatan harian baru untuk tanggal ' . $formattedDate, $request);

        return redirect()->route('property.income.index')->with('success', 'Pendapatan harian berhasil dicatat.');
    }

    public function edit(DailyIncome $dailyIncome)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->property_id != $dailyIncome->property_id) {
            abort(403, 'Akses tidak diizinkan untuk mengedit data ini.');
        }
        $property = $user->property;
        return view('property.income.edit', compact('dailyIncome', 'property'));
    }
    
    public function update(Request $request, DailyIncome $dailyIncome)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->property_id != $dailyIncome->property_id) {
            abort(403, 'Akses tidak diizinkan untuk memperbarui data ini.');
        }
    
        $validatedData = $request->validate([
            'date' => 'required|date|unique:daily_incomes,date,' . $dailyIncome->id . ',id,property_id,' . $dailyIncome->property_id,
            'offline_rooms' => 'required|integer|min:0', 'offline_room_income' => 'required|numeric|min:0',
            'online_rooms' => 'required|integer|min:0', 'online_room_income' => 'required|numeric|min:0',
            'ta_rooms' => 'required|integer|min:0', 'ta_income' => 'required|numeric|min:0',
            'gov_rooms' => 'required|integer|min:0', 'gov_income' => 'required|numeric|min:0',
            'corp_rooms' => 'required|integer|min:0', 'corp_income' => 'required|numeric|min:0',
            'compliment_rooms' => 'required|integer|min:0', 'compliment_income' => 'required|numeric|min:0',
            'house_use_rooms' => 'required|integer|min:0', 'house_use_income' => 'required|numeric|min:0',
            'afiliasi_rooms' => 'required|integer|min:0', 'afiliasi_room_income' => 'required|numeric|min:0',
            'breakfast_income' => 'required|numeric|min:0',
            'lunch_income' => 'required|numeric|min:0',
            'dinner_income' => 'required|numeric|min:0',
            'others_income' => 'required|numeric|min:0',
        ], [
            'date.unique' => 'Pendapatan untuk tanggal ini sudah ada.',
        ]);
    
        $property = $dailyIncome->property;
        $total_rooms_sold = (int)$validatedData['offline_rooms'] + (int)$validatedData['online_rooms'] + (int)$validatedData['ta_rooms'] + (int)$validatedData['gov_rooms'] + (int)$validatedData['corp_rooms'] + (int)$validatedData['compliment_rooms'] + (int)$validatedData['house_use_rooms'] + (int)$validatedData['afiliasi_rooms'];
        $total_rooms_revenue = (float)$validatedData['offline_room_income'] + (float)$validatedData['online_room_income'] + (float)$validatedData['ta_income'] + (float)$validatedData['gov_income'] + (float)$validatedData['corp_income'] + (float)$validatedData['compliment_income'] + (float)$validatedData['house_use_income'] + (float)$validatedData['afiliasi_room_income'];
        $total_fb_revenue = (float)$validatedData['breakfast_income'] + (float)$validatedData['lunch_income'] + (float)$validatedData['dinner_income'];
        $total_revenue = $total_rooms_revenue + $total_fb_revenue + (float)$validatedData['others_income'];
        $arr = ($total_rooms_sold > 0) ? ($total_rooms_revenue / $total_rooms_sold) : 0;
        $occupancy = ($property->total_rooms > 0) ? ($total_rooms_sold / $property->total_rooms) * 100 : 0;
    
        $updateData = array_merge($validatedData, [
            'total_rooms_sold' => $total_rooms_sold,
            'total_rooms_revenue' => $total_rooms_revenue,
            'total_fb_revenue' => $total_fb_revenue,
            'total_revenue' => $total_revenue,
            'arr' => $arr,
            'occupancy' => $occupancy,
        ]);
        
        $dailyIncome->update($updateData);

        // Tambahkan Log
        $formattedDate = Carbon::parse($dailyIncome->date)->isoFormat('D MMMM YYYY');
        $this->logActivity('Memperbarui data pendapatan harian untuk tanggal ' . $formattedDate, $request);
    
        if ($user->role === 'admin') {
            return redirect()->route('admin.properties.show', $dailyIncome->property_id)->with('success', 'Data pendapatan berhasil diperbarui.');
        }
    
        return redirect()->route('property.income.index')->with('success', 'Data pendapatan berhasil diperbarui.');
    }

    public function destroy(DailyIncome $dailyIncome)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        
        if ($user->role !== 'admin' && $user->property_id != $dailyIncome->property_id) {
            abort(403, 'Akses tidak diizinkan untuk menghapus data ini.');
        }

        $originalDate = $dailyIncome->date;
        $dailyIncome->delete();

        // Tambahkan Log
        $formattedDate = Carbon::parse($originalDate)->isoFormat('D MMMM YYYY');
        $this->logActivity('Menghapus data pendapatan harian untuk tanggal ' . $formattedDate, request());

        if ($user->role === 'admin') {
            return back()->with('success', 'Data pendapatan untuk tanggal ' . $formattedDate . ' berhasil dihapus.');
        }

        return redirect()->route('property.income.index')->with('success', 'Data pendapatan untuk tanggal ' . $formattedDate . ' berhasil dihapus.');
    }

    public function exportIncomesExcel(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->property_id) {
            return redirect()->back()->with('error', 'Tidak dapat mengekspor data, properti tidak ditemukan.');
        }

        $propertyId = $user->property_id;
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $fileName = 'laporan_pendapatan_' . Str::slug($user->property->name) . '_' . Carbon::now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new PropertyIncomesExport($propertyId, $startDate, $endDate), $fileName);
    }

    public function exportIncomesCsv(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->property_id) {
            return redirect()->back()->with('error', 'Tidak dapat mengekspor data, properti tidak ditemukan.');
        }

        $propertyId = $user->property_id;
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $fileName = 'laporan_pendapatan_' . Str::slug($user->property->name) . '_' . Carbon::now()->format('Ymd_His') . '.csv';

        return Excel::download(new PropertyIncomesExport($propertyId, $startDate, $endDate), $fileName, \Maatwebsite\Excel\Excel::CSV);
    }
}
=======
<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
// ===== BAGIAN USE STATEMENT (PASTIKAN SEMUA INI ADA) =====
use App\Models\DailyIncome;
use App\Models\Property;
use App\Http\Traits\LogActivity;
=======
use App\Models\DailyIncome;
use App\Models\Property;
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PropertyIncomesExport;
use Illuminate\Support\Str;
<<<<<<< HEAD
use App\Services\ReservationPriceService;
use App\Models\DailyOccupancy;
use App\Models\Reservation;
use App\Events\OccupancyUpdated;

class PropertyIncomeController extends Controller
{
    use LogActivity; // <-- 1. Gunakan Trait di sini
    
    protected $priceService;

    public function __construct(ReservationPriceService $priceService)
    {
        $this->priceService = $priceService;
    }
    
    public function calendar()
    {
        // View akan membutuhkan variabel 'properties' jika ada filter, 
        // tapi untuk pengguna properti, kita bisa lewatkan properti mereka saja.
        $property = auth()->user()->property;
        return view('property.calendar.index', compact('property'));
    }
    
    public function getCalendarData(Request $request)
    {
        $user = auth()->user();
        $property = $user->property;

        if (!$property) {
            return response()->json(['events' => [], 'chartData' => []]);
        }
        
        // Data untuk Events Kalender
        $events = Reservation::where('property_id', $property->id)
            ->select('id', 'guest_name as title', 'checkin_date as start', 'checkout_date as end')
            ->get();

        // Data untuk Chart Okupansi (30 Hari Terakhir)
        $startDate = Carbon::now()->subDays(30);
        $chartOccupancyData = DailyOccupancy::where('property_id', $property->id)
            ->where('date', '>=', $startDate)
            ->orderBy('date', 'asc')
            ->get(['date', 'occupied_rooms']);

        $chartData = [
            'labels' => $chartOccupancyData->pluck('date')->map(fn ($date) => Carbon::parse($date)->format('d M')),
            'data' => $chartOccupancyData->pluck('occupied_rooms'),
        ];
        
        return response()->json([
            'events' => $events,
            'chartData' => $chartData,
        ]);
    }

=======

class PropertyIncomeController extends Controller
{
    /**
     * Menampilkan dashboard untuk pengguna properti.
     */
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
    public function dashboard()
    {
        $user = Auth::user();
        $property = $user->property;

        if (!$property) {
<<<<<<< HEAD
            abort(403, 'Akun Anda tidak terikat pada properti manapun.');
        }

        // Tentukan rentang tanggal untuk bulan ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Buat query dasar dengan filter rentang tanggal
        $incomesThisMonthQuery = DailyIncome::where('property_id', $property->id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth]);

        // Hitung total untuk setiap kategori HANYA untuk bulan ini
        $totalRevenue = (clone $incomesThisMonthQuery)->sum('total_revenue');
        $totalRoomRevenue = (clone $incomesThisMonthQuery)->sum('total_rooms_revenue');
        $totalFbRevenue = (clone $incomesThisMonthQuery)->sum('total_fb_revenue');
        $totalOthersIncome = (clone $incomesThisMonthQuery)->sum('others_income');

        $occupancyToday = DailyOccupancy::where('property_id', $property->id)
            ->where('date', today()->toDateString())
            ->first();

        $latestIncomes = $property->dailyIncomes()
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        return view('property.dashboard', [
            'property' => $property,
            'totalRevenue' => $totalRevenue,
            'totalRoomRevenue' => $totalRoomRevenue,
            'totalFbRevenue' => $totalFbRevenue,
            'totalOthersIncome' => $totalOthersIncome,
            'occupancyToday' => $occupancyToday,
            'latestIncomes' => $latestIncomes,
        ]);
    }

    // In app/Http/Controllers/PropertyIncomeController.php

    public function updateOccupancy(Request $request)
    {
        $user = Auth::user();
        $property = $user->property;
    
        if (!$property) {
            return redirect()->back()->with('error', 'Akun Anda tidak terikat dengan properti manapun.');
        }
    
        $validated = $request->validate([
            'occupied_rooms' => 'required|integer|min:0',
            'date' => 'required|date_format:Y-m-d',
        ]);
    
        $manualRooms = $validated['occupied_rooms'];
    
        // === START OF THE FIX ===
        // Find the record or create it with default values if it doesn't exist.
        $dailyOccupancy = DailyOccupancy::firstOrCreate(
            [
                'property_id' => $property->id,
                'date' => $validated['date'],
            ],
            [
                // Provide default values for new records
                'occupied_rooms' => 0,
                'reservasi_ota' => 0,
                'reservasi_properti' => 0,
            ]
        );
        // === END OF THE FIX ===
    
        // Now, update the values based on the user's input
        $dailyOccupancy->reservasi_properti = $manualRooms;
        $dailyOccupancy->occupied_rooms = $dailyOccupancy->reservasi_ota + $manualRooms; // Recalculate total
        $dailyOccupancy->save();
    
        // Trigger the event for notifications
        \App\Events\OccupancyUpdated::dispatch($property, $dailyOccupancy);
        
        // Log the activity
        if (in_array(\App\Http\Traits\LogActivity::class, class_uses($this))) {
            $this->logActivity('Memperbarui okupansi properti manual menjadi ' . $manualRooms . ' kamar untuk tanggal ' . $validated['date'], $request);
        }
        
        return redirect()->route('property.dashboard')->with('success', 'Jumlah okupansi properti berhasil diperbarui.');
    }

    public function createOtaReservation()
    {
        $user = Auth::user();
        $property = $user->property;
        $sources = ['Traveloka', 'Booking.com', 'Agoda', 'Tiket.com'];

        return view('property.reservations.create', compact('property', 'sources'));
    }

    public function storeOtaReservation(Request $request)
    {
        $user = Auth::user();
        $property = $user->property;

        $validated = $request->validate([
            'source' => 'required|string',
            'guest_name' => 'required|string|max:255',
            'checkin_date' => 'required|date',
            'checkout_date' => 'required|date|after_or_equal:checkin_date',
        ]);

        $finalPrice = $this->priceService->getCurrentPricesForProperty($property->id, $validated['checkin_date'])
                                ->firstWhere('name', $request->room_type_name)['price_ota'] ?? 0; // Sesuaikan jika ada pilihan tipe kamar

        Reservation::create($validated + [
            'final_price' => $finalPrice,
            'property_id' => $property->id
        ]);

        // Tambahkan Log
        $this->logActivity('Menambahkan reservasi OTA baru untuk tamu: ' . $validated['guest_name'], $request);

        return redirect()->route('property.reservations.create')->with('success', 'Reservasi OTA berhasil ditambahkan.');
    }

=======
            return redirect('/')->with('error', 'Anda tidak terkait dengan properti manapun atau properti tidak ditemukan.');
        }

        $todayIncome = DailyIncome::where('property_id', $property->id)
            ->whereDate('date', Carbon::today())
            ->first();

        return view('property.dashboard', compact('property', 'todayIncome'));
    }

    /**
     * Menampilkan daftar riwayat pendapatan harian.
     */
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
    public function index(Request $request)
    {
        $user = Auth::user();
        $property = $user->property;

        if (!$property) {
            return redirect('/')->with('error', 'Anda tidak terkait dengan properti manapun.');
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = DailyIncome::where('property_id', $property->id);

        if ($startDate) {
            try {
                $query->whereDate('date', '>=', Carbon::parse($startDate));
            } catch (\Exception $e) {}
        }
        if ($endDate) {
            try {
                $query->whereDate('date', '<=', Carbon::parse($endDate));
            } catch (\Exception $e) {}
        }

        $incomes = $query->orderBy('date', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('property.income.index', compact('incomes', 'property', 'startDate', 'endDate'));
    }

<<<<<<< HEAD
=======
    /**
     * Menampilkan form untuk membuat data pendapatan harian baru.
     */
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
    public function create()
    {
        $user = Auth::user();
        $property = $user->property;

        if (!$property) {
            return redirect('/')->with('error', 'Anda tidak terkait dengan properti manapun.');
        }

        return view('property.income.create', [
            'property' => $property,
            'date' => old('date', Carbon::today()->toDateString())
        ]);
    }

<<<<<<< HEAD
=======
    /**
     * Menyimpan data pendapatan harian baru ke database.
     */
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
    public function store(Request $request)
    {
        $user = Auth::user();
        $property = $user->property;
<<<<<<< HEAD
        if (!$property) {
            abort(403);
        }
    
        $validatedData = $request->validate([
            'date' => 'required|date|unique:daily_incomes,date,NULL,id,property_id,' . $property->id,
=======

        if (!$property) {
            return redirect('/')->with('error', 'Tidak dapat menyimpan data, Anda tidak terkait dengan properti.');
        }

        // 1. Validasi semua input dari form (DENGAN KOLOM BARU)
        $validatedData = $request->validate([
            'date' => 'required|date|unique:daily_incomes,date,NULL,id,property_id,'.$property->id,
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
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
<<<<<<< HEAD
            'afiliasi_rooms' => 'required|integer|min:0',
            'afiliasi_room_income' => 'required|numeric|min:0',
=======
            'mice_income' => 'required|numeric|min:0',
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
            'breakfast_income' => 'required|numeric|min:0',
            'lunch_income' => 'required|numeric|min:0',
            'dinner_income' => 'required|numeric|min:0',
            'others_income' => 'required|numeric|min:0',
        ], [
            'date.unique' => 'Pendapatan untuk tanggal ini sudah pernah dicatat.',
        ]);
<<<<<<< HEAD
    
        $total_rooms_sold =
            (int)$validatedData['offline_rooms'] + (int)$validatedData['online_rooms'] + (int)$validatedData['ta_rooms'] +
            (int)$validatedData['gov_rooms'] + (int)$validatedData['corp_rooms'] + (int)$validatedData['compliment_rooms'] +
            (int)$validatedData['house_use_rooms'] + (int)$validatedData['afiliasi_rooms'];
    
        $total_rooms_revenue =
            (float)$validatedData['offline_room_income'] + (float)$validatedData['online_room_income'] + (float)$validatedData['ta_income'] +
            (float)$validatedData['gov_income'] + (float)$validatedData['corp_income'] + (float)$validatedData['compliment_income'] +
            (float)$validatedData['house_use_income'] + (float)$validatedData['afiliasi_room_income'];
    
        $total_fb_revenue = (float)$validatedData['breakfast_income'] + (float)$validatedData['lunch_income'] + (float)$validatedData['dinner_income'];
    
        $total_revenue = $total_rooms_revenue + $total_fb_revenue + (float)$validatedData['others_income'];
    
        $arr = ($total_rooms_sold > 0) ? ($total_rooms_revenue / $total_rooms_sold) : 0;
        $occupancy = ($property->total_rooms > 0) ? ($total_rooms_sold / $property->total_rooms) * 100 : 0;
    
=======

        // 2. Kalkulasi nilai total berdasarkan input
        $total_rooms_sold =
            $validatedData['offline_rooms'] + $validatedData['online_rooms'] + $validatedData['ta_rooms'] +
            $validatedData['gov_rooms'] + $validatedData['corp_rooms'] + $validatedData['compliment_rooms'] +
            $validatedData['house_use_rooms'];

        $total_rooms_revenue =
            $validatedData['offline_room_income'] + $validatedData['online_room_income'] + $validatedData['ta_income'] +
            $validatedData['gov_income'] + $validatedData['corp_income'] + $validatedData['compliment_income'] +
            $validatedData['house_use_income'] + $validatedData['mice_income'];

        $total_fb_revenue = $validatedData['breakfast_income'] + $validatedData['lunch_income'] + $validatedData['dinner_income'];
        $total_revenue = $total_rooms_revenue + $total_fb_revenue + $validatedData['others_income'];
        
        $arr = ($total_rooms_sold > 0) ? ($total_rooms_revenue / $total_rooms_sold) : 0;
        $occupancy = ($property->total_rooms > 0) ? ($total_rooms_sold / $property->total_rooms) * 100 : 0;

        // 3. Siapkan data yang akan dimasukkan ke database
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
        $incomeData = array_merge($validatedData, [
            'property_id' => $property->id,
            'user_id' => $user->id,
            'total_rooms_sold' => $total_rooms_sold,
            'total_rooms_revenue' => $total_rooms_revenue,
            'total_fb_revenue' => $total_fb_revenue,
            'total_revenue' => $total_revenue,
            'arr' => $arr,
            'occupancy' => $occupancy,
        ]);
<<<<<<< HEAD
    
        DailyIncome::create($incomeData);
    
        // Tambahkan Log
        $formattedDate = Carbon::parse($incomeData['date'])->isoFormat('D MMMM YYYY');
        $this->logActivity('Mencatat pendapatan harian baru untuk tanggal ' . $formattedDate, $request);
=======

        // 4. Simpan data ke database
        DailyIncome::create($incomeData);
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9

        return redirect()->route('property.income.index')->with('success', 'Pendapatan harian berhasil dicatat.');
    }

<<<<<<< HEAD
=======
    /**
     * Menampilkan form untuk mengedit data pendapatan harian.
     */
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
    public function edit(DailyIncome $dailyIncome)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->property_id != $dailyIncome->property_id) {
            abort(403, 'Akses tidak diizinkan untuk mengedit data ini.');
        }
        $property = $user->property;
        return view('property.income.edit', compact('dailyIncome', 'property'));
    }
<<<<<<< HEAD
    
=======

    /**
     * Memperbarui data pendapatan harian di database.
     */
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
    public function update(Request $request, DailyIncome $dailyIncome)
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->property_id != $dailyIncome->property_id) {
            abort(403, 'Akses tidak diizinkan untuk memperbarui data ini.');
        }
<<<<<<< HEAD
    
        $validatedData = $request->validate([
            'date' => 'required|date|unique:daily_incomes,date,' . $dailyIncome->id . ',id,property_id,' . $dailyIncome->property_id,
            'offline_rooms' => 'required|integer|min:0', 'offline_room_income' => 'required|numeric|min:0',
            'online_rooms' => 'required|integer|min:0', 'online_room_income' => 'required|numeric|min:0',
            'ta_rooms' => 'required|integer|min:0', 'ta_income' => 'required|numeric|min:0',
            'gov_rooms' => 'required|integer|min:0', 'gov_income' => 'required|numeric|min:0',
            'corp_rooms' => 'required|integer|min:0', 'corp_income' => 'required|numeric|min:0',
            'compliment_rooms' => 'required|integer|min:0', 'compliment_income' => 'required|numeric|min:0',
            'house_use_rooms' => 'required|integer|min:0', 'house_use_income' => 'required|numeric|min:0',
            'afiliasi_rooms' => 'required|integer|min:0', 'afiliasi_room_income' => 'required|numeric|min:0',
=======

        // 1. Validasi semua input dari form (DENGAN KOLOM BARU)
        $validatedData = $request->validate([
            'date' => 'required|date|unique:daily_incomes,date,' . $dailyIncome->id . ',id,property_id,' . $dailyIncome->property_id,
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
            'mice_income' => 'required|numeric|min:0',
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
            'breakfast_income' => 'required|numeric|min:0',
            'lunch_income' => 'required|numeric|min:0',
            'dinner_income' => 'required|numeric|min:0',
            'others_income' => 'required|numeric|min:0',
        ], [
            'date.unique' => 'Pendapatan untuk tanggal ini sudah ada.',
        ]);
<<<<<<< HEAD
    
        $property = $dailyIncome->property;
        $total_rooms_sold = (int)$validatedData['offline_rooms'] + (int)$validatedData['online_rooms'] + (int)$validatedData['ta_rooms'] + (int)$validatedData['gov_rooms'] + (int)$validatedData['corp_rooms'] + (int)$validatedData['compliment_rooms'] + (int)$validatedData['house_use_rooms'] + (int)$validatedData['afiliasi_rooms'];
        $total_rooms_revenue = (float)$validatedData['offline_room_income'] + (float)$validatedData['online_room_income'] + (float)$validatedData['ta_income'] + (float)$validatedData['gov_income'] + (float)$validatedData['corp_income'] + (float)$validatedData['compliment_income'] + (float)$validatedData['house_use_income'] + (float)$validatedData['afiliasi_room_income'];
        $total_fb_revenue = (float)$validatedData['breakfast_income'] + (float)$validatedData['lunch_income'] + (float)$validatedData['dinner_income'];
        $total_revenue = $total_rooms_revenue + $total_fb_revenue + (float)$validatedData['others_income'];
        $arr = ($total_rooms_sold > 0) ? ($total_rooms_revenue / $total_rooms_sold) : 0;
        $occupancy = ($property->total_rooms > 0) ? ($total_rooms_sold / $property->total_rooms) * 100 : 0;
    
=======

        // 2. Kalkulasi ulang nilai total
        $property = $dailyIncome->property;
        $total_rooms_sold =
            $validatedData['offline_rooms'] + $validatedData['online_rooms'] + $validatedData['ta_rooms'] +
            $validatedData['gov_rooms'] + $validatedData['corp_rooms'] + $validatedData['compliment_rooms'] +
            $validatedData['house_use_rooms'];

        $total_rooms_revenue =
            $validatedData['offline_room_income'] + $validatedData['online_room_income'] + $validatedData['ta_income'] +
            $validatedData['gov_income'] + $validatedData['corp_income'] + $validatedData['compliment_income'] +
            $validatedData['house_use_income'] + $validatedData['mice_income'];

        $total_fb_revenue = $validatedData['breakfast_income'] + $validatedData['lunch_income'] + $validatedData['dinner_income'];
        $total_revenue = $total_rooms_revenue + $total_fb_revenue + $validatedData['others_income'];
        
        $arr = ($total_rooms_sold > 0) ? ($total_rooms_revenue / $total_rooms_sold) : 0;
        $occupancy = ($property->total_rooms > 0) ? ($total_rooms_sold / $property->total_rooms) * 100 : 0;

        // 3. Siapkan data yang akan diperbarui
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
        $updateData = array_merge($validatedData, [
            'total_rooms_sold' => $total_rooms_sold,
            'total_rooms_revenue' => $total_rooms_revenue,
            'total_fb_revenue' => $total_fb_revenue,
            'total_revenue' => $total_revenue,
            'arr' => $arr,
            'occupancy' => $occupancy,
        ]);
        
<<<<<<< HEAD
        $dailyIncome->update($updateData);

        // Tambahkan Log
        $formattedDate = Carbon::parse($dailyIncome->date)->isoFormat('D MMMM YYYY');
        $this->logActivity('Memperbarui data pendapatan harian untuk tanggal ' . $formattedDate, $request);
    
        if ($user->role === 'admin') {
            return redirect()->route('admin.properties.show', $dailyIncome->property_id)->with('success', 'Data pendapatan berhasil diperbarui.');
        }
    
        return redirect()->route('property.income.index')->with('success', 'Data pendapatan berhasil diperbarui.');
    }

=======
        // 4. Update data di database
        $dailyIncome->update($updateData);

        if ($user->role === 'admin') {
            return redirect()->route('admin.properties.show', $dailyIncome->property_id)->with('success', 'Data pendapatan berhasil diperbarui.');
        }

        return redirect()->route('property.income.index')->with('success', 'Data pendapatan berhasil diperbarui.');
    }

    /**
     * Menghapus data pendapatan harian dari database.
     */
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
    public function destroy(DailyIncome $dailyIncome)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        
        if ($user->role !== 'admin' && $user->property_id != $dailyIncome->property_id) {
            abort(403, 'Akses tidak diizinkan untuk menghapus data ini.');
        }

        $originalDate = $dailyIncome->date;
        $dailyIncome->delete();

<<<<<<< HEAD
        // Tambahkan Log
        $formattedDate = Carbon::parse($originalDate)->isoFormat('D MMMM YYYY');
        $this->logActivity('Menghapus data pendapatan harian untuk tanggal ' . $formattedDate, request());

        if ($user->role === 'admin') {
            return back()->with('success', 'Data pendapatan untuk tanggal ' . $formattedDate . ' berhasil dihapus.');
        }

        return redirect()->route('property.income.index')->with('success', 'Data pendapatan untuk tanggal ' . $formattedDate . ' berhasil dihapus.');
    }

=======
        if ($user->role === 'admin') {
            return back()->with('success', 'Data pendapatan untuk tanggal ' . Carbon::parse($originalDate)->isoFormat('D MMMM YYYY') . ' berhasil dihapus.');
        }

        return redirect()->route('property.income.index')->with('success', 'Data pendapatan untuk tanggal ' . Carbon::parse($originalDate)->isoFormat('D MMMM YYYY') . ' berhasil dihapus.');
    }

    /**
     * Mengekspor data pendapatan ke Excel.
     */
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
    public function exportIncomesExcel(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->property_id) {
            return redirect()->back()->with('error', 'Tidak dapat mengekspor data, properti tidak ditemukan.');
        }

        $propertyId = $user->property_id;
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
<<<<<<< HEAD
=======

>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
        $fileName = 'laporan_pendapatan_' . Str::slug($user->property->name) . '_' . Carbon::now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new PropertyIncomesExport($propertyId, $startDate, $endDate), $fileName);
    }

<<<<<<< HEAD
=======
    /**
     * Mengekspor data pendapatan ke CSV.
     */
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
    public function exportIncomesCsv(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->property_id) {
            return redirect()->back()->with('error', 'Tidak dapat mengekspor data, properti tidak ditemukan.');
        }

        $propertyId = $user->property_id;
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
<<<<<<< HEAD
=======

>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
        $fileName = 'laporan_pendapatan_' . Str::slug($user->property->name) . '_' . Carbon::now()->format('Ymd_His') . '.csv';

        return Excel::download(new PropertyIncomesExport($propertyId, $startDate, $endDate), $fileName, \Maatwebsite\Excel\Excel::CSV);
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
>>>>>>> origin/master
