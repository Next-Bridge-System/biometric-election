<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use OwenIt\Auditing\Contracts\Auditable;

class Payment extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function certificate()
    {
        return $this->belongsTo(Certificate::class, 'certificate_id', 'id');
    }

    // public function setPaidDateAttribute($value)
    // {
    //     $this->attributes['paid_date'] = (new Carbon($value))->format('d-m-Y');
    // }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return (new Carbon($value))->format('d-m-Y');
    }

    public function getUpdatedAtAttribute($value)
    {
        return (new Carbon($value))->format('d-m-Y');
    }

    public function lowerCourtApplication()
    {
        return $this->belongsTo(LowerCourt::class, 'lower_court_id', 'id');
    }
}
