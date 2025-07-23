<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Reservation;
use App\Services\ReservationPriceService; // <-- Panggil Service yang sudah kita buat
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    // Inject service kita ke controller melalui constructor
    public function __construct(protected ReservationPriceService $priceService) {}

    /**
     * Menampilkan form untuk membuat reservasi baru.
     */
    public function create()
    {
        $properties = Property::all();
        $sources = ['Traveloka', 'Booking.com', 'Agoda', 'Tiket.com']; // Daftar OTA

        return view('ecommerce.reservations.create', compact('properties', 'sources'));
    }

    /**
     * Menyimpan data reservasi baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'source' => 'required|string',
            'guest_name' => 'required|string|max:255',
            'checkin_date' => 'required|date',
            'checkout_date' => 'required|date|after_or_equal:checkin_date',
        ]);

        // Gunakan service untuk menghitung harga final pada saat reservasi dibuat
        $finalPrice = $this->priceService->getCurrentRoomPrice($validated['property_id'], $validated['checkin_date']);

        // Simpan ke database dengan menambahkan harga final
        Reservation::create($validated + ['final_price' => $finalPrice]);

        return redirect()->route('ecommerce.reservations.create')->with('success', 'Reservasi berhasil dibuat!');
    }

    /**
     * Endpoint untuk AJAX request dari frontend untuk mendapatkan harga dinamis.
     */
    public function getPrice(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'checkin_date' => 'required|date',
        ]);

        $price = $this->priceService->getCurrentRoomPrice($validated['property_id'], $validated['checkin_date']);

        // Kembalikan harga dalam format JSON
        return response()->json(['price' => number_format($price, 0, ',', '.')]);
    }
}