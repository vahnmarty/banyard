<?php

namespace App\Models;

use App\Models\Traits\ModelHasUuid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use ModelHasUuid;

    protected $guarded  = [];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
