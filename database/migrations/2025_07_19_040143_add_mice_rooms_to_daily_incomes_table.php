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
            // Menambahkan kolom untuk kamar dan pendapatan dari MICE Room
            $table->integer('mice_rooms')->default(0)->after('house_use_income');
            $table->decimal('mice_room_income', 15, 2)->default(0)->after('mice_rooms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_incomes', function (Blueprint $table) {
            $table->dropColumn('mice_rooms');
            $table->dropColumn('mice_room_income');
        });
    }
};