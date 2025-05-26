<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class Complaint extends Model
{
    use InteractsWithMedia;

    protected $guarded = [];

    public function attachments()
    {
        return $this->hasMany(ComplaintFile::class);
    }

    public function notices()
    {
        return $this->hasMany(ComplaintNotice::class, 'complaint_id', 'id')->where('notice_type','notice');
    }

    public function hearings()
    {
        return $this->hasMany(ComplaintNotice::class, 'complaint_id', 'id')->where('notice_type','hearing');
    }

    public function complaintType()
    {
        return $this->belongsTo(ComplaintType::class, 'complaint_type_id', 'id');
    }

    public function complaintStatus()
    {
        return $this->belongsTo(ComplaintStatus::class, 'complaint_status_id', 'id');
    }
}
