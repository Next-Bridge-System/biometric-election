<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class GcHighCourt extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function scopeGcHighCourtRecord($query, $filter)
    {
        return $query
            ->leftJoin('users', 'users.id', '=', 'gc_high_courts.user_id')
            ->select(
                'gc_high_courts.id as id',
                'gc_high_courts.lawyer_name as name',
                'gc_high_courts.cnic_no as cnic',
                'gc_high_courts.father_name as father',
                'gc_high_courts.date_of_birth as dob',
                'gc_high_courts.created_at as created_at',
                'gc_high_courts.app_status as app_status',
                'gc_high_courts.app_status as app_type',
                'gc_high_courts.rcpt_no_hc as rcpt_no',
                'gc_high_courts.rcpt_date as rcpt_date',
                DB::raw('CASE WHEN gc_high_courts.id THEN "gc_hc" END AS type'),
                DB::raw('CONCAT("") as hc_exemption'),
                'gc_high_courts.enr_date_lc as enr_date_lc',
                'gc_high_courts.enr_date_hc as enr_date_hc',
            )
            ->when($filter['search_voter_member'], function ($qry) use ($filter) {
                $qry->where('gc_high_courts.voter_member_hc', $filter['search_voter_member']);
            })
            ->when($filter['search_by'] == 'name' && $filter['search_data'], function ($qry) use ($filter) {
                return $qry->where('gc_high_courts.lawyer_name', 'LIKE', '%' . $filter['search_data'] . '%');
            })
            ->when($filter['search_by_02'] == 'father_name' && $filter['search_data_02'], function ($qry) use ($filter) {
                return $qry->where('gc_high_courts.father_name', 'LIKE', '%' . $filter['search_data_02'] . '%');
            })
            ->when($filter['search_by'] == 'cnic' && $filter['search_data'], function ($qry) use ($filter) {
                $qry->where('gc_high_courts.cnic_no', 'LIKE', '%' . $filter['search_data'] . '%');
            })
            ->when($filter['search_by'] == 'hcr_no' && $filter['search_data'], function ($qry) use ($filter) {
                $qry->where('gc_high_courts.hcr_no_hc', $filter['search_data']);
            })
            ->when($filter['search_by'] == 'lic_no' && $filter['search_data'], function ($qry) use ($filter) {
                return $qry->where('gc_high_courts.license_no_hc', $filter['search_data']);
            })
            ->when($filter['search_by'] == 'bf_no' && $filter['search_data'], function ($qry) use ($filter) {
                return $qry->where('gc_high_courts.bf_no_hc', $filter['search_data']);
            })
            ->when($filter['search_by'] == 'dob' && $filter['search_data'], function ($qry) use ($filter) {
                return $qry->where('gc_high_courts.date_of_birth', $filter['search_data']);
            })
            ->when($filter['search_by'] == 'enr_date' && $filter['search_data'], function ($qry) use ($filter) {
                return $qry->where('gc_high_courts.enr_date_hc', $filter['search_data']);
            });
    }
}
