<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_daily_incomes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Pengguna yang mencatat
            $table->date('date');
            $table->decimal('mice_income', 15, 2)->default(0);
            $table->decimal('fnb_income', 15, 2)->default(0);
            $table->decimal('offline_room_income', 15, 2)->default(0);
            $table->decimal('online_room_income', 15, 2)->default(0);
            $table->decimal('others_income', 15, 2)->default(0);
            $table->timestamps();

            $table->unique(['property_id', 'date']); // Satu entri per properti per hari
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_incomes');
    }
};