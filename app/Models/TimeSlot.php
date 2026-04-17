<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TimeSlot extends Model
{
    protected $guarded = [];

    public $appends = [
        'formatted_time'
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function getFormattedTimeAttribute()
    {
        return Carbon::createFromFormat('H:i:s', $this->time)->format('g:i A');
    }
}
