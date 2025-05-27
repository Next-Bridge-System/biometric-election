<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'election_id',
        'seat_id',
        'name_english',
        'name_urdu',
        'image_url',
        'status',
        'created_by'
    ];

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }
}
