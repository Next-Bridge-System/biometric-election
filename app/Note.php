<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Note extends Model
{
    protected $guarded = [];

    public function getCreatedAtAttribute( $value ) {
        return $this->attributes['created_at'] = (new Carbon($value))->format('d-m-Y h:i:s a');
    }
}
