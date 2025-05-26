<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class Application extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function educations()
    {
        return $this->hasMany(LawyerEducation::class, 'application_id')->orderBy('qualification', 'asc');
    }

    public function uploads()
    {
        return $this->hasOne(LawyerUpload::class, 'application_id');
    }

    public function address()
    {
        return $this->hasOne(LawyerAddress::class, 'application_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function tehsil()
    {
        return $this->belongsTo(Tehsil::class);
    }

    public function barAssociation()
    {
        return $this->belongsTo(Bar::class, 'bar_association');
    }

    public function srlBar()
    {
        return $this->belongsTo(Bar::class, 'srl_bar_name');
    }

    public function printCertificate()
    {
        return $this->hasOne(PrintCertificate::class, 'application_id');
    }

    public function paymentVoucher()
    {
        return $this->hasOne(Payment::class, 'application_id');
    }

    public function paymentVouchers()
    {
        return $this->hasMany(Payment::class, 'application_id');
    }

    public function unPaidPayment()
    {
        return $this->hasOne(Payment::class, 'application_id')->where('application_type', $this->application_type)->where('payment_status', 0);
    }

    public function paidPaymentCount()
    {
        return $this->hasMany(Payment::class, 'application_id')->where('application_type', $this->application_type)->where('payment_status', 1)->count();
    }

    public function voterMemberLc()
    {
        return $this->belongsTo(Bar::class, 'voter_member_lc');
    }

    public function voterMemberHc()
    {
        return $this->belongsTo(Bar::class, 'voter_member_hc');
    }

    public function additional_notes()
    {
        return $this->hasMany(Note::class, 'application_id');
    }

    public function getDateOfBirthAttribute($value)
    {
        if (isset($value)) {
            return (new Carbon($value))->format('d-m-Y');
        }
    }

    public function getDateOfEnrollmentLcAttribute($value)
    {
        return (new Carbon($value))->format('d-m-Y');
    }

    public function getDateOfEnrollmentHcAttribute($value)
    {
        return (new Carbon($value))->format('d-m-Y');
    }

    public function prints()
    {
        return $this->hasMany(PrintSecureCard::class);
    }

    public function vppost()
    {
        return $this->hasOne(VPP::class);
    }

    public function fingerprints()
    {
        return $this->hasMany(Biometric::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fir()
    {
        return $this->hasOne(PoliceVerification::class);
    }

    // DATABASE VIEWS
    public function scopeIntimationApplication($query)
    {
        return $query->select('*')->where('application_type', 6)->orderBy('id', 'desc')->where('is_accepted', 1);
    }

    public function scopeIntimationPartialApplication($query)
    {
        return $query->select('*')->where('application_type', 6)->orderBy('id', 'desc')->where('is_accepted', 0);
    }

    public function scopeIntimationAllApplication($query)
    {
        return $query->select('*')->where('application_type', 6)->orderBy('id', 'desc');
    }

    // 22-12-2024
    public function scopeIntimationReport($query, $filter)
    {
        return $query
            ->select(
                'app.id as app_id',
                'app.application_token_no as app_token_no',
                'app.intimation_start_date as intimation_start_date',
                'app.so_of as app_father_husband',
                'app.rcpt_no as app_rcpt_no',
                'app.rcpt_date as app_rcpt_date',

                DB::raw('REPLACE(b.name, " Bar Association", "") as bar_name'),

                'u.name as user_name',
                'u.cnic_no as user_cnic',
                DB::raw('CONCAT("0", u.phone) as user_phone'),

                DB::raw('CONCAT(COALESCE(app_status.value, ""), ",",
                CASE 
                    WHEN p.payment_status = 1 THEN "Paid"
                    WHEN p.payment_status = 0 THEN "Unpaid"
                    ELSE "Unpaid"
                END) AS status')
            )
            ->from('applications as app')
            ->join('bars as b', 'b.id', 'app.bar_association')
            ->join('users as u', 'u.id', 'app.user_id')
            ->leftJoin('payments as p', 'p.application_id', 'app.id')
            ->join('app_statuses as app_status', 'app_status.id', 'app.application_status')
            ->join('app_types as app_type', 'app_type.id', 'app.application_type')

            ->when($filter['bar_id'], function ($qry) use ($filter) {
                $qry->where('app.bar_association', $filter['bar_id']);
            })
            ->when($filter['application_date'], function ($qry) use ($filter) {

                $date_type = $filter['application_date_type'];
                $date_key = $filter['application_date'];
                $date_range = $filter['custom_date_range_input'] ?? NULL;

                $from_date = $to_date = NULL;
                if ($date_range) {
                    $date_range = explode('-', $date_range);
                    $from_date = date("Y-m-d", strtotime($date_range[0]));
                    $to_date = date("Y-m-d", strtotime($date_range[1]));
                }

                switch ($date_key) {
                    case 'today':
                        $qry->whereDate('app.' . $date_type, Carbon::today());
                        break;

                    case 'yesterday':
                        $qry->whereDate('app.' . $date_type, Carbon::yesterday());
                        break;

                    case 'last_7_days':
                        $qry->whereDate('app.' . $date_type, Carbon::now()->subDays(7));
                        break;

                    case 'last_30_days':
                        $qry->whereDate('app.' . $date_type, Carbon::now()->subDays(30));
                        break;

                    case 'date_range':
                        $qry->whereDate('app.' . $date_type, '>=', $from_date);
                        $qry->whereDate('app.' . $date_type, '<=', $to_date);
                        break;
                }
            })
            ->where('app.application_type', 6)
            ->where('app.is_accepted', 1)
            ->orderBy('app.rcpt_no', 'asc');
    }

    public function scopeVppApplication($query)
    {
        return $query->select('id', 'application_token_no', 'advocates_name', 'last_name', 'reg_no_lc', 'voter_member_lc', 'so_of', 'date_of_enrollment_lc')->orderBy('reg_no_lc', 'asc')->whereIn('application_type', ['1', '2', '3', '4']);
    }
}
