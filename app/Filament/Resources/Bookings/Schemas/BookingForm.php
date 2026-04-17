<?php

namespace App\Filament\Resources\Bookings\Schemas;

use App\Filament\Forms\Components\CalendarPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                CalendarPicker::make('date')
                    ->year(date('Y'))
                    ->required(),
            ]);
    }
}
