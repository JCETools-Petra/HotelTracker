<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('daily_incomes', function (Blueprint $table) {
            // ======================= AWAL BLOK YANG DIUBAH =======================
            
            // Tambahkan 3 kolom baru tanpa ->after()
            // Kolom akan ditambahkan di akhir tabel, yang secara fungsional tidak masalah.
            $table->decimal('breakfast_income', 15, 2)->default(0);
            $table->decimal('lunch_income', 15, 2)->default(0);
            $table->decimal('dinner_income', 15, 2)->default(0);
            
            // Hapus kolom fb_income yang lama (jika ada)
            if (Schema::hasColumn('daily_incomes', 'fb_income')) {
                $table->dropColumn('fb_income');
            }

            // ======================= AKHIR BLOK YANG DIUBAH ======================
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_incomes', function (Blueprint $table) {
            // Logika untuk mengembalikan jika diperlukan
            $table->decimal('fb_income', 15, 2)->default(0)->after('room_income');
            $table->dropColumn(['breakfast_income', 'lunch_income', 'dinner_income']);
        });
    }
};