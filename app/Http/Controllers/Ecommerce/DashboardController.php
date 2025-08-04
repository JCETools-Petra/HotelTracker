<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Http\Traits\LogActivity;
use App\Services\ReservationPriceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DailyOccupancy;
use App\Models\Reservation;

class DashboardController extends Controller
{
    use LogActivity;

    protected $priceService;

    public function __construct(ReservationPriceService $priceService)
    {
        $this->priceService = $priceService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $property = $user->property;

        if (!$property) {
            abort(403, 'Akun Anda tidak terikat pada properti manapun.');
        }
        
        $occupancyToday = DailyOccupancy::where('property_id', $property->id)
                                        ->where('date', today()->toDateString())
                                        ->first();

        $currentOccupancy = $occupancyToday ? $occupancyToday->occupied_rooms : 0;

        $ownReservationRooms = Reservation::where('property_id', $property->id)
            ->where('user_id', $user->id)
            ->whereDate('checkin_date', today()->toDateString())
            ->sum('number_of_rooms');

        $currentOccupancy += $ownReservationRooms;
        $currentPrices = $this->priceService->getCurrentPricesForProperty($property->id, today()->toDateString());

        // Log the activity
        $this->logActivity('Melihat dashboard harga OTA.', $request);

        return view('ecommerce.dashboard', compact('property', 'currentPrices', 'currentOccupancy'));
    }
}