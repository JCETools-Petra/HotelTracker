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
        Schema::create('function_sheets', function (Blueprint $table) {
            $table->id();
            // Relasi one-to-one dengan tabel bookings
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            
            $table->string('room_setup')->nullable(); // Classroom, Theatre, dll.
            $table->json('equipments')->nullable(); // LCD, Mic, Whiteboard, dll.
            $table->json('menus')->nullable(); // Coffee Break, Lunch, Dinner
            $table->json('schedule')->nullable(); // Time Schedule Acara
            $table->json('department_pics')->nullable(); // PIC per departemen
            $table->text('notes')->nullable(); // Catatan khusus BEO
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('function_sheets');
    }
};