<?php

use App\LawyerEducation;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

if (!function_exists('uploadEducationalCertificate')) {
    function uploadEducationalCertificate($request, $id, $application_id)
    {
        $model = LawyerEducation::find($id);
        $directory = 'applications/academic-records/' . $application_id;
        if ($request->hasFile('certificate_url')) {
            $fileName = $request->file('certificate_url')->getClientOriginalName();
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('certificate_url')));
            ($model == NULL) ? $modal = LawyerEducation::create(['application_id' => $model->id, 'certificate' => $url]) : $model->update(['certificate' => $url]);

            if (\Auth::guard('admin')->check()) {
                createUploadActivity($model, 'Application');
            }
        }
    }
}

if (!function_exists('getIntimationStartDate')) {
    function getIntimationStartDate($application)
    {
        if (isset($application->final_submitted_at)) {
            $final_submission_date = date('d-m-Y', strtotime($application->final_submitted_at));
        } else {
            $final_submission_date = date('d-m-Y', strtotime($application->created_at));
        }

        if (isset($application->rcard_date)) {
            $prev_month_date = date("d-m-Y", strtotime('-1 month', strtotime($final_submission_date)));
            $date = date('d-m-Y', strtotime($application->rcard_date));
            if (Carbon::parse($date)->gte(Carbon::parse($prev_month_date))) {
                $date = $application->rcard_date;
            } else {
                if (isset($application->intimation_start_date)) {
                    $date = date('d-m-Y', strtotime($application->intimation_start_date));
                } else {
                    if ($application->srl_joining_date == NULL) {
                        $date = $final_submission_date;
                    } else {
                        $prev_month_date = date("d-m-Y", strtotime('-1 month', strtotime($final_submission_date)));
                        $date = date('d-m-Y', strtotime($application->srl_joining_date));
                        if (Carbon::parse($date)->gte(Carbon::parse($prev_month_date))) {
                            $date = $application->srl_joining_date;
                        } else {
                            $date = $final_submission_date;
                            if ($final_submission_date == NULL) {
                                $date = $application->created_at;
                            }
                        }
                    }
                }
            }
        } else {
            if (isset($application->intimation_start_date)) {
                $date = date('d-m-Y', strtotime($application->intimation_start_date));
            } else {
                if ($application->srl_joining_date == NULL) {
                    $date = $final_submission_date;
                } else {
                    $prev_month_date = date("d-m-Y", strtotime('-1 month', strtotime($final_submission_date)));
                    $date = date('d-m-Y', strtotime($application->srl_joining_date));
                    if (Carbon::parse($date)->gte(Carbon::parse($prev_month_date))) {
                        $date = $application->srl_joining_date;
                    } else {
                        $date = $final_submission_date;
                        if ($final_submission_date == NULL) {
                            $date = $application->created_at;
                        }
                    }
                }
            }
        }

        $intimation_date = date('d-m-Y', strtotime($date));

        return [
            'intimation_date' => $intimation_date,
            'final_submission_date' => $final_submission_date,
        ];
    }
}

if (!function_exists('getObjections')) {
    function getObjections($id)
    {
        $name = null;

        switch ($id) {
            case $id == 1:
                $name = 'Copy of Senior\'s Identity Card issued by the Punjab Bar Council.';
                break;
            case $id == 2:
                $name = 'Three latest passport size Photographs.';
                break;
            case $id == 3:
                $name = 'Attested copies of the Resuld Cards of LLB Part -I,II & III / Final Semester/ Roll No Slip.';
                break;
            case $id == 4:
                $name = 'Copy of your Computerised NIC.';
                break;
            case $id == 5:
                $name = 'Fee Schedule has been changed from 01.07.2016. Send slip deposited in the HBL Ltd, as the balance of the Intimation Fee.';
                break;
            case $id == 6:
                $name = 'AJ&K Unviersity, AJ&K is not recognised by the Pakistan Bar Council before 01.07.2008.';
                break;
            case $id == 7:
                $name = 'Federal Urdu University is not recognised by the Pakistan Bar Council before 07.11.2012.';
                break;
            case $id == 8:
                $name = 'Senior\'s Name and Singnatures are missing.';
                break;
            case $id == 9:
                $name = 'Candidate\'s Signatures are missing.';
                break;
            case $id == 10:
                $name = 'Senior\'s standing is less then 10 years. Join a new Senior and send new Intimation Form.';
                break;
            case $id == 11:
                $name = 'Course not complete. Your training will be start after appearing in the last paper of LL.B part III/last semmester. Send a copy of Date sheet alongwith Roll.# slip to this office.';
                break;
            case $id == 12:
                $name = 'Your Senior has already three apprenticee .You are directed to join a new senior and send a new intimation form alongwith the copy of this letter.';
                break;
            case $id == 13:
                $name = 'Intimation fees have been enhanced from Rs.1500 to  Rs.2000/- upto the age of 35 years from 01.07.2023. Send Deposit slip of Rs. 500/-  as the balance of the Intimation Fee.';
                break;
            case $id == 14:
                $name = 'From 01.07.2023, Intimation Fee up to 35 years is Rs.3000/-.';
                break;
            case $id == 15:
                $name = 'From 01.07.2023, Intimation Fee above 35 to 40 years is Rs. 7000/-.';
                break;
            case $id == 16:
                $name = 'From 01.07.2023, Intimation Fee up to 50 years is Rs. 20,000/-';
                break;
            case $id == 17:
                $name = 'From 01.07.2023, Intimation Fee up to 60 years is Rs. 30,000/-. ';
                break;
            case $id == 18:
                $name = 'From 01.07.2023, Intimation Fee above 60 years is Rs. 50,000/-. .';
                break;
            case $id == 19:
                $name = 'Your University is not recognised by the Pakistan Bar Council.';
                break;
            case $id == 20:
                $name = 'Pl. provide Letter from Pakistan Bar Council regarding equivalence of Graduate Diploma in Law as LL.B Degree.';
                break;
            case $id == 21:
                $name = 'Missing Matric  Result Card';
                break;
            case $id == 22:
                $name = 'Missing F A / FSC / ICS. Result Card';
                break;
            case $id == 23:
                $name = 'Missing BA Result Card';
                break;
            case $id == 24:
                $name = 'Missing LLB Part 1 Result Card';
                break;
            case $id == 25:
                $name = 'Missing  LLB part 2 Result Card';
                break;
            case $id == 26:
                $name = 'Missing  LLB part 3 Result Card';
                break;
            case $id == 27:
                $name = 'Missing  LLB 5 Year Degree';
                break;
            case $id == 28:
                $name = 'Missing  Bar Association Name';
                break;
            case $id == 29:
                $name = 'Missing Senior Copy of Cnic';
                break;
            case $id == 30:
                $name = 'Missing Senior Mobile No';
                break;
            case $id == 31:
                $name = 'Attached degree not clear';
                break;
            case $id == 32:
                $name = 'Attached cnic not clear';
                break;
            case $id == 33:
                $name = 'Result announced date is missing';
                break;
            default:
                break;
        }

        return $name;
    }
}

function getIntimationDuration($intimation_start_date)
{
    $now = Carbon::parse(Carbon::now());
    $years = Carbon::parse($intimation_start_date)->diff($now)->format('%y');
    $months = Carbon::parse($intimation_start_date)->diff($now)->format('%m.%d');
    $intimation_duration = ($years * 12) + $months;

    return $intimation_duration;
}
