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
            // Perintah ini akan mengubah kolom 'id' yang ada
            // menjadi big integer, unsigned, auto-increment, dan primary key.
            $table->id()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_incomes', function (Blueprint $table) {
            // Logika untuk mengembalikan jika perlu (opsional)
            $table->bigInteger('id')->change();
        });
    }
};