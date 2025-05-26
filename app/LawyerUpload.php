<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LawyerUpload extends Model
{
    protected $guarded = [];

    public function lowerCourt()
    {
        return $this->belongsTo(LowerCourt::class);
    }
}
