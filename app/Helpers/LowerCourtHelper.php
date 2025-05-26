<?php

use App\AppStatus;
use App\GcLowerCourt;
use App\HighCourt;
use App\LawyerEducation;
use App\LawyerUpload;
use App\LowerCourt;
use App\Payment;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorDynamicHTML;


if (!function_exists('getLcStatuses')) {
    function getLcStatuses($id)
    {
        try {
            $response = [];
            $lc = LowerCourt::find($id);
            $intimation_app_no = $lc->user->intimation->application_token_no ?? '';
            $intimation_rcpt_no = $lc->user->intimation->rcpt_no ?? '';
            $intimation_rcpt_date = $lc->user->intimation->rcpt_date ?? '';

            if ($lc->is_moved_from_intimation == 1) {
                $response['res'] = '<span class="badge badge-primary m-1">Moved From Intimation - ' . $intimation_app_no . ' / ' . getDateFormat($intimation_rcpt_date) . ' / ' . $intimation_rcpt_no . '</span>';
            } else if ($lc->is_excel) {
                $response['res'] = '<span class="badge badge-primary m-1">Excel Import</span>';
            } else {
                $response['res'] = '<span class="badge badge-primary m-1">Direct Entry</span>';
            }

            if ($lc->is_exemption == 1) {
                $response['res'] .= '<span class="badge badge-info m-1">Exemption</span>';
            }

            return $response;
        } catch (\Throwable $th) {
            //
        }
    }
}

if (!function_exists('getLcVoucherName')) {
    function getLcVoucherName($type)
    {
        $name = NULL;
        switch ($type) {
            case $type == 1:
                $name = 'PAKISTAN B.C';
                break;
            case $type == 2:
                $name = 'ENROLMENT FEE';
                break;
            case $type == 3:
                $name = 'GROUP INSURANCE';
                break;
            case $type == 4:
                $name = 'BENEVOLENT FUND';
                break;
            case $type == 5:
                $name = 'PLJ';
                break;
            case $type == 6:
                $name = 'LAWYER WELFARE FUND';
                break;
            default:
                break;
        }
        return $name;
    }
}

if (!function_exists('getLcVoucherNo')) {
    function getLcVoucherNo($id)
    {
        $vocuherNo = 2000000000000 + $id;
        return $vocuherNo;
    }
}

if (!function_exists('getLcVchAmount')) {
    function getLcVchAmount($id, $voucher_type = 1)
    {
        $application = App\LowerCourt::find($id);
        $age_cal_date = isset($application->final_submitted_at) ? $application->final_submitted_at : $application->created_at;
        $age = Carbon::parse($application->date_of_birth)->diff($age_cal_date)->format('%y.%m%d');

        $amount = 0;

        if ($age >= 21 && $age <= 26) {

            // PAKISTAN B.C
            if ($voucher_type == 1) {
                $amount = 100;
            }

            // ENROLMENT FEE
            if ($voucher_type == 2) {
                $amount = getGeneralFund($age, $application)['total'];
            }

            // GROUP INSURANCE
            if ($voucher_type == 3) {
                $amount = 2000;
            }

            // BENEVOLENT FUND
            if ($voucher_type == 4) {
                $amount = 10000;
                if ($application->bf_plan == 2) {
                    $amount = $amount * 2;
                }
            }

            // PLJ
            if ($voucher_type == 5) {
                if ($application->plj_br == 0) {
                    $amount = 3200;
                }
            }

            // LAWYER WELFARE FUND
            if ($voucher_type == 6) {
                $amount = 1000;
            }
        }

        if ($age > 26 && $age <= 30) {

            // PAKISTAN B.C
            if ($voucher_type == 1) {
                $amount = 375;
            }

            // ENROLMENT FEE
            if ($voucher_type == 2) {
                $amount = getGeneralFund($age, $application)['total'];
            }

            // GROUP INSURANCE
            if ($voucher_type == 3) {
                $amount = 2000;
            }

            // BENEVOLENT FUND
            if ($voucher_type == 4) {
                $amount = 15000;
                if ($application->bf_plan == 2) {
                    $amount = $amount * 2;
                }
            }

            // PLJ
            if ($voucher_type == 5) {
                if ($application->plj_br == 0) {
                    $amount = 4000;
                }
            }

            // LAWYER WELFARE FUND
            if ($voucher_type == 6) {
                $amount = 2000;
            }
        }

        if ($age > 30 && $age <= 35) {

            // PAKISTAN B.C
            if ($voucher_type == 1) {
                $amount = 450;
            }

            // ENROLMENT FEE
            if ($voucher_type == 2) {
                $amount = getGeneralFund($age, $application)['total'];
            }

            // GROUP INSURANCE
            if ($voucher_type == 3) {
                $amount = 2000;
            }

            // BENEVOLENT FUND
            if ($voucher_type == 4) {
                $amount = 25000;
                if ($application->bf_plan == 2) {
                    $amount = $amount * 2;
                }
            }

            // PLJ
            if ($voucher_type == 5) {
                if ($application->plj_br == 0) {
                    $amount = 4500;
                }
            }

            // LAWYER WELFARE FUND
            if ($voucher_type == 6) {
                $amount = 2000;
            }
        }

        if ($age > 35 && $age <= 40) {

            // PAKISTAN B.C
            if ($voucher_type == 1) {
                $amount = 600;
            }

            // ENROLMENT FEE
            if ($voucher_type == 2) {
                $amount = getGeneralFund($age, $application)['total'];
            }

            // GROUP INSURANCE
            if ($voucher_type == 3) {
                $amount = 5000;
            }

            // BENEVOLENT FUND
            if ($voucher_type == 4) {
                $amount = 40000;
                if ($application->bf_plan == 2) {
                    $amount = $amount * 2;
                }
            }

            // PLJ
            if ($voucher_type == 5) {
                if ($application->plj_br == 0) {
                    $amount = 5000;
                }
            }

            // LAWYER WELFARE FUND
            if ($voucher_type == 6) {
                $amount = 3000;
            }
        }

        if ($age > 40 && $age <= 50) {

            // PAKISTAN B.C
            if ($voucher_type == 1) {
                $amount = 750;
            }

            // ENROLMENT FEE
            if ($voucher_type == 2) {
                $amount = getGeneralFund($age, $application)['total'];
            }

            // GROUP INSURANCE
            if ($voucher_type == 3) {
                $amount = 10000;
            }

            // BENEVOLENT FUND
            if ($voucher_type == 4) {
                $amount = 0;
            }

            // PLJ
            if ($voucher_type == 5) {
                if ($application->plj_br == 0) {
                    $amount = 7000;
                }
            }

            // LAWYER WELFARE FUND
            if ($voucher_type == 6) {
                $amount = 10000;
            }
        }

        if ($age > 50 && $age <= 60) {

            // PAKISTAN B.C
            if ($voucher_type == 1) {
                $amount = 1000;
            }

            // ENROLMENT FEE
            if ($voucher_type == 2) {
                $amount = getGeneralFund($age, $application)['total'];
            }

            // GROUP INSURANCE
            if ($voucher_type == 3) {
                $amount = 10000;
            }

            // BENEVOLENT FUND
            if ($voucher_type == 4) {
                $amount = 0;
            }

            // PLJ
            if ($voucher_type == 5) {
                if ($application->plj_br == 0) {
                    $amount = 10000;
                }
            }

            // LAWYER WELFARE FUND
            if ($voucher_type == 6) {
                $amount = 10000;
            }
        }

        if ($age > 60) {

            // PAKISTAN B.C
            if ($voucher_type == 1) {
                $amount = 1250;
            }

            // ENROLMENT FEE
            if ($voucher_type == 2) {
                $amount = getGeneralFund($age, $application)['total'];
            }

            // GROUP INSURANCE
            if ($voucher_type == 3) {
                $amount = 10000;
            }

            // BENEVOLENT FUND
            if ($voucher_type == 4) {
                $amount = 0;
            }

            // PLJ
            if ($voucher_type == 5) {
                if ($application->plj_br == 0) {
                    $amount = 12000;
                }
            }

            // LAWYER WELFARE FUND
            if ($voucher_type == 6) {
                $amount = 10000;
            }
        }

        return $amount;
    }
}

if (!function_exists('getLcBankAccount')) {
    function getLcBankAccount($type)
    {
        $account = NULL;
        switch ($type) {
            case $type == 1:
                $account = '(Pakistan B.C. A/C 0042-79918974-03)';
                break;
            case $type == 2:
                $account = '(Punjab B.C. A/C 0042-79000543-03)';
                break;
            case $type == 3:
                $account = '(Group Insurance A/C 0042-79000544-03)';
                break;
            case $type == 4:
                $account = '(Benevolent Fund A/C 0042-79000545-03)';
                break;
            case $type == 5:
                $account = '(PLJ A/C 0042-79000546-03)';
                break;
            case $type == 6:
                $account = '(LWF A/C 0042-79922451-03)';
                break;
            default:
                break;
        }
        return $account;
    }
}

if (!function_exists('getLcBank1LinkNumber')) {
    function getLcBank1LinkNumber($type)
    {
        $account = NULL;
        switch ($type) {
            case $type == 1:
                $account = ''; // PAKISTAN BC
                break;
            case $type == 2:
                $account = '1001145142'; // PUNJAB BC
                break;
            case $type == 3:
                $account = '1001145140'; // GI
                break;
            case $type == 4:
                $account = '1001145139'; // BF
                break;
            case $type == 5:
                $account = '1001145158'; // PLJ
                break;
            case $type == 6:
                $account = '1001145156'; // LWF
                break;
            default:
                break;
        }
        return $account;
    }
}

if (!function_exists('getLcPaymentStatus')) {
    function getLcPaymentStatus($id)
    {
        $application = LowerCourt::find($id);
        $total_payment_count = Payment::where('lower_court_id', $application->id)
            ->where('application_type', 1)
            ->where('amount', '>', 0)
            ->count();

        $paid_payment_count = Payment::where('lower_court_id', $application->id)
            ->where('application_type', 1)
            ->where('payment_status', 1)
            ->where('amount', '>', 0)
            ->count();

        $unpaid_payment_count = Payment::where('lower_court_id', $application->id)
            ->where('application_type', 1)
            ->where('payment_status', 0)
            ->where('amount', '>', 0)
            ->count();

        $response = [];
        if ($total_payment_count == $paid_payment_count && $total_payment_count > 0) {
            $response['badge'] = 'success';
            $response['name'] = 'Paid';
        } else if ($total_payment_count == $unpaid_payment_count && $total_payment_count > 0) {
            $response['badge'] = 'danger';
            $response['name'] = 'Unpaid';
        } else if ($total_payment_count != $unpaid_payment_count && $total_payment_count != $paid_payment_count && $total_payment_count > 0) {
            $response['badge'] = 'warning';
            $response['name'] = 'Partial';
        } else {
            $response['badge'] = 'secondary';
            $response['name'] = 'Pending';
        }

        return $response;
    }
}

if (!function_exists('getLcHomeAddress')) {
    function getLcHomeAddress($application_id)
    {
        $address = \App\LawyerAddress::where('lower_court_id', $application_id)->first();

        if ($address != null) {
            return  $address->ha_house_no . ', ' . $address->ha_str_address . ', ' . $address->ha_town . ', ' .
                $address->ha_city  . ', ' . getCountryName($address->ha_country_id);
        } else {
            return '-';
        }
    }
}

if (!function_exists('getLcPostalAddress')) {
    function getLcPostalAddress($application_id)
    {
        $address = \App\LawyerAddress::where('lower_court_id', $application_id)->first();

        if ($address != null) {
            return  $address->pa_house_no . ', ' . $address->pa_str_address . ', ' . $address->pa_town . ', ' .
                $address->pa_city  . ', ' . getCountryName($address->pa_country_id);
        }
    }
}

if (!function_exists('getGeneralFund')) {
    function getGeneralFund($age, $application)
    {
        $age_cal_date = isset($application->final_submitted_at) ? $application->final_submitted_at : $application->created_at;
        $age = Carbon::parse($application->date_of_birth)->diff($age_cal_date)->format('%y.%m%d');

        $degreeAmount = 0;
        if ($application->intimation_degree_fee == 0) {
            if (isset($application->degree_place)) {
                if ($application->degree_place == 1) {
                    $degreeAmount = 3000;
                } elseif ($application->degree_place == 2) {
                    $degreeAmount = 5000;
                } elseif ($application->degree_place == 3) {
                    $degreeAmount = 25000;
                } else {
                    $degreeAmount = 500;
                }
            }
        }

        $exemption_fee = 0;
        if ($application->is_exemption == 1) {
            $exemption_fee = 10000;
        }

        if ($age >= 21 && $age <= 26) {
            $amount = [
                'enrollment_fee' => 200,
                'id_card_fee' => 1500,
                'certificate_fee' =>  0,
                'building_fund' => 0,
                'general_fund' => 0,
                'degree_fee' => $degreeAmount,
                'exemption_fee' => $exemption_fee
            ];
        }

        if ($age > 26 && $age <= 30) {
            $amount = [
                'enrollment_fee' => 750,
                'id_card_fee' => 2800,
                'certificate_fee' => 500,
                'building_fund' => 1000,
                'general_fund' => 1000,
                'degree_fee' => $degreeAmount,
                'exemption_fee' => $exemption_fee
            ];
        }

        if ($age > 30 && $age <= 35) {
            $amount = [
                'enrollment_fee' => 900,
                'id_card_fee' => 2800,
                'certificate_fee' => 1000,
                'building_fund' => 1000,
                'general_fund' => 2000,
                'degree_fee' => $degreeAmount,
                'exemption_fee' => $exemption_fee
            ];
        }

        if ($age > 35 && $age <= 40) {
            $amount = [
                'enrollment_fee' => 1200,
                'id_card_fee' => 2800,
                'certificate_fee' => 1000,
                'building_fund' => 2000,
                'general_fund' => 20000,
                'degree_fee' => $degreeAmount,
                'exemption_fee' => $exemption_fee,
            ];
        }

        if ($age > 40 && $age <= 50) {
            $amount = [
                'enrollment_fee' => 1500,
                'id_card_fee' => 2800,
                'certificate_fee' => 2000,
                'building_fund' => 5000,
                'general_fund' => 50000,
                'degree_fee' => $degreeAmount,
                'exemption_fee' => $exemption_fee,
            ];
        }

        if ($age > 50 && $age <= 60) {
            $amount = [
                'enrollment_fee' => 2000,
                'id_card_fee' => 2800,
                'certificate_fee' => 2000,
                'building_fund' => 5000,
                'general_fund' => 170000,
                'degree_fee' => $degreeAmount,
                'exemption_fee' => $exemption_fee,
            ];
        }

        if ($age > 60) {
            $amount = [
                'enrollment_fee' => 2500,
                'id_card_fee' => 2800,
                'certificate_fee' => 2000,
                'building_fund' => 5000,
                'general_fund' => 275000,
                'degree_fee' => $degreeAmount,
                'exemption_fee' => $exemption_fee,
            ];
        }

        $amount['total'] = $amount['enrollment_fee'] + $amount['id_card_fee'] + $amount['certificate_fee'] + $amount['building_fund'] + $amount['general_fund'] + $amount['degree_fee'] + $amount['exemption_fee'];
        return $amount;
    }
}

if (!function_exists('getDifferenceGC')) {
    function getDifferenceGC($application)
    {
        $differenceEnrollment =  Payment::where('user_id', $application->user_id)
            ->where('lower_court_id', $application->id)
            ->where('application_type', 1)
            ->where('voucher_type', 0)
            ->where('voucher_name', 'enrollment_fee')
            ->where('payment_status', 1)
            ->sum('amount');

        $differenceTotal =  Payment::where('user_id', $application->user_id)
            ->where('lower_court_id', $application->id)
            ->where('application_type', 1)
            ->where('voucher_type', 0)
            ->where('voucher_name', 'total')
            ->where('payment_status', 1)
            ->sum('amount');

        return [
            'gc_fund' => $differenceTotal - $differenceEnrollment,
            'enrollment' => $differenceEnrollment,
        ];
    }
}


if (!function_exists('validateIntimationApplication')) {
    function validateIntimationApplication($application, $is_move = 0)
    {
        $paymentStatus = getPaymentStatus($application->id)['name'];

        $response['errors'] = [];

        if ($application->llb_passing_year == NULL || empty($application->llb_passing_year)) {
            $response['errors'][] = 'LLB passing year is missing';
        }

        if ($application->intimation_start_date == NULL || empty($application->intimation_start_date)) {
            $response['errors'][] = 'Intimation start date is missing';
        } else {

            if (getIntimationDuration($application->intimation_start_date) < 6) {
                $response['errors'][] = 'application has not completed the 6 month duration';
            }
        }

        if ($application->bar_association == NULL || empty($application->bar_association)) {
            $response['errors'][] = 'Bar Association    is missing';
        }
        if ($application->advocates_name == NULL || empty($application->advocates_name)) {
            $response['errors'][] = 'First Name is missing';
        }
        if ($application->last_name == NULL || empty($application->last_name)) {
            $response['errors'][] = 'Last Name is missing';
        }
        if ($application->so_of == NULL || empty($application->so_of)) {
            $response['errors'][] = 'Father\'s Name is missing';
        }
        if ($application->gender == NULL || empty($application->gender)) {
            $response['errors'][] = 'Gender is missing';
        }
        if ($application->date_of_birth == NULL || empty($application->date_of_birth)) {
            $response['errors'][] = 'Date Of Birth is missing';
        }
        if ($application->active_mobile_no == NULL || empty($application->active_mobile_no)) {
            $response['errors'][] = 'Mobile No. is missing';
        }
        if ($application->final_submitted_at == NULL || empty($application->final_submitted_at)) {
            $response['errors'][] = 'Final Submission Date is missing';
        }
        if ($application->cnic_no == NULL || empty($application->cnic_no)) {
            $response['errors'][] = 'CNIC No. is missing';
        }

        if ($application->cnic_expiry_date == NULL || empty($application->cnic_expiry_date)) {
            $response['errors'][] = 'CNIC Expiry date is missing';
        }
        if ($application->srl_enr_date == NULL || empty($application->srl_enr_date)) {
            $response['errors'][] = 'Senior Lawyer Enrollment Date is missing';
        }
        if ($application->srl_joining_date == NULL || empty($application->srl_joining_date)) {
            $response['errors'][] = 'Senior Lawyer Joining Date is missing';
        }
        if ($application->rcpt_no == null || $application->rcpt_date == null) {
            $response['errors'][] = 'RCPT# is missing';
        }

        if ($application->is_accepted == NULL || empty($application->is_accepted) || $application->is_accepted == 0) {
            $response['errors'][] = 'Not Accepted';
        }

        if ($paymentStatus != 'Paid') {
            $response['errors'][] = 'Payment Not Complete';
        }

        if ($application->application_status != 1) {
            $response['errors'][] = 'application not approved/active';
        }

        if (count($response['errors']) > 0 || $application->objections != null) {
            $application->error_logs = json_encode($response['errors']) ?? [];
            $application->save();
            $response['application'] = $application;
            if ($application->objections != null && count(json_decode($application->objections)) > 0) {
                $objs = json_decode($application->objections);
                $objections = [];

                foreach ($objs as $obj) {
                    $objections[] = getObjections($obj);
                }

                $response['objections'] = $objections;
            }
            $response['status'] = 'has errors Or objections Not Moved application id = ' . $application->id;
            return $response;
        } else {

            if ($is_move == 1) {

                moveToLcCheckCnicExist($application->cnic_no);

                // Updating Application that the application is moved to LowerCourt
                $application->is_intimation_completed = 1;
                $application->intimation_completed_at = Carbon::now();
                $application->app_type = 3; // MOVE TO LOWER COURT
                $application->save();


                $payment = Payment::where('application_id', $application->id)
                    ->where('payment_status', 1)
                    ->where('degree_fee', '>', 0)
                    ->first();

                if ($payment) {
                    $intimation_degree_fee = true;
                } else {
                    $intimation_degree_fee = false;
                }


                // Moving Application to Lower Court
                $lowerCourt = LowerCourt::where('cnic_no', $application->cnic_no)->first();
                if ($lowerCourt == null) {
                    $lowerCourt = LowerCourt::create([
                        'user_id' => $application->user_id,
                        'father_name' => $application->so_of,
                        'gender' => $application->gender,
                        'date_of_birth' => $application->date_of_birth,
                        'cnic_no' => $application->cnic_no,
                        'cnic_expiry_date' => $application->cnic_expiry_date,
                        'age' => $application->age,
                        'blood' => $application->blood,
                        'llb_passing_year' => $application->llb_passing_year,
                        'srl_name' => $application->srl_name,
                        'srl_bar_id' => $application->srl_bar_name,
                        'srl_office_address' => $application->srl_office_address,
                        'srl_enr_date' => $application->srl_enr_date,
                        'srl_mobile_no' => $application->srl_mobile_no,
                        'srl_cnic_no' => $application->srl_cnic_no,
                        'srl_joining_date' => $application->srl_joining_date,
                        'is_moved_from_intimation' => 1,
                        'intimation_date' => Carbon::parse($application->intimation_start_date)->format('Y-m-d'),
                        'app_type' => 5, // MOVE FROM INTIMATION
                        'app_status' => 6, // PENDING
                        'intimation_degree_fee' => $intimation_degree_fee,
                        'degree_place' => $application->degree_place,
                    ]);

                    $application->educations()->update([
                        'lower_court_id' => $lowerCourt->id
                    ]);
                    if ($application->uploads != NULL) {
                        $application->uploads->update([
                            'lower_court_id' => $lowerCourt->id
                        ]);
                    }
                    if ($application->address != NULL) {
                        $application->address->update([
                            'lower_court_id' => $lowerCourt->id
                        ]);
                    }
                }

                // Updating USER now as a Lower Court ...
                $user = $application->user;
                $user->update([
                    'register_as' => 'lc'
                ]);

                if (auth()->guard('frontend')->check()) {
                    $lowerCourt->update(['is_frontend' => 1]);
                    $front_user_id = $application->user_id;
                    if ($lowerCourt->created_by == null) {
                        $lowerCourt->update(['created_by' => $front_user_id]);
                    }
                    if ($lowerCourt->updated_by == null) {
                        $lowerCourt->update(['updated_by' => $front_user_id]);
                    }
                }

                if (auth()->guard('admin')->check()) {
                    $lowerCourt->update(['is_frontend' => 0]);
                    $admin_id = auth()->guard('admin')->user()->id;
                    if ($lowerCourt->created_by == null) {
                        $lowerCourt->update(['created_by' => $admin_id]);
                    }
                    if ($lowerCourt->updated_by == null) {
                        $lowerCourt->update(['updated_by' => $admin_id]);
                    }
                }

                $response['application'] = $application;
                $response['application_status'] = true;
                $response['lowerCourtID'] = $lowerCourt->id;
                $response['status'] = 'Application id = ' . $application->id . ' is moved to lower court id = ' . $lowerCourt->id;
            }
            $response['errors'] = [];

            return $response;
        }
    }
}

if (!function_exists('getLcInterviewStatus')) {
    function getLcInterviewStatus($application)
    {
        $response = [];
        $total = $application->assignMembers->count();
        $verified = $application->assignMembers->where('is_code_verified', true)->count();

        if ($verified == $total) {
            $response['badge'] = 'success';
            $response['name'] = 'Done';
        } else {
            $response['badge'] = 'warning';
            $response['name'] = 'Pending';
        }

        return $response;
    }
}

if (!function_exists('getLCUserName')) {
    function getLCUserName($id)
    {
        $application = LowerCourt::find($id);

        return $application->user->name ?? 'N/A';
    }
}

if (!function_exists('getLCKeyTitle')) {
    function getLCKeyTitle($key)
    {
        $data = [
            'id' => 'ID',
            'lower_court_id' => 'ID',
            'application_id' => 'Application ID',
            'application_token_no' => "Application Token No.",
            'card_status' => 'Card Status',
            'submitted_by' => 'Submitted By',
            'updated_by' => 'Updated By',
            'is_excel_import' => 'Excel Export',
            'print_date' => 'Printing Date',
            'is_approved' => 'Approved',
            'advocates_name' => 'Advocate\'s Name',
            'last_name' => 'Last Name',
            'so_of' => 'Father\'s Name',
            'gender' => 'Gender',
            'date_of_birth' => "Date Of Birth",
            'profile_image_url' => 'Profile Image',
            'profile_image_name' => 'Profile Image Name',
            'email' => 'Name',
            'cnic_no' => 'CNIC No.',
            'cnic_expiry_date' => 'CNIC Expiry Date',
            'whatsapp_no' => 'Whatsapp No.',
            'district_id' => 'District',
            'tehsil_id' => 'Tehsil',
            'postal_address' => 'Postal Address',
            'address_2' => "Address 2",
            'reg_no_lc' => 'Registraion No.',
            'high_court_roll_no' => 'High Court Roll No.',
            'sr_no_lc' => 'Snr. Lower Court No.',
            'sr_no_hc' => 'Snr. High Court No.',
            'license_no_lc' => 'Snr. Lower Court License No.',
            'license_no_hc' => 'Snr. High Court License No.',
            'bf_no_lc' => 'B.F No (L.C.):',
            'bf_no_hc' => 'B.F No (H.C.):',
            'plj_no_lc' => 'PLJ No. (LC):',
            'rf_id' => 'RF-ID',
            'date_of_enrollment_lc' => 'Date Of Enrollment (L.C.)',
            'date_of_enrollment_hc' => 'Date Of Enrollment (H.C.)',
            'voter_member_lc' => 'Voter Member (L.C.)',
            'voter_member_hc' => 'Voter Member (H.C.)',
            'hcr_no' => 'HRC No',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'llb_passing_year' => 'LLB Passing Year',
            'blood' => 'Blood Group',
            'srl_name' => 'Srl Name',
            'srl_office_address' => 'Srl Office Address',
            'srl_enr_date' => 'Srl Enr Date',
            'srl_mobile_no' => 'Srl Mobile No',
            'srl_cnic_no' => 'Srl Cnic No',
            'srl_joining_date' => 'Srl Joining Date',
            'age' => 'Age',
            'is_accepted' => 'Accepted',
            'active_mobile_no' => "Active Mobile No.",
            'user_id' => 'User Name',
            'srl_bar_name' => 'Snr. Bar Name',
            'bar_association' => 'Bar Association Name',
            'application_type' => 'Application Type',
            'application_status' => 'Application Status',
            'is_academic_record' => 'Academic Record',
            'qualification' => 'Qualification',
            'university_id' => 'University Name',
            'institute' => 'Institute Name',
            'sub_qualification' => 'Sub Qualification Name',
            'final_submitted_at' => 'Final Submission Date',
            'is_final_submitted' => 'Is Final Submission',
            'same_as' => 'Same As',
            // Questions
            'is_engaged_in_business' => 'Whether the applicant is/was engaged in any business, service, profession or vocation in Pakistan? If so. the nature there of and the place at which it is carried out?',
            'is_practice_in_pbc' => 'Whether the applicant proposes to practice generally within the jurisdiction ofthe Punjab Bar Council? ',
            'practice_place' => 'State place of practice?',
            'is_declared_insolvent' => 'Whether the applicant has been declared insolvent?',
            'is_dismissed_from_gov' => 'Whether the applicant has been dismissed/removed from service of Government or of a Public Statutory Corporation, ',
            'dismissed_reason' => 'Dismissed Reason',
            'is_enrolled_as_adv' => 'Whether the applicant is enrolled as an advocate on the Roll of any other Bar Council?',
            'is_offensed' => 'Whether the applicant has been convicted of any offence? Ifso, date and particulars thereof?',
            'offensed_date' => 'offensed Date',
            'offensed_reason' => 'offensed Reason',
            'is_prev_rejected' => 'Whether the application ofthe applicant of enrolment has previously been rejected?',
            'notes' => 'Additional Notes',
            // Images = Lawyer Uploads
            'profile_image' => 'Profile Image',
            'cnic_front' => 'CNIC Front',
            'cnic_back' => 'CNIC Back',
            'card_front' => 'Card Front',
            'card_back' => 'Card Back',
            'certificate_lc' => 'Certificate (L.C.)',
            'affidavit_lc' => 'Affidavit (L.C.)',
            'cases_lc' => 'Cases (L.C.)',
            'voucher_lc' => 'Voucher (L.C.)',
            'gat_lc' => 'GAT (L.C.)',
            'certificate_hc' => 'Certificate (H.C.)',
            'affidavit_hc' => 'Affidavit (H.C.)',
            'cases_hc' => 'Cases (H.C.)',
            'voucher_hc' => 'Voucher (H.C.)',
            'gat_hc' => 'GAT (H.C.)',
            'srl_cnic_front' => 'Snr. Laywer CNIC Front',
            'srl_cnic_back' => 'Snr. Laywer CNIC Back',
            'srl_license_front' => 'Snr. Laywer License Front',
            'srl_license_back' => 'Snr. Laywer License Back',
            'license_front' => 'License Front',
            'license_back' => 'License Back',
            // University
            'total_marks' => 'Total Marks',
            'obtained_marks' => 'Obtained Marks',
            'passing_year' => 'Passing Year',
            'roll_no' => 'Roll No',
            'certificate' => 'Certificate',
            'gat_pass' => 'GAT Password',
            // Payments
            'title' => 'Title',
            'type' => 'Type',
            'date' => 'Date',
            'admin_id' => 'Admin Name',
            'payment_type' => 'Payment Type',
            'voucher_no' => 'Voucher No.',
            'bank_name' => 'Bank Name',
            'voucher_file' => 'Voucher_ File',
            'paid_date' => 'Paid Date',
            'customer_id' => 'Customer ID',
            'order_id' => 'Order ID',
            'package_id' => 'Package ID',
            'invoice_id' => 'Invoice ID',
            'card_last4' => 'Card Last4',
            'card_type' => 'Card Type',
            'invoice_url' => 'Invoice URL',
            'charged_amount' => 'Charged Amount',
            'discount' => 'Discount',
            'charged_at' => 'Charged At',
            'payment_status' => 'Payment Status',
            'acct_dept_payment_status' => 'Account Dept Payment Status',
            'objections' => 'Objections',
            // ADDRESS
            'ha_house_no' => 'Home Address - House no.',
            'ha_str_address' => 'Home Address - Street Address',
            'ha_town' => 'Home Address - Town',
            'ha_city' => 'Home Address - City',
            'ha_postal_code' => 'Home Address - Postal Code',
            'ha_country_id' => 'Home Address - Country',
            'ha_province_id' => 'Home Address - Province',
            'ha_district_id' => 'Home Address - District',
            'ha_tehsil_id' => 'Home Address - Tehsil',
            'pa_house_no' => 'Postal Address - House no.',
            'pa_str_address' => 'Postal Address - Street Address',
            'pa_town' => 'Postal Address - Town',
            'pa_city' => 'Postal Address - City',
            'pa_postal_code' => 'Postal Address - Postal Code',
            'pa_country_id' => 'Postal Address - Country',
            'pa_province_id' => 'Postal Address - Province',
            'pa_district_id' => 'Postal Address - District',
            'pa_tehsil_id' => 'Postal Address - Tehsil',

        ];

        return $data[$key] ?? $key;
    }
}

if (!function_exists('getLCLogData')) {
    function getLCLogData($key, $log, $applicationID)
    {
        $data = [
            'admin_id' => getAdminName($log),
            'user_id' => getLCUserName($applicationID),
            'srl_bar_name' => getBarName($log),
            'bar_association' => getBarName($log),
            // 'application_status' => getLcApplicationStatus($applicationID)['name'] ?? 'N/a',
            'is_academic_record' => $log ? 'Completed' : 'Requirements Pending',
            'qualification' => getQualificationName($log),
            'university_id' => \App\University::find($log)->name ?? 'N/a',
            'institute' => $log != NULL ? $log : 'N/a',
            'sub_qualification' => $log != NULL ? $log : 'N/a',
            'is_accepted' => $log ? 'Accepted' : 'Pending',
            'is_approved' => $log ? 'Approved' : 'Not Approved',
            'type' => $log != NULL ? $log : 'N/a',
            'payment_type' => $log  ? 'Online' : 'N/a',
            'payment_status' => $log  ? 'Paid' : 'Un Paid',
            'created_at' => $log != null ? date('d-m-Y h:i:s A', strtotime($log)) : 'N/a',
            'updated_at' => $log != null ? date('d-m-Y h:i:s A', strtotime($log)) : 'N/a',
            'charged_at' => $log != null ? date('d-m-Y h:i:s A', strtotime($log)) : 'N/a',
            'acct_dept_payment_status' => $log ? 'Approved' : 'Not Approved',
        ];


        return $data[$key] ?? $log;
    }
}

if (!function_exists('uploadLcEducationalCertificate')) {
    function uploadLcEducationalCertificate($request, $id, $application_id)
    {
        $model = LawyerEducation::find($id);
        $directory = 'lc-educations/' . $application_id;
        if ($request->hasFile('certificate_url')) {
            $request->file('certificate_url')->getClientOriginalName();
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('certificate_url')));
            ($model == NULL) ? LawyerEducation::create(['lower_court_id' => $model->id, 'certificate' => $url]) : $model->update(['certificate' => $url]);
        }
    }
}


if (!function_exists('getLcTransactionNo')) {
    function getLcTransactionNo($id, $vch_no)
    {
        try {
            $lc = LowerCourt::find($id);
            $transaction_no = $lc->payments()->where('voucher_type', $vch_no)->first()->transaction_id;
        } catch (\Throwable $th) {
            $transaction_no = '';
        }

        return $transaction_no;
    }
}

if (!function_exists('getLcObjections')) {
    function getLcObjections($id)
    {
        $name = null;

        switch ($id) {
            case $id == 1:
                $name = 'Copies of Matriculation Certificate, LL.B. Parts I, II & Ill Result Cards duly attested by the Member Punjab Bar Council/President Bar Association/ Gazetted Officer and LL.B. Provisional Certificate in original.';
                break;
            case $id == 2:
                $name = 'Two Character Certificates from the Advocates.';
                break;
            case $id == 3:
                $name = 'Undertaking regarding your membership of a Bar Association. (Please mention the name of the Bar Association).';
                break;
            case $id == 4:
                $name = 'List of Twenty Cases signed by Senior Advocate in which you rendered assistance to your senior.';
                break;
            case $id == 5:
                $name = 'Your six passport size photographs in Uniform.';
                break;
            case $id == 6:
                $name = 'Affidavit, stating that no criminal Proceedings were ever instituted against you in any Court of Law for professional misconduct.';
                break;
            case $id == 7:
                $name = 'A deposit slip of Rs._________Regarding________Fee.';
                break;
            case $id == 8:
                $name = 'Please mention the Roll Number of the Written Examination which you have passed.';
                break;
            case $id == 9:
                $name = 'You have not replied to the Column Nos._______of Form A, please do the needful by submitting revised Form. ';
                break;
            case $id == 10:
                $name = 'The Training Certificate (Form B) is signed by the Advocate Mr._____________, who is not your senior as per Intimation Form you have submitted earlier. Please send revised Form. ';
                break;
            case $id == 11:
                $name = 'Signatures of your senior on Form B do not correspond with the signatures on the intimation form. Please send revised Form.';
                break;
            case $id == 12:
                $name = 'Dates on Form B do not correspond with the date mentioned in the Intimation Form. Please send revised Form.';
                break;
            case $id == 13:
                $name = 'Affidavit explaining gap in between your academic examinations or thereafter, till the submission of application for enrolment as an advocate. ';
                break;
            case $id == 14:
                $name = 'Application for enrolment sent by you is premature. Please apply after		 , by sending Form A, B, fresh after completion. ';
                break;
            case $id == 15:
                $name = 'Form A, is not properly filled and also un-attested. Please send revised. On receipt of the above mentioned documents, your case will be referred to the Enrolment Committee for consideration.';
                break;
            default:
                break;
        }

        return $name;
    }
}

if (!function_exists('getLcAge')) {
    function getLcAge($date_of_birth, $application)
    {
        $age_cal_date = isset($application->final_submitted_at) ? $application->final_submitted_at : $application->created_at;
        $age = Carbon::parse($date_of_birth)->diff($age_cal_date)->format('%y.%m.%d');

        return $age;
    }
}

if (!function_exists('convertLcAgeToWords')) {
    function convertLcAgeToWords($age)
    {
        $age = explode(".", $age);
        $years = isset($age[0]) ? $age[0] . ' Years' : '';

        $months = NULL;
        if (isset($age[1]) && $age[1] > 0) {
            if ($age[1] > 1) {
                $months = $age[1] . ' Months';
            } else {
                $months = $age[1] . ' Month';
            }
            $months = str_replace('0', '', $months);
        }

        $days = NULL;
        if (isset($age[2]) && $age[2] > 0) {
            if ($age[2] > 1) {
                $days = $age[2] . ' Days';
            } else {
                $days = $age[2] . ' Day';
            }
            $days = str_replace('0', '', $days);
        }

        // $result = $years . ' ' . $months . ' ' . $days;
        $result = $years;

        return $result;
    }
}


if (!function_exists('getGcLcAppStatus')) {
    function getGcLcAppStatus($status)
    {
        $response['name'] = 'NULL';
        $response['badge'] = 'secondary';

        switch ($status) {
            case $status == 1:
                $response['name'] = 'Active';
                $response['badge'] = 'success';
                break;
            case $status == 2:
                $response['name'] = 'Suspended';
                $response['badge'] = 'danger';
                break;
            case $status == 3:
                $response['name'] = 'Died';
                $response['badge'] = 'danger';
                break;
            case $status == 4:
                $response['name'] = 'Removed';
                $response['badge'] = 'danger';
                break;
            case $status == 5:
                $response['name'] = 'Transfer in';
                $response['badge'] = 'warning';
                break;
            case $status == 6:
                $response['name'] = 'Transfer out';
                $response['badge'] = 'warning';
                break;
            case $status == 7:
                $response['name'] = 'Pending';
                $response['badge'] = 'primary';
                break;
            case $status == 8:
                $response['name'] = 'Move To High Court';
                $response['badge'] = 'success';
                break;
            case $status == 10:
                $response['name'] = 'Rejected';
                $response['badge'] = 'danger';
                break;
            default:
                break;
        }

        return $response;
    }
}

if (!function_exists('getAdvocateCertificateStatus')) {
    function getAdvocateCertificateStatus($application)
    {
        $res = false;
        $date = false;
        $lc_date = $application->lc_date;

        if ($lc_date) {
            $lc_next_month_date = date("d-m-Y", strtotime('+2 month', strtotime($lc_date)));
            if (Carbon::parse(Carbon::now())->gte(Carbon::parse($lc_next_month_date))) {
                $date = true;
            }
        }

        if ($application->license_no_lc && $date == true && getLcPaymentStatus($application->id)['name'] == 'Paid') {
            $res = true;
        }

        return $res;
    }
}

function getLowerCourtDuration($user_id)
{
    try {
        $user = User::find($user_id);
        $current_date = Carbon::parse(Carbon::now());
        $duration = 0;

        if ($user->register_as == 'lc') {
            $lc = LowerCourt::where('user_id', $user->id)->first();
            $duration = Carbon::parse($lc->lc_date)->diff($current_date)->format('%y.%m');

            if ($lc->hc_exemption_at) {
                $duration = 2;
            }
        }

        if ($user->register_as == 'gc_lc') {
            $gc_lc = GcLowerCourt::where('user_id', $user->id)->first();
            $duration = Carbon::parse($gc_lc->date_of_enrollment_lc)->diff($current_date)->format('%y.%m');
        }

        return (float) $duration;
    } catch (\Throwable $th) {
        return 0;
    }
}

function validateLowerCourt($user_id)
{
    $reasons = [];
    $user = User::find($user_id);

    if ($user->register_as == 'lc') {
        $lc = LowerCourt::where('user_id', $user->id)->first();

        if ($lc->app_status != 1) {
            $reasons[] = 'The lower court application status is not active.';
        }

        if (getLcPaymentStatus($lc->id)['name'] != 'Paid') {
            $reasons[] = 'The lower court application payment status is not paid.';
        }

        $objections = json_decode($lc->objections, TRUE);

        if (isset($objections)) {
            foreach ($objections as $key => $objection) {
                $reasons[] = getLcObjections($objection);
            }
        }
    }

    if ($user->register_as == 'gc_lc') {
        $gc_lc = GcLowerCourt::where('user_id', $user->id)->first();

        if (in_array($gc_lc->app_status, [2, 3, 4, 5, 6, 7])) {
            $reasons[] = 'The gc lower court application status is not active.';
        }

        if ($user->gc_status == 'pending') {
            $reasons[] = 'The data is not verified by the punjab bar council';
        }
    }

    return $reasons;
}


function lowerCourtShortDetail($id, $type)
{
    try {
        $data = [];
        $generator = new BarcodeGeneratorDynamicHTML();

        if ($type == 'lc') {
            $record = LowerCourt::where('id', $id)->where('is_final_submitted', 1)->firstOrFail();

            $objections = [];
            if (isset($record->objections)) {
                foreach (json_decode($record->objections, TRUE) as $key => $value) {
                    $objections[] = ++$key . '-' . getLcObjections($value);
                }
            }

            $data = [
                'user_id' => $record->user_id,
                'app_no' => $record->id,
                'rcpt_no' => '<span>' . $record->rcpt_date . '</span> <br> <span class="badge badge-secondary">' . $record->rcpt_no_lc . '</span>',
                'leg_no' => $record->reg_no_lc,
                'lic_no' => $record->license_no_lc,
                'bf_no' => $record->bf_no_lc,
                'plj_no' => $record->plj_no_lc,
                'gi_no' => $record->gi_no_lc,
                'lawyer_name' => $record->lawyer_name,
                'sdw_name' => $record->father_name,
                'cnic_no' => $record->cnic_no,
                'gender' => $record->gender,
                'address' => getLcPostalAddress($record->id),
                'contact' => $record->mobile_no,
                'email' => $record->email,
                'enr_date' => getDateFormat($record->lc_date),
                'dob' => getDateFormat($record->date_of_birth),
                'voter_member' => str_replace('BAR ASSOCIATION', '', getBarName($record->voter_member_lc)),
                'app_status' => appStatus($record->app_status, $record->app_type),
                'other_status' => getLcStatuses($record->id)['res'],
                'payment_status' => getLcPaymentStatus($record->id)['name'] . ' Payment',
                'status_reason' => implode('<br>', $objections),
                'img_url' => isset($record->uploads->profile_image) ? asset('storage/app/public/' . $record->uploads->profile_image) : 'https://portal.pbbarcouncil.com/public/admin/images/no-image.png',
                'bar_code' => $generator->getBarcode($record->cnic_no, $generator::TYPE_CODE_128),
            ];
        }

        if ($type == 'gc_lc') {

            $record = GcLowerCourt::where('id', $id)->firstOrFail();
            $user = User::find($record->user_id);

            if ($user && isset($user->getFirstMedia('gc_profile_image')->id) && $user->getFirstMedia('gc_profile_image')->id) {
                $image_url = asset('storage/app/public/' . $user->getFirstMedia('gc_profile_image')->id . '/' . $user->getFirstMedia('gc_profile_image')->file_name);
            } else {
                $image_url = 'https://portal.pbbarcouncil.com/storage/app/public/applications/profile-images/LC/' . $record->image;
            }

            $data = [
                'user_id' => $record->user_id,
                'app_no' => $record->id,
                'rcpt_no' => NULL,
                'leg_no' => $record->reg_no_lc,
                'lic_no' => $record->license_no_lc,
                'bf_no' => $record->bf_no_lc,
                'plj_no' => $record->plj_no_lc,
                'gi_no' => $record->gi_no_lc,
                'lawyer_name' => $record->lawyer_name,
                'sdw_name' => $record->father_name,
                'cnic_no' => $record->cnic_no,
                'gender' => $record->gender,
                'address' => $record->address_1,
                'contact' => $record->contact_no,
                'email' => $record->email,
                'enr_date' => getDateFormat($record->date_of_enrollment_lc),
                'dob' => getDateFormat($record->date_of_birth),
                'voter_member' => str_replace('BAR ASSOCIATION', '', getBarName($record->voter_member_lc)),
                'app_status' => appStatus($record->app_status, $record->app_type),
                'payment_status' => NULL,
                'other_status' => NULL,
                'status_reason' => $record->enr_status_reason,
                'img_url' => $image_url,
                'bar_code' => $generator->getBarcode($record->cnic_no, $generator::TYPE_CODE_128),
            ];
        }

        return $data;
    } catch (\Throwable $th) {
        throw $th;
    }
}


function getProfileImage($user_id, $register_as)
{
    try {
        if ($register_as == 'lc') {
            $lower_court = LowerCourt::where('user_id', $user_id)->first();
            $lawyer_upload = LawyerUpload::where('lower_court_id', $lower_court->id)->first();
            $image = asset('storage/app/public/' . $lawyer_upload->profile_image);
        }

        if ($register_as == 'hc') {
            $high_court = HighCourt::where('user_id', $user_id)->first();
            $lawyer_upload = LawyerUpload::where('high_court_id', $high_court->id)->first();
            $image = asset('storage/app/public/' . $lawyer_upload->profile_image);
        }

        if (in_array($register_as, ['gc_lc', 'gc_hc'])) {
            $user = User::find($user_id);
            $image = asset('storage/app/public/' . $user->getFirstMedia('gc_profile_image')->id . '/' . $user->getFirstMedia('gc_profile_image')->file_name);
        }

        return $image;
    } catch (\Throwable $th) {
        //throw $th;
    }
}
