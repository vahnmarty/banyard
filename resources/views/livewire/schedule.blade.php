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

    public $index = 0;

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
            $date = $startOfWeek->format('Y-m-d');

            $days[] = $date;

            $startOfWeek->modify('+1 day');

            if($date == date('Y-m-d')){
                $this->index = $i;
            }
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

        <div>

            <div class="bg-white border-b shadow-sm">
                <div class="max-w-6xl mx-auto px-6 md:px-8">
                    <header class="flex justify-between py-6 items-center">
                        <a href="{{ url('/') }}" class="font-bold text-xl md:text-2xl">Banyard Pickleball</a>

                        <div>
                            <x-filament::button tag="a" target="_blank" href="https://calendly.com/vahnmarty/banyard-pickleball">Book Now</x-filament::button>
                        </div>
                    </header>
                </div>

            </div>

            <div class="max-w-6xl mx-auto px-6 md:px-8 pt-8">

                <div>
                    <div class="flex items-center gap-2">
                        <x-filament::icon icon="heroicon-o-calendar-days" class="size-8 md:size-10 text-info-500"/>
                        <h1 class="font-bold text-xl md:text-3xl">Schedule</h1>
                    </div>
                    <p class="mt-4 font-semibold">{{ date('F d', strtotime($day1)) }} - {{ date('F d', strtotime($day7)) }}</p>
                    <div class="border-t-4 mt-2 border-info-500 w-10"></div>
                </div>

                <div class="mt-8 mb-16">
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden">

                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[900px] text-sm">

                                <thead class="bg-slate-50 border-b">
                                    <tr>
                                        <th class="p-4 text-left text-gray-600 font-medium sticky left-0  z-20 bg-slate-50">
                                            <div class="flex gap-2">
                                                <x-filament::icon icon="heroicon-o-clock" color="primary" class="text-info-500"/>
                                                <span>Time</span>
                                            </div>
                                        </th>

                                        @foreach($days as $lDay)
                                            <th class="p-4 text-center font-medium
                                                {{ $lDay['active'] ? 'bg-success-500 text-white rounded-t-xl' : 'text-gray-600' }}">
                                                <div>{{ $lDay['display'] }}</div>
                                                <div class="text-xs opacity-80">{{ $lDay['d'] }}</div>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>

                                <tbody class="divide-y">

                                    @foreach($bookings as $time => $bookingArray)
                                        <tr class="hover:bg-slate-50 transition">

                                            {{-- TIME --}}
                                            <td class="p-4 font-medium text-gray-600 sticky left-0 bg-white z-10">
                                                {{ Carbon::parse($time)->format('h:i A') }}
                                            </td>

                                            {{-- CELLS --}}
                                            @foreach($bookingArray as $ii => $booking)
                                                @php
                                                    $isToday =  ($ii == $index);
                                                @endphp

                                                <td class="p-3 text-center {{ $isToday ? 'bg-success-100' : '' }}">

                                                    @if($booking)
                                                        <div class="
                                                            block px-3 py-1.5 rounded-lg text-xs font-medium
                                                            {{ $isToday
                                                                ? 'bg-success-300 text-gray-900 shadow'
                                                                : 'bg-info-50 text-info-700 border border-info-100'
                                                            }}
                                                        ">
                                                            {{ $booking['name'] }}
                                                        </div>
                                                    @else
                                                        <span class="text-gray-300 text-xs">—</span>
                                                    @endif

                                                </td>
                                            @endforeach

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>
                        </div>

                    </div>
                </div>


            </div>


            <div class="bg-slate-300 py-3">
                <ul class="mt-2 space-y-2   md:flex-row flex xl:gap-x-20 gap-x-6 justify-center">
                    <li>
                        <a target="_blank" href="https://facebook.com/banyard.pb" class="flex items-center gap-2">
                            <img src="{{ asset('img/facebook.png') }}" alt="facebook" class="size-5">
                            <span class="text-sm hidden md:inline-block">@banyard.pb</span>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" href="https://instagram.com/banyard.pb" class="flex items-center gap-2">
                            <img src="{{ asset('img/instagram.png') }}" alt="instagram" class="size-5">
                            <span class="text-sm hidden md:inline-block">@banyard.pb</span>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" href="https://reclub.co/clubs/@banyard-pickleball" class="flex items-center gap-2">
                            <img src="{{ asset('img/reclub.png') }}" alt="reclub" class="size-5">
                            <span class="text-sm hidden md:inline-block">@banyard-pickleball</span>
                        </a>
                    </li>
                </ul>


            </div>

        </div>

    </div>

</div>
