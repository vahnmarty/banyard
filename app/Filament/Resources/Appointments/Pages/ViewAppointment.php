<?php

namespace App\Filament\Resources\Appointments\Pages;

use App\Filament\Resources\Appointments\AppointmentResource;
use App\Mail\AppointmentConfirmed;
use App\Models\Appointment;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
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

                    // Email Notify

                    foreach($record->bookings as $booking)
                    {
                        $booking->confirmed_at = now();
                        $booking->save();
                    }

                    Mail::to($record->email, $record->name)->send(new AppointmentConfirmed($record));

                    Notification::make()
                        ->title('Booking has been confirmed!')
                        ->success()
                        ->send();
                }),
            Action::make('cancel')
                ->color('danger')
                ->hidden(fn(Appointment $record) => $record->isConfirmed() || $record->isCancelled())
        ];
    }

}
