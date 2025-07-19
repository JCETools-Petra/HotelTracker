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
    // ======================= AWAL BLOK YANG DIUBAH =======================
    protected $fillable = [
        'property_id',
        'user_id',
        'date',
        
        // Kolom jumlah kamar
        'offline_rooms',
        'online_rooms',
        'mice_rooms',
        'ta_rooms',
        'gov_rooms',
        'corp_rooms',
        'compliment_rooms',
        'house_use_rooms',

        // Kolom pendapatan
        'offline_room_income',
        'online_room_income',
        'mice_room_income',
        'ta_income',
        'gov_income',
        'corp_income',
        'compliment_income',
        'house_use_income',
        'mice_income',
        // 'fnb_income' dihapus dan diganti dengan 3 di bawah ini
        'breakfast_income',
        'lunch_income',
        'dinner_income',
        'others_income',
    ];
    // ======================= AKHIR BLOK YANG DIUBAH ======================

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
    ];

    // ======================= BLOK BARU DITAMBAHKAN =======================
    /**
     * Accessor untuk menghitung total F&B secara otomatis.
     * Ini memastikan kode lama yang mungkin masih memanggil ->fnb_income tetap berfungsi.
     */
    public function getFbIncomeAttribute(): float
    {
        return $this->breakfast_income + $this->lunch_income + $this->dinner_income;
    }
    // ====================================================================

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