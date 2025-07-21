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
            // Menambahkan kolom-kolom yang hilang
            if (!Schema::hasColumn('daily_incomes', 'offline_rooms')) {
                $table->integer('offline_rooms')->default(0)->after('date');
            }
            if (!Schema::hasColumn('daily_incomes', 'offline_room_income')) {
                $table->decimal('offline_room_income', 15, 2)->default(0)->after('offline_rooms');
            }
            if (!Schema::hasColumn('daily_incomes', 'online_rooms')) {
                $table->integer('online_rooms')->default(0)->after('offline_room_income');
            }
            if (!Schema::hasColumn('daily_incomes', 'online_room_income')) {
                $table->decimal('online_room_income', 15, 2)->default(0)->after('online_rooms');
            }

            // Menambahkan kembali kolom-kolom kalkulasi yang mungkin hilang
            if (!Schema::hasColumn('daily_incomes', 'total_rooms_sold')) {
                $table->integer('total_rooms_sold')->default(0)->after('others_income');
            }
            if (!Schema::hasColumn('daily_incomes', 'total_rooms_revenue')) {
                $table->decimal('total_rooms_revenue', 15, 2)->default(0)->after('total_rooms_sold');
            }
            if (!Schema::hasColumn('daily_incomes', 'total_fb_revenue')) {
                $table->decimal('total_fb_revenue', 15, 2)->default(0)->after('total_rooms_revenue');
            }
             if (!Schema::hasColumn('daily_incomes', 'total_revenue')) {
                $table->decimal('total_revenue', 15, 2)->default(0)->after('total_fb_revenue');
            }
            if (!Schema::hasColumn('daily_incomes', 'arr')) {
                $table->decimal('arr', 15, 2)->default(0)->after('total_revenue');
            }
            if (!Schema::hasColumn('daily_incomes', 'occupancy')) {
                $table->decimal('occupancy', 8, 2)->default(0)->after('arr');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_incomes', function (Blueprint $table) {
            $table->dropColumn([
                'offline_rooms',
                'offline_room_income',
                'online_rooms',
                'online_room_income',
                'total_rooms_sold',
                'total_rooms_revenue',
                'total_fb_revenue',
                'total_revenue',
                'arr',
                'occupancy',
            ]);
        });
    }
};