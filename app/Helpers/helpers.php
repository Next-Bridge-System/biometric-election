<?php

use App\Application;
use App\AppStatus;
use App\AppType;
use App\Bar;
use App\Certificate;
use App\Division;
use App\GcHighCourt;
use App\GcLowerCourt;
use App\HighCourt;
use App\LowerCourt;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

if (!function_exists('getAdminName')) {
    function getAdminName($id)
    {
        $admin = App\Admin::find($id);
        $getAdminName = ($admin->name ?? '');
        return $getAdminName;
    }
}

if (!function_exists('getCountryName')) {
    function getCountryName($id)
    {
        $country = App\Country::find($id);
        $getCountryName = ($country->name ?? 'N/A');
        return $getCountryName;
    }
}

if (!function_exists('getProvinceName')) {
    function getProvinceName($id)
    {
        $province = App\Province::find($id);
        $getProvinceName = ($province->name ?? 'N/A');
        return $getProvinceName;
    }
}

if (!function_exists('getDivisionName')) {
    function getDivisionName($id)
    {
        $division = App\Division::find($id);
        $getDivisionName = ($division->name ?? NULL);
        return $getDivisionName;
    }
}

if (!function_exists('getDistrictName')) {
    function getDistrictName($id)
    {
        $district = App\District::find($id);
        $getDistrictName = ($district->name ?? 'N/A');
        return $getDistrictName;
    }
}

if (!function_exists('getTehsilName')) {
    function getTehsilName($id)
    {
        $tehsil = App\Tehsil::find($id);
        $getTehsilName = ($tehsil->name ?? 'N/A');
        return $getTehsilName;
    }
}

if (!function_exists('getLawyerName')) {
    function getLawyerName($id)
    {
        $getLawyerName = null;
        if (isset($id) && $id != null) {
            $application = App\Application::find($id);
            if (isset($application->advocates_name) && isset($application->last_name)) {
                $getLawyerName = $application->advocates_name . ' ' . $application->last_name;
            } else if (isset($application->advocates_name)) {
                $getLawyerName = $application->advocates_name;
            }
        }
        return $getLawyerName;
    }
}

if (!function_exists('getBarName')) {
    function getBarName($id)
    {
        $bar = \App\Bar::find($id);
        $barName = ($bar->name ?? NULL);
        return $barName;
    }
}

if (!function_exists('getVoucherAmount')) {
    function getVoucherAmount($id)
    {
        $application = App\Application::find($id);
        $age = $application->age;

        if ($application->final_submitted_at) {
            $final_date = $application->final_submitted_at;
        } else {
            $final_date = $application->created_at;
        }

        $degree_amount = 0;
        if (isset($application->degree_place)) {
            if ($application->degree_place == 1) {
                $degree_amount = 3000;
            } elseif ($application->degree_place == 2) {
                $degree_amount = 5000;
            } elseif ($application->degree_place == 3) {
                $degree_amount = 25000;
            }
        }

        if ($final_date != NULL) {
            $policy = \App\Policy::where('application_type', 1)->whereDate('start_date', '<=', $final_date)->whereDate('end_date', '>=', $final_date)->first();
            if ($policy != NULL) {
                if (count($policy->policyFees) == 5) {
                    foreach ($policy->policyFees as $key => $item) {
                        if ($key + 1 == count($policy->policyFees)) {
                            if (str_contains('60+', $item->from) && $age >= 60) {
                                return [
                                    'total_amount' => $item->amount + $degree_amount,
                                    'enr_fee_pbc' => $item->amount,
                                    'degree_fee' => $degree_amount,
                                ];
                                // return $item->amount + $degree_amount;
                            }
                        } else {
                            if ($age >= $item->from && $age <= $item->to) {
                                return [
                                    'total_amount' => $item->amount + $degree_amount,
                                    'enr_fee_pbc' => $item->amount,
                                    'degree_fee' => $degree_amount,
                                ];
                                // return $item->amount + $degree_amount;
                            }
                        }
                    }
                }
            }
        }

        // if ($age >= 1 && $age < 35) {
        //     $amount = $degree_amount + 2000;
        // }

        // if ($age >= 35 && $age < 40) {
        //     $amount = $degree_amount + 5500;
        // }

        // if ($age >= 40 && $age < 50) {
        //     $amount = $degree_amount + 15000;
        // }

        // if ($age >= 50 && $age < 60) {
        //     $amount = $degree_amount + 25000;
        // }

        // if ($age >= 60) {
        //     $amount = $degree_amount + 40000;
        // }


    }
}


if (!function_exists('getQualificationName')) {
    function getQualificationName($key)
    {
        $name = NULL;
        switch ($key) {
            case $key == 1:
                $name = 'Metric';
                break;
            case $key == 2:
                $name = 'Intermediate';
                break;
            case $key == 3:
                $name = 'BA / BA HONS';
                break;
            case $key == 4:
                $name = 'LLB PART-1';
                break;
            case $key == 5:
                $name = 'LLB PART-2';
                break;
            case $key == 6:
                $name = 'LLB PART-3';
                break;
            case $key == 7:
                $name = 'LLB Hons / Bar at Law';
                break;
            case $key == 8:
                $name = 'MA / MSC / LLM';
                break;
            case $key == 9:
                $name = 'LAW-GAT';
                break;
            case $key == 10:
                $name = 'C-Law';
                break;
            default:
                break;
        }

        return $name;
    }
}

if (!function_exists('getApplicationType')) {
    function getApplicationType($id)
    {
        $application = App\Application::find($id);
        $name = NULL;

        if (isset($application->application_type)) {
            $type = $application->application_type;

            switch ($type) {
                case $type == 1:
                    $name = 'Lower Court';
                    break;
                case $type == 2:
                    $name = 'High Court';
                    break;
                case $type == 3:
                    $name = 'Renewal High Court';
                    break;
                case $type == 4:
                    $name = 'Renewal Lower Court';
                    break;
                case $type == 5:
                    $name = 'Existing Lawyer';
                    break;
                case $type == 6:
                    $name = 'Intimation';
                    break;
                default:
                    break;
            }
        }

        return $name;
    }
}

if (!function_exists('getApplicationTokenNo')) {
    function getApplicationTokenNo($id)
    {
        $application = App\Application::find($id);

        return $application->application_token_no;
    }
}

if (!function_exists('getUserName')) {
    function getUserName($id)
    {
        $application = App\Application::find($id);
        return $application->advocates_name . ' ' . $application->last_name ?? '- -';
    }
}

if (!function_exists('sendMessageAPI')) {
    function sendMessageAPI($data)
    {
        // Lifetime SMS API

        try {
            $phone  = preg_replace('/[^A-Za-z0-9\-]/', '', $data['phone']);
            $url = "https://lifetimesms.com/otp";

            // Lower Court - Candidate Members
            if ($data['event_id'] == 195) {
                $sms_data = [
                    "member_name" => $data['member_name'],
                    "member_contact" => $data['member_contact'],
                ];
            }

            // Lower Court - Candidate Interview Code
            if ($data['event_id'] == 193) {
                $sms_data = [
                    "candidate_cnic" => $data['candidate_cnic'],
                    "candidate_code" => $data['candidate_code'],
                ];
            }

            // Submitted Application
            if ($data['event_id'] == 141) {
                $sms_data = [
                    "code" => $data['code'],
                    "email" => $data['email'],
                    "passcode" => $data['passcode'],
                ];
            }

            //	OTP - PBC || OTP - FORGET PBC ||  CERTIFICATE - LINKS
            if ($data['event_id'] == 79 || $data['event_id'] == 83 || $data['event_id'] == 91) {
                $sms_data = array("code" => $data['otp']);
            }

            $sms_data = json_encode($sms_data);
            $parameters = [
                "api_token" => "f37729c81902ffcfdf98b00d6cd7fa4930e4c77135",
                "api_secret" => "pbc_api_secret_key",
                "to" => $phone,
                "from" => "8584",
                "event_id" => $data['event_id'],
                "data" => $sms_data,
            ];

            $ch = curl_init();
            $timeout  =  30;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            $response = curl_exec($ch);
            curl_close($ch);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

if (!function_exists('sendMailToUser')) {
    function sendMailToUser($data, $user)
    {
        try {
            $res = Mail::to($user)->bcc('moizchauhdry01@gmail.com')->send(new \App\Mail\GeneralMail($data));
        } catch (\Throwable $e) {
            \Log::info($e);
        }
    }
}

if (!function_exists('sendMailToUsers')) {
    function sendMailToUsers($data, $users)
    {
        foreach ($users as $user) {
            try {
                Mail::to($user)->bcc('moizchauhdry01@gmail.com')->send(new \App\Mail\GeneralMail($data));
            } catch (\Throwable $e) {
                \Log::info($e);
            }
        }
    }
}

if (!function_exists('getAccessToken')) {
    function getAccessToken()
    {
        $token = Str::random(80) . time();
        return $token;
    }
}

if (!function_exists('getLawyerMobileNo')) {
    function getLawyerMobileNo($id)
    {
        $application = App\Application::find($id);
        return $application->active_mobile_no;
    }
}

if (!function_exists('getApplicationStatus')) {
    function getApplicationStatus($id)
    {
        $application = App\Application::find($id);
        $name = NULL;

        if (isset($application->application_status)) {
            $status = $application->application_status;

            switch ($status) {
                case $status == 1:
                    $name = 'Completed'; // Active
                    break;
                case $status == 2:
                    $name = 'Suspended';
                    break;
                case $status == 3:
                    $name = 'Died';
                    break;
                case $status == 4:
                    $name = 'Removed';
                    break;
                case $status == 5:
                    $name = 'Transfer in';
                    break;
                case $status == 6:
                    $name = 'Transfer out';
                    break;
                case $status == 7:
                    $name = 'In Progress'; // Pending
                    break;
                case $status == 0:
                    $name = 'Rejected';
                    break;
                default:
                    break;
            }
        }

        return $name;
    }
}

if (!function_exists('getCardStatus')) {
    function getCardStatus($id)
    {
        $application = App\Application::find($id);
        $card_status = $application->card_status;
        $value = NULL;

        switch ($card_status) {
            case $card_status == 1:
                $value = 'Pending';
                break;
            case $card_status == 2:
                $value = 'Printing';
                break;
            case $card_status == 3:
                $value = 'Dispatched';
                break;
            case $card_status == 4:
                $value = 'By Hand';
                break;
            case $card_status == 5:
                $value = 'Done';
                break;
            default:
                break;
        }

        return $value;
    }
}

if (!function_exists('createActivity')) {
    function createActivity($model, $type)
    {
        $modelName = explode('\\', get_class($model));
        if ($type == "Application" && $modelName[1] == 'Application') {
            $application = \App\Application::find($model->id);
        } else {
            $application = \App\Application::find($model->application_id);
        }


        $log = new \App\ActivityLog();
        if (!$model->wasRecentlyCreated) {
            $changes = $model->getChanges();
        } else {
            $changes = $model->getOriginal();
            $log->is_created = true;
        }
        unset($changes['updated_at']);
        if (empty($changes)) {
            return true;
        }
        $log->log = json_encode($changes);
        $log->user_id = $application->user_id;
        $log->application_id = $application->id;
        $log->type = $type;
        $log->admin_id = Auth::guard('admin')->user()->id ?? NULL;
        $log->activity_at = \Carbon\Carbon::now()->format('Y-m-d');
        $log->save();
        return true;
    }
}

if (!function_exists('createUploadActivity')) {
    function createUploadActivity($model, $type)
    {
        $modelName = explode('\\', get_class($model));
        if ($type == "Application" && $modelName[1] == 'Application') {
            $application = \App\Application::find($model->id);
        } else {
            $application = \App\Application::find($model->application_id);
        }


        $log = new \App\ActivityLog();
        if (!$model->wasRecentlyCreated) {
            $changes = $model->getChanges();
            unset($changes['updated_at']);
        }
        if (empty($changes)) {
            return true;
        }
        $log->log = json_encode($changes);
        $log->user_id = $application->user_id;
        $log->application_id = $application->id;
        $log->type = $type;
        $log->admin_id = Auth::guard('admin')->user()->id ?? NULL;
        $log->activity_at = \Carbon\Carbon::now()->format('Y-m-d');
        $log->is_media = True;
        $log->save();
        return true;
    }
}

if (!function_exists('createDeleteActivity')) {
    function createDeleteActivity($id, $type, $modal)
    {
        $application = \App\Application::find($id);
        $log = new \App\ActivityLog();
        $record = array('Record' => $modal);
        $log->log = json_encode($record);
        $log->user_id = $application->user_id;
        $log->application_id = $application->id;
        $log->type = $type;
        $log->admin_id = Auth::guard('admin')->user()->id ?? NULL;
        $log->activity_at = \Carbon\Carbon::now()->format('Y-m-d');
        $log->save();
        return true;
    }
}

if (!function_exists('getKeyTitle')) {
    function getKeyTitle($key)
    {
        $data = [
            'id' => 'ID',
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

        ];

        return $data[$key] ?? $key;
    }
}

if (!function_exists('getLogData')) {
    function getLogData($key, $log, $applicationID)
    {
        $objections_array = [];
        if ($key == 'objections' && isset($log)) {
            foreach (json_decode($log, TRUE) as  $value) {
                $objections_array[] = getObjections($value);
            }
        }

        $data = [
            'admin_id' => getAdminName($log),
            //            'user_id' => getUserName($applicationID),
            'srl_bar_name' => getBarName($log),
            'bar_association' => getBarName($log),
            'application_type' => getApplicationType($applicationID),
            'application_status' => getApplicationStatus($applicationID),
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
            'objections' => $objections_array != null ? json_encode($objections_array) : 'ALL OBJECTIONS REMOVED.',
            'acct_dept_payment_status' => $log ? 'Approved' : 'Not Approved',
        ];


        return $data[$key] ?? $log;
    }
}

if (!function_exists('getPaymentStatus')) {
    function getPaymentStatus($id)
    {
        $application = App\Application::find($id);
        $payments = \App\Payment::select('id', 'payment_status', 'application_id')->where('application_id', $application->id)
            ->where('application_type', $application->application_type)
            ->get();
        $response = [];

        if ($payments->count() == $payments->where('payment_status', 1)->count() && $payments->count() > 0) {
            $response['badge'] = 'success';
            $response['name'] = 'Paid';
        } elseif ($payments->count() == $payments->where('payment_status', 0)->count() && $payments->count() > 0) {
            $response['badge'] = 'danger';
            $response['name'] = 'Unpaid';
        } elseif ($payments->count() > $payments->where('payment_status', 0)->count() && $payments->count() > 0) {
            $response['badge'] = 'warning';
            $response['name'] = 'Partial Paid';
        } else {
            $response['badge'] = 'info';
            $response['name'] = 'Unknown';
        }

        return $response;
    }
}

if (!function_exists('getVoterMemberName')) {
    function getVoterMemberName($value)
    {
        $bar = Bar::find($value);
        if (isset($bar)) {
            $voter_member_lc = str_replace('BAR ASSOCIATION', '', $bar->name);
        } else {
            $voter_member_lc = $value;
        }

        return $voter_member_lc;
    }
}

if (!function_exists('transformDate')) {
    function transformDate($value, $format = 'd-m-Y')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }
}

if (!function_exists('getStatus')) {
    function getStatus($status)
    {
        switch ($status) {
            case $status == 1:
                $name = 'Active';
                break;
            case $status == 2:
                $name = 'Suspended';
                break;
            case $status == 3:
                $name = 'Died';
                break;
            case $status == 4:
                $name = 'Removed';
                break;
            case $status == 5:
                $name = 'Transfer in';
                break;
            case $status == 6:
                $name = 'Transfer out';
                break;
            case $status == 7:
                $name = 'Pending';
                break;
            case $status == 0:
                $name = 'Rejected';
                break;
            default:
                break;
        }

        return $name;
    }
}


if (!function_exists('getManagePaymentStatus')) {
    function getManagePaymentStatus($id)
    {
        $response = [];
        $payment = App\Payment::find($id);

        if ($payment->payment_status == 1) {
            $response['badge'] = 'success';
            $response['name'] = 'Paid';
        } elseif ($payment->payment_status == 0) {
            $response['badge'] = 'danger';
            $response['name'] = 'Unpaid';
        } else {
            $response['badge'] = 'info';
            $response['name'] = 'Pending';
        }

        return $response;
    }
}

if (!function_exists('getAcctDeptStatus')) {
    function getAcctDeptStatus($id)
    {
        $response = [];
        $payment = App\Payment::find($id);

        if ($payment->acct_dept_payment_status == 1) {
            $response['badge'] = 'success';
            $response['name'] = 'Approved';
        } elseif ($payment->acct_dept_payment_status == 0) {
            $response['badge'] = 'danger';
            $response['name'] = 'Not Approved';
        } else {
            $response['badge'] = 'info';
            $response['name'] = 'Pending';
        }

        return $response;
    }
}

/**
 * @param int $number
 * @return string
 */
function numberToRomanRepresentation($number)
{
    $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
    $returnValue = '';
    while ($number > 0) {
        foreach ($map as $roman => $int) {
            if ($number >= $int) {
                $number -= $int;
                $returnValue .= $roman;
                break;
            }
        }
    }
    return $returnValue;
}

if (!function_exists('getCertificateAmount')) {
    function getCertificateAmount($id)
    {
        $certificate = Certificate::find($id);
        if ($certificate->type == 1) {
            $amount = 1000;
        } else {
            $amount = 3000;
        }
        return $amount;
    }
}

if (!function_exists('getDateFormat')) {
    function getDateFormat($date)
    {
        try {
            $format_date = null;
            if (isset($date)) {
                $format_date = Carbon::parse($date)->format('d-m-Y');
            }
            return $format_date;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

if (!function_exists('setDateFormat')) {
    function setDateFormat($date)
    {
        $format_date = null;
        if (isset($date)) {
            $format_date = Carbon::parse($date)->format('Y-m-d');
        }
        return $format_date;
    }
}


if (!function_exists('permission')) {
    function permission($permission)
    {
        if (auth()->guard('admin')->user()) {
            return  auth()->guard('admin')->user()->hasPermission($permission);
        } else {
            return false;
        }
    }
}

function clean($string)
{
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    return preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.
}

function otp()
{
    $digits = 6;
    $otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
    return $otp;
}

if (!function_exists('user_gc_status')) {
    function user_gc_status($status)
    {
        try {
            if ($status == 'approved') {
                $response['name'] = 'Approved';
                $response['badge'] = 'success';
            } else if ($status == 'disapproved') {
                $response['name'] = 'Disapproved';
                $response['badge'] = 'danger';
            } else {
                $response['name'] = 'Pending';
                $response['badge'] = 'warning';
            }

            return $response;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

function appStatus($status, $type)
{
    try {
        $result = getAppStatusOnly($status);
        $result .= getAppTypeOnly($type);
        return $result;
    } catch (\Throwable $th) {
        //throw $th;
    }
}

function getAppStatusOnly($status)
{
    try {
        $app_status = AppStatus::where('key', $status)->first();
        $result = '<span style="margin:2px;" class="badge badge-' . $app_status->label . '">' . $app_status->value . '</span>';
        return $result;
    } catch (\Throwable $th) {
        //throw $th;
    }
}

function getAppTypeOnly($type)
{
    try {
        $app_type = AppType::where('key', $type)->first();
        $result = '<span style="margin:2px;" class="badge badge-' . $app_type->label . '">' . $app_type->value . '</span>';
        return $result;
    } catch (\Throwable $th) {
        //throw $th;
    }
}

function resetDashboard($user_id, $register_as)
{
    $record = null;

    if ($register_as == 'lc') {
        $record = LowerCourt::where('user_id', $user_id)->where('app_type', 5)->first();
    }

    if ($register_as == 'gc_lc') {
        $record = GcLowerCourt::where('user_id', $user_id)->where('app_type', 5)->first();
    }

    if ($register_as == 'hc') {
        $record = HighCourt::where('user_id', $user_id)->where('app_type', 6)->first();
    }

    if ($register_as == 'gc_hc') {
        $record = GcHighCourt::where('user_id', $user_id)->where('app_type', 6)->first();
    }

    if ($record) {
        return 0;  // DONT SHOW RESET BUTTIN
    } else {
        return 1; // SHOW RESET BUTTIN
    }
}


function checkCnicExist($cnic)
{
    $intimation = Application::where('cnic_no', $cnic)->first();
    if (isset($intimation)) {
        abort(403, 'The cnic already exist in the intimation application');
    }

    $lc = LowerCourt::where('cnic_no', $cnic)->first();
    if (isset($lc)) {
        abort(403, 'The cnic already exist in the lower court application');
    }

    $gc_lc = GcLowerCourt::where('cnic_no', $cnic)->first();
    if (isset($gc_lc)) {
        abort(403, 'The cnic already exist in the gc lower court application');
    }

    // $hc = HighCourt::where('cnic_no', $cnic)->first();
    $hc = User::where('register_as', 'hc')->where('cnic_no', $cnic)->first();
    if (isset($hc)) {
        abort(403, 'The cnic already exist in the high court application');
    }

    $gc_hc = GcHighCourt::where('cnic_no', $cnic)->first();
    if (isset($gc_hc)) {
        abort(403, 'The cnic already exist in the gc high court application');
    }
}

function gcCheckCnicExist($cnic)
{
    $intimation = Application::where('cnic_no', $cnic)->first();
    if (isset($intimation)) {
        abort(403, 'The cnic already exist in the intimation application');
    }

    $lc = LowerCourt::where('cnic_no', $cnic)->first();
    if (isset($lc)) {
        abort(403, 'The cnic already exist in the lower court application');
    }

    // $hc = HighCourt::where('cnic_no', $cnic)->first();
    $hc = User::where('register_as', 'hc')->where('cnic_no', $cnic)->first();

    if (isset($hc)) {
        abort(403, 'The cnic already exist in the high court application');
    }
}


function moveToLcCheckCnicExist($cnic)
{

    $lc = LowerCourt::where('cnic_no', $cnic)->first();
    if (isset($lc)) {
        abort(403, 'The cnic already exist in the lower court application');
    }

    // $hc = HighCourt::where('cnic_no', $cnic)->first();
    $hc = User::where('register_as', 'hc')->where('cnic_no', $cnic)->first();

    if (isset($hc)) {
        abort(403, 'The cnic already exist in the high court application');
    }

    $gc_lc = GcLowerCourt::where('app_status', "!=", 9)->where('cnic_no', $cnic)->first();
    if (isset($gc_lc)) {
        abort(403, 'The cnic already exist in the gc lower court application');
    }

    $gc_hc = GcHighCourt::where('cnic_no', $cnic)->first();
    if (isset($gc_hc)) {
        abort(403, 'The cnic already exist in the gc high court application');
    }
}

function moveToHcCheckCnicExist($cnic)
{
    // $hc = HighCourt::where('cnic_no', $cnic)->first();
    $hc = User::where('register_as', 'hc')->where('cnic_no', $cnic)->first();

    if (isset($hc)) {
        abort(403, 'The cnic already exist in the high court application');
    }

    $gc_lc = GcLowerCourt::where('app_status', '!=', 9)->where('cnic_no', $cnic)->first();
    if (isset($gc_lc)) {
        abort(403, 'The cnic already exist in the gc lower court application');
    }

    $gc_hc = GcHighCourt::where('cnic_no', $cnic)->first();
    if (isset($gc_hc)) {
        abort(403, 'The cnic already exist in the gc high court application');
    }
}

function gcMoveToHcCheckCnicExist($cnic)
{
    $intimation = Application::where('cnic_no', $cnic)->first();
    if (isset($intimation)) {
        abort(403, 'The cnic already exist in the intimation application');
    }

    $lc = LowerCourt::where('cnic_no', $cnic)->first();
    if (isset($lc)) {
        abort(403, 'The cnic already exist in the lower court application');
    }

    // $hc = HighCourt::where('cnic_no', $cnic)->first();
    $hc = User::where('register_as', 'hc')->where('cnic_no', $cnic)->first();

    if (isset($hc)) {
        abort(403, 'The cnic already exist in the high court application');
    }

    $gc_hc = GcHighCourt::where('cnic_no', $cnic)->first();
    if (isset($gc_hc)) {
        abort(403, 'The cnic already exist in the gc high court application');
    }
}

function voterMemberReport($filter)
{
    if ($filter['search_user_type'] == 'lc') {
        $lc = LowerCourt::query()
            ->join('users', 'users.id', '=', 'lower_courts.user_id')
            ->join('app_statuses', 'app_statuses.id', '=', 'lower_courts.app_status')
            ->leftJoin('lawyer_addresses', 'lower_courts.id', '=', 'lawyer_addresses.lower_court_id')
            ->select(
                'users.id as user_id',
                DB::raw('CASE WHEN users.register_as = "lc" THEN "approved" END as status'),
                'users.name as lawyer',
                'lower_courts.father_name as father',
                'lower_courts.date_of_birth as dob',
                'lower_courts.cnic_no as cnic_no',
                'lower_courts.lc_date as enr_date_lc',
                'lower_courts.reg_no_lc as lc_ledger',
                'lower_courts.license_no_lc as lc_license',
                DB::raw('CONCAT(lawyer_addresses.ha_house_no,", ",lawyer_addresses.ha_city) as address'),
                DB::raw('CONCAT("0", users.phone) as phone'),
                'app_statuses.value as app_status',
                'users.profile_image as profile_image',
            )
            ->whereNotNull('lower_courts.lc_date')
            // ->where('lower_courts.app_status', 1)
            ->where('lower_courts.app_type', '!=', 4)
            ->when($filter['search_user_status'], function ($qry) use ($filter) {
                if ($filter['search_user_status'] == 'unverified') {
                    $qry->where('lower_courts.user_id', NULL);
                }
            })
            ->when($filter['search_app_status'], function ($qry) use ($filter) {
                $qry->where('lower_courts.app_status', $filter['search_app_status']);
            })
            ->when($filter['search_gender'], function ($qry) use ($filter) {
                if ($filter['search_gender'] == 'male') {
                    $qry->where('lower_courts.gender', 'Male');
                }
                if ($filter['search_gender'] == 'female') {
                    $qry->where('lower_courts.gender', 'Female');
                }
            })
            ->when($filter['search_voter_member'], function ($qry) use ($filter) {
                $qry->where('lower_courts.voter_member_lc', $filter['search_voter_member']);
            })
            ->when($filter['search_start_date'] && $filter['search_end_date'], function ($qry) use ($filter) {
                $qry->where('lower_courts.lc_date', '>=', $filter['search_start_date']);
                $qry->where('lower_courts.lc_date', '<=', $filter['search_end_date']);
            })
            ->when($filter['search_request_date_from'] && $filter['search_request_date_to'], function ($qry) use ($filter) {
                $qry->where(function ($q) use ($filter) {
                    $q->whereDate('users.gc_requested_at', '>=', $filter['search_request_date_from']);
                    $q->whereDate('users.gc_requested_at', '<=', $filter['search_request_date_to']);
                });
            });

        $gc_lc = GcLowerCourt::query()
            ->leftJoin('users', 'users.id', '=', 'gc_lower_courts.user_id')
            ->join('app_statuses', 'app_statuses.id', '=', 'gc_lower_courts.app_status')
            ->select(
                'users.id as user_id',
                'users.gc_status as status',
                'gc_lower_courts.lawyer_name as lawyer',
                'gc_lower_courts.father_name as father',
                'gc_lower_courts.date_of_birth as dob',
                'gc_lower_courts.cnic_no as cnic_no',
                'gc_lower_courts.date_of_enrollment_lc as enr_date_lc',
                'gc_lower_courts.reg_no_lc as lc_ledger',
                'gc_lower_courts.license_no_lc as lc_license',
                DB::raw('gc_lower_courts.address_1 as address'),
                DB::raw('gc_lower_courts.contact_no as phone'),
                'app_statuses.value as app_status',
                'users.profile_image as profile_image',
            )
            ->whereNotNull('gc_lower_courts.date_of_enrollment_lc')
            // ->where('gc_lower_courts.app_status', 1)
            ->where('gc_lower_courts.app_type', '!=', 4)
            ->when($filter['search_gender'], function ($qry) use ($filter) {
                if ($filter['search_gender'] == 'male') {
                    $qry->where('gc_lower_courts.enr_app_sdw', 1);
                }
                if ($filter['search_gender'] == 'female') {
                    $qry->whereIn('gc_lower_courts.enr_app_sdw', [2, 3]);
                }
            })
            ->when($filter['search_voter_member'], function ($qry) use ($filter) {
                $qry->where('gc_lower_courts.voter_member_lc', $filter['search_voter_member']);
            })
            ->when($filter['search_user_status'], function ($qry) use ($filter) {
                if ($filter['search_user_status'] == 'verified') {
                    $qry->where('users.gc_status', 'approved');
                }
                if ($filter['search_user_status'] == 'unverified') {
                    $qry->where(function ($q) use ($filter) {
                        $q->where('users.gc_status', "!=", 'approved');
                        $q->orWhere('gc_lower_courts.user_id', NULL);
                    });
                }
            })
            ->when($filter['search_app_status'], function ($qry) use ($filter) {
                $qry->where('gc_lower_courts.app_status', $filter['search_app_status']);
            })
            ->when($filter['search_start_date'] && $filter['search_end_date'], function ($qry) use ($filter) {
                $qry->where('gc_lower_courts.date_of_enrollment_lc', '>=', $filter['search_start_date']);
                $qry->where('gc_lower_courts.date_of_enrollment_lc', '<=', $filter['search_end_date']);
            })
            ->when($filter['search_request_date_from'] && $filter['search_request_date_to'], function ($qry) use ($filter) {
                $qry->where(function ($q) use ($filter) {
                    $q->whereDate('users.gc_requested_at', '>=', $filter['search_request_date_from']);
                    $q->whereDate('users.gc_requested_at', '<=', $filter['search_request_date_to']);
                });
            });

        $records = $lc->union($gc_lc)->orderBy('enr_date_lc', 'asc');
    }

    if ($filter['search_user_type'] == 'hc') {
        $hc = HighCourt::query()
            ->join('users', 'users.id', '=', 'high_courts.user_id')
            ->join('app_statuses', 'app_statuses.id', '=', 'high_courts.app_status')
            ->leftJoin('lawyer_addresses', 'high_courts.id', '=', 'lawyer_addresses.high_court_id')
            ->select(
                'users.id as user_id',
                DB::raw('CASE WHEN users.register_as = "hc" THEN "approved" END as status'),
                'users.name as lawyer',
                'users.father_name as father',
                'users.date_of_birth as dob',
                'users.cnic_no as cnic_no',
                'high_courts.enr_date_hc as enr_date_hc',
                'high_courts.enr_date_lc as enr_date_lc',
                'high_courts.lc_ledger as lc_ledger',
                'high_courts.hcr_no_hc as hc_hcr',
                'high_courts.license_no_hc as hc_license',
                DB::raw('CONCAT(lawyer_addresses.ha_house_no,", ",lawyer_addresses.ha_city) as address'),
                DB::raw('CONCAT("0", users.phone) as phone'),
                'app_statuses.value as app_status',
                'users.profile_image as profile_image',
            )
            // ->where('high_courts.app_status', 1)
            ->whereNotNull('high_courts.enr_date_hc')
            ->when($filter['search_user_status'], function ($qry) use ($filter) {
                if ($filter['search_user_status'] == 'unverified') {
                    $qry->where('high_courts.user_id', NULL);
                }
            })
            ->when($filter['search_gender'], function ($qry) use ($filter) {
                if ($filter['search_gender'] == 'male') {
                    $qry->where('users.gender', 'Male');
                }
                if ($filter['search_gender'] == 'female') {
                    $qry->where('users.gender', 'Female');
                }
            })
            ->when($filter['search_voter_member'], function ($qry) use ($filter) {
                $qry->where('high_courts.voter_member_hc', $filter['search_voter_member']);
            })
            ->when($filter['search_app_status'], function ($qry) use ($filter) {
                $qry->where('high_courts.app_status', $filter['search_app_status']);
            })
            ->when($filter['search_start_date'] && $filter['search_end_date'], function ($qry) use ($filter) {
                $qry->where('high_courts.enr_date_hc', '>=', $filter['search_start_date']);
                $qry->where('high_courts.enr_date_hc', '<=', $filter['search_end_date']);
            })
            ->when($filter['search_request_date_from'] && $filter['search_request_date_to'], function ($qry) use ($filter) {
                $qry->where(function ($q) use ($filter) {
                    $q->whereDate('users.gc_requested_at', '>=', $filter['search_request_date_from']);
                    $q->whereDate('users.gc_requested_at', '<=', $filter['search_request_date_to']);
                });
            });

        $gc_hc = GcHighCourt::query()
            ->leftJoin('users', 'users.id', '=', 'gc_high_courts.user_id')
            ->join('app_statuses', 'app_statuses.id', '=', 'gc_high_courts.app_status')
            ->select(
                'users.id as user_id',
                'users.gc_status as status',
                'gc_high_courts.lawyer_name as lawyer',
                'gc_high_courts.father_name as father',
                'gc_high_courts.date_of_birth as dob',
                'gc_high_courts.cnic_no as cnic_no',
                'gc_high_courts.enr_date_hc as enr_date_hc',
                'gc_high_courts.enr_date_lc as enr_date_lc',
                'gc_high_courts.lc_ledger as lc_ledger',
                'gc_high_courts.hcr_no_hc as hc_hcr',
                'gc_high_courts.license_no_hc as hc_license',
                DB::raw('gc_high_courts.address_1 as address'),
                DB::raw('gc_high_courts.contact_no as phone'),
                'app_statuses.value as app_status',
                'users.profile_image as profile_image',
            )
            ->whereNotNull('gc_high_courts.enr_date_hc')
            // ->where('gc_high_courts.app_status', 1)
            ->when($filter['search_gender'], function ($qry) use ($filter) {
                if ($filter['search_gender'] == 'male') {
                    $qry->where('gc_high_courts.lc_sdw', 1);
                }
                if ($filter['search_gender'] == 'female') {
                    $qry->whereIn('gc_high_courts.lc_sdw', [2, 3]);
                }
            })
            ->when($filter['search_voter_member'], function ($qry) use ($filter) {
                $qry->where('gc_high_courts.voter_member_hc', $filter['search_voter_member']);
            })
            ->when($filter['search_user_status'], function ($qry) use ($filter) {
                if ($filter['search_user_status'] == 'verified') {
                    $qry->where('users.gc_status', 'approved');
                }
                if ($filter['search_user_status'] == 'unverified') {
                    $qry->where(function ($q) use ($filter) {
                        $q->where('users.gc_status', "!=", 'approved');
                        $q->orWhere('gc_high_courts.user_id', NULL);
                    });
                }
            })
            ->when($filter['search_app_status'], function ($qry) use ($filter) {
                $qry->where('gc_high_courts.app_status', $filter['search_app_status']);
            })
            ->when($filter['search_start_date'] && $filter['search_end_date'], function ($qry) use ($filter) {
                $qry->where('gc_high_courts.enr_date_hc', '>=', $filter['search_start_date']);
                $qry->where('gc_high_courts.enr_date_hc', '<=', $filter['search_end_date']);
            })
            ->when($filter['search_request_date_from'] && $filter['search_request_date_to'], function ($qry) use ($filter) {
                $qry->where(function ($q) use ($filter) {
                    $q->whereDate('users.gc_requested_at', '>=', $filter['search_request_date_from']);
                    $q->whereDate('users.gc_requested_at', '<=', $filter['search_request_date_to']);
                });
            });

        $records = $hc->union($gc_hc)->orderBy('enr_date_hc', 'asc');
    }

    return $records;
}

function lawyerSummaryReport($filter)
{
    $records = DB::table('divisions')
        ->select([
            'divisions.id',
            'divisions.name',
            'bars.name as bar_name',
            'bars.id as bar_id',
        ])
        ->join('districts', 'districts.division_id', '=', 'divisions.id')
        ->join('bars', 'bars.district_id', '=', 'districts.id')
        ->when($filter['search_division'], function ($qry) use ($filter) {
            if ($filter['search_division'] != '') {
                $qry->where('divisions.id', $filter['search_division']);
            }
        })
        ->when($filter['search_voter_member'], function ($qry) use ($filter) {
            $qry->where('bars.id', $filter['search_voter_member']);
        });

    $records = $records->groupByRaw('`divisions`.`name`,`divisions`.`id`,`bars`.`id`,`bars`.`name`');  // Group by division and month of bar creation
    $records = $records->orderBy('divisions.name', 'asc');

    return $records;
}

function lawyerSummaryReportEx($filter)
{
    if ($filter['search_user_type'] == 'LC' || $filter['search_user_type'] == 'all') {
        $lc = LowerCourt::query()
            ->join('users', 'users.id', '=', 'lower_courts.user_id')
            ->join('app_statuses', 'app_statuses.id', '=', 'lower_courts.app_status')
            ->leftJoin('lawyer_addresses', 'lower_courts.id', '=', 'lawyer_addresses.lower_court_id')
            ->select(
                'users.id as user_id',
                DB::raw('CASE WHEN users.register_as = "lc" THEN "approved" END as status'),
                'users.name as lawyer',
                'lower_courts.father_name as father',
                'lower_courts.lc_date as enr_date_lc',
                'lower_courts.reg_no_lc as lc_ledger',
                DB::raw('CONCAT(lawyer_addresses.ha_house_no,", ",lawyer_addresses.ha_city) as address'),
                DB::raw('CONCAT("0", users.phone) as phone'),
                'app_statuses.value as app_status',
                'users.profile_image as profile_image',
            )
            ->whereNotNull('lower_courts.lc_date')
            // ->where('lower_courts.app_status', 1)
            ->where('lower_courts.app_type', '!=', 4)
            ->when($filter['search_app_status'], function ($qry) use ($filter) {
                $qry->where('lower_courts.app_status', $filter['search_app_status']);
            })->when($filter['search_voter_member'], function ($qry) use ($filter) {
                $qry->where('lower_courts.voter_member_lc', $filter['search_voter_member']);
            });

        $gc_lc = GcLowerCourt::query()
            ->leftJoin('users', 'users.id', '=', 'gc_lower_courts.user_id')
            ->join('app_statuses', 'app_statuses.id', '=', 'gc_lower_courts.app_status')
            ->select(
                'users.id as user_id',
                'users.gc_status as status',
                'gc_lower_courts.lawyer_name as lawyer',
                'gc_lower_courts.father_name as father',
                'gc_lower_courts.date_of_enrollment_lc as enr_date_lc',
                'gc_lower_courts.reg_no_lc as lc_ledger',
                DB::raw('gc_lower_courts.address_1 as address'),
                DB::raw('gc_lower_courts.contact_no as phone'),
                'app_statuses.value as app_status',
                'users.profile_image as profile_image',
            )
            ->whereNotNull('gc_lower_courts.date_of_enrollment_lc')
            // ->where('gc_lower_courts.app_status', 1)
            ->where('gc_lower_courts.app_type', '!=', 4)
            ->when($filter['search_voter_member'], function ($qry) use ($filter) {
                $qry->where('gc_lower_courts.voter_member_lc', $filter['search_voter_member']);
            })
            ->when($filter['search_app_status'], function ($qry) use ($filter) {
                $qry->where('gc_lower_courts.app_status', $filter['search_app_status']);
            });

        $recordsLC = $lc->union($gc_lc)->orderBy('enr_date_lc', 'asc');
    }

    if ($filter['search_user_type'] == 'HC' || $filter['search_user_type'] == 'all') {
        $hc = HighCourt::query()
            ->join('users', 'users.id', '=', 'high_courts.user_id')
            ->join('app_statuses', 'app_statuses.id', '=', 'high_courts.app_status')
            ->leftJoin('lawyer_addresses', 'high_courts.id', '=', 'lawyer_addresses.high_court_id')
            ->select(
                'users.id as user_id',
                DB::raw('CASE WHEN users.register_as = "hc" THEN "approved" END as status'),
                'users.name as lawyer',
                'users.father_name as father',
                'high_courts.enr_date_hc as enr_date_hc',
                'high_courts.enr_date_lc as enr_date_lc',
                'high_courts.lc_ledger as lc_ledger',
                DB::raw('CONCAT(lawyer_addresses.ha_house_no,", ",lawyer_addresses.ha_city) as address'),
                DB::raw('CONCAT("0", users.phone) as phone'),
                'app_statuses.value as app_status',
                'users.profile_image as profile_image',
            )
            // ->where('high_courts.app_status', 1)
            ->whereNotNull('high_courts.enr_date_hc')
            ->when($filter['search_voter_member'], function ($qry) use ($filter) {
                $qry->where('high_courts.voter_member_hc', $filter['search_voter_member']);
            })
            ->when($filter['search_app_status'], function ($qry) use ($filter) {
                $qry->where('high_courts.app_status', $filter['search_app_status']);
            });

        $gc_hc = GcHighCourt::query()
            ->leftJoin('users', 'users.id', '=', 'gc_high_courts.user_id')
            ->join('app_statuses', 'app_statuses.id', '=', 'gc_high_courts.app_status')
            ->select(
                'users.id as user_id',
                'users.gc_status as status',
                'gc_high_courts.lawyer_name as lawyer',
                'gc_high_courts.father_name as father',
                'gc_high_courts.enr_date_hc as enr_date_hc',
                'gc_high_courts.enr_date_lc as enr_date_lc',
                'gc_high_courts.lc_ledger as lc_ledger',
                DB::raw('gc_high_courts.address_1 as address'),
                DB::raw('gc_high_courts.contact_no as phone'),
                'app_statuses.value as app_status',
                'users.profile_image as profile_image',
            )
            ->whereNotNull('gc_high_courts.enr_date_hc')
            // ->where('gc_high_courts.app_status', 1)
            ->when($filter['search_voter_member'], function ($qry) use ($filter) {
                $qry->where('gc_high_courts.voter_member_hc', $filter['search_voter_member']);
            })
            ->when($filter['search_app_status'], function ($qry) use ($filter) {
                $qry->where('gc_high_courts.app_status', $filter['search_app_status']);
            });

        $recordsHC = $hc->union($gc_hc)->orderBy('enr_date_hc', 'asc');
    }

    if ($filter['search_user_type'] == 'LC') {
        return $recordsLC;
    } else if ($filter['search_user_type'] == 'HC') {
        return $recordsHC;
    } else {
        return [
            'HC' => $recordsHC,
            'LC' => $recordsLC,
        ];
    }
}

function getLawyerCounts($filter, $voterID, $type = 'LC')
{

    if ($type == 'LC') {
        $records = DB::table('lower_courts')
            ->when($voterID, function ($qry) use ($voterID) {
                $qry->where('lower_courts.voter_member_lc', $voterID);
            })
            ->when($filter['search_app_status'], function ($qry) use ($filter) {
                if ($filter['search_app_status'] != 'all') {
                    $qry->where('lower_courts.app_status', $filter['search_app_status']);
                }
            });

        $gc_records = DB::table('gc_lower_courts')
            ->where('gc_lower_courts.app_type', '!=', 4)
            ->when($voterID, function ($qry) use ($voterID) {
                $qry->where('gc_lower_courts.voter_member_lc', $voterID);
            })
            ->when($filter['search_app_status'], function ($qry) use ($filter) {
                if ($filter['search_app_status'] != 'all') {
                    $qry->where('gc_lower_courts.app_status', $filter['search_app_status']);
                }
            });

        return $records->count() + $gc_records->count();
    } else if ($type == 'HC') {

        $records = DB::table('high_courts')
            ->when($voterID, function ($qry) use ($voterID) {
                $qry->where('high_courts.voter_member_hc', $voterID);
            })
            ->when($filter['search_app_status'], function ($qry) use ($filter) {
                if ($filter['search_app_status'] != 'all') {
                    $qry->where('high_courts.app_status', $filter['search_app_status']);
                }
            });

        $gc_records = DB::table('gc_high_courts')
            ->when($voterID, function ($qry) use ($voterID) {
                $qry->where('gc_high_courts.voter_member_hc', $voterID);
            })
            ->when($filter['search_app_status'], function ($qry) use ($filter) {
                if ($filter['search_app_status'] != 'all') {
                    $qry->where('gc_high_courts.app_status', $filter['search_app_status']);
                }
            });


        return $records->count() + $gc_records->count();
    }
}

function generalSearch($filter)
{
    $users = DB::table('users')->where('users.register_as', '!=', 'intimation')
        ->select('users.*', 'hc.id as hc_id', 'lc.id as lc_id', 'gc_lc.id as gc_lc_id', 'gc_hc.id as gc_hc_id')
        ->leftJoin('high_courts as hc', function ($qry) {
            $qry->on('hc.user_id', 'users.id')
                ->where('users.register_as', 'hc');
        })
        ->leftJoin('lower_courts as lc', function ($qry) {
            $qry->on('lc.user_id', 'users.id')
                ->where('users.register_as', 'lc');
        })
        ->leftJoin('gc_high_courts as gc_hc', function ($qry) {
            $qry->on('gc_hc.user_id', 'users.id')
                ->where('users.register_as', 'gc_hc');
        })
        ->leftJoin('gc_lower_courts as gc_lc', function ($qry) {
            $qry->on('gc_lc.user_id', 'users.id')
                ->where('users.register_as', 'gc_lc');
        });

    if ($filter['search_cnic'] != '' && $filter['search_cnic'] != null) {
        $users->where('users.cnic_no', 'LIKE', '%' . $filter['search_cnic'] . '%');
    } else {
        $users->where('users.cnic_no', 'LIKE', '%' . 'N/A' . '%');
    }


    return $users->orderBy('users.name');
}

if (!function_exists('get_complaint_voucher_no')) {
    function get_complaint_voucher_no($payment_id)
    {
        $vocuherNo = 5000000000000 + $payment_id;
        return $vocuherNo;
    }
}


function amountInWords($amount)
{
    $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    return $digit->format($amount);
}

function getMemberForCertificate($date, $designation)
{

    $member = \App\Member::where('designation', $designation)
        ->whereDate('tenure_start_date', '<=', $date)
        ->whereDate('tenure_end_date', '>=', $date)
        ->first();

    if ($member != null) {
        return $member;
    } else {
        return null;
    }
}

if (!function_exists('getDateTimeFormat')) {
    function getDateTimeFormat($date)
    {
        try {
            $format_date = null;
            if (isset($date)) {
                $format_date = Carbon::parse($date)->format('d-m-Y h:i A');
            }
            return $format_date;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

if (!function_exists('cleanData')) {
    function cleanData($data)
    {
        try {
            if ($data) {
                return preg_replace('/\D/', '', $data);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

if (!function_exists('generalSearchQuery')) {
    function generalSearchQuery($filter)
    {
        $user = User::query()
            ->select(
                'users.id as main_id',
                DB::raw('"user" as lawyer_type'),
                DB::raw('users.register_as as current_user_type'),
                'users.name as lawyer',
                'users.father_name as father',
                'users.date_of_birth as dob',
                'users.cnic_no as cnic',
                DB::raw('CONCAT("0", users.phone) as phone'),
                DB::raw('CONCAT("") as app_status'),
                DB::raw('CONCAT("") as profile_image'),

                DB::raw('CONCAT("") as rcpt_date'),
                DB::raw('CONCAT("") as rcpt_no'),
                'users.created_at as final_submitted_at',
            )
            ->when($filter['cnic_no'], function ($qry) use ($filter) {
                $qry->where("users.clean_cnic_no", $filter['clean_cnic_no']);
            });

        $intimation = Application::query()
            ->leftJoin('users', 'users.id', '=', 'applications.user_id')
            ->leftJoin('app_statuses', 'app_statuses.id', '=', 'applications.application_status')
            ->select(
                'applications.id as main_id',
                DB::raw('"intimation" as lawyer_type'),
                DB::raw('users.register_as as current_user_type'),
                'users.name as lawyer',
                'applications.so_of as father',
                'applications.date_of_birth as dob',
                'applications.cnic_no as cnic',
                DB::raw('CONCAT("0", users.phone) as phone'),
                'app_statuses.value as app_status',
                'users.profile_image as profile_image',

                'applications.rcpt_date as rcpt_date',
                'applications.rcpt_no as rcpt_no',
                'applications.final_submitted_at as final_submitted_at',
            )
            ->when($filter['cnic_no'], function ($qry) use ($filter) {
                $qry->where("applications.clean_cnic_no", $filter['clean_cnic_no']);
            });

        $lc = LowerCourt::query()
            ->leftJoin('users', 'users.id', '=', 'lower_courts.user_id')
            ->leftJoin('app_statuses', 'app_statuses.id', '=', 'lower_courts.app_status')
            ->select(
                'lower_courts.id as main_id',
                DB::raw('"lc" as lawyer_type'),
                DB::raw('users.register_as as current_user_type'),
                'users.name as lawyer',
                'lower_courts.father_name as father',
                'lower_courts.date_of_birth as dob',
                'lower_courts.cnic_no as cnic',
                DB::raw('CONCAT("0", users.phone) as phone'),
                'app_statuses.value as app_status',
                'users.profile_image as profile_image',

                'lower_courts.rcpt_date as rcpt_date',
                'lower_courts.rcpt_no_lc as rcpt_no',
                'lower_courts.final_submitted_at as final_submitted_at',
            )
            ->when($filter['cnic_no'], function ($qry) use ($filter) {
                $qry->where("lower_courts.clean_cnic_no", $filter['clean_cnic_no']);
            });

        $gc_lc = GcLowerCourt::query()
            ->leftJoin('users', 'users.id', '=', 'gc_lower_courts.user_id')
            ->leftJoin('app_statuses', 'app_statuses.id', '=', 'gc_lower_courts.app_status')
            ->select(
                'gc_lower_courts.id as main_id',
                DB::raw('"gc_lc" as lawyer_type'),
                DB::raw('users.register_as as current_user_type'),
                'gc_lower_courts.lawyer_name as lawyer',
                'gc_lower_courts.father_name as father',
                'gc_lower_courts.date_of_birth as dob',
                'gc_lower_courts.cnic_no as cnic',
                DB::raw('gc_lower_courts.contact_no as phone'),
                'app_statuses.value as app_status',
                'users.profile_image as profile_image',

                DB::raw('CONCAT("") as rcpt_date'),
                DB::raw('CONCAT("") as rcpt_no'),
                'gc_lower_courts.created_at as final_submitted_at',
            )
            ->when($filter['cnic_no'], function ($qry) use ($filter) {
                $qry->where("gc_lower_courts.clean_cnic_no", $filter['clean_cnic_no']);
            });


        $hc = HighCourt::query()
            ->leftJoin('users', 'users.id', '=', 'high_courts.user_id')
            ->leftJoin('app_statuses', 'app_statuses.id', '=', 'high_courts.app_status')
            ->select(
                'high_courts.id as main_id',
                DB::raw('"hc" as lawyer_type'),
                DB::raw('users.register_as as current_user_type'),
                'users.name as lawyer',
                'users.father_name as father',
                'users.date_of_birth as dob',
                'users.cnic_no as cnic_no',
                DB::raw('CONCAT("0", users.phone) as phone'),
                'app_statuses.value as app_status',
                'users.profile_image as profile_image',

                'high_courts.rcpt_date as rcpt_date',
                'high_courts.rcpt_no_hc as rcpt_no',
                'high_courts.final_submitted_at as final_submitted_at',
            )
            ->when($filter['cnic_no'], function ($qry) use ($filter) {
                $qry->where("users.clean_cnic_no", $filter['clean_cnic_no']);
            });

        $gc_hc = GcHighCourt::query()
            ->leftJoin('users', 'users.id', '=', 'gc_high_courts.user_id')
            ->leftJoin('app_statuses', 'app_statuses.id', '=', 'gc_high_courts.app_status')
            ->select(
                'gc_high_courts.id as main_id',
                DB::raw('"gc_hc" as lawyer_type'),
                DB::raw('users.register_as as current_user_type'),
                'gc_high_courts.lawyer_name as lawyer',
                'gc_high_courts.father_name as father',
                'gc_high_courts.date_of_birth as dob',
                'gc_high_courts.cnic_no as cnic_no',
                DB::raw('gc_high_courts.contact_no as phone'),
                'app_statuses.value as app_status',
                'users.profile_image as profile_image',

                DB::raw('CONCAT("") as rcpt_date'),
                DB::raw('CONCAT("") as rcpt_no'),
                'gc_high_courts.created_at as final_submitted_at',
            )
            ->when($filter['cnic_no'], function ($qry) use ($filter) {
                $qry->where("gc_high_courts.clean_cnic_no", $filter['clean_cnic_no']);
            });

        $records = $user->unionAll($intimation)->unionAll($lc)->unionAll($gc_lc)->unionAll($hc)->unionAll($gc_hc);

        return $records;
    }

    if (!function_exists('printReceipt')) {
        function printReceipt(User $user)
        {
            try {
                $profile = CapabilityProfile::load("simple");

                // Use the exact shared name of your printer from Windows
                // $connector = new WindowsPrintConnector("EPSON_TM_T88V");
                $connector = new WindowsPrintConnector("EPSON88");
                $printer = new Printer($connector, $profile);

                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setTextSize(2, 2);
                $printer->text("Biometric Elections Software\n");
                $printer->feed(4);
                $printer->setTextSize(1, 1);
                $printer->setEmphasis(true);
                $printer->text("Token No: $user->id\n");
                $printer->setEmphasis(false);
                $printer->feed(2);
                $printer->text("CNIC No: $user->cnic_no\n");
                $printer->feed(4);

                // Receipt title
                $printer->setEmphasis(true);
                $printer->text("Biometric Registration Receipt\n");
                $printer->setEmphasis(false);
                $printer->feed();

                $barcodeData = (string) $user->clean_cnic_no;
                $printer->setBarcodeHeight(80);
                $printer->setBarcodeWidth(1);
                $printer->barcode($barcodeData, Printer::BARCODE_CODE39);
                $printer->feed(4);

                // Cut
                $printer->cut();
                $printer->close();

                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
    }

    if (!function_exists('printVoteConfirmationReceipt')) {
        function printVoteConfirmationReceipt($user, $votes)
        {
            try {
                $profile = CapabilityProfile::load("simple");

                // Use the exact shared name of your printer from Windows
                // $connector = new WindowsPrintConnector("EPSON_TM_T88V");
                $connector = new WindowsPrintConnector("EPSON88");
                $printer = new Printer($connector, $profile);

                $printer->setJustification(Printer::JUSTIFY_CENTER);
                $printer->setTextSize(2, 2);
                $printer->text("Biometric Elections Software\n");
                $printer->feed(4);
                $printer->setTextSize(1, 1);
                $printer->setEmphasis(true);
                $printer->text("Token No: $user->id\n");
                $printer->setEmphasis(false);
                $printer->feed(2);
                $printer->text("CNIC No: $user->cnic_no\n");
                $printer->feed(4);

                foreach ($votes as $key => $vote) {
                    $printer->text($vote['seat_name'].':'. $vote['candidate_name']);
                    $printer->setEmphasis(false);
                    $printer->feed(4);
                }

                // Receipt title
                $printer->setEmphasis(true);
                $printer->text("Vote Confirmation Receipt\n");
                $printer->setEmphasis(false);
                $printer->feed();

                $barcodeData = (string) $user->clean_cnic_no;
                $printer->setBarcodeHeight(80);
                $printer->setBarcodeWidth(1);
                $printer->barcode($barcodeData, Printer::BARCODE_CODE39);
                $printer->feed(4);

                // Cut
                $printer->cut();
                $printer->close();

                return true;
            } catch (\Exception $e) {
                return false;
            }
        }
    }
}
