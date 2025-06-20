<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Menampilkan halaman kalender.
     */
    public function index()
    {
        return view('sales.calendar.index');
    }

    /**
     * Menyediakan data event dalam format JSON untuk FullCalendar.
     */
    public function events()
    {
        $bookings = Booking::all();

        $events = $bookings->map(function ($booking) {
            $color = '#f59e0b'; // Default: Kuning (Booking Sementara)
            if ($booking->status === 'Booking Pasti') {
                $color = '#10b981'; // Hijau (Booking Pasti)
            } elseif ($booking->status === 'Cancel') {
                $color = '#ef4444'; // Merah (Cancel)
            }

            return [
                'title' => $booking->client_name . ' - ' . $booking->event_type,
                'start' => $booking->event_date . 'T' . $booking->start_time,
                'end' => $booking->event_date . 'T' . $booking->end_time,
                'url' => route('sales.bookings.edit', $booking), // Link ke halaman edit booking
                'backgroundColor' => $color,
                'borderColor' => $color,
            ];
        });

        return response()->json($events);
    }
}