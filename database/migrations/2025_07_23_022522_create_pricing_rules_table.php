<?php

// database/migrations/YYYY_MM_DD_HHMMSS_create_pricing_rules_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->decimal('publish_rate', 10, 2);
            $table->decimal('bottom_rate', 10, 2);
            $table->integer('tier_limit');
            $table->decimal('percentage_increase', 5, 2);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('pricing_rules');
    }
};
