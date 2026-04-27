<?php

use App\Models\Appointment;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

new class extends Component
{

    public Appointment $app;

    public $bookings = [];

    public function mount($uuid)
    {
        $this->app = Appointment::with('bookings')->where('uuid', $uuid)->firstOrFail();

        $this->bookings = $this->app->bookings;
    }



};
?>

<div class="h-full bg-neutral-100">
    <div class="">

        <div>

            @include('partials.header')

            <div class="max-w-6xl mx-auto px-3 md:px-8 pt-8">

                @if($app->isExpired())
                <x-filament::callout color="danger" icon="heroicon-o-information-circle">
                    <x-slot name="heading">
                        Booking has expired.
                    </x-slot>
                    <x-slot name="description">
                        This booking date has already passed.
                    </x-slot>
                </x-filament::callout>
                @endif

                @if($app->isCancelled())
                <x-filament::callout color="danger" icon="heroicon-o-information-circle">
                    <x-slot name="heading">
                        Booking Cancelled.
                    </x-slot>
                    <x-slot name="description">
                        This booking has been cancelled.
                    </x-slot>
                </x-filament::callout>
                @endif

                <div class="mt-8 bg-white p-4 md:p-8 rounded-md shadow-md">

                    <h3 class="font-bold text-2xl">Banyard Pickleball Court Reservation</h3>

                    <div class="space-y-4 mt-8">
                        <div class="flex gap-4 items-center">
                            <x-filament::icon icon="heroicon-o-calendar-days" class="text-primary-600 size-8"/>
                            <p class="text-lg">{{ $app->date->format('F d, Y (l)'); }}</p>
                        </div>
                        <div class="flex gap-4 items-start">
                            <x-filament::icon icon="heroicon-o-clock" class="text-primary-600 size-8"/>
                            <div>
                                @foreach($bookings as $booking)
                                <p class="text-lg">{{ $booking->getFormattedTime() }}</p>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex gap-4 items-center">
                            <x-filament::icon icon="heroicon-o-banknotes" class="text-primary-600 size-8"/>
                            <div class="text-lg">
                                <span>₱{{ number_format($app->getTotal(), 2) }}</span>
                            </div>
                        </div>
                        <div class="flex gap-4 items-center">
                            <x-filament::icon icon="heroicon-o-user-circle" class="text-primary-600 size-8"/>
                            <div class="text-lg">
                                <span>{{ $app->name . " ({$app->email})" }}</span>
                                @if(!$app->isConfirmed())
                                <span class="text-success-600 font-semibold text-sm italic">An email has been sent to this email address.</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex gap-4 items-center">
                            <x-filament::icon icon="heroicon-o-information-circle" class="text-primary-600 size-8"/>
                            @if($app->isPending())
                            <x-filament::callout color="warning">

                                <x-slot name="description">
                                    Your appointment is pending for confirmation.
                                </x-slot>
                            </x-filament::callout>
                            @endif

                            @if($app->isConfirmed())
                            <p class="text-success-700  text-xl">Your appointment has been confirmed.</p>
                            @endif
                        </div>

                        <div class="mt-8 border-t pt-8">
                            <x-filament::button tag="a" href="{{ route('schedule') }}">View Schedule</x-filament::button>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <div class="flex  items-center gap-8">
                        <div>
                            <p>For more updates, Follow us on: </p>
                        </div>
                        <div>
                            <ul class="mt-2 space-y-2 flex flex-row gap-x-4">
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



        </div>

    </div>

</div>
