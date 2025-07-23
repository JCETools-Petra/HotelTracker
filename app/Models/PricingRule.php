<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PricingRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'publish_rate',
        'bottom_rate',
        'tier_limit',
        'percentage_increase'
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}