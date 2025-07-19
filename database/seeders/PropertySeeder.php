<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property; // Pastikan model Property di-import

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $properties = [
            ['name' => 'Hotel Properti A'],
            ['name' => 'Hotel Properti B'],
            ['name' => 'Hotel Properti C'],
            ['name' => 'Hotel Properti D'],
            ['name' => 'Hotel Properti E'],
            ['name' => 'Hotel Properti F'],
        ];

        // Looping untuk membuat setiap properti
        foreach ($properties as $propertyData) {
            Property::create($propertyData);
        }

        // Konfirmasi bahwa properti telah dibuat (opsional)
        $this->command->info(count($properties) . ' properti berhasil dibuat oleh PropertySeeder.');
    }
}