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
            $table->renameColumn('mice_rooms', 'afiliasi_rooms');
            $table->renameColumn('mice_room_income', 'afiliasi_room_income');
            $table->renameColumn('mice_income', 'afiliasi_income');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_incomes', function (Blueprint $table) {
            $table->renameColumn('afiliasi_rooms', 'mice_rooms');
            $table->renameColumn('afiliasi_room_income', 'mice_room_income');
            $table->renameColumn('afiliasi_income', 'mice_income');
        });
    }
};
