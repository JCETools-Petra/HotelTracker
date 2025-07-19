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
        Schema::create('targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade'); // Relasi ke properti
            $table->year('year'); // Tahun target, misal: 2025
            $table->unsignedTinyInteger('month'); // Bulan target (1-12)
            $table->decimal('target_amount', 15, 2); // Jumlah target pendapatan
            // Opsional: Anda bisa menambahkan kolom 'income_category' jika ingin target per kategori nanti
            // $table->string('income_category')->nullable()->default('total_revenue'); 
            $table->timestamps();

            // Pastikan kombinasi property, tahun, dan bulan adalah unik
            $table->unique(['property_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('targets');
    }
};