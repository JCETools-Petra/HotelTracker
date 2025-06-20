<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard untuk sales dengan data ringkasan.
     */
    public function index()
    {
        // Tentukan rentang waktu bulan ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // 1. Ambil data untuk kartu statistik
        $totalBookingsThisMonth = Booking::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        
        $confirmedBookingsThisMonth = Booking::where('status', 'Booking Pasti')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();
        
        $estimatedRevenueThisMonth = Booking::where('status', 'Booking Pasti')
            ->whereBetween('event_date', [$startOfMonth, $endOfMonth])
            ->sum('total_price');

        $totalPaxThisMonth = Booking::where('status', 'Booking Pasti')
            ->whereBetween('event_date', [$startOfMonth, $endOfMonth])
            ->sum('participants');

        // 2. Ambil data untuk jadwal event 7 hari ke depan
        $upcomingEvents = Booking::where('status', 'Booking Pasti')
            ->whereBetween('event_date', [Carbon::today(), Carbon::today()->addDays(7)])
            ->orderBy('event_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();
            
        // 3. Ambil data 5 booking terbaru
        $recentBookings = Booking::with('property')->latest()->take(5)->get();


        return view('sales.dashboard', compact(
            'totalBookingsThisMonth',
            'confirmedBookingsThisMonth',
            'estimatedRevenueThisMonth',
            'totalPaxThisMonth',
            'upcomingEvents',
            'recentBookings'
        ));
    }
}