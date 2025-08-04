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