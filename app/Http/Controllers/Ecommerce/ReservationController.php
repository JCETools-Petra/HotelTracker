<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\DailyOccupancy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function create()
    {
        return view('ecommerce.reservations.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'nullable|email',
            'checkin_date' => 'required|date',
            'checkout_date' => 'required|date|after_or_equal:checkin_date',
            'number_of_rooms' => 'required|integer|min:1',
        ]);

        $reservation = Reservation::create($validated + [
            'property_id' => $user->property_id,
            'user_id' => $user->id,
            'source' => 'Manual',
            'final_price' => 0,
        ]);

        $occupancy = DailyOccupancy::firstOrCreate(
            [
                'property_id' => $user->property_id,
                'date' => $reservation->checkin_date,
            ],
            ['occupied_rooms' => 0]
        );
        $occupancy->increment('occupied_rooms', $reservation->number_of_rooms);

        return redirect()->route('ecommerce.dashboard')->with('success', 'Reservasi berhasil dibuat.');
    }

    public function edit(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);
        return view('ecommerce.reservations.edit', compact('reservation'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $this->authorizeReservation($reservation);

        $validated = $request->validate([
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'nullable|email',
            'checkin_date' => 'required|date',
            'checkout_date' => 'required|date|after_or_equal:checkin_date',
            'number_of_rooms' => 'required|integer|min:1',
        ]);

        $oldDate = $reservation->checkin_date;
        $oldRooms = $reservation->number_of_rooms;

        $reservation->update($validated);

        if ($oldDate !== $reservation->checkin_date || $oldRooms !== $reservation->number_of_rooms) {
            $oldOccupancy = DailyOccupancy::where('property_id', $reservation->property_id)
                ->where('date', $oldDate)
                ->first();
            if ($oldOccupancy) {
                $oldOccupancy->decrement('occupied_rooms', $oldRooms);
            }

            $newOccupancy = DailyOccupancy::firstOrCreate(
                [
                    'property_id' => $reservation->property_id,
                    'date' => $reservation->checkin_date,
                ],
                ['occupied_rooms' => 0]
            );
            $newOccupancy->increment('occupied_rooms', $reservation->number_of_rooms);
        }

        return redirect()->route('ecommerce.dashboard')->with('success', 'Reservasi berhasil diperbarui.');
    }

    public function destroy(Reservation $reservation)
    {
        $this->authorizeReservation($reservation);

        $occupancy = DailyOccupancy::where('property_id', $reservation->property_id)
            ->where('date', $reservation->checkin_date)
            ->first();
        if ($occupancy) {
            $occupancy->decrement('occupied_rooms', $reservation->number_of_rooms);
        }

        $reservation->delete();

        return redirect()->route('ecommerce.dashboard')->with('success', 'Reservasi berhasil dihapus.');
    }

    public function events(Request $request)
    {
        $user = Auth::user();
        $reservations = Reservation::where('property_id', $user->property_id)
            ->where('user_id', $user->id)
            ->get();

        $events = $reservations->map(function ($reservation) {
            return [
                'id' => $reservation->id,
                'title' => $reservation->guest_name,
                'start' => $reservation->checkin_date,
                'end' => Carbon::parse($reservation->checkout_date)->addDay()->toDateString(),
            ];
        });

        return response()->json($events);
    }

    private function authorizeReservation(Reservation $reservation): void
    {
        $user = Auth::user();
        if ($reservation->user_id !== $user->id) {
            abort(403);
        }
    }
}