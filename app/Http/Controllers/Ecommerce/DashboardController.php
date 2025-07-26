<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Services\ReservationPriceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DailyOccupancy; // <-- TAMBAHKAN INI

class DashboardController extends Controller
{
    protected $priceService;

    public function __construct(ReservationPriceService $priceService)
    {
        $this->priceService = $priceService;
    }

    public function index()
    {
        $user = Auth::user();
        $property = $user->property;

        if (!$property) {
            abort(403, 'Akun Anda tidak terikat pada properti manapun.');
        }

        // ## AWAL PERUBAHAN ##
        // Ambil data okupansi hari ini
        $occupancyToday = DailyOccupancy::where('property_id', $property->id)
                                        ->where('date', today()->toDateString())
                                        ->first();

        // Siapkan variabelnya, default 0 jika belum diinput
        $currentOccupancy = $occupancyToday ? $occupancyToday->occupied_rooms : 0;
        // ## AKHIR PERUBAHAN ##

        $currentPrices = $this->priceService->getCurrentPricesForProperty($property->id, today()->toDateString());

        // Kirim variabel baru ke view
        return view('ecommerce.dashboard', compact('property', 'currentPrices', 'currentOccupancy'));
    }
}