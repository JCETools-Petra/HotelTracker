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
        'user_id', // Ditambahkan sesuai logika controller
        'date',
        
        // Kolom jumlah kamar
        'offline_rooms',
        'online_rooms',
        'ta_rooms',
        'gov_rooms',
        'corp_rooms',
        'compliment_rooms',
        'house_use_rooms',

        // Kolom pendapatan
        'offline_room_income',
        'online_room_income',
        'ta_income',
        'gov_income',
        'corp_income',
        'compliment_income',
        'house_use_income',
        'mice_income',
        'fnb_income',
        'others_income',
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