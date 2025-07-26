<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pricing_rules', function (Blueprint $table) {
            // Hapus kolom walkin yang tidak terpakai
            $table->dropColumn(['bottom_rate_walkin', 'publish_rate_walkin']);
        });
    }

    public function down(): void
    {
        Schema::table('pricing_rules', function (Blueprint $table) {
            // Logika untuk mengembalikan perubahan jika diperlukan
            $table->decimal('bottom_rate_walkin', 10, 2)->default(0);
            $table->decimal('publish_rate_walkin', 10, 2)->default(0);
        });
    }
};