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
        Schema::table('function_sheets', function (Blueprint $table) {
            // Hapus kolom billing_details yang tidak lagi digunakan
            $table->dropColumn('billing_details');

            // Tambahkan kolom untuk harga per pax setelah room_setup
            $table->decimal('price_per_pax', 15, 2)->default(0)->after('room_setup');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('function_sheets', function (Blueprint $table) {
            // Jika ingin rollback, kembalikan kolom billing_details dan hapus price_per_pax
            $table->dropColumn('price_per_pax');
            $table->json('billing_details')->nullable();
        });
    }
};