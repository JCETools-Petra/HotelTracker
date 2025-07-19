<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RevenueTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'month_year',
        'target_amount',
    ];

    protected $casts = [
        'month_year' => 'date',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Menghitung target pendapatan harian berdasarkan bulan yang diberikan.
     *
     * @param int $propertyId
     * @param Carbon $date
     * @return float
     */
    public static function getTargetForDate(int $propertyId, Carbon $date): float
    {
        $targetMonth = $date->copy()->startOfMonth();
        $revenueTarget = self::where('property_id', $propertyId)
                             ->where('month_year', $targetMonth->format('Y-m-d'))
                             ->first();

        if (!$revenueTarget) {
            return 0;
        }

        $monthlyTarget = $revenueTarget->target_amount;
        $daysInMonth = $date->daysInMonth;

        return $daysInMonth > 0 ? $monthlyTarget / $daysInMonth : 0;
    }
}
