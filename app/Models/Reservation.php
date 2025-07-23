// app/Models/Reservation.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'source',
        'final_price',
        'guest_name',
        'guest_email',
        'checkin_date',
        'checkout_date',
        'number_of_rooms',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}