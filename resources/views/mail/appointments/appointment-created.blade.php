<x-mail::message>
# Banyard Pickleball Court Reservation

**Date**&nbsp;
{{ $app->getDate()->format('F d, Y (l)') }}

**Time**&nbsp;
{{ $app->bookings->map->getFormattedTime()->join(', ') }}

**Amount**&nbsp;
₱{{ number_format($app->getTotal(), 2) }}

**Booked by**&nbsp;
{{ $app->name . " ({$app->email})" }}


---

**Your appointment is pending for confirmation.**
Please wait for approval. We'll notify you once it's confirmed.

---

<x-mail::button url="{{ route('schedule') }}">
View Schedule
</x-mail::button>

**Helpful reminders:**
- Please arrive at least **30 minutes before your schedule**
- Bring proper sports attire
- Be ready to present your booking if needed

---

For more updates, follow us:

Facebook:  [@banyard.pb](https://facebook.com/banyard.pb)
Instagram:  [@banyard.pb](https://instagram.com/banyard.pb)
Reclub:  [@banyard-pickleball](https://reclub.co/clubs/@banyard-pickleball)

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
