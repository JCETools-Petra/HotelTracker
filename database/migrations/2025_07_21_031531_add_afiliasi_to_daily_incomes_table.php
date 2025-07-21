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
            // Menambahkan kolom afiliasi setelah kolom house_use_income
            $table->integer('afiliasi_rooms')->default(0)->after('house_use_income');
            $table->decimal('afiliasi_room_income', 15, 2)->default(0)->after('afiliasi_rooms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_incomes', function (Blueprint $table) {
            // Menghapus kolom jika migrasi di-rollback
            $table->dropColumn(['afiliasi_rooms', 'afiliasi_room_income']);
        });
    }
};