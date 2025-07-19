<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('function_sheets', function (Blueprint $table) {
            $table->dropColumn('price_per_pax');
            $table->foreignId('price_package_id')->nullable()->after('room_setup')->constrained('price_packages');
        });
    }

    public function down(): void
    {
        Schema::table('function_sheets', function (Blueprint $table) {
            $table->dropForeign(['price_package_id']);
            $table->dropColumn('price_package_id');
            $table->decimal('price_per_pax', 15, 2)->default(0);
        });
    }
};