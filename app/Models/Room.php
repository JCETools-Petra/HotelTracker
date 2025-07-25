<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['property_id', 'name', 'capacity', 'notes'];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}