<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MiceCategory extends Model
{
    // Di dalam class MiceCategory
    public function functionSheets()
    {
        return $this->hasMany(FunctionSheet::class);
    }
    public function bookings()
{
    return $this->hasMany(\App\Models\Booking::class);
}
}
