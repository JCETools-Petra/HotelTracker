<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DailyIncome; // Pastikan model DailyIncome Anda ada di App\Models
use App\Models\Property;    // Untuk mendapatkan ID properti yang ada atau membuat baru jika perlu
use App\Models\User;       // Untuk mendapatkan ID pengguna yang ada
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
        // Hapus data lama untuk periode dan properti ini agar tidak duplikat jika seeder dijalankan ulang
        // Ini opsional, sesuaikan jika Anda tidak ingin menghapus data yang sudah ada.
        $this->command->info('Menghapus data lama untuk periode April-Juli 2025, properti ID 1-6...');
        DailyIncome::whereIn('property_id', range(1, 6))
            ->where(function ($query) {
                $query->whereYear('date', 2025)
                      ->whereMonth('date', '>=', 4) // Mulai dari April
                      ->whereMonth('date', '<=', 7); // Sampai Juli
            })
            ->delete();
        $this->command->info('Data lama berhasil dihapus.');

        $propertyIds = range(1, 6); // Anda memiliki 6 properti

        // Asumsi user_id untuk data entry. Sesuaikan dengan user_id yang ada di database Anda.
        // Misalnya, property_id 1 diinput oleh user_id 2, property_id 2 oleh user_id 3, dst.
        // Pastikan user dengan ID ini ada di tabel 'users'.
        $userIds = [
            1 => 2, // Property 1 -> User 2
            2 => 3, // Property 2 -> User 3
            3 => 4, // Property 3 -> User 4
            4 => 5, // Property 4 -> User 5 (Asumsi user ID 5 ada)
            5 => 6, // Property 5 -> User 6 (Asumsi user ID 6 ada)
            6 => 7, // Property 6 -> User 7 (Asumsi user ID 7 ada)
        ];

        // Opsional: Validasi apakah properti dan user ada
        $existingPropertyIds = Property::whereIn('id', $propertyIds)->pluck('id')->toArray();
        $missingPropertyIds = array_diff($propertyIds, $existingPropertyIds);
        if (!empty($missingPropertyIds)) {
            $this->command->warn('Properti dengan ID berikut tidak ditemukan: ' . implode(', ', $missingPropertyIds) . '. Data tidak akan dibuat untuk properti ini.');
            // Filter $propertyIds agar hanya berisi yang ada
            $propertyIds = $existingPropertyIds;
        }

        foreach ($userIds as $propId => $usrId) {
            if (!User::find($usrId)) {
                $this->command->warn("User dengan ID: {$usrId} (untuk Properti ID: {$propId}) tidak ditemukan. Pastikan user ini ada atau perbarui array \$userIds.");
                // Hapus properti dari daftar jika user terkait tidak ada
                if (($key = array_search($propId, $propertyIds)) !== false) {
                    unset($propertyIds[$key]);
                }
            }
        }


        $monthsToSeed = [
            4 => 'April', // April
            5 => 'Mei',   // Mei
            6 => 'Juni',  // Juni
            7 => 'Juli'   // Juli
        ];
        $year = 2025;
        $dailyIncomes = [];

        if (empty($propertyIds)) {
            $this->command->error('Tidak ada properti valid yang tersisa untuk di-seed setelah pengecekan. Seeder dihentikan.');
            return;
        }

        foreach ($propertyIds as $propertyId) {
            // Pastikan user_id untuk properti ini ada setelah filter
            if (!isset($userIds[$propertyId])) {
                // Seharusnya tidak terjadi jika $propertyIds sudah difilter berdasarkan user yang ada
                $this->command->warn("Tidak ada user_id yang valid untuk property_id: {$propertyId}. Lewati properti ini.");
                continue;
            }
            $userId = $userIds[$propertyId];

            foreach ($monthsToSeed as $monthNumber => $monthName) {
                $daysInMonth = Carbon::createFromDate($year, $monthNumber, 1)->daysInMonth;
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $currentDate = Carbon::createFromDate($year, $monthNumber, $day);

                    $dailyIncomes[] = [
                        'property_id' => $propertyId,
                        'user_id' => $userId,
                        'date' => $currentDate->toDateString(),
                        'mice_income' => rand(500000, 3000000),
                        'fnb_income' => rand(700000, 4000000),
                        'offline_room_income' => rand(1000000, 5000000),
                        'online_room_income' => rand(800000, 4500000),
                        'others_income' => rand(0, 1000000),
                        'created_at' => $currentDate, // Menggunakan $currentDate agar konsisten
                        'updated_at' => $currentDate, // Menggunakan $currentDate agar konsisten
                    ];
                }
                $this->command->info("Data untuk Properti ID: {$propertyId}, Bulan: {$monthName} {$year} disiapkan.");
            }
        }

        // Insert data dalam batch untuk efisiensi
        if (!empty($dailyIncomes)) {
            foreach (array_chunk($dailyIncomes, 200) as $chunk) { // Chunk per 200 entri
                DailyIncome::insert($chunk);
            }
            $this->command->info(count($dailyIncomes) . ' dummy daily income records created successfully.');
        } else {
            $this->command->info('Tidak ada data dummy baru yang dibuat.');
        }
    }
}
