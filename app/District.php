<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $guarded = [];

    public function tehsils()
    {
        return $this->hasMany(Tehsil::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
