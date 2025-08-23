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
    {
        return $this->hasOne(User::class);
    }

    public function dailyIncomes(): HasMany
    {
        return $this->hasMany(DailyIncome::class);
    }

    public function dailyOccupancies(): HasMany
    {
        return $this->hasMany(DailyOccupancy::class);
    }
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }
    public function roomTypes(): HasMany
    {
        // Satu Properti bisa punya banyak Tipe Kamar
        return $this->hasMany(RoomType::class);
    }
}