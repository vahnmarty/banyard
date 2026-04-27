<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentConfirmed extends Mailable
{
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
            subject: 'Booking Confirmed: Banyard Pickleball',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.appointments.appointment-confirmed',
            with: [
                'app' => $this->app,
                'url' => $this->getUrl(),
            ],
        );
    }

    public function getUrl()
    {
        return route('view-appointment', $this->app->uuid);
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
