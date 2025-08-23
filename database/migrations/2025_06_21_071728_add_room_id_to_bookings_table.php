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
    Schema::table('bookings', function (Blueprint $table) {
        // Tambahkan kolom baru room_id setelah property_id
        $table->foreignId('room_id')->nullable()->constrained()->onDelete('set null')->after('property_id');
        
        // Hapus kolom lama yang tidak terstruktur
        if (Schema::hasColumn('bookings', 'room_used')) {
            $table->dropColumn('room_used');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            //
        });
    }
};
