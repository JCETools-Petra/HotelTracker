<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // dalam file ...modify_id_in_bookings_table.php

    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Baris ini akan memperbaiki kolom 'id' Anda
            $table->id()->change();
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
