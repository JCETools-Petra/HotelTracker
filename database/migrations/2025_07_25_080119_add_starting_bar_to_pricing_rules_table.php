<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pricing_rules', function (Blueprint $table) {
            // Tambahkan kolom baru setelah 'percentage_increase'
            // Defaultnya adalah 1, artinya kenaikan harga dimulai dari Bar 1
            $table->tinyInteger('starting_bar')->default(1)->after('percentage_increase');
        });
    }

    public function down(): void
    {
        Schema::table('pricing_rules', function (Blueprint $table) {
            $table->dropColumn('starting_bar');
        });
    }
};