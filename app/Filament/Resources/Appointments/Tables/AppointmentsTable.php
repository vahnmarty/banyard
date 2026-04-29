<?php

namespace App\Filament\Resources\Appointments\Tables;

use App\Models\Appointment;
use App\Models\Booking;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class AppointmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('total')
                    ->numeric(),
                TextColumn::make('date')
                    ->label('Date')
                    ->dateTime('F d, Y'),
                TextColumn::make('bookings.formatted_time')
                    ->label('Time'),
                TextColumn::make('confirmed_at')
                    ->dateTime(),
                TextColumn::make('paid_at')
                    ->dateTime(),
                TextColumn::make('cancelled_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('cancellation_reason')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('notes')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('confirmed_at')
                    ->nullable()
                    ->label('Confirmed')
                    ->default(false),
                TernaryFilter::make('cancelled_at')
                    ->nullable()
                    ->label('Cancelled')
                    ->default(false)
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([

            ])
            ->paginated(['all', 100, 50, 10]);
    }
}
