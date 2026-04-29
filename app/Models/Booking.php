<?php

namespace App\Models;

use App\Models\Traits\ModelHasUuid;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use ModelHasUuid;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date'
    ];


    public function isConfirmed()
    {
        return filled($this->confirmed_at);
    }

    public function isCancelled()
    {
        return filled($this->cancelled_at);
    }

    public function isActive()
    {
        return $this->isConfirmed() && !$this->isCancelled();
    }

    public function scopeActive($query){
        return $query->whereNotNull('confirmed_at')->whereNull('cancelled_at');
    }

    public function getFormattedTime()
    {
        return Carbon::createFromFormat('H:i:s', $this->time)->format('g:i A');
    }

    public function getFormattedTimeAttribute()
    {
        return $this->getFormattedTime();
    }

    public function scopeConfirmed($query)
    {
        return $query->whereNotNull('confirmed_at');
    }
}
