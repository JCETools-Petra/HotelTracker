<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('function_sheets', function (Blueprint $table) {
            $table->foreignId('mice_category_id')->nullable()->constrained('mice_categories')->after('property_id');
        });
    }

    public function down(): void
    {
        Schema::table('function_sheets', function (Blueprint $table) {
            $table->dropForeign(['mice_category_id']);
            $table->dropColumn('mice_category_id');
        });
    }
};