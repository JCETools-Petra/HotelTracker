<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique();
            $table->date('booking_date');
            $table->string('client_name');
            $table->string('event_type');
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('participants');
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->string('person_in_charge');
            $table->enum('status', ['Booking Sementara', 'Booking Pasti', 'Cancel'])->default('Booking Sementara');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};