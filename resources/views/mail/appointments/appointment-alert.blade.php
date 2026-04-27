<x-mail::message>
# Banyard Pickleball Court Reservation

**Date**&nbsp;
{{ $app->date->format('F d, Y (l)') }}

**Time**&nbsp;
{{ $app->bookings->map->getFormattedTime()->join(', ') }}

**Amount**&nbsp;
₱{{ number_format($app->getTotal(), 2) }}

**Booked by**&nbsp;
{{ $app->name . " ({$app->email})" }}


<x-mail::button :url="$url">
    View Appointment
</x-mail::button>



{{ config('app.name') }}
</x-mail::message>
