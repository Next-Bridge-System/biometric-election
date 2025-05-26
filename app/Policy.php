<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    protected $guarded = [];

    public function policyFees()
    {
        return $this->hasMany(PolicyFee::class);
    }

}
