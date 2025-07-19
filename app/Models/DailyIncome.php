<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyIncome extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'property_id',
        'user_id',
        'date',
        
        // Room counts
        'offline_rooms',
        'online_rooms',
        'ta_rooms',
        'gov_rooms',
        'corp_rooms',
        'compliment_rooms',
        'house_use_rooms',
        'mice_rooms', // Kolom baru

        // Income sources
        'offline_room_income',
        'online_room_income',
        'ta_income',
        'gov_income',
        'corp_income',
        'compliment_income',
        'house_use_income',
        'mice_room_income', // Kolom baru
        'mice_income',
        'breakfast_income',
        'lunch_income',
        'dinner_income',
        'others_income',
        
        // Calculated fields
        'total_rooms_sold',
        'total_rooms_revenue',
        'total_fb_revenue',
        'total_revenue',
        'arr',
        'occupancy',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the total F&B income.
     */
    public function getFbIncomeAttribute(): float
    {
        return ($this->breakfast_income ?? 0) + ($this->lunch_income ?? 0) + ($this->dinner_income ?? 0);
    }

    /**
     * Get the property that owns the daily income.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
    
    /**
     * Get the user who created the daily income.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
