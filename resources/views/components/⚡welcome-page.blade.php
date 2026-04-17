<?php

use Livewire\Component;

new class extends Component
{
    public $time_slots;

    public function mount()
    {
        $this->time_slots = $this->getTimeSlots();
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
                    <p>5:00 AM - 3:00 PM: <strong>FREE</strong></p>
                    <p>3:00 PM - 12:00 AM: <strong>₱75.00/hr</strong> </p>
                </div>
            </div>

            <div>

                <div class="py-8">

                <div class="text-center">
                    <h2 class="text-3xl font-bold text-center">Schedule</h2>
                    <p class="mt-2 text-sm">April 19 - April 25</p>
                </div>

                    <div class="w-full overflow-x-auto">
                        <table class="min-w-[800px] w-full border border-gray-300 border-collapse text-sm bg-white mt-6">
                            <thead>
                                <tr>
                                    <th class="table-th sticky left-0 bg-white z-20"></th>

                                    <th class="table-th">Sunday</th>
                                    <th class="table-th">Monday</th>
                                    <th class="table-th">Tuesday</th>
                                    <th class="table-th">Wednesday</th>
                                    <th class="table-th">Thursday</th>
                                    <th class="table-th">Friday</th>
                                    <th class="table-th">Saturday</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($time_slots as $time)
                                <tr>
                                    <td class="table-td whitespace-nowrap sticky left-0 bg-white z-10">
                                        {{ $time }}
                                    </td>

                                    @foreach(range(1, 7) as $slot)
                                    <td class="table-td text-center">
                                        -
                                    </td>
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
