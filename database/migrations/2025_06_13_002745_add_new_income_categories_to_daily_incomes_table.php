<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_incomes', function (Blueprint $table) {
            // PERBAIKAN: Meletakkan semua kolom baru setelah 'others_income'
            // untuk memastikan kolom patokannya pasti ada.
            
            $table->integer('ta_rooms')->default(0)->after('others_income');
            $table->decimal('ta_income', 15, 2)->default(0)->after('ta_rooms');
            
            $table->integer('gov_rooms')->default(0)->after('ta_income');
            $table->decimal('gov_income', 15, 2)->default(0)->after('gov_rooms');

            $table->integer('corp_rooms')->default(0)->after('gov_income');
            $table->decimal('corp_income', 15, 2)->default(0)->after('corp_rooms');

            $table->integer('compliment_rooms')->default(0)->after('corp_income');
            $table->decimal('compliment_income', 15, 2)->default(0)->after('compliment_rooms');
            
            $table->integer('house_use_rooms')->default(0)->after('compliment_income');
            $table->decimal('house_use_income', 15, 2)->default(0)->after('house_use_rooms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('daily_incomes', function (Blueprint $table) {
            // Periksa jika kolom ada sebelum mencoba menghapusnya, untuk menghindari error saat rollback
            $columnsToDrop = [
                'ta_rooms', 'ta_income',
                'gov_rooms', 'gov_income',
                'corp_rooms', 'corp_income',
                'compliment_rooms', 'compliment_income',
                'house_use_rooms', 'house_use_income'
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('daily_incomes', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};