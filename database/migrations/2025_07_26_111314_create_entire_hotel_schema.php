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
        // 1. Tabel Properties (Dibuat pertama karena tidak bergantung pada tabel lain)
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('chart_color')->default('#cccccc');
            $table->integer('total_rooms')->default(0); // Kolom ini penting untuk kalkulasi
            $table->integer('bar_1')->default(0);
            $table->integer('bar_2')->default(0);
            $table->integer('bar_3')->default(0);
            $table->integer('bar_4')->default(0);
            $table->integer('bar_5')->default(0);
            $table->timestamps();
        });

        // 2. Tabel Users (Bergantung pada 'properties')
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

        // 3. Tabel Room Types (Bergantung pada 'properties')
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
        });

        // 4. Tabel Pricing Rules (Bergantung pada 'room_types')
        Schema::create('pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained()->onDelete('cascade');
            $table->decimal('publish_rate', 15, 2)->default(0);
            $table->decimal('bottom_rate', 15, 2)->default(0);
            $table->decimal('percentage_increase', 5, 2)->default(0);
            $table->tinyInteger('starting_bar')->default(1);
            $table->timestamps();
        });

        // 5. Tabel Daily Occupancies (Bergantung pada 'properties')
        Schema::create('daily_occupancies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('occupied_rooms');
            $table->timestamps();
            $table->unique(['property_id', 'date']);
        });

        // 6. Tabel Daily Incomes (Bergantung pada 'properties' dan 'users')
        Schema::create('daily_incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('offline_rooms')->default(0);
            $table->decimal('offline_room_income', 15, 2)->default(0);
            $table->integer('online_rooms')->default(0);
            $table->decimal('online_room_income', 15, 2)->default(0);
            $table->integer('ta_rooms')->default(0);
            $table->decimal('ta_income', 15, 2)->default(0);
            $table->integer('gov_rooms')->default(0);
            $table->decimal('gov_income', 15, 2)->default(0);
            $table->integer('corp_rooms')->default(0);
            $table->decimal('corp_income', 15, 2)->default(0);
            $table->integer('compliment_rooms')->default(0);
            $table->decimal('compliment_income', 15, 2)->default(0);
            $table->integer('house_use_rooms')->default(0);
            $table->decimal('house_use_income', 15, 2)->default(0);
            $table->integer('afiliasi_rooms')->default(0);
            $table->decimal('afiliasi_room_income', 15, 2)->default(0);
            $table->decimal('breakfast_income', 15, 2)->default(0);
            $table->decimal('lunch_income', 15, 2)->default(0);
            $table->decimal('dinner_income', 15, 2)->default(0);
            $table->decimal('others_income', 15, 2)->default(0);
            $table->integer('total_rooms_sold')->default(0);
            $table->decimal('total_rooms_revenue', 15, 2)->default(0);
            $table->decimal('total_fb_revenue', 15, 2)->default(0);
            $table->decimal('total_revenue', 15, 2)->default(0);
            $table->decimal('arr', 15, 2)->default(0);
            $table->decimal('occupancy', 5, 2)->default(0);
            $table->timestamps();
            $table->unique(['property_id', 'date']);
        });

        // 7. Tabel Reservations (Bergantung pada 'properties')
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('source')->nullable();
            $table->decimal('final_price', 15, 2)->nullable();
            $table->string('guest_name');
            $table->string('guest_email')->nullable();
            $table->date('checkin_date');
            $table->date('checkout_date');
            $table->integer('number_of_rooms')->default(1);
            $table->timestamps();
        });

        // 8. Tabel Activity Logs (Opsional, tapi sangat disarankan)
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('property_id')->nullable()->constrained()->onDelete('set null');
            $table->text('description');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus tabel dalam urutan terbalik
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('daily_incomes');
        Schema::dropIfExists('daily_occupancies');
        Schema::dropIfExists('pricing_rules');
        Schema::dropIfExists('room_types');
        Schema::dropIfExists('users');
        Schema::dropIfExists('properties');
    }
};