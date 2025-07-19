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
            if (!Schema::hasColumn('daily_incomes', 'afiliasi_rooms')) {
                $table->integer('afiliasi_rooms')->default(0)->after('house_use_income');
            }
            if (!Schema::hasColumn('daily_incomes', 'afiliasi_room_income')) {
                $table->decimal('afiliasi_room_income', 15, 2)->default(0)->after('afiliasi_rooms');
            }
            if (!Schema::hasColumn('daily_incomes', 'afiliasi_income')) {
                $table->decimal('afiliasi_income', 15, 2)->default(0)->after('afiliasi_room_income');
            }
        });
    }

    public function down(): void
    {
        Schema::table('daily_incomes', function (Blueprint $table) {
            $table->dropColumn(['afiliasi_rooms', 'afiliasi_room_income', 'afiliasi_income']);
        });
    }

};
