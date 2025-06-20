<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'booking_date',
        'client_name',
        'event_type',
        'event_date',
        'start_time',
        'end_time',
        'participants',
        'property_id',
        'person_in_charge',
        'status',
        'total_price',      // <-- PASTIKAN INI ADA
        'down_payment',     // <-- PASTIKAN INI ADA
        'notes',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function functionSheet()
    {
        return $this->hasOne(FunctionSheet::class);
    }
}