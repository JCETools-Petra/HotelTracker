<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Relasi ke kategori MICE, diletakkan setelah 'property_id'
            $table->foreignId('mice_category_id')->nullable()->constrained('mice_categories')->after('property_id');

            // Status pembayaran untuk filter "sudah dibayar full"
            $table->string('payment_status')->default('Unpaid')->after('status'); // Pilihan: Unpaid, Down Payment, Paid
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['mice_category_id']);
            $table->dropColumn(['mice_category_id', 'payment_status']);
        });
    }
};