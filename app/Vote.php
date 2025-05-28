<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = ['election_id', 'seat_id', 'candidate_id'];

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
