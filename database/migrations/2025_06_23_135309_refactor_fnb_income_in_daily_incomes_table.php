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
            // Tambahkan 3 kolom baru setelah kolom 'mice_income'
            $table->decimal('breakfast_income', 15, 2)->default(0)->after('mice_income');
            $table->decimal('lunch_income', 15, 2)->default(0)->after('breakfast_income');
            $table->decimal('dinner_income', 15, 2)->default(0)->after('lunch_income');

            // Hapus kolom fnb_income yang lama
            if (Schema::hasColumn('daily_incomes', 'fnb_income')) {
                $table->dropColumn('fnb_income');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_incomes', function (Blueprint $table) {
            // Logika untuk mengembalikan jika diperlukan (rollback)
            $table->decimal('fnb_income', 15, 2)->default(0)->after('mice_income');
            $table->dropColumn(['breakfast_income', 'lunch_income', 'dinner_income']);
        });
    }
};