<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pindahkan kolom Bar ke tabel properties
        Schema::table('properties', function (Blueprint $table) {
            $table->integer('bar_1')->default(0)->after('chart_color');
            $table->integer('bar_2')->default(0)->after('bar_1');
            $table->integer('bar_3')->default(0)->after('bar_2');
            $table->integer('bar_4')->default(0)->after('bar_3');
            $table->integer('bar_5')->default(0)->after('bar_4');
        });

        // Hapus kolom Bar dari tabel pricing_rules
        Schema::table('pricing_rules', function (Blueprint $table) {
            $table->dropColumn(['bar_1', 'bar_2', 'bar_3', 'bar_4', 'bar_5']);
        });
    }

    public function down(): void
    {
        // Logika untuk mengembalikan perubahan (rollback)
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['bar_1', 'bar_2', 'bar_3', 'bar_4', 'bar_5']);
        });

        Schema::table('pricing_rules', function (Blueprint $table) {
            $table->integer('bar_1')->default(0);
            $table->integer('bar_2')->default(0);
            $table->integer('bar_3')->default(0);
            $table->integer('bar_4')->default(0);
            $table->integer('bar_5')->default(0);
        });
    }
};