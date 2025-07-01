<?php

namespace App\Exports;

use App\Models\Property;
use App\Models\DailyIncome;
use App\Models\Booking;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class AdminPropertiesSummaryExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // 1. Ambil filter dari request
        $propertyId = $this->request->input('property_id');
        $period = $this->request->input('period', 'month');

        // 2. Tentukan rentang tanggal
        $startDate = null;
        $endDate = null;
        switch ($period) {
            case 'today':
                $startDate = Carbon::today()->startOfDay();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'month':
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
        }

        // 3. Ambil daftar properti yang akan diekspor
        $properties = Property::when($propertyId, function ($query, $propertyId) {
            return $query->where('id', $propertyId);
        })->get();

        // 4. Proses setiap properti untuk menghitung total pendapatannya
        $properties->transform(function ($property) use ($startDate, $endDate) {
            
            // Ambil semua pendapatan harian untuk properti ini dalam rentang waktu
            $incomes = DailyIncome::where('property_id', $property->id)
                                  ->whereBetween('date', [$startDate, $endDate])
                                  ->get();
            
            // Ambil semua pendapatan MICE dari booking untuk properti ini
            $miceRevenue = Booking::where('property_id', $property->id)
                                  ->where('status', 'Booking Pasti')
                                  ->whereBetween('event_date', [$startDate, $endDate])
                                  ->sum('total_price');

            // Hitung setiap kategori pendapatan
            $property->total_income_records = $incomes->count();
            $property->total_mice_revenue = $miceRevenue;
            $property->total_fnb_revenue = $incomes->sum('breakfast_income') + $incomes->sum('lunch_income') + $incomes->sum('dinner_income');
            $property->total_offline_revenue = $incomes->sum('offline_room_income');
            $property->total_online_revenue = $incomes->sum('online_room_income');
            $property->total_other_revenue = $incomes->sum('ta_income') + $incomes->sum('gov_income') + $incomes->sum('corp_income') + $incomes->sum('compliment_income') + $incomes->sum('house_use_income') + $incomes->sum('others_income');
            
            // Hitung Grand Total
            $property->grand_total = $property->total_mice_revenue + $property->total_fnb_revenue + $property->total_offline_revenue + $property->total_online_revenue + $property->total_other_revenue;

            return $property;
        });

        return $properties;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nama Properti',
            'Total Catatan Pendapatan',
            'Total Pendapatan MICE (Rp)',
            'Total Pendapatan F&B (Rp)',
            'Total Pendapatan Kamar Offline (Rp)',
            'Total Pendapatan Kamar Online (Rp)',
            'Total Pendapatan Lainnya (Rp)',
            'Grand Total Pendapatan (Rp)',
        ];
    }

    /**
     * @param mixed $property
     * @return array
     */
    public function map($property): array
    {
        return [
            $property->name,
            $property->total_income_records,
            $property->total_mice_revenue,
            $property->total_fnb_revenue,
            $property->total_offline_revenue,
            $property->total_online_revenue,
            $property->total_other_revenue,
            $property->grand_total,
        ];
    }
}
