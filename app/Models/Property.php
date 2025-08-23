<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
<<<<<<< HEAD
    'name',
    'chart_color',
    'address',
    'bar_1', // <-- TAMBAHKAN
    'bar_2', // <-- TAMBAHKAN
    'bar_3', // <-- TAMBAHKAN
    'bar_4', // <-- TAMBAHKAN
    'bar_5', // <-- TAMBAHKAN
];

    // =======================================================
    // !! RELASI YANG HILANG ADA DI SINI !!
    // =======================================================
    /**
     * Setiap properti memiliki satu aturan harga.
     */
    public function pricingRule(): HasOne
    {
        return $this->hasOne(PricingRule::class);
    }
    // =======================================================


    // == RELASI LAMA ANDA (TETAP DIPERTAHANKAN) ==
    public function manager(): HasOne
=======
        'name',
        'chart_color'
    ];

    // Relasi: Satu Property dikelola oleh satu User (dalam skenario Anda)
    // Jika bisa banyak user, gunakan hasMany(User::class)
    public function manager(): HasOne // Atau BelongsTo jika foreign key ada di properties
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
    {
        return $this->hasOne(User::class);
    }

<<<<<<< HEAD
=======
    // Relasi: Satu Property memiliki banyak DailyIncome
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
    public function dailyIncomes(): HasMany
    {
        return $this->hasMany(DailyIncome::class);
    }
<<<<<<< HEAD

    public function dailyOccupancies(): HasMany
    {
        return $this->hasMany(DailyOccupancy::class);
    }
=======
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
<<<<<<< HEAD

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }
    public function roomTypes(): HasMany
    {
        // Satu Properti bisa punya banyak Tipe Kamar
        return $this->hasMany(RoomType::class);
    }
=======
    public function incomes()
{
    return $this->hasMany(Income::class);
}
>>>>>>> 53544687d3a99f485bc9b6a4bf95626ea03e58e9
}