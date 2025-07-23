// database/migrations/2025_07_23_022540_create_reservations_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');

            // Kolom e-commerce ditambahkan langsung di sini
            $table->string('source')->nullable(); 
            $table->decimal('final_price', 10, 2)->nullable();

            // Kolom Anda yang sudah ada
            $table->string('guest_name');
            $table->string('guest_email')->nullable();
            $table->date('checkin_date');
            $table->date('checkout_date');
            $table->integer('number_of_rooms')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};