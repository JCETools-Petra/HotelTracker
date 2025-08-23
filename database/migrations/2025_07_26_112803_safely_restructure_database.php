<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Add new 'bar' columns to the properties table
        if (!Schema::hasColumn('properties', 'bar_1')) {
            Schema::table('properties', function (Blueprint $table) {
                $table->integer('bar_1')->default(0);
                $table->integer('bar_2')->default(0);
                $table->integer('bar_3')->default(0);
                $table->integer('bar_4')->default(0);
                $table->integer('bar_5')->default(0);
            });
        }

        // Step 2 (Optional but Recommended): Transfer existing bar data
        // This assumes the bar values were the same for all room types in a property
        // and takes the values from the first pricing rule it finds for each property.
        $properties = DB::table('properties')->get();
        foreach ($properties as $property) {
            $firstPricingRule = DB::table('pricing_rules')
                ->join('room_types', 'pricing_rules.room_type_id', '=', 'room_types.id')
                ->where('room_types.property_id', $property->id)
                ->select('pricing_rules.*')
                ->first();

            if ($firstPricingRule) {
                DB::table('properties')->where('id', $property->id)->update([
                    'bar_1' => $firstPricingRule->bar_1 ?? 0,
                    'bar_2' => $firstPricingRule->bar_2 ?? 0,
                    'bar_3' => $firstPricingRule->bar_3 ?? 0,
                    'bar_4' => $firstPricingRule->bar_4 ?? 0,
                    'bar_5' => $firstPricingRule->bar_5 ?? 0,
                ]);
            }
        }

        // Step 3: Remove old 'bar' columns from the pricing_rules table
        if (Schema::hasColumn('pricing_rules', 'bar_1')) {
            Schema::table('pricing_rules', function (Blueprint $table) {
                $table->dropColumn(['bar_1', 'bar_2', 'bar_3', 'bar_4', 'bar_5']);
            });
        }

        // Step 4: Add the missing 'starting_bar' column to pricing_rules
         if (!Schema::hasColumn('pricing_rules', 'starting_bar')) {
            Schema::table('pricing_rules', function (Blueprint $table) {
                $table->tinyInteger('starting_bar')->default(1);
            });
        }
    }

    public function down(): void
    {
        // Logic to reverse the changes if you ever need to roll back
        if (Schema::hasColumn('properties', 'bar_1')) {
            Schema::table('properties', function (Blueprint $table) {
                $table->dropColumn(['bar_1', 'bar_2', 'bar_3', 'bar_4', 'bar_5']);
            });
        }

        if (!Schema::hasColumn('pricing_rules', 'bar_1')) {
            Schema::table('pricing_rules', function (Blueprint $table) {
                $table->integer('bar_1')->default(0);
                $table->integer('bar_2')->default(0);
                $table->integer('bar_3')->default(0);
                $table->integer('bar_4')->default(0);
                $table->integer('bar_5')->default(0);
            });
        }

        if (Schema::hasColumn('pricing_rules', 'starting_bar')) {
            Schema::table('pricing_rules', function (Blueprint $table) {
                $table->dropColumn('starting_bar');
            });
        }
    }
};