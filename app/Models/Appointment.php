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

    protected $casts = [
        'date' => 'date'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class)->orderBy('time');
    }

    public function booking()
    {
        return $this->hasOne(Booking::class);
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
        $date = $this->date;

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
