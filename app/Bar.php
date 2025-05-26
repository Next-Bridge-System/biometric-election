<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bar extends Model
{
    protected $guarded = [];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    // EXPORT DATA FORMATS
    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }

}
