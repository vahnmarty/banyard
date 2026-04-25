<?php

namespace App\Models;

use App\Models\Traits\ModelHasUuid;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use ModelHasUuid;

    protected $guarded = [];


    public function isConfirmed()
    {
        return filled($this->confirmed_at);
    }
}
