<?php

use App\AppStatus;
use App\GcHighCourt;
use App\GcLowerCourt;
use App\HighCourt;
use App\LawyerAddress;
use App\LawyerUpload;
use App\LowerCourt;
use App\Payment;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Picqer\Barcode\BarcodeGeneratorDynamicHTML;


if (!function_exists('getHcVoucherName')) {
    function getHcVoucherName($type)
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
                $name = 'PLJ LAW SITE';
                break;
            case $type == 6:
                $name = 'LAWYER WELFARE FUND';
                break;
            case $type == 7:
                $name = 'RENEWAL FEE';
                break;
            default:
                break;
        }
        return $name;
    }
}

if (!function_exists('getHcVoucherNo')) {
    function getHcVoucherNo($id)
    {
        $vocuherNo = 3000000000000 + $id;
        return $vocuherNo;
    }
}

if (!function_exists('getHcVchAmount')) {
    function getHcVchAmount($id, $voucher_type = 1)
    {
        $application = HighCourt::find($id);
        $amount = 0;

        if ($voucher_type == 1) {
            $amount = 335;
        }
        if ($voucher_type == 2) {
            $amount = getHcGeneralFund($application)['total'];
        }
        if ($voucher_type == 3) {
            $amount = 2000;
        }
        if ($voucher_type == 4) {
            $amount = 0;
        }
        if ($voucher_type == 5) {
            $amount = 500;
        }
        if ($voucher_type == 6) {
            $amount = 2000;
        }
        if ($voucher_type == 7) {

            // IT WAS 2020 BUT NOW ALL THE CHECKS CHANHGED TO 2021

            // This scenario will be change on 2025
            // current date July 11, 2023
            $start_date = Carbon::parse($application->lc_exp_date);

            if ($start_date->lessThanOrEqualTo(Carbon::parse('2021-12-31'))) {

                $end_date = Carbon::parse(Carbon::now());
                // $total_years = (int) $start_date->diffInYears(Carbon::parse($end_date)); //2018-2023 = 5

                $start_year = $start_date->format('Y');
                $end_year = $end_date->format('Y');

                $total_years = (int) $end_year - $start_year;

                // if ($end_date->format('m-d') === '12-31') {
                //     $total_years = $total_years - 1;
                // }

                // $years_of_2021 = (int) $start_date->diffInYears(Carbon::parse('2021-12-31')); // 2018-2021 = 2
                $years_of_2021 = (int) 2021 - $start_year; // 2021-2018 = 2

                $amount_till_2021  = 600 * $years_of_2021;

                $years_from_2021_present = $total_years - $years_of_2021; // 5-2 = 3

                if ($years_from_2021_present > 0) {
                    $amount_from_2021_present  = 1000 * $years_from_2021_present;
                } else {
                    $amount_from_2021_present = 0;
                }

                $amount = $amount_till_2021 + $amount_from_2021_present;
            }

            if ($start_date->greaterThan(Carbon::parse('2021-12-31'))) {

                $end_date = Carbon::parse(Carbon::now());
                // $years = (int) $start_date->diffInYears($end_date);

                $start_year = $start_date->format('Y');
                $end_year = $end_date->format('Y');

                $years = (int) $end_year - $start_year;

                // if ($end_date->format('m-d') === '12-31') {
                //     $years = $years - 1;
                // }

                if ($years > 0) {
                    $amount =  1000 * $years;
                } else {
                    $amount = 0;
                }
            }
        }

        return $amount;
    }
}

if (!function_exists('getHcBankAccount')) {
    function getHcBankAccount($type)
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
                $account = '(PLJ LAW SITE A/C 0042-79000554-03)';
                break;
            case $type == 6:
                $account = '(LWF A/C 0042-79922451-03)';
                break;
            case $type == 7:
                $account = '(Punjab B.C. A/C 0042-79000543-03)';
                break;
            default:
                break;
        }
        return $account;
    }
}

if (!function_exists('getHcBank1LinkNumber')) {
    function getHcBank1LinkNumber($type)
    {
        $account = NULL;
        switch ($type) {
            case $type == 1:
                $account = ''; // PAKISTAN BC
                break;
            case $type == 2:
                $account = '1001145142'; // ENROLLMENT FEE
                break;
            case $type == 3:
                $account = '1001145140'; // GI
                break;
            case $type == 4:
                $account = '1001145139'; // BF
                break;
            case $type == 5:
                $account = '1001145141'; // PLJ LAW SITE
                break;
            case $type == 6:
                $account = '1001145156'; // LWF
                break;
            case $type == 7:
                $account = '1001145142'; // RENEWAL
                break;
            default:
                break;
        }
        return $account;
    }
}

if (!function_exists('getHcPaymentStatus')) {
    function getHcPaymentStatus($id)
    {
        $application = HighCourt::find($id);
        $total_payment_count = Payment::where('high_court_id', $application->id)
            ->where('application_type', 2)
            ->where('amount', '>', 0)
            ->count();

        $paid_payment_count = Payment::where('high_court_id', $application->id)
            ->where('application_type', 2)
            ->where('payment_status', 1)
            ->where('amount', '>', 0)
            ->count();

        $unpaid_payment_count = Payment::where('high_court_id', $application->id)
            ->where('application_type', 2)
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
            $response['badge'] = 'danger';
            $response['name'] = 'Unpaid';
        }

        return $response;
    }
}

if (!function_exists('getHcGeneralFund')) {
    function getHcGeneralFund($application)
    {
        $exemption_fee = 0;
        if ($application->hc_exemption_at) {
            $exemption_fee = 30000; // 20000
        }

        $amount = [
            'enrollment_fee' => 665,
            'id_card_fee' => 1200,
            'certificate_fee' =>  2000,
            'building_fund' => 2000,
            'general_fund' => 10000,
            'degree_fee' => 0,
            'exemption_fee' => $exemption_fee,
        ];

        $amount['total'] = $amount['enrollment_fee'] + $amount['id_card_fee'] + $amount['certificate_fee'] + $amount['building_fund'] + $amount['general_fund'] + $amount['degree_fee'] + $amount['exemption_fee'];

        return $amount;
    }
}

if (!function_exists('getHcHomeAddress')) {
    function getHcHomeAddress($application_id)
    {
        $address = LawyerAddress::where('high_court_id', $application_id)->first();

        if ($address != null) {
            return  $address->ha_house_no . ', ' . $address->ha_city  . ', ' . getCountryName($address->ha_country_id);
        } else {
            return '-';
        }
    }
}

if (!function_exists('getHcPostalAddress')) {
    function getHcPostalAddress($application_id)
    {
        $address = LawyerAddress::where('high_court_id', $application_id)->first();

        if ($address != null) {
            return  $address->pa_house_no . ', ' . $address->pa_city  . ', ' . getCountryName($address->pa_country_id);
        } else {
            return '-';
        }
    }
}

if (!function_exists('getHcTransactionNo')) {
    function getHcTransactionNo($id, $vch_no)
    {
        try {
            $hc = HighCourt::find($id);
            $transaction_no = $hc->payments()->where('voucher_type', $vch_no)->first()->transaction_id;
        } catch (\Throwable $th) {
            $transaction_no = '';
        }

        return $transaction_no;
    }
}

if (!function_exists('getHcStatuses')) {
    function getHcStatuses($id)
    {
        try {
            $response = [];
            $lc = HighCourt::find($id);
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

if (!function_exists('moveToHighCourt')) {
    function moveToHighCourt($user_id)
    {
        DB::beginTransaction();

        try {
            $user = User::find($user_id);

            if ($user->register_as == 'lc') {
                $lower_court = LowerCourt::where('user_id', $user->id)->first();
                moveToHcCheckCnicExist($lower_court->cnic_no);

                if ($lower_court) {
                    $data = [
                        'user_id' => $user->id,
                        'sr_no_hc' => $user->sr_no_hc,
                        'is_frontend' => true,
                        'app_status' => 6,
                        'app_type' => 6,
                        'age' => $lower_court->age,
                        'enr_date_lc' => $lower_court->lc_date,
                        'llb_year' => $lower_court->llb_passing_year,
                        'enr_status_type' => $lower_court->enr_status_type,
                        'enr_status_reason' => $lower_court->enr_status_reason,
                        'lc_ledger' => $lower_court->reg_no_lc,
                        'lc_sdw' => $lower_court->lc_sdw,
                        'lc_last_status' => $lower_court->app_type,
                        'lc_lic' => $lower_court->license_no_lc,
                        'is_practice_in_pbc' => $lower_court->is_practice_in_pbc,
                        'is_engaged_in_business' => $lower_court->is_engaged_in_business,
                        'is_declared_insolvent' => $lower_court->is_declared_insolvent,
                        'is_dismissed_from_public_service' => $lower_court->is_dismissed_from_gov,
                        'is_enrolled_as_advocate' => $lower_court->is_enrolled_as_adv,
                        'is_prev_rejected' => $lower_court->is_prev_rejected,
                        'is_any_misconduct' => $lower_court->is_any_misconduct,
                        'voter_member_hc' => $lower_court->voter_member_lc,
                        'bf_no_hc' => $lower_court->bf_no_lc,
                        'hc_exemption_at' => $lower_court->hc_exemption_at,
                    ];

                    $user->update([
                        'register_as' => 'hc',
                        'father_name' => $lower_court->father_name,
                        'gender' => $lower_court->gender,
                        'date_of_birth' => setDateFormat($lower_court->date_of_birth),
                        'cnic_no' => $lower_court->cnic_no,
                        'cnic_expired_at' => setDateFormat($lower_court->cnic_expiry_date),
                        'blood' => $lower_court->blood,
                    ]);

                    $high_court = HighCourt::updateOrCreate(['user_id' => $user->id], $data);
                    $high_court->update([
                        'move_from_lc' => true,
                        'move_from_lc_at' => Carbon::now(),
                    ]);

                    $lawyer_address = LawyerAddress::where('lower_court_id', $lower_court->id)->first();
                    $lawyer_address->update(['high_court_id' => $high_court->id]);

                    $lawyer_uploads = LawyerUpload::where('lower_court_id', $lower_court->id)->first();
                    if ($lawyer_uploads) {
                        $lawyer_uploads->update(['high_court_id' => $high_court->id]);
                    }

                    $lower_court->update([
                        'move_to_hc' => true,
                        'move_to_hc_at' => Carbon::now(),
                        'app_type' => 7,
                    ]);
                }
            }

            if ($user->register_as == 'gc_lc') {
                $gc_lc = GcLowerCourt::where('user_id', $user->id)->first();
                gcMoveToHcCheckCnicExist($gc_lc->cnic_no);

                if ($gc_lc) {
                    $data = [
                        'user_id' => $user->id,
                        'sr_no_hc' => $user->sr_no_hc,
                        'is_frontend' => true,
                        'app_status' => 6,
                        'app_type' => 6,
                        'age' => $gc_lc->age,
                        'enr_date_lc' => $gc_lc->date_of_enrollment_lc,
                        'enr_status_reason' => $gc_lc->enr_status_reason,
                        'lc_ledger' => $gc_lc->reg_no_lc,
                        'lc_lic' => $gc_lc->license_no_lc,
                        'voter_member_hc' => $gc_lc->voter_member_lc,
                        'lc_last_status' => $gc_lc->app_type,
                        'bf_no_hc' => $gc_lc->bf_no_lc,
                    ];

                    $user->update([
                        'register_as' => 'hc',
                        'father_name' => $gc_lc->father_name,
                        'gender' => $gc_lc->gender,
                        'date_of_birth' => $gc_lc->date_of_birth,
                        'cnic_no' => $gc_lc->cnic_no,
                    ]);

                    $high_court = HighCourt::updateOrCreate(['user_id' => $user->id], $data);
                    $high_court->update([
                        'move_from_lc' => true,
                        'move_from_lc_at' => Carbon::now(),
                    ]);

                    LawyerAddress::updateOrCreate(['high_court_id' => $high_court->id], [
                        'ha_house_no' => $gc_lc->address_1,
                    ]);

                    $profile_image = isset($user->getFirstMedia('gc_profile_image')->id) ? $user->getFirstMedia('gc_profile_image')->id . '/' . $user->getFirstMedia('gc_profile_image')->file_name : NULL;
                    LawyerUpload::updateOrCreate(['high_court_id' => $high_court->id], [
                        'profile_image' => $profile_image,
                        'cnic_front' => $user->getFirstMedia('gc_cnic_front')->id . '/' . $user->getFirstMedia('gc_cnic_front')->file_name,
                        'cnic_back' => $user->getFirstMedia('gc_cnic_back')->id . '/' . $user->getFirstMedia('gc_cnic_back')->file_name,
                        'lc_card_front' => $user->getFirstMedia('gc_license_front')->id . '/' . $user->getFirstMedia('gc_license_front')->file_name,
                        'lc_card_back' => $user->getFirstMedia('gc_license_back')->id . '/' . $user->getFirstMedia('gc_license_back')->file_name,
                    ]);

                    $gc_lc->update([
                        'move_to_hc' => true,
                        'move_to_hc_at' => Carbon::now(),
                        'app_type' => 7,
                    ]);
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
            DB::rollback();
            return redirect()->route('frontend.dashboard');
        }
    }
}

function highCourtShortDetail($id, $type)
{
    try {
        $data = [];
        $generator = new BarcodeGeneratorDynamicHTML();

        if ($type == 'hc') {
            $record = HighCourt::find($id);

            $objections = [];
            if (isset($record->objections)) {
                foreach (json_decode($record->objections, TRUE) as $key => $value) {
                    $objections[] = ++$key . '-' . getHcObjections($value);
                }
            }

            $data = [
                'user_id' => $record->user_id,
                'app_no' => $record->id,
                'rcpt_no' => '<span>' . $record->rcpt_date . '</span> <br> <span class="badge badge-secondary">' . $record->rcpt_no_lc . '</span>',
                'hcr_no' => $record->hcr_no_hc,
                'lic_no' => $record->license_no_hc,
                'bf_no' => $record->bf_no_hc,
                'plj_no' => $record->plj_no_hc,
                'gi_no' => $record->gi_no_hc,
                'lawyer_name' => $record->lawyer_name,
                'sdw_name' => $record->user->father_name,
                'cnic_no' => $record->user->cnic_no,
                'gender' => $record->user->gender,
                'address' => getHcPostalAddress($record->id),
                'contact' => $record->mobile_no,
                'email' => $record->user->email,
                'enr_date' => getDateFormat($record->enr_date_hc),
                'dob' => getDateFormat($record->user->date_of_birth),
                'voter_member' => str_replace('BAR ASSOCIATION', '', getBarName($record->voter_member_hc)),
                'app_status' => appStatus($record->app_status, $record->app_type),
                'other_status' => getHcStatuses($record->id)['res'],
                'payment_status' => getHcPaymentStatus($record->id)['name'] . ' Payment',
                'status_reason' => implode('<br>', $objections),
                'img_url' => isset($record->uploads->profile_image) ? asset('storage/app/public/' . $record->uploads->profile_image) : 'https://portal.pbbarcouncil.com/public/admin/images/no-image.png',
                'bar_code' => $generator->getBarcode($record->cnic_no, $generator::TYPE_CODE_128),
                // LOWER COURT
                'lc_ledger' => $record->lc_ledger,
                'enr_date_lc' => getDateFormat($record->enr_date_lc),
                'lc_lic' => $record->lc_lic,
            ];
        }

        if ($type == 'gc_hc') {
            $record = GcHighCourt::find($id);
            $user = User::find($record->user_id);

            if ($user && isset($user->getFirstMedia('gc_profile_image')->id) && $user->getFirstMedia('gc_profile_image')->id) {
                $image_url = asset('storage/app/public/' . $user->getFirstMedia('gc_profile_image')->id . '/' . $user->getFirstMedia('gc_profile_image')->file_name);
            } else {
                $image_url = 'https://portal.pbbarcouncil.com/storage/app/public/applications/profile-images/HC/' . $record->image;
                if (!file_exists($image_url)) {
                    $image_url = 'https://portal.pbbarcouncil.com/public/admin/images/no-image.png';
                }
            }

            $data = [
                'user_id' => $record->user_id,
                'app_no' => $record->id,
                'rcpt_no' => NULL,
                'hcr_no' => $record->hcr_no_hc,
                'lic_no' => $record->license_no_hc,
                'bf_no' => $record->bf_no_hc,
                'plj_no' => $record->plj_no_hc,
                'gi_no' => $record->gi_no_hc,
                'lawyer_name' => $record->lawyer_name,
                'sdw_name' => $record->father_name,
                'cnic_no' => $record->cnic_no,
                'gender' => $record->gender,
                'address' => $record->address_1,
                'contact' => $record->contact_no,
                'email' => $record->email,
                'enr_date' => getDateFormat($record->enr_date_hc),
                'dob' => getDateFormat($record->date_of_birth),
                'voter_member' => str_replace('BAR ASSOCIATION', '', getBarName($record->voter_member_hc)),
                'app_status' => appStatus($record->app_status, $record->app_type),
                'payment_status' => NULL,
                'other_status' => NULL,
                'status_reason' => $record->enr_status_reason,
                'img_url' => $image_url,
                'bar_code' => $generator->getBarcode($record->cnic_no, $generator::TYPE_CODE_128),
                // LOWER COURT
                'lc_ledger' => $record->lc_ledger,
                'enr_date_lc' => getDateFormat($record->enr_date_lc),
                'lc_lic' => $record->lc_lic,
            ];
        }

        return $data;
    } catch (\Throwable $th) {
        throw $th;
    }
}

if (!function_exists('getHcObjections')) {
    function getHcObjections($id)
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

if (!function_exists('getHcAdvocateCertificateStatus')) {
    function getHcAdvocateCertificateStatus($application)
    {
        $res = false;
        $date = false;
        $enr_date_hc = $application->enr_date_hc;

        if ($enr_date_hc) {
            $lc_next_month_date = date("d-m-Y", strtotime('+2 month', strtotime($enr_date_hc)));
            if (Carbon::parse(Carbon::now())->gte(Carbon::parse($lc_next_month_date))) {
                $date = true;
            }
        }

        if ($application->license_no_hc && $date == true && getHcPaymentStatus($application->id)['name'] == 'Paid') {
            $res = true;
        }

        return $res;
    }
}
