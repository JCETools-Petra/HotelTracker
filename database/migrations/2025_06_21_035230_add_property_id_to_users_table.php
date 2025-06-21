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
        // PERBAIKAN: Cek terlebih dahulu apakah kolom sudah ada atau belum
        if (!Schema::hasColumn('users', 'property_id')) {
            Schema::table('users', function (Blueprint $table) {
                // Menambahkan kolom foreign key yang terhubung ke tabel properties
                // Dibuat nullable agar admin/owner tidak perlu memiliki properti
                $table->foreignId('property_id')->nullable()->constrained('properties')->after('role');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // PERBAIKAN: Cek terlebih dahulu apakah kolom ada sebelum mencoba menghapusnya
        if (Schema::hasColumn('users', 'property_id')) {
            Schema::table('users', function (Blueprint $table) {
                // Menghapus foreign key dan kolom jika migration di-rollback
                $table->dropForeign(['property_id']);
                $table->dropColumn('property_id');
            });
        }
    }
};
