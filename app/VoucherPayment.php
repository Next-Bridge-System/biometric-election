<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoucherPayment extends Model
{
    protected $guarded = [];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
