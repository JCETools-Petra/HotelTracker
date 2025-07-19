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
        // Langkah 1: Kembalikan nama kolom 'afiliasi' menjadi 'mice' jika sudah terlanjur diubah
        if (Schema::hasColumn('daily_incomes', 'afiliasi_rooms')) {
            Schema::table('daily_incomes', function (Blueprint $table) {
                $table->renameColumn('afiliasi_rooms', 'mice_rooms');
                $table->renameColumn('afiliasi_room_income', 'mice_room_income');
                $table->renameColumn('afiliasi_income', 'mice_income');
            });
        }

        // Langkah 2: Tambahkan kolom baru untuk 'afiliasi'
        Schema::table('daily_incomes', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_incomes', 'afiliasi_rooms')) {
                $table->integer('afiliasi_rooms')->default(0)->after('mice_income');
            }
            if (!Schema::hasColumn('daily_incomes', 'afiliasi_room_income')) {
                $table->decimal('afiliasi_room_income', 15, 2)->default(0)->after('afiliasi_rooms');
            }
            if (!Schema::hasColumn('daily_incomes', 'afiliasi_income')) {
                $table->decimal('afiliasi_income', 15, 2)->default(0)->after('afiliasi_room_income');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_incomes', function (Blueprint $table) {
            // Hapus kolom afiliasi
            if (Schema::hasColumn('daily_incomes', 'afiliasi_rooms')) {
                $table->dropColumn('afiliasi_rooms');
            }
            if (Schema::hasColumn('daily_incomes', 'afiliasi_room_income')) {
                $table->dropColumn('afiliasi_room_income');
            }
            if (Schema::hasColumn('daily_incomes', 'afiliasi_income')) {
                $table->dropColumn('afiliasi_income');
            }
        });
        
        // Kembalikan nama kolom 'mice' menjadi 'afiliasi' (kebalikan dari proses up)
        if (Schema::hasColumn('daily_incomes', 'mice_rooms')) {
             Schema::table('daily_incomes', function (Blueprint $table) {
                $table->renameColumn('mice_rooms', 'afiliasi_rooms');
                $table->renameColumn('mice_room_income', 'afiliasi_room_income');
                $table->renameColumn('mice_income', 'afiliasi_income');
            });
        }
    }
};
