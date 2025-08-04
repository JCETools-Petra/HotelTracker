<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function events(Request $request)
    {
        $user = Auth::user();
        $reservations = Reservation::where('property_id', $user->property_id)
            ->where('user_id', $user->id)
            ->get();

        $events = $reservations->map(function ($reservation) {
            return [
                'title' => $reservation->guest_name,
                'start' => $reservation->checkin_date,
                'end' => Carbon::parse($reservation->checkout_date)->addDay()->toDateString(),
            ];
        });

        return response()->json($events);
    }
}
