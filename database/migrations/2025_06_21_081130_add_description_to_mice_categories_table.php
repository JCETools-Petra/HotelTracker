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
        Schema::table('mice_categories', function (Blueprint $table) {
            // Tambahkan kolom 'description' setelah kolom 'name'
            $table->text('description')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mice_categories', function (Blueprint $table) {
            // Hapus kolom jika migrasi di-rollback
            $table->dropColumn('description');
        });
    }
};