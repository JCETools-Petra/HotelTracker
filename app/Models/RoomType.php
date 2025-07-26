<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = ['property_id', 'name'];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function pricingRule()
    {
        // Setiap Tipe Kamar memiliki satu aturan harga
        return $this->hasOne(PricingRule::class);
    }
}