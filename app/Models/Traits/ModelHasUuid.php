<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait ModelHasUuid{

    public static function bootModelHasUuid()
        {
            static::creating(function ($model) {
                if (empty($model->uuid)) {
                    $model->uuid = (string) Str::uuid();
                }
            });
        }
}
