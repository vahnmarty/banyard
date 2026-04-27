<?php

namespace App\Filament\Resources\Appointments\Pages;

use App\Filament\Forms\Components\CalendarPicker;
use App\Filament\Forms\Components\CheckboxListButton;
use App\Filament\Resources\Appointments\AppointmentResource;
use App\Models\Appointment;
use App\Models\Booking;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Facades\DB;

class ListAppointments extends ListRecords
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('manual')
                ->label('Manual Appointment')
                ->schema([
                    Grid::make(2)
                        ->schema([
                            CalendarPicker::make('date')
                                ->label("Select Date")
                                ->year(date('Y'))
                                ->minDate(date('Y-m-d'))
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

                                        $slots =  TimeSlot::active()
                                            ->whereNotIn('time', $bookedTimes)
                                            ->get()
                                            ->pluck('formatted_time', 'time')
                                            ->toArray();

                                        return $slots;
                                    }

                                    return [];

                                })
                                ->required()
                                ->rules(['max:10'])
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
                    Select::make('players_count')
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
                ->action(function(array $data){
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

                            Notification::make()
                                ->title("Appointment Created!")
                                ->success()
                                ->send();

                            return redirect()->to(AppointmentResource::getUrl('view', ['record' => $app]));;

                        } catch (\Throwable $th) {

                            DB::rollBack();

                            throw $th;
                        }
                }),
        ];
    }
}
