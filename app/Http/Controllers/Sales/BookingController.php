<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Models\FunctionSheet; 
use Illuminate\Support\Str;
use App\Models\PricePackage;
use App\Models\MiceCategory;

class BookingController extends Controller
{
    public function index(Request $request) // <-- Tambahkan Request $request
    {
        // Mulai dengan query dasar
        $query = Booking::query();

        // 1. Terapkan filter pencarian (berdasarkan nama klien atau nomor booking)
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('client_name', 'like', "%{$searchTerm}%")
                  ->orWhere('booking_number', 'like', "%{$searchTerm}%");
            });
        }

        // 2. Terapkan filter status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // 3. Terapkan filter rentang tanggal acara
        if ($request->filled('start_date')) {
            $query->whereDate('event_date', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('event_date', '<=', $request->input('end_date'));
        }

        // Ambil hasil query dengan relasi property, urutkan, dan paginasi
        $bookings = $query->with('property')->latest()->paginate(10);
        
        // Penting: Sertakan query string dalam link paginasi
        $bookings->appends($request->all());

        return view('sales.bookings.index', compact('bookings'));
    }

    public function showBeo(Booking $booking)
    {
        // Memastikan BEO sudah ada, jika tidak, redirect ke halaman edit BEO
        if (!$booking->functionSheet) {
            return redirect()->route('sales.bookings.beo', $booking)
                ->with('error', 'Silakan lengkapi Function Sheet terlebih dahulu.');
        }

        return view('sales.bookings.show-beo', [
            'booking' => $booking,
            'beo' => $booking->functionSheet
        ]);
    }

    public function create()
    {
        $properties = Property::orderBy('name')->get();
        $miceCategories = MiceCategory::all();
        return view('sales.bookings.create', compact('properties', 'packages', 'miceCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_date' => 'required|date',
            'client_name' => 'required|string|max:255',
            'event_type' => 'required|string|max:255',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'participants' => 'required|integer|min:1',
            'property_id' => 'required|exists:properties,id',
            'person_in_charge' => 'required|string|max:255',
            'status' => 'required|in:Booking Sementara,Booking Pasti,Cancel',
            'notes' => 'nullable|string',
        ]);

        $bookingData = $request->all();
        // Generate nomor booking unik
        $bookingData['booking_number'] = 'BKN-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        Booking::create($bookingData);

        return redirect()->route('sales.bookings.index')->with('success', 'Booking berhasil ditambahkan.');
    }

    public function show(Booking $booking)
    {
        // Untuk BEO di masa depan
        return view('sales.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $properties = Property::orderBy('name')->get();
        $miceCategories = MiceCategory::all();
        return view('sales.bookings.edit', compact('booking', 'properties', 'packages', 'miceCategories'));
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'booking_date' => 'required|date',
            'client_name' => 'required|string|max:255',
            'event_type' => 'required|string|max:255',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'participants' => 'required|integer|min:1',
            'property_id' => 'required|exists:properties,id',
            'person_in_charge' => 'required|string|max:255',
            'status' => 'required|in:Booking Sementara,Booking Pasti,Cancel',
            'notes' => 'nullable|string',
        ]);

        $booking->update($request->all());

        return redirect()->route('sales.bookings.index')->with('success', 'Booking berhasil diperbarui.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('sales.bookings.index')->with('success', 'Booking berhasil dihapus.');
    }
    public function beo(Booking $booking)
    {
        if ($booking->status !== 'Booking Pasti') {
            return redirect()->route('sales.bookings.index')->with('error', 'Hanya booking dengan status "Booking Pasti" yang bisa dibuatkan BEO.');
        }

        $beo = $booking->functionSheet ?? new FunctionSheet();
        $departments = ['SECURITY', 'BANQUET', 'CHEF KICTHEN', 'ENGINEERING', 'PUBLIC AREA', 'STEWARDING', 'FRONT OFFICE', 'ACCOUNTING'];
        
        // Ambil paket harga yang aktif
        $pricePackages = PricePackage::where('is_active', true)->orderBy('name')->get();

        return view('sales.bookings.beo', compact('booking', 'beo', 'departments', 'pricePackages'));
    }

    /**
     * Menyimpan data BEO (create atau update) (Versi Revisi).
     */
    public function storeBeo(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'contact_phone' => 'nullable|string|max:20',
            'room_setup' => 'required|string',
            'price_package_id' => 'required|exists:price_packages,id',
            'event_segments' => 'nullable|array',
            'menu_details' => 'nullable|array',
            'equipment_details' => 'nullable|array',
            'department_notes' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);
        
        // Fungsi untuk membersihkan baris kosong dari input dinamis
        $filterEmpty = function ($items) {
            if (empty($items)) return null;
            return array_filter($items, function ($item) {
                return count(array_filter((array)$item)) > 0;
            });
        };

        // Ambil detail paket harga yang dipilih
        $pricePackage = PricePackage::find($validated['price_package_id']);
        
        // Simpan atau update Function Sheet
        $functionSheet = $booking->functionSheet()->updateOrCreate(
            ['booking_id' => $booking->id], // Cari berdasarkan ini
            [ // Data untuk disimpan
                // [FIX] Logika nomor BEO sekarang aman dari error 'null'
                'beo_number' => $booking->functionSheet?->beo_number ?? 'BEO-' . date('Y') . '-' . str_pad($booking->id, 5, '0', STR_PAD_LEFT),
                'dealed_by' => auth()->user()->name,
                'contact_phone' => $validated['contact_phone'],
                'room_setup' => $validated['room_setup'],
                'price_package_id' => $validated['price_package_id'],
                'notes' => $validated['notes'],
                'event_segments' => $filterEmpty($validated['event_segments'] ?? []),
                'menu_details' => $filterEmpty($validated['menu_details'] ?? []),
                'equipment_details' => $filterEmpty($validated['equipment_details'] ?? []),
                'department_notes' => array_filter($validated['department_notes'] ?? []),
            ]
        );

        // Hitung dan update total harga di booking utama
        $totalPrice = $pricePackage->price * $booking->participants;
        $booking->update(['total_price' => $totalPrice]);

        // Redirect dengan pesan sukses
        return redirect()->route('sales.bookings.index')->with('success', 'Function Sheet (BEO) berhasil disimpan.');
    }
    public function printBeo(Booking $booking)
    {
        if (!$booking->functionSheet) {
            return redirect()->back()->with('error', 'Function Sheet belum dibuat.');
        }

        return view('sales.bookings.print-beo', [
            'booking' => $booking,
            'beo' => $booking->functionSheet
        ]);
    }
}