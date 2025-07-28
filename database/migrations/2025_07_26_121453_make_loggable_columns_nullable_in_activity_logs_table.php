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
        // Check for the columns before trying to modify them
        if (Schema::hasColumn('activity_logs', 'loggable_type') && Schema::hasColumn('activity_logs', 'loggable_id')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                // Modify the columns to make them nullable
                $table->string('loggable_type')->nullable()->change();
                $table->unsignedBigInteger('loggable_id')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is the reverse operation, in case you need to roll back
        if (Schema::hasColumn('activity_logs', 'loggable_type') && Schema::hasColumn('activity_logs', 'loggable_id')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->string('loggable_type')->nullable(false)->change();
                $table->unsignedBigInteger('loggable_id')->nullable(false)->change();
            });
        }
    }
};