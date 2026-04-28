<?php

use App\Filament\Forms\Components\CalendarPicker;
use App\Filament\Forms\Components\CheckboxListButton;
use App\Mail\AppointmentCreated;
use App\Mail\NewAppointmentAlert;
use App\Models\Appointment;
use App\Models\Booking;
use App\Models\TimeSlot;
use Livewire\Component;
use Carbon\Carbon;
use Filament\Forms\Components\Radio;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Illuminate\Contracts\View\View;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

new class extends Component implements HasSchemas
{
    use InteractsWithSchemas;


    public $day1, $day7;

    public $data = [];

    public function mount()
    {
        $this->form->fill();

    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        CalendarPicker::make('date')
                            ->label("Select Date")
                            ->year(date('Y'))
                            ->minDate(date('Y-m-d'))
                            ->maxDate(Carbon::now()->addDays(30)->format('Y-m-d'))
                            ->required()
                            ->live(),
                        CheckboxListButton::make('time')
                            ->label('Time Slot')
                            ->columns(2)
                            ->hidden(fn(Get $schemaGet) => $schemaGet('date') == null)
                            ->options(function(Get $schemaGet){
                                $date = $schemaGet('date');

                                if(filled($date)){
                                    $bookedTimes = Booking::where('date', $date)
                                        ->get()
                                        ->pluck('time')
                                        ->toArray();

                                    // $slots =  TimeSlot::active()
                                    //     ->whereNotIn('time', $bookedTimes)
                                    //     ->get()
                                    //     ->pluck('formatted_time', 'time')
                                    //     ->toArray();

                                    $slots = TimeSlot::active()
                                    ->whereNotIn(DB::raw("TIME_FORMAT(time, '%H:%i:%s')"), function ($query) use ($date) {
                                        $query->select(DB::raw("TIME_FORMAT(time, '%H:%i:%s')"))
                                            ->from('bookings')
                                            ->where('date', $date);
                                    })
                                    ->get()
                                    ->pluck('formatted_time', 'time')
                                    ->toArray();

                                    return $slots;
                                }

                                return [];

                            })
                            ->required()
                            ->rules(['max:3'])
                            ->validationMessages([
                                'max' => 'Maximum of 3 slots per booking.'
                            ]),
                    ]),

                TextInput::make('name')
                    ->maxLength(72)
                    ->required(),
                TextInput::make('club')
                    ->label('Name of your Club/Group/Organization')
                    ->maxLength(72)
                    ->placeholder('(Optional)'),
                Radio::make('players_count')
                    ->label('Number of Players')
                    ->options([
                        '1-3' => '1-3',
                        '4-8' => '4-8',
                        '9+' => '9+'
                    ]),
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->placeholder("Please enter a valid email address"),
            ])
            ->statePath('data');
    }



    public function submit()
    {
        $data = $this->form->getState();

        DB::beginTransaction();

        try {

            $app = new Appointment;
            $app->name = $data['name'];
            $app->email = $data['email'];
            $app->date = $data['date'];
            $app->save();

            foreach($data['time'] as $time)
            {
                $booking = new Booking;
                $booking->appointment_id = $app->getKey();
                $booking->date = $data['date'];
                $booking->time = $time;
                $booking->name = $data['name'];
                $booking->email = $data['email'];
                $booking->club = $data['club'];
                $booking->players_count = $data['players_count'];
                $booking->save();
            }

            DB::commit();

            Mail::to(config('mail.receiver.address'))->send(new NewAppointmentAlert($app));
            Mail::to($data['email'])->send(new AppointmentCreated($app));



            return redirect()->route('view-appointment', $app->uuid);

        } catch (\Throwable $th) {

            DB::rollBack();
            throw $th;
        }
    }
};
?>

<div>

    <x-slot name="meta">
            <meta property="og:title" content="Pickleball Schedule">
            <meta property="og:description" content="View available time slots and book your court.">
            <meta property="og:image" content="{{ url('/og-image.png') }}">
            <meta property="og:url" content="{{ url('/schedule') }}">
            <meta property="og:type" content="website">

            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:title" content="Pickleball Schedule">
            <meta name="twitter:image" content="{{ url('/og-image.png') }}">
        </x-slot>
    <div class="bg-neutral-100">

        <div>

            @include('partials.header')

            <div class="max-w-6xl mx-auto px-3 md:px-8 pt-8">

                <div>
                    <div class="flex items-center gap-2">
                        <x-filament::icon icon="heroicon-o-chat-bubble-left-ellipsis" class="size-8 md:size-10 text-info-500"/>
                        <h1 class="font-bold text-xl md:text-3xl">Booking Form</h1>
                    </div>
                </div>

                <div class="mt-8 mb-16 bg-white p-4 md:p-8 rounded-md shadow-md">
                    <form wire:submit.prevent="submit">
                        {{  $this->form }}

                        <div class="mt-8">
                            <x-filament::button type="submit" size="xl">Submit</x-filament::button>
                        </div>
                    </form>
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
