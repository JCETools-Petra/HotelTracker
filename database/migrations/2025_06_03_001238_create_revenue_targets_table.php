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
        Schema::create('revenue_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->date('month_year'); // Menyimpan tanggal, misalnya '2025-05-01' untuk Mei 2025
            $table->decimal('target_amount', 15, 2); // Menyimpan target sebagai desimal, 15 digit total, 2 di belakang koma
            // Anda bisa menggunakan ->unsignedBigInteger('target_amount'); jika ingin integer besar tanpa desimal
            $table->timestamps();

            // Menambahkan unique constraint untuk property_id dan month_year
            // agar tidak ada target duplikat untuk properti yang sama di bulan yang sama.
            $table->unique(['property_id', 'month_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenue_targets');
    }
};
