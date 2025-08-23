<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Relations\HasOne; // <-- Pastikan ini di-import
>>>>>>> origin/master

class RoomType extends Model
{
    use HasFactory;

<<<<<<< HEAD
    protected $fillable = ['property_id', 'name'];

=======
    protected $fillable = [
        'property_id',
        'name',
        'bottom_rate',
    ];

    /**
     * Definisi relasi ke Property.
     */
>>>>>>> origin/master
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

<<<<<<< HEAD
    public function pricingRule()
    {
        // Setiap Tipe Kamar memiliki satu aturan harga
=======
    /**
     * TAMBAHKAN ATAU GANTI RELASI INI
     *
     * Satu Tipe Kamar memiliki satu Aturan Harga.
     */
    public function pricingRule(): HasOne
    {
>>>>>>> origin/master
        return $this->hasOne(PricingRule::class);
    }
}