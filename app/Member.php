<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $guarded = [];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function tehsil()
    {
        return $this->belongsTo(Tehsil::class);
    }

    public function bar()
    {
        return $this->belongsTo(Bar::class);
    }

    public function group()
    {
        return $this->belongsTo(MemberGroup::class, 'member_group_id', 'id');
    }
}
