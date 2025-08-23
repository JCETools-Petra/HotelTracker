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
        Schema::table('daily_occupancies', function (Blueprint $table) {
            // Kolom untuk kamar dari reservasi online (yang kita buat)
            $table->integer('reservasi_ota')->default(0)->after('occupied_rooms');
            // Kolom untuk kamar dari input manual pengguna properti
            $table->integer('reservasi_properti')->default(0)->after('reservasi_ota');
        });
    }

    public function down(): void
    {
        Schema::table('daily_occupancies', function (Blueprint $table) {
            $table->dropColumn(['reservasi_ota', 'reservasi_properti']);
        });
    }
};
