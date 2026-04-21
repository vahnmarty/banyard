<?php

use App\Models\Booking;
use App\Models\TimeSlot;
use Livewire\Component;
use Carbon\Carbon;

new class extends Component
{
    public $time_slots;

    public $day1, $day7;

    public $bookings = [];

    public $days;

    public function mount()
    {
        $this->time_slots = $this->getTimeSlots();

        $days = $this->getDaysFromCurrentWeek();

        $this->day1 = $days[0];
        $this->day7 = $days[1];

        $this->bookings = $this->getBookings();

        $this->days = $this->getDaysArray();
    }

    public function getDaysArray()
    {
        $days = $this->getWeekDays();
        $array = [];

        foreach($days as $day)
        {
            $array[$day]['d'] = Carbon::parse($day)->format('d');
            $array[$day]['display'] = Carbon::parse($day)->format('l');
            $array[$day]['active'] =  $day == date('Y-m-d') ? true : false;
        }

        return $array;
    }

    public function getBookings()
    {
        $days = $this->getWeekDays();
        $time_slots = TimeSlot::active()->get();

        $array = [];

        foreach($time_slots as $slot)
        {
            foreach($days as $date)
            {
                $booking = Booking::where('date', $date)->where('time', $slot->time)->first();

                if($booking){
                    $array[$slot->time][] = $booking->toArray();
                }else{
                    $array[$slot->time][] = null;
                }
            }


        }

        return $array;
    }

    public function getWeekDays()
    {
        $startOfWeek = new DateTime();
        $startOfWeek->modify('sunday last week');

        $days = [];

        for ($i = 0; $i < 7; $i++) {
            $days[] = $startOfWeek->format('Y-m-d');
            $startOfWeek->modify('+1 day');
        }

        return $days;
    }

    public function getTimeSlots()
    {
        $array = [];

        foreach(range(3, 11) as $i)
        {
            $array[] = $i . ':00 PM';
        }

        return $array;
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
};
?>

<div>
    <div class="bg-neutral-100">

        <div class="max-w-5xl mx-auto px-8">

            <header class="flex justify-between py-6 items-center">
                <a href="{{ url('/') }}" class="font-bold text-2xl">Banyard.</a>

                <div>
                    <x-filament::button tag="a" href="{{ url('/book') }}">Book Now</x-filament::button>
                </div>
            </header>

            <div class="flex flex-col md:flex-row justify-between gap-8 mt-8">
                <div>
                    <h3 class="font-bold flex gap-1"> <x-filament::icon icon="heroicon-o-map-pin"/> Location</h3>
                    <p>Zone Meteor, IISHAI, Suarez, Iligan City</p>
                    <a href="https://maps.app.goo.gl/tVGNjHQswwVVFJgR7" class="text-info-600 underline" target="_blank">https://maps.app.goo.gl/tVGNjHQswwVVFJgR7</a>
                </div>
                <div>
                    <h3 class="font-bold flex gap-1"> <x-filament::icon icon="heroicon-o-banknotes"/> Rates</h3>
                    <p>5:00 AM - 2:00 PM: <strong>FREE</strong></p>
                    <p>2:00 PM - 10:00 PM: <strong>₱75.00/hr</strong> </p>
                </div>
            </div>

            <div>

                <div class="py-8">

                <div class="text-center">
                    <h2 class="text-3xl font-bold text-center">Schedule</h2>
                    <p class="mt-2 text-sm">{{ date('F d', strtotime($day1)) }} - {{ date('F d', strtotime($day7)) }}</p>
                </div>

                    <div class="w-full overflow-x-auto">
                        <table class="min-w-[800px] w-full border border-gray-300 border-collapse text-sm bg-white mt-6">
                            <thead>
                                <tr>
                                    <th class="table-th sticky left-0 bg-white z-20"></th>
                                    @foreach($days as $lDay)
                                    <th class="table-th {{ $lDay['active'] ? 'bg-success-400' : '' }}">{{  $lDay['display'] }} {{ $lDay['d'] }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $time => $bookingArray)
                                <tr>
                                    <td class="table-td whitespace-nowrap sticky left-0 bg-white z-10">
                                        {{ Carbon::parse($time)->format('h:i A') }}
                                    </td>

                                    @foreach($bookingArray as $booking)
                                        @if($booking['date'] == date('Y-m-d') )
                                        <td class="table-td text-center bg-success-400">
                                        <div class="py-2 text-xs">
                                            {{  $booking['name'] }}
                                        </div>
                                    </td>
                                        @else
                                        <td class="table-td text-center">
                                            <div class="py-2 text-xs border border-primary-200 bg-primary-100">
                                                {{  $booking['name'] }}
                                            </div>
                                        </td>
                                        @endif
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <div>
                <ul class="mt-2 space-y-2  flex-col md:flex-row flex gap-x-6 justify-center">
                    <li>
                        <a href="https://facebook.com/banyard.pb" class="flex items-center gap-2">
                            <img src="{{ asset('img/facebook.png') }}" alt="facebook" class="size-5">
                            <span class="text-sm">@banyard.pb</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://instagram.com/banyard.pb" class="flex items-center gap-2">
                            <img src="{{ asset('img/instagram.png') }}" alt="instagram" class="size-5">
                            <span class="text-sm">@banyard.pb</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://reclub.co/clubs/@banyard-pickleball" class="flex items-center gap-2">
                            <img src="{{ asset('img/reclub.png') }}" alt="reclub" class="size-5">
                            <span class="text-sm">@banyard-pickleball</span>
                        </a>
                    </li>
                </ul>

            </div>

        </div>

        <footer class="text-sm py-3 mt-8">
            <div class="text-center">Made with 🩷 by <a href="https://www.instagram.com/vahn.marty" class="underline">@vahnmarty</a></div>
        </footer>
    </div>

</div>
