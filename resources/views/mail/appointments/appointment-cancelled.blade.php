<x-mail::message>
# Banyard Pickleball Court Reservation

We regret to inform you that your booking has been Cancelled.

**Date**&nbsp;
{{ $app->date->format('F d, Y (l)') }}

**Time**&nbsp;
{{ $app->bookings->map->getFormattedTime()->join(', ') }}

**Amount**&nbsp;
₱{{ number_format($app->getTotal(), 2) }}

**Booked by**&nbsp;
{{ $app->name . " ({$app->email})" }}


---

For more updates, follow us:

Facebook:  [@banyard.pb](https://facebook.com/banyard.pb)
Instagram:  [@banyard.pb](https://instagram.com/banyard.pb)
Reclub:  [@banyard-pickleball](https://reclub.co/clubs/@banyard-pickleball)

Regards,<br>
{{ config('app.name') }}
</x-mail::message>
