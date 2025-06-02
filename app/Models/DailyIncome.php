<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyIncome extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'user_id',
        'date',
        'mice_income',
        'fnb_income',
        'offline_room_income',
        'online_room_income',
        'others_income',
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