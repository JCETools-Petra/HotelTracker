<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Property extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Relasi: Satu Property dikelola oleh satu User (dalam skenario Anda)
    // Jika bisa banyak user, gunakan hasMany(User::class)
    public function manager(): HasOne // Atau BelongsTo jika foreign key ada di properties
    {
        return $this->hasOne(User::class);
    }

    // Relasi: Satu Property memiliki banyak DailyIncome
    public function dailyIncomes(): HasMany
    {
        return $this->hasMany(DailyIncome::class);
    }
}