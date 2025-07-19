<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DailyIncome;
use Illuminate\Support\Facades\Log;

class RecalculateIncomes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'income:recalculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Force recalculation of all totals for every existing daily income record';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to forcefully recalculate all daily incomes...');

        $allIncomes = DailyIncome::all();
        $progressBar = $this->output->createProgressBar($allIncomes->count());

        if ($allIncomes->isEmpty()) {
            $this->info("\nNo income records found to recalculate.");
            return 0;
        }

        foreach ($allIncomes as $income) {
            // Recalculate all totals from scratch
            $total_rooms_sold =
                ($income->offline_rooms ?? 0) +
                ($income->online_rooms ?? 0) +
                ($income->ta_rooms ?? 0) +
                ($income->gov_rooms ?? 0) +
                ($income->corp_rooms ?? 0) +
                ($income->compliment_rooms ?? 0) +
                ($income->house_use_rooms ?? 0) +
                ($income->mice_rooms ?? 0);

            $total_rooms_revenue =
                ($income->offline_room_income ?? 0) +
                ($income->online_room_income ?? 0) +
                ($income->ta_income ?? 0) +
                ($income->gov_income ?? 0) +
                ($income->corp_income ?? 0) +
                ($income->compliment_income ?? 0) +
                ($income->house_use_income ?? 0) +
                ($income->mice_room_income ?? 0);

            $total_fb_revenue =
                ($income->breakfast_income ?? 0) +
                ($income->lunch_income ?? 0) +
                ($income->dinner_income ?? 0);

            $total_revenue =
                $total_rooms_revenue +
                $total_fb_revenue +
                ($income->mice_income ?? 0) +
                ($income->others_income ?? 0);

            $property = $income->property;
            $arr = ($total_rooms_sold > 0) ? ($total_rooms_revenue / $total_rooms_sold) : 0;
            $occupancy = ($property && $property->total_rooms > 0) ? ($total_rooms_sold / $property->total_rooms) * 100 : 0;

            // Update the record with all new calculated values without checking the old total
            $income->total_rooms_sold = $total_rooms_sold;
            $income->total_rooms_revenue = $total_rooms_revenue;
            $income->total_fb_revenue = $total_fb_revenue;
            $income->total_revenue = $total_revenue;
            $income->arr = $arr;
            $income->occupancy = $occupancy;

            // Use quiet saving to avoid firing events if any are attached
            $income->saveQuietly();
            
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->info("\n\nRecalculation complete!");
        $this->info("All " . $allIncomes->count() . " records have been re-evaluated and updated.");

        return 0;
    }
}
