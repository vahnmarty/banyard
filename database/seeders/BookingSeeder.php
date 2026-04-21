<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\TimeSlot;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public $day1, $day7;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $days = $this->getDaysFromCurrentWeek();

        $this->day1 = $days[0];
        $this->day7 = $days[1];

        $startOfWeek = now()->startOfWeek(Carbon::SUNDAY);

        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i)->format('Y-m-d');

            $this->generateBooking($date);
        }


    }

    public function generateBooking($date)
    {
        $time_slots = TimeSlot::get();
        foreach($time_slots as $slot)
        {
            $booking = new Booking;
            $booking->date = $date;
            $booking->time = $slot->time;
            $booking->name = $this->randomName();
            $booking->email = 'vahnmarty@gmail.com';
            $booking->save();
        }
    }

    public function randomName()
    {
        $array = [ '9200 Pickleball', 'Ban Jahns', 'ALW', 'Brokeside PB', 'PekelYardz', 'Jeena'];

        return $array[array_rand($array)];
    }

    public static function getDaysFromCurrentWeek()
    {
        $today = new DateTime();

        // First day of the week (Monday)
        $startOfWeek = clone $today;
        $startOfWeek->modify('sunday last week');

        // Last day of the week (Sunday)
        $endOfWeek = clone $today;
        $endOfWeek->modify('saturday this week');

        return [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')];
    }
}
