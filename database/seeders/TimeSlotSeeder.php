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
        TimeSlot::truncate();

        $slots = [];

        for ($hour = 0; $hour < 24; $hour++) {
            // Active only from 2PM (14) to 11PM (23)
            $active = $hour >= 14 && $hour <= 23;

            TimeSlot::firstOrCreate([
                'time' => $hour . ':00:00',
                'active' => $active
            ]);
        }
    }

}
