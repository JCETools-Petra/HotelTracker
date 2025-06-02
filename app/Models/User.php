<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail; // Jika Anda menggunakan verifikasi email
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; // <-- 1. IMPORT SoftDeletes TRAIT
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Untuk relasi property

class User extends Authenticatable implements MustVerifyEmail // Implement MustVerifyEmail jika Anda menggunakannya
{
    use HasFactory, Notifiable, SoftDeletes; // <-- 2. GUNAKAN SoftDeletes TRAIT DI SINI

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',         // Pastikan 'role' ada di sini jika Anda mengisinya via create()
        'property_id',  // Pastikan 'property_id' ada di sini
        'email_verified_at', // Jika Anda mengatur ini saat create user
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'deleted_at' => 'datetime', // Opsional, tapi baik untuk konsistensi casting
    ];

    /**
     * Mendapatkan properti yang terkait dengan pengguna (jika pengguna adalah tipe 'pengguna_properti').
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}