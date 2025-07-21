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
        Schema::table('function_sheets', function (Blueprint $table) {
            // Hapus kolom lama yang tidak terstruktur
            $table->dropColumn(['equipments', 'menus', 'schedule', 'department_pics']);

            // Tambah kolom baru sesuai format PDF
            $table->string('beo_number')->unique()->after('booking_id');
            $table->string('contact_phone')->nullable()->after('beo_number');
            $table->string('dealed_by')->nullable()->after('contact_phone');

            // Kolom JSON untuk data yang dinamis dan fleksibel
            $table->json('event_segments')->nullable(); // Untuk rincian acara (bisa lebih dari satu)
            $table->json('menu_details')->nullable(); // Untuk rincian menu
            $table->json('equipment_details')->nullable(); // Untuk rincian perlengkapan (CUSTOM)
            $table->json('department_notes')->nullable(); // Untuk catatan per departemen (CUSTOM)
            $table->json('billing_details')->nullable(); // Untuk rincian tagihan (CUSTOM)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('function_sheets', function (Blueprint $table) {
            $table->dropColumn([
                'beo_number', 
                'contact_phone', 
                'dealed_by',
                'event_segments', 
                'menu_details', 
                'equipment_details', 
                'department_notes', 
                'billing_details'
            ]);

            // Kembalikan kolom lama jika diperlukan
            $table->json('equipments')->nullable();
            $table->json('menus')->nullable();
            $table->json('schedule')->nullable();
            $table->json('department_pics')->nullable();
        });
    }
};