<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DailyIncome;
use App\Models\Property;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DummyDailyIncomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Menghapus data lama untuk periode April-Agustus 2025...');
        DailyIncome::whereIn('property_id', range(1, 8)) // Menyesuaikan dengan data Anda
            ->where(function ($query) {
                $query->whereYear('date', 2025)
                      ->whereMonth('date', '>=', 4)
                      ->whereMonth('date', '<=', 8);
            })
            ->delete();
        $this->command->info('Data lama berhasil dihapus.');

        $propertyIds = Property::pluck('id')->toArray();
        $userIds = User::where('role', '!=', 'admin')->pluck('id', 'property_id')->toArray();

        if (empty($propertyIds)) {
            $this->command->error('Tidak ada properti yang ditemukan. Seeder dihentikan.');
            return;
        }

        $monthsToSeed = [4, 5, 6, 7, 8]; // April, Mei, Juni, Juli, Agustus
        $year = 2025;
        $dailyIncomes = [];

        foreach ($propertyIds as $propertyId) {
            $userId = $userIds[$propertyId] ?? User::where('role', 'admin')->first()->id;

            if (!$userId) {
                $this->command->warn("Tidak ada user yang bisa di-assign untuk Properti ID: {$propertyId}. Properti ini dilewati.");
                continue;
            }

            foreach ($monthsToSeed as $monthNumber) {
                $monthName = Carbon::create()->month($monthNumber)->format('F');
                $daysInMonth = Carbon::createFromDate($year, $monthNumber, 1)->daysInMonth;
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $currentDate = Carbon::createFromDate($year, $monthNumber, $day);

                    // ================== PERBAIKAN UTAMA DI SINI ==================
                    // Menghapus 'offline_rooms' dan 'online_rooms' dari array
                    // dan memastikan semua nama kolom lain sudah benar.
                    $dailyIncomes[] = [
                        'property_id' => $propertyId,
                        'user_id' => $userId,
                        'date' => $currentDate->toDateString(),
                        
                        'mice_income' => rand(500000, 2500000),
                        'fnb_income' => rand(700000, 3500000),
                        'others_income' => rand(0, 1000000),
                        'offline_room_income' => rand(1000000, 5000000),
                        'online_room_income' => rand(2000000, 6000000),

                        // Kategori Baru (kamar dan pendapatan)
                        'ta_rooms' => rand(2, 8),
                        'ta_income' => rand(500000, 2000000),
                        
                        'gov_rooms' => rand(1, 5),
                        'gov_income' => rand(300000, 1500000),

                        'corp_rooms' => rand(3, 10),
                        'corp_income' => rand(800000, 2500000),

                        'compliment_rooms' => rand(0, 2),
                        'compliment_income' => 0, 

                        'house_use_rooms' => rand(0, 3),
                        'house_use_income' => 0,

                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ];
                    // ================== AKHIR PERBAIKAN ==================
                }
                $this->command->info("Data untuk Properti ID: {$propertyId}, Bulan: {$monthName} {$year} disiapkan.");
            }
        }

        if (!empty($dailyIncomes)) {
            // Kita perlu memastikan semua array memiliki keys yang sama.
            // Langkah ini untuk mengatasi jika ada data yang hilang di tengah iterasi.
            $sampleKeys = array_keys($dailyIncomes[0]);
            $uniformChunks = [];
            foreach (array_chunk($dailyIncomes, 200) as $chunk) {
                $uniformChunk = [];
                foreach ($chunk as $item) {
                    $uniformItem = [];
                    foreach ($sampleKeys as $key) {
                        $uniformItem[$key] = $item[$key] ?? null; // Default to null if a key is missing
                    }
                    $uniformChunk[] = $uniformItem;
                }
                 DailyIncome::insert($uniformChunk);
            }
            $this->command->info(count($dailyIncomes) . ' data pendapatan harian berhasil dibuat.');
        } else {
            $this->command->info('Tidak ada data dummy baru yang dibuat.');
        }
    }
}