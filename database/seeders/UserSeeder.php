<?php

// database/seeders/UserSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Property;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Ganti dengan password yang aman untuk produksi
            'role' => 'admin',
            'property_id' => null, // Admin tidak terikat pada satu properti spesifik
            'email_verified_at' => now(), // Opsional: langsung set terverifikasi
        ]);

        // Buat Pengguna Properti
        $properties = Property::all(); // Pastikan PropertySeeder sudah dijalankan sebelumnya

        if ($properties->isEmpty()) {
            $this->command->warn('Tidak ada properti di database. Harap jalankan PropertySeeder terlebih dahulu atau buat properti secara manual sebelum menjalankan UserSeeder.');
            // Anda bisa membuat properti default di sini jika diperlukan untuk seeder
            // Contoh:
            // Property::create(['name' => 'Properti Contoh 1']);
            // Property::create(['name' => 'Properti Contoh 2']);
            // $properties = Property::all(); // Muat ulang properti
        }

        $propertyUserCounter = 1;
        foreach ($properties as $property) {
            // Untuk menghindari error duplikasi email jika seeder dijalankan berkali-kali tanpa migrate:fresh
            $userEmail = 'user' . strtolower(str_replace(' ', '', $property->name)) . '@example.com';
            $existingUser = User::where('email', $userEmail)->first();

            if (!$existingUser) {
                User::create([
                    'name' => 'User Properti ' . $property->name,
                    'email' => $userEmail,
                    'password' => Hash::make('password'), // Ganti dengan password yang aman untuk produksi
                    'role' => 'pengguna_properti', // KONSISTEN: Menggunakan 'pengguna_properti'
                    'property_id' => $property->id,
                    'email_verified_at' => now(), // Opsional: langsung set terverifikasi
                ]);
            } else {
                $this->command->info('Pengguna dengan email ' . $userEmail . ' sudah ada, tidak dibuat ulang oleh seeder.');
            }
            $propertyUserCounter++; // Ini mungkin tidak lagi relevan jika email dibuat berdasarkan nama properti
        }

        // Contoh pengguna properti tambahan jika diperlukan
        // Pastikan email unik dan property_id valid
        // if (!User::where('email', 'testuser@example.com')->first() && $properties->first()) {
        //     User::create([
        //         'name' => 'Test User Property',
        //         'email' => 'testuser@example.com',
        //         'password' => Hash::make('password'),
        //         'role' => 'pengguna_properti',
        //         'property_id' => $properties->first()->id, // Contoh mengambil ID properti pertama
        //         'email_verified_at' => now(),
        //     ]);
        // }
    }
}