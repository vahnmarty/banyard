<?php

namespace App\Models;

use App\Models\Traits\ModelHasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Appointment extends Model
{
    use ModelHasUuid;

    protected $guarded  = [];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function booking()
    {
        return $this->hasOne(Booking::class);
    }

    public function getDate()
    {
        $booking = $this->bookings()->first();

        return $booking->date;
    }

    public function isPending()
    {
        return is_null($this->confirmed_at);
    }

    public function isCancelled()
    {
        return filled($this->cancelled_at);
    }

    public function isConfirmed()
    {
        return filled($this->confirmed_at);
    }

    public function isExpired()
    {
        $date = $this->getDate();

        if (!$date) {
            return false; // or true depending on your logic
        }

        return Carbon::parse($date)->isBefore(date('Y-m-d'));
    }

    public function getTotal()
    {
        return $this->bookings()->sum('price');
    }
}
