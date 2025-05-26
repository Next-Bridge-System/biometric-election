<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements Auditable, HasMedia
{
    use Notifiable;
    use \OwenIt\Auditing\Auditable;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function lc()
    {
        return $this->hasOne(LowerCourt::class, 'user_id', 'id');
    }

    public function hc()
    {
        return $this->hasOne(HighCourt::class, 'user_id', 'id');
    }

    public function intimation()
    {
        return $this->hasOne(Application::class, 'user_id', 'id');
    }

    public function partialLc()
    {
        return $this->hasOne(LowerCourt::class, 'user_id', 'id')->where('is_final_submitted', 0);
    }

    public function partialIntimation()
    {
        return $this->hasOne(Application::class, 'user_id', 'id')->where('is_accepted', 0);
    }

    public function scopeVoterMemberReport($query, $filter)
    {
        return $query->when($filter['search_user_type'] == 'lc', function ($parent) use ($filter) {
            return $parent->whereIn('users.register_as', ['lc', 'gc_lc'])
                ->leftJoin('lower_courts', 'users.id', '=', 'lower_courts.user_id')
                ->leftJoin('gc_lower_courts', 'users.id', '=', 'gc_lower_courts.user_id')
                ->leftJoin('lawyer_addresses', 'lower_courts.id', '=', 'lawyer_addresses.lower_court_id')
                ->select(
                    DB::raw('users.id'),
                    DB::raw('CASE 
                            WHEN users.register_as = "lc" THEN users.name
                            WHEN users.register_as = "gc_lc" THEN gc_lower_courts.lawyer_name
                            END AS name'),
                    DB::raw('users.cnic_no'),
                    DB::raw('CONCAT("0", users.phone) as phone'),
                    DB::raw('users.register_as'),
                    DB::raw('lower_courts.user_id'),
                    DB::raw('gc_lower_courts.user_id'),
                    DB::raw('CASE 
                            WHEN users.register_as = "lc" THEN lower_courts.lc_date
                            WHEN users.register_as = "gc_lc" THEN gc_lower_courts.date_of_enrollment_lc
                            END AS enr_date'),
                    DB::raw('CASE 
                            WHEN users.register_as = "lc" THEN lower_courts.father_name
                            WHEN users.register_as = "gc_lc" THEN gc_lower_courts.father_name
                            END AS father_name'),
                    DB::raw('CASE 
                            WHEN users.register_as = "lc" THEN CONCAT(lawyer_addresses.ha_house_no,", ",lawyer_addresses.ha_str_address,", ",lawyer_addresses.ha_city)
                            WHEN users.register_as = "gc_lc" THEN gc_lower_courts.address_1
                            END AS address'),
                )
                ->whereNotNull(
                    DB::raw('CASE 
                            WHEN users.register_as = "lc" THEN lower_courts.lc_date
                            WHEN users.register_as = "gc_lc" THEN gc_lower_courts.date_of_enrollment_lc
                            END')
                )
                ->where(function ($query) {
                    $query->where(function ($q) {
                        $q->where('users.register_as', 'lc')->where('lower_courts.app_type', '!=', 4);
                    })->orWhere(function ($q) {
                        $q->where('users.register_as', 'gc_lc')->where('gc_lower_courts.app_type', '!=', 4);
                    });
                })
                ->where(function ($query) {
                    $query->where(function ($q) {
                        $q->where('users.register_as', 'gc_lc')->where('users.gc_status', 'approved');
                    })->orWhere('users.register_as', 'lc');
                })
                ->when($filter['search_voter_member'], function ($qry) use ($filter) {
                    $qry->where(function ($q) use ($filter) {
                        $q->where('lower_courts.voter_member_lc', $filter['search_voter_member']);
                        $q->orWhere('gc_lower_courts.voter_member_lc', $filter['search_voter_member']);
                    });
                })
                ->when($filter['search_start_date'] && $filter['search_end_date'], function ($qry) use ($filter) {
                    $qry->where(function ($q) use ($filter) {
                        $q->where('lower_courts.lc_date', '>=', $filter['search_start_date']);
                        $q->where('lower_courts.lc_date', '<=', $filter['search_end_date']);
                    });
                    $qry->orWhere(function ($q) use ($filter) {
                        $q->where('gc_lower_courts.date_of_enrollment_lc', '>=', $filter['search_start_date']);
                        $q->where('gc_lower_courts.date_of_enrollment_lc', '<=', $filter['search_end_date']);
                    });
                });
        })->when($filter['search_user_type'] == 'hc', function ($parent) use ($filter) {
            $parent->whereIn('users.register_as', ['hc', 'gc_hc'])
                ->leftJoin('high_courts', 'users.id', '=', 'high_courts.user_id')
                ->leftJoin('gc_high_courts', 'users.id', '=', 'gc_high_courts.user_id')
                ->leftJoin('lawyer_addresses', 'high_courts.id', '=', 'lawyer_addresses.high_court_id')
                ->select(
                    DB::raw('users.id'),
                    DB::raw('CASE 
                            WHEN users.register_as = "hc" THEN users.name
                            WHEN users.register_as = "gc_hc" THEN gc_high_courts.lawyer_name
                            END AS name'),
                    DB::raw('users.cnic_no'),
                    DB::raw('users.register_as'),
                    DB::raw('CONCAT("0", users.phone) as phone'),
                    DB::raw('high_courts.user_id'),
                    DB::raw('gc_high_courts.user_id'),
                    DB::raw('CASE 
                            WHEN users.register_as = "hc" THEN high_courts.enr_date_lc
                            WHEN users.register_as = "gc_hc" THEN gc_high_courts.enr_date_lc
                            END AS enr_date_lc'),
                    DB::raw('CASE 
                            WHEN users.register_as = "hc" THEN high_courts.enr_date_hc
                            WHEN users.register_as = "gc_hc" THEN gc_high_courts.enr_date_hc
                            END AS enr_date'),
                    DB::raw('CASE 
                            WHEN users.register_as = "hc" THEN high_courts.father_name
                            WHEN users.register_as = "gc_hc" THEN gc_high_courts.father_name
                            END AS father_name'),
                    DB::raw('CASE 
                            WHEN users.register_as = "hc" THEN CONCAT(lawyer_addresses.ha_house_no,", ",lawyer_addresses.ha_str_address,", ",lawyer_addresses.ha_city)
                            WHEN users.register_as = "gc_hc" THEN gc_high_courts.address_1
                            END AS address'),
                )
                ->whereNotNull(
                    DB::raw('CASE 
                            WHEN users.register_as = "hc" THEN high_courts.enr_date_hc
                            WHEN users.register_as = "gc_hc" THEN gc_high_courts.enr_date_hc
                            END')
                )
                ->where(function ($query) {
                    $query->where(function ($q) {
                        $q->where('users.register_as', 'gc_hc')->where('users.gc_status', 'approved');
                    })->orWhere('users.register_as', 'hc');
                })
                ->when($filter['search_voter_member'], function ($qry) use ($filter) {
                    $qry->where(function ($q) use ($filter) {
                        $q->where('high_courts.voter_member_hc', $filter['search_voter_member']);
                        $q->orWhere('gc_high_courts.voter_member_hc', $filter['search_voter_member']);
                    });
                })
                ->when($filter['search_start_date'] && $filter['search_end_date'], function ($qry) use ($filter) {
                    $qry->where(function ($q) use ($filter) {
                        $q->where('high_courts.enr_date_hc', '>=', $filter['search_start_date']);
                        $q->where('high_courts.enr_date_hc', '<=', $filter['search_end_date']);
                    });
                    $qry->orWhere(function ($q) use ($filter) {
                        $q->where('gc_high_courts.enr_date_hc', '>=', $filter['search_start_date']);
                        $q->where('gc_high_courts.enr_date_hc', '<=', $filter['search_end_date']);
                    });
                });
        });
    }
}
