<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VPP extends Model
{
    protected $guarded = [];

    protected $table = 'vpp';

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function getVppDeliveredAttribute( $value ) {
        if ($value == 1) {
            return 'Yes';
        } else {
            return 'No';
        }
    }

    public function getVppReturnedAttribute( $value ) {
        if ($value == 1) {
            return 'Yes';
        } else {
            return 'No';
        }
    }

    public function getVppDuplicateAttribute( $value ) {
        if ($value == 1) {
            return 'Yes';
        } else {
            return 'No';
        }
    }

}
