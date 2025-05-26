<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PrintSecureCard extends Model
{
    protected $guarded = [];

    public function applications()
    {
        return $this->belongsTo(Application::class,'application_id');
    }

    public function application()
    {
        return $this->belongsTo(Application::class,'application_id');
    }

    public function getCreatedAtAttribute( $value ) {
        return (new Carbon($value))->format('d-m-Y h:i A');
    }

    public function getPrintedAtAttribute( $value ) {
        return (new Carbon($value))->format('d-m-Y h:i A');
    }

    public function getCardStatusAttribute( $value ) {
        switch ($value) {
            case $value == 1:
                $card_status = 'Pending';
                break;
            case $value == 2:
                $card_status = 'Printing';
                break;
            case $value == 3:
                $card_status = 'Dispatched';
                break;
            case $value == 4:
                $card_status = 'By Hand';
                break;
            case $value == 5:
                $card_status = 'Done';
                break;
            default:
                break;
        }
        return $card_status;
    }

}
