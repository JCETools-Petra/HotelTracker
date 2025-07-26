<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_occupancies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('occupied_rooms');
            $table->timestamps();

            $table->unique(['property_id', 'date']); // Hanya boleh ada 1 data per properti per hari
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_occupancies');
    }
};