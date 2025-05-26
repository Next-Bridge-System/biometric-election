<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class LowerCourt extends Model implements Auditable
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
        return $this->hasMany(LawyerEducation::class, 'lower_court_id')->orderBy('qualification', 'asc');
    }

    public function uploads()
    {
        return $this->hasOne(LawyerUpload::class, 'lower_court_id');
    }

    public function address()
    {
        return $this->hasOne(LawyerAddress::class, 'lower_court_id');
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

    public function voterMemberLc()
    {
        return $this->belongsTo(Bar::class, 'voter_member_lc', 'id');
    }

    public function additional_notes()
    {
        return $this->hasMany(Note::class, 'application_id');
    }

    public function srlBar()
    {
        return $this->belongsTo(Bar::class, 'srl_bar_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'lower_court_id', 'id');
    }

    public function assignMembers()
    {
        return $this->hasMany(AssignMember::class, 'lower_court_id', 'id');
    }

    public function groupInsurances()
    {
        return $this->hasMany(GroupInsurance::class, 'lc_id', 'id');
    }

    public function scopeLowerCourtRecord($query, $filter)
    {
        $admin = Auth::guard('admin')->user();

        return $query
            ->join('users', 'users.id', '=', 'lower_courts.user_id')
            ->where('is_final_submitted', 1)
            ->select(
                'lower_courts.id as id',
                'users.name as name',
                'lower_courts.cnic_no as cnic',
                'lower_courts.father_name as father',
                'lower_courts.date_of_birth as dob',
                'lower_courts.created_at as created_at',
                'lower_courts.app_status as app_status',
                'lower_courts.app_type as app_type',
                DB::raw('CASE WHEN lower_courts.id THEN "lc" END AS type'),
                DB::raw('CASE WHEN lower_courts.is_exemption = 1 THEN "Exemption" END AS lc_exemption')
            )
            ->when($admin->is_super == 0, function ($qry) use ($admin) {
                return $qry->where('voter_member_lc', $admin->bar_id)->orWhere('created_by', $admin->id);
            })
            ->when($filter['search_voter_member'], function ($qry) use ($filter) {
                return $qry->where('lower_courts.voter_member_lc', $filter['search_voter_member']);
            })
            ->when($filter['search_by'] == 'name' && $filter['search_data'], function ($qry) use ($filter) {
                return $qry->where('users.name', 'LIKE', '%' . $filter['search_data'] . '%');
            })
            ->when($filter['search_by'] == 'cnic' && $filter['search_data'], function ($qry) use ($filter) {
                return $qry->where('lower_courts.cnic_no', 'LIKE', '%' . $filter['search_data'] . '%');
            })
            ->when($filter['search_by'] == 'dob' && $filter['search_data'], function ($qry) use ($filter) {
                return $qry->where('lower_courts.date_of_birth', getDateFormat($filter['search_data']));
            })
            ->when($filter['search_by_02'] == 'father_name' && $filter['search_data_02'], function ($qry) use ($filter) {
                return $qry->where('lower_courts.father_name', 'LIKE', '%' . $filter['search_data_02'] . '%');
            })
            ->when($filter['search_by'] == 'reg_no' && $filter['search_data'], function ($qry) use ($filter) {
                $qry->where('lower_courts.reg_no_lc', $filter['search_data']);
            })
            ->when($filter['search_by'] == 'lic_no' && $filter['search_data'], function ($qry) use ($filter) {
                return $qry->where('lower_courts.license_no_lc', $filter['search_data']);
            })
            ->when($filter['search_by'] == 'bf_no' && $filter['search_data'], function ($qry) use ($filter) {
                return $qry->where('lower_courts.bf_no_lc', $filter['search_data']);
            })
            ->when($filter['search_by'] == 'enr_date' && $filter['search_data'], function ($qry) use ($filter) {
                return $qry->where('lower_courts.lc_date', $filter['search_data']);
            })
            ->when($filter['search_by'] == 'rcpt_no' && $filter['search_data'], function ($qry) use ($filter) {
                return $qry->where('lower_courts.rcpt_no_lc', $filter['search_data']);
            });
    }
}
