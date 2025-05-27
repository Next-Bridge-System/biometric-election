<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = ['election_id', 'name_english', 'name_urdu', 'image_url', 'created_by', 'status'];

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
