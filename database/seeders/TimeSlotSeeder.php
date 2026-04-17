<?php

namespace Database\Seeders;

use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $slots = [];

        for ($hour = 0; $hour < 24; $hour++) {
            // Format time (12-hour format with AM/PM)
            $time = Carbon::createFromTime($hour, 0)->format('g A');

            // Active only from 2PM (14) to 11PM (23)
            $active = $hour >= 14 && $hour <= 23;

            TimeSlot::firstOrCreate([
                'time' => $time,
                'active' => $active
            ]);
        }



    }
}
