<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'user_id', // Ditambahkan
        'date',
        'offline_rooms',
        'offline_room_income', // Diperbaiki dari offline_income
        'online_rooms',
        'online_room_income',  // Diperbaiki dari online_income
        'ta_rooms',
        'ta_income',
        'gov_rooms',
        'gov_income',
        'corp_rooms',
        'corp_income',
        'compliment_rooms',
        'compliment_income',
        'house_use_rooms',
        'house_use_income',
        'mice_income',    // Ditambahkan
        'fnb_income',     // Ditambahkan
        'others_income',  // Ditambahkan
    ];


    protected $casts = [
        'date' => 'date', // Pastikan kolom date di-cast sebagai objek Carbon/Date
    ];

    // Relasi: DailyIncome dimiliki oleh satu Property
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    // Relasi: DailyIncome dicatat oleh satu User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}