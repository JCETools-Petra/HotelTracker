<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'property_id',
        'description',
        'amount',
        'date',
    ];

    // Relasi ke model Property
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}