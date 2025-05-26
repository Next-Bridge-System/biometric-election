<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppType extends Model
{
    protected $guarded = [];

    public function scopeLcType($query)
    {
        return $query->orderBy('key', 'asc')->where('status', 1);
    }

    public function scopeHcType($query)
    {
        return $query->orderBy('key', 'asc')->where('status', 1);
    }
}
