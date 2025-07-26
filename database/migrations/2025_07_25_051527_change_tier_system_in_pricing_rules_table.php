<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pricing_rules', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn('tier_limit');

            // Tambahkan 5 kolom baru untuk sistem Bar setelah kolom bottom_rate
            $table->integer('bar_1')->default(0)->after('bottom_rate');
            $table->integer('bar_2')->default(0)->after('bar_1');
            $table->integer('bar_3')->default(0)->after('bar_2');
            $table->integer('bar_4')->default(0)->after('bar_3');
            $table->integer('bar_5')->default(0)->after('bar_4');
        });
    }

    public function down(): void
    {
        Schema::table('pricing_rules', function (Blueprint $table) {
            // Logika untuk mengembalikan perubahan jika diperlukan (rollback)
            $table->integer('tier_limit')->default(1);

            $table->dropColumn(['bar_1', 'bar_2', 'bar_3', 'bar_4', 'bar_5']);
        });
    }
};