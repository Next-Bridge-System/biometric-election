<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class GcLowerCourt extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $guarded = [];

    public function scopeGcLowerCourtRecord($query, $filter)
    {
        return $query
            ->leftJoin('users', 'users.id', '=', 'gc_lower_courts.user_id')
            ->join('app_statuses', 'app_statuses.key', '=', 'gc_lower_courts.app_status')
            ->select(
                'gc_lower_courts.id as id',
                'gc_lower_courts.lawyer_name as name',
                'gc_lower_courts.cnic_no as cnic',
                'gc_lower_courts.father_name as father',
                'gc_lower_courts.date_of_birth as dob',
                'gc_lower_courts.created_at as created_at',
                'gc_lower_courts.app_status as app_status',
                'gc_lower_courts.app_type as app_type',
                DB::raw('CASE WHEN gc_lower_courts.id THEN "gc_lc" END AS type'),
                DB::raw('CONCAT("") as lc_exemption'),
            )
            ->when($filter['search_voter_member'], function ($qry) use ($filter) {
                $qry->where('gc_lower_courts.voter_member_lc', $filter['search_voter_member']);
            })
            ->when($filter['search_by'] == 'name' && $filter['search_data'], function ($qry) use ($filter) {
                return $qry->where('gc_lower_courts.lawyer_name', 'LIKE', '%' . $filter['search_data'] . '%');
            })
            ->when($filter['search_by'] == 'cnic' && $filter['search_data'], function ($qry) use ($filter) {
                return $qry->where('gc_lower_courts.cnic_no', 'LIKE', '%' . $filter['search_data'] . '%');
            })
            ->when($filter['search_by_02'] == 'father_name' && $filter['search_data_02'], function ($qry) use ($filter) {
                return $qry->where('gc_lower_courts.father_name', 'LIKE', '%' . $filter['search_data_02'] . '%');
            })
            ->when($filter['search_by'] == 'lic_no' && $filter['search_data'], function ($qry) use ($filter) {
                return $qry->where('gc_lower_courts.license_no_lc', $filter['search_data']);
            })
            ->when($filter['search_by'] == 'reg_no' && $filter['search_data'], function ($qry) use ($filter) {
                $qry->where('gc_lower_courts.reg_no_lc', $filter['search_data']);
            })
            ->when($filter['search_by'] == 'bf_no' && $filter['search_data'], function ($qry) use ($filter) {
                return $qry->where('gc_lower_courts.bf_no_lc', $filter['search_data']);
            })
            ->when($filter['search_by'] == 'dob' && $filter['search_data'], function ($qry) use ($filter) {
                return $qry->where('gc_lower_courts.date_of_birth', $filter['search_data']);
            })
            ->when($filter['search_by'] == 'enr_date' && $filter['search_data'], function ($qry) use ($filter) {
                return $qry->where('gc_lower_courts.enr_date_lc', $filter['search_data']);
            });
    }
}
