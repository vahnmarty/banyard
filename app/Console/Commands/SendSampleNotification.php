<?php

namespace App\Console\Commands;

use App\Mail\NewAppointmentAlert;
use App\Models\Appointment;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

#[Signature('send:sample {app}')]
#[Description('Send Sample Notification')]
class SendSampleNotification extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $appId = $this->argument('app');

        $app = Appointment::find($appId);

        Mail::to(config('mail.to.address'), config('mail.to.name'))->send(new NewAppointmentAlert($app));
    }
}
