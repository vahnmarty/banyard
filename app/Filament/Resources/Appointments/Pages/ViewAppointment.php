<?php

namespace App\Filament\Resources\Appointments\Pages;

use App\Filament\Resources\Appointments\AppointmentResource;
use App\Mail\AppointmentCancelled;
use App\Mail\AppointmentConfirmed;
use App\Models\Appointment;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Mail;

class ViewAppointment extends ViewRecord
{
    protected static string $resource = AppointmentResource::class;

    protected string $view = 'filament.resources.appointments.view-appointment';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('confirm')
                ->color('primary')
                ->requiresConfirmation()
                ->hidden(fn(Appointment $record) => $record->isConfirmed() || $record->isCancelled())
                ->action(function(Appointment $record){


                    $record->confirmed_at = now();
                    $record->save();

                    Mail::to($record->email)->send(new AppointmentConfirmed($record));

                    foreach($record->bookings as $booking)
                    {
                        $booking->confirmed_at = now();
                        $booking->save();
                    }


                    Notification::make()
                        ->title('Booking has been confirmed!')
                        ->success()
                        ->send();

                    return redirect()->to(AppointmentResource::getUrl());
                }),
            Action::make('resend')
                ->label("Resend Confirmation")
                ->color('primary')
                ->requiresConfirmation()
                ->visible(fn(Appointment $record) => $record->isConfirmed())
                ->action(function(Appointment $record){


                    Mail::to($record->email)->send(new AppointmentConfirmed($record));

                    Notification::make()
                        ->title('Email sent!')
                        ->success()
                        ->send();

                }),
            Action::make('cancel')
                ->color('danger')
                ->hidden(fn(Appointment $record) => $record->isConfirmed() || $record->isCancelled())
                ->schema([
                    Select::make('cancellation_reason')
                        ->label('Cancellation Reason')
                        ->options([
                            'client_cancelled' => 'Client Cancelled',
                            'no_show' => 'No Show',
                            'other' => 'Other',
                        ])
                        ->required(),
                    Textarea::make('notes')
                        ->label('Notes'),
                ])
                ->requiresConfirmation()
                ->action(function(Appointment $record, array $data){


                    $record->cancelled_at = now();
                    $record->cancellation_reason = $data['cancellation_reason'];
                    $record->notes = $data['notes'] ?? $record->notes;
                    $record->save();

                    Mail::to($record->email)->send(new AppointmentCancelled($record));

                    foreach($record->bookings as $booking)
                    {
                        $booking->cancelled_at = now();
                        $booking->save();
                    }


                    Notification::make()
                        ->title('Booking has been Cancelled!')
                        ->success()
                        ->send();
                }),
        ];
    }

}
