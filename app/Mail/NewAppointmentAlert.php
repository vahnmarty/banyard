<?php

namespace App\Mail;

use App\Filament\Resources\Appointments\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewAppointmentAlert extends Mailable
{
    use Queueable, SerializesModels;

    public Appointment $app;

    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $app)
    {
        $this->app = $app;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Appointment Alert',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.appointments.appointment-alert',
            with: [
                'app' => $this->app,
                'url' => $this->getUrl(),
            ],
        );
    }

    public function getUrl()
    {
        return AppointmentResource::getUrl('view', ['record' => $this->app]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
