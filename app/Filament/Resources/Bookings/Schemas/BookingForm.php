<?php

namespace App\Filament\Resources\Bookings\Schemas;

use App\Filament\Forms\Components\CalendarPicker;
use App\Filament\Forms\Components\CheckboxListButton;
use App\Models\TimeSlot;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                CalendarPicker::make('date')
                    ->year(date('Y'))
                    ->default(date('Y-m-d'))
                    ->required()
                    ->live(),
                CheckboxListButton::make('time')
                    ->columns(2)
                    ->lazy()
                    ->options(function(Get $schemaGet){
                        $date = $schemaGet('date');

                        if(filled($date)){
                            return TimeSlot::active()->get()->pluck('formatted_time', 'time');
                        }

                        return [];

                    })
                    ->required(),
                TextInput::make('name'),
                TextInput::make('email'),
            ]);
    }

}
