<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppStatus extends Model
{
    protected $guarded = [];

    public function scopeLcStatus($query)
    {
        return $query->orderBy('key', 'asc')->where('status', 1);
    }

    public function scopeHcStatus($query)
    {
        return $query->orderBy('key', 'asc')->where('status', 1);
    }
}
