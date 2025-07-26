<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pricing_rules', function (Blueprint $table) {
            // Tambahkan kolom baru setelah bottom_rate dan publish_rate yang sudah ada
            $table->decimal('bottom_rate_walkin', 10, 2)->default(0)->after('bottom_rate');
            $table->decimal('publish_rate_walkin', 10, 2)->default(0)->after('publish_rate');
        });
    }

    public function down(): void
    {
        Schema::table('pricing_rules', function (Blueprint $table) {
            $table->dropColumn(['bottom_rate_walkin', 'publish_rate_walkin']);
        });
    }
};