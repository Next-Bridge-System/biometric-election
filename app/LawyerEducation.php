<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class LawyerEducation extends Model implements Auditable
{    
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function university() {
        return $this->belongsTo(University::class,'university_id');
    }

    public function lowerCourt()
    {
        return $this->belongsTo(LowerCourt::class);
    }
}
