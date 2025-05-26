<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LawyerAddress extends Model
{
    protected $guarded = [];

    public function tehsil()
    {
        return $this->belongsTo(Tehsil::class,'ha_tehsil_id');
    }

    public function lowerCourt()
    {
        return $this->belongsTo(LowerCourt::class);
    }
}
