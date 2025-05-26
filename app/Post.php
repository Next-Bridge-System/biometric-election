<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];

    public function postNotes()
    {
        return $this->hasMany(Note::class, 'application_id')->where('application_type', 'POST');
    }

    public function postTypeRelation()
    {
        return $this->belongsTo(PostType::class, 'post_type', 'id');
    }
}
