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
        // Check if the column exists before trying to modify it
        if (Schema::hasColumn('activity_logs', 'action')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                // Modify the 'action' column to make it nullable
                $table->string('action')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is the reverse operation, in case you need to roll back
        if (Schema::hasColumn('activity_logs', 'action')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->string('action')->nullable(false)->change();
            });
        }
    }
};