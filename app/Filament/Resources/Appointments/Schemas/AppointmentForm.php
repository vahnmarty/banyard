<?php

namespace App\Filament\Resources\Appointments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('uuid')
                    ->label('UUID')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('total')
                    ->numeric(),
                DateTimePicker::make('confirmed_at'),
                DateTimePicker::make('paid_at'),
                TextInput::make('receipt'),
                TextInput::make('reference'),
                DateTimePicker::make('cancelled_at'),
                TextInput::make('cancellation_reason'),
                TextInput::make('notes'),
            ]);
    }
}
