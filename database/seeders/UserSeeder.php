<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
        
        // Property User
        User::create([
            'name' => 'Property',
            'email' => 'property@property.com',
            'password' => Hash::make('password'),
            'role' => 'property'
        ]);

        // Sales User (BARU)
        User::create([
            'name' => 'Sales Person',
            'email' => 'sales@hotel.com',
            'password' => Hash::make('password'),
            'role' => 'sales'
        ]);
    }
}