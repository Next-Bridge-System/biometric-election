<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class HighCourt extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];
    protected $appends = ['lawyer_name', 'cnic_no', 'mobile_no', 'email'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function educations()
    {
        return $this->hasMany(LawyerEducation::class, 'high_court_id')->orderBy('qualification', 'asc');
    }

    public function uploads()
    {
        return $this->hasOne(LawyerUpload::class, 'high_court_id');
    }

    public function address()
    {
        return $this->hasOne(LawyerAddress::class, 'high_court_id');
    }

    public function getLawyerNameAttribute()
    {
        $user = User::where('id', $this->user_id)->first();
        if (isset($user)) {
            $lawyer_name = $user->fname . ' ' . $user->lname;
            return $lawyer_name;
        }
    }

    public function getCnicNoAttribute()
    {
        $user = User::where('id', $this->user_id)->first();
        if (isset($user)) {
            $cnic_no = $user->cnic_no;
            return $cnic_no;
        }
    }

    public function getMobileNoAttribute()
    {
        $user = User::where('id', $this->user_id)->first();
        if (isset($user) && isset($user->phone)) {
            $mobile_no = '+92' . $user->phone;
            return $mobile_no;
        }
    }

    public function getEmailAttribute()
    {
        $user = User::where('id', $this->user_id)->first();
        if (isset($user)) {
            $email = $user->email;
            return $email;
        }
    }

    public function voterMemberHc()
    {
        return $this->belongsTo(Bar::class, 'voter_member_hc', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'high_court_id', 'id');
    }

    public function additional_notes()
    {
        return $this->hasMany(Note::class, 'application_id');
    }

    public function scopeHighCourtRecord($query, $filter)
    {
        $admin = Auth::guard('admin')->user();

        return $query
            ->join('users', 'users.id', '=', 'high_courts.user_id')
            ->select(
                'high_courts.id as id',
                'users.name as name',
                'users.cnic_no as cnic',
                'users.father_name as father',
                'users.date_of_birth as dob',
                'high_courts.created_at as created_at',
                'high_courts.app_status as app_status',
                'high_courts.app_type as app_type',
                'high_courts.rcpt_no_hc as rcpt_no',
                'high_courts.rcpt_date as rcpt_date',
                DB::raw('CASE WHEN high_courts.id THEN "hc" END AS type'),
                DB::raw('CASE WHEN high_courts.hc_exemption_at THEN "Exemption" END AS hc_exemption'),
                'high_courts.enr_date_lc as enr_date_lc',
                'high_courts.enr_date_hc as enr_date_hc',
            )
            ->when($admin->is_super == 0, function ($qry) use ($admin) {
                return $qry->where('high_courts.voter_member_hc', $admin->bar_id)->orWhere('created_by', $admin->id);
            })
            ->when($filter['search_voter_member'], function ($qry) use ($filter) {
                return $qry->where('high_courts.voter_member_hc', $filter['search_voter_member']);
            })
            ->when($filter['search_by'] == 'name' && $filter['search_data'], function ($qry) use ($filter) {
                return $qry->where('users.name', 'LIKE', '%' . $filter['search_data'] . '%');
            })
            ->when($filter['search_by_02'] == 'father_name' && $filter['search_data_02'], function ($qry) use ($filter) {
                return $qry->where('users.father_name', 'LIKE', '%' . $filter['search_data_02'] . '%');
            })
            ->when($filter['search_by'] == 'cnic' && $filter['search_data'], function ($qry) use ($filter) {
                $qry->where('users.cnic_no', 'LIKE', '%' . $filter['search_data'] . '%');
            })
            ->when($filter['search_by'] == 'hcr_no' && $filter['search_data'], function ($qry) use ($filter) {
                $qry->where('high_courts.hcr_no_hc', $filter['search_data']);
            })
            ->when($filter['search_by'] == 'lic_no' && $filter['search_data'], function ($qry) use ($filter) {
                return $qry->where('high_courts.license_no_hc', $filter['search_data']);
            })
            ->when($filter['search_by'] == 'bf_no' && $filter['search_data'], function ($qry) use ($filter) {
                return $qry->where('high_courts.bf_no_hc', $filter['search_data']);
            })
            ->when($filter['search_by'] == 'dob' && $filter['search_data'], function ($qry) use ($filter) {
                return $qry->where('users.date_of_birth', $filter['search_data']);
            })
            ->when($filter['search_by'] == 'enr_date' && $filter['search_data'], function ($qry) use ($filter) {
                return $qry->where('high_courts.enr_date_hc', $filter['search_data']);
            })
            ->when($filter['search_by'] == 'rcpt_no' && $filter['search_data'], function ($qry) use ($filter) {
                return $qry->where('high_courts.rcpt_no_hc', $filter['search_data']);
            })
            ->when(in_array($filter['slug'], ['submit', 'all']), function ($qry) {
                return $qry->where('high_courts.is_final_submitted', 1);
            })
            ->when($filter['slug'] == 'partial', function ($qry) {
                return $qry->where('high_courts.is_final_submitted', 0);
            });
    }
}
