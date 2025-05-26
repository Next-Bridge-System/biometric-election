<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use OwenIt\Auditing\Contracts\Auditable;

class LawyerRequest extends Model implements HasMedia, Auditable
{
    use InteractsWithMedia;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function lawyer_request_category()
    {
        return $this->belongsTo(LawyerRequestCategory::class);
    }

    public function lawyer_request_sub_category()
    {
        return $this->belongsTo(LawyerRequestSubCategory::class);
    }

    public function bar()
    {
        return $this->belongsTo(Bar::class);
    }
}
