<?php

// database/seeders/UserSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Property;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat Admin
        if (!User::where('email', 'admin@example.com')->first()) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'property_id' => null, // Admin tidak terikat pada satu properti spesifik
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);
        }

        // ================== PERUBAHAN DI SINI: Buat Owner ==================
        if (!User::where('email', 'owner@example.com')->first()) {
            User::create([
                'name' => 'Owner User',
                'email' => 'owner@example.com',
                'password' => Hash::make('password'),
                'role' => 'owner', // Peran baru 'owner'
                'property_id' => null, // Owner juga tidak terikat pada properti spesifik
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);
        }
        // ================== AKHIR PERUBAHAN ==================

        // Buat Pengguna Properti
        $properties = Property::all();

        if ($properties->isEmpty()) {
            $this->command->warn('Tidak ada properti di database. Harap jalankan PropertySeeder terlebih dahulu atau buat properti secara manual sebelum menjalankan UserSeeder.');
        }

        foreach ($properties as $property) {
            $userEmail = 'user' . strtolower(str_replace(' ', '', $property->name)) . '@example.com';
            $existingUser = User::where('email', $userEmail)->first();

            if (!$existingUser) {
                User::create([
                    'name' => 'User Properti ' . $property->name,
                    'email' => $userEmail,
                    'password' => Hash::make('password'),
                    'role' => 'pengguna_properti',
                    'property_id' => $property->id,
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]);
            } else {
                $this->command->info('Pengguna dengan email ' . $userEmail . ' sudah ada, tidak dibuat ulang oleh seeder.');
            }
        }
    }
}