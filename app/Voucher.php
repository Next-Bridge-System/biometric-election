<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $guarded = [];

    public function payments()
    {
        return $this->hasMany(VoucherPayment::class);
    }

    public function bar()
    {
        return $this->belongsTo(Bar::class, 'station', 'id');
    }
}
