// app/Services/ReservationPriceService.php
<?php

namespace App\Services;

use App\Models\Property;
use App\Models\Reservation;
use Carbon\Carbon;

class ReservationPriceService
{
    public function getCurrentRoomPrice(int $propertyId, string $checkinDate): float
    {
        $property = Property::with('pricingRule')->find($propertyId);
        $rule = $property?->pricingRule;

        if (!$rule) {
            return 0.0;
        }

        $roomsSoldCount = Reservation::where('property_id', $propertyId)
            ->whereDate('checkin_date', Carbon::parse($checkinDate))
            ->count();

        if ($rule->tier_limit <= 0) {
            return (float) $rule->bottom_rate;
        }

        $priceIncreaseTiers = floor($roomsSoldCount / $rule->tier_limit);

        $currentPrice = (float) $rule->bottom_rate;
        $percentage = (float) $rule->percentage_increase / 100;
        $increaseAmount = (float) $rule->bottom_rate * $percentage;

        $currentPrice += ($priceIncreaseTiers * $increaseAmount);

        return $currentPrice;
    }
}