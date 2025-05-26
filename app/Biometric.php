<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Biometric extends Model
{
    protected $guarded = [];

    public function getCreatedAtAttribute($value)
    {
        return (new Carbon($value))->format('d-m-Y h:i a');
    }

    public function getUpdatedAtAttribute($value)
    {
        return (new Carbon($value))->format('d-m-Y h:i a');
    }
}
