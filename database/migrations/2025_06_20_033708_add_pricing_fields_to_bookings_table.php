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
        // Perintah untuk menambahkan kolom baru ke tabel 'bookings'
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('total_price', 15, 2)->default(0)->after('status');
            $table->decimal('down_payment', 15, 2)->default(0)->after('total_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Perintah untuk menghapus kolom jika migration di-rollback
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['total_price', 'down_payment']);
        });
    }
};