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
        // 1. Tabel Properties (tanpa relasi)
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('chart_color')->default('#cccccc');
            $table->integer('bar_1')->default(0);
            $table->integer('bar_2')->default(0);
            $table->integer('bar_3')->default(0);
            $table->integer('bar_4')->default(0);
            $table->integer('bar_5')->default(0);
            $table->timestamps();
        });

        // 2. Tabel Users (dengan relasi ke properties)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role');
            $table->foreignId('property_id')->nullable()->constrained()->onDelete('set null');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        // 3. Tabel Room Types (dengan relasi ke properties)
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        // 4. Tabel Pricing Rules (dengan relasi ke room_types)
        Schema::create('pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained()->onDelete('cascade');
            $table->decimal('publish_rate', 10, 2)->default(0);
            $table->decimal('bottom_rate', 10, 2)->default(0);
            $table->decimal('percentage_increase', 5, 2)->default(0);
            $table->tinyInteger('starting_bar')->default(1);
            $table->timestamps();
        });

        // 5. Tabel Daily Occupancies (dengan relasi ke properties)
        Schema::create('daily_occupancies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('occupied_rooms');
            $table->timestamps();
            $table->unique(['property_id', 'date']);
        });

        // 6. Tabel Reservations (dengan relasi ke properties)
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('source')->nullable();
            $table->decimal('final_price', 10, 2)->nullable();
            $table->string('guest_name');
            $table->string('guest_email')->nullable();
            $table->date('checkin_date');
            $table->date('checkout_date');
            $table->integer('number_of_rooms')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus tabel dalam urutan terbalik untuk menghindari error foreign key
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('daily_occupancies');
        Schema::dropIfExists('pricing_rules');
        Schema::dropIfExists('room_types');
        Schema::dropIfExists('users');
        Schema::dropIfExists('properties');
    }
};