<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_rules', function (Blueprint $table) {
            $table->id();

            // This foreign key now correctly points to a table that will already exist.
            $table->foreignId('room_type_id')->constrained()->onDelete('cascade');

            $table->decimal('publish_rate', 10, 2)->default(0);
            $table->decimal('bottom_rate', 10, 2)->default(0);
            $table->decimal('percentage_increase', 5, 2)->default(0);
            $table->tinyInteger('starting_bar')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_rules');
    }
};