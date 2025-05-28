<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    protected $fillable = ['title_english', 'title_urdu', 'created_by', 'status'];

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
