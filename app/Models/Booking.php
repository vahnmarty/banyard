<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = [];

    public function isConfirmed()
    {
        return filled($this->confirmed_at);
    }
}
