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
        
        // Kolom jumlah kamar
        'offline_rooms',
        'online_rooms',
        'ta_rooms',
        'gov_rooms',
        'corp_rooms',
        'compliment_rooms',
        'house_use_rooms',
        'afiliasi_rooms',

        // Kolom pendapatan
        'offline_room_income',
        'online_room_income',
        'ta_income',
        'gov_income',
        'corp_income',
        'compliment_income',
        'house_use_income',
        'afiliasi_room_income',
        'breakfast_income',
        'lunch_income',
        'dinner_income',
        'others_income',

        // ======================= AWAL PERBAIKAN =======================
        // Daftarkan semua kolom kalkulasi agar bisa disimpan ke database
        'total_rooms_sold',
        'total_rooms_revenue',
        'total_fb_revenue',
        'total_revenue',
        'arr',
        'occupancy',
        // ======================= AKHIR PERBAIKAN =======================
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
     * Accessor untuk menghitung total F&B secara otomatis.
     */
    public function getFbIncomeAttribute(): float
    {
        return $this->breakfast_income + $this->lunch_income + $this->dinner_income;
    }

    /**
     * Mendapatkan properti yang memiliki pendapatan ini.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
    
    /**
     * Mendapatkan pengguna yang mencatat pendapatan ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}