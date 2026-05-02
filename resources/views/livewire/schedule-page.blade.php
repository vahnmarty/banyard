<?php

use App\Models\Booking;
use App\Models\TimeSlot;
use Livewire\Component;
use Carbon\Carbon;
use Livewire\Attributes\Url;

new class extends Component
{
    public $time_slots;

    public $day1;

    public $day7;

    public $bookings = [];

    public $header_days;

    public $index = 0;

    public $next_counter = 0;
    public $prev_counter = 0;
    public $next_counter_max = 3;
    public $prev_counter_max = 1;

    public $init_week = true;

    public function mount()
    {
        $this->time_slots = $this->getTimeSlots();

        $this->initCalendarDates();

        $this->header_days = $this->getDaysArray();

        $this->generateBookings();

    }

    public function initCalendarDates()
    {
        if(!$this->day1 && !$this->day7){
            $days = $this->getDaysFromCurrentWeek();

            $this->day1 = $days[0];
            $this->day7 = $days[1];
        }
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

    public function generateBookings()
    {
        $time_slots = TimeSlot::active()->get();

        $days = $this->getWeekDays();

        $array = [];

        foreach($time_slots as $slot)
        {
            foreach($days as $date)
            {
                $booking = Booking::where('date', $date)->where('time', $slot->time)->confirmed()->first();


                if($booking){
                    $initArray = $booking->toArray();
                    $initArray['active'] = $booking->date->isToday();
                    $array[$slot->time][] = $initArray;
                }else{
                    $isActive = $date == date('Y-m-d');
                    $array[$slot->time][] = ['active' => $isActive];
                }
            }


        }

        $this->bookings =  $array;
    }

    public function getWeekDays()
    {
        $day1 = $this->day1;
        $day7 = $this->day7;

        $start = new DateTime($day1);
        $end = new DateTime($day7);

        $days = [];
        $i = 0;

        while ($start <= $end) {
            $date = $start->format('Y-m-d');

            $days[] = $date;

            if ($date == date('Y-m-d')) {
                $this->index = $i;
            }

            $start->modify('+1 day');
            $i++;
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

        $startOfWeek = clone $today;
        $startOfWeek->modify('monday this week');

        $endOfWeek = clone $today;
        $endOfWeek->modify('sunday this week');

        return [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')];
    }

    public function nextWeek()
    {
        if($this->next_counter < $this->next_counter_max)
        {
            $day1 = $this->day1;
            $day7 = $this->day7;

            $this->day1 = Carbon::parse($day1)->addDays(7)->format('Y-m-d');
            $this->day7 = Carbon::parse($day7)->addDays(7)->format('Y-m-d');
            $this->header_days = $this->getDaysArray();

            $this->generateBookings();

            $this->next_counter++;
            $this->prev_counter_max++;

        }

        return;

    }

    public function prevWeek()
    {
        if($this->prev_counter < $this->prev_counter_max)
        {
            $day1 = $this->day1;
            $day7 = $this->day7;

            $this->day1 = Carbon::parse($day1)->subDays(7)->format('Y-m-d');
            $this->day7 = Carbon::parse($day7)->subDays(7)->format('Y-m-d');
            $this->header_days = $this->getDaysArray();

            $this->generateBookings();

            $this->prev_counter++;
            $this->next_counter_max++;
        }

        return;
    }
};
?>

<div>
    <div class="bg-neutral-100">

        <div>

            @include('partials.header')

            <div class="max-w-6xl mx-auto px-6 md:px-8 pt-8">

                <div>
                    <div class="flex items-center gap-2">
                        <x-filament::icon icon="heroicon-o-calendar-days" class="size-8 md:size-10 text-info-500"/>
                        <h1 class="font-bold text-xl md:text-3xl">Schedule</h1>
                    </div>
                    <div class="flex items-center mt-4 gap-x-4">
                        <div>
                            <x-filament::icon-button wire:click="prevWeek()" icon="heroicon-o-chevron-left" class="text-neutral-400"/>
                        </div>
                        <div>
                            <p class="font-semibold">{{ date('F d', strtotime($day1)) }} - {{ date('F d', strtotime($day7)) }}</p>
                        </div>
                        <div>
                            <x-filament::icon-button wire:click="nextWeek()"  icon="heroicon-o-chevron-right"/>
                        </div>
                    </div>
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

                                        @foreach($header_days as $lDay)
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

                                            <td class="p-4 font-medium text-gray-600 sticky left-0 bg-white z-10">
                                                {{ Carbon::parse($time)->format('h:i A') }}
                                            </td>

                                            @foreach($bookingArray as $ii => $booking)
                                                @php
                                                    $isHighlight = $booking['active'];
                                                @endphp

                                                <td class="p-3 text-center {{ $isHighlight ? 'bg-success-100' : '' }}">

                                                    @if($booking)
                                                        <div class="
                                                            block px-3 py-1.5 rounded-lg text-xs font-medium
                                                            {{ $isHighlight
                                                                ? 'bg-success-300 text-gray-900 shadow'
                                                                : 'bg-info-50 text-info-700 border border-info-100'
                                                            }}
                                                        ">
                                                            {{ $booking['name'] ?? "-" }}
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
