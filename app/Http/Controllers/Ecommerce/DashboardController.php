<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Http\Traits\LogActivity;
use App\Services\ReservationPriceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DailyOccupancy;

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
        $currentPrices = $this->priceService->getCurrentPricesForProperty($property->id, today()->toDateString());

        // Log the activity
        $this->logActivity('Melihat dashboard harga OTA.', $request);

        return view('ecommerce.dashboard', compact('property', 'currentPrices', 'currentOccupancy'));
    }
}