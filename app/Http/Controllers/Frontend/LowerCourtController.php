<?php

namespace App\Http\Controllers\Frontend;

use App\Bar;
use App\Country;
use App\District;
use App\Http\Controllers\Controller;
use App\LawyerAddress;
use App\LawyerEducation;
use App\LawyerUpload;
use App\LowerCourt;
use App\Payment;
use App\Province;
use App\Tehsil;
use App\University;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class LowerCourtController extends Controller
{
    public function createStep1(Request $request, $id = null)
    {
        $user = Auth::guard('frontend')->user();

        $data = [
            'app_type' => 1,
            'is_approved' => 0,
            'app_status' => 6,
            'is_frontend' => true,
            'user_id' => $user->id,
        ];

        $application = LowerCourt::where('user_id', $user->id)->first();

        if ($application == NULL) {
            $application = LowerCourt::create($data);
        }

        $request->session()->forget('lower_court_application');
        if (empty($request->session()->get('lower_court_application'))) {
            $application->fill(['id' => $application->id]);
            $request->session()->put('lower_court_application', $application->id);
        }

        $bars = Bar::select('id', 'name')->orderBy('name', 'asc')->get();
        $years = range(Carbon::now()->year, 1900);

        if ($request->isMethod('post') && $request->ajax()) {

            $rules = [
                'voter_member_lc' => ['required'],
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'father_name' => 'required|max:255',
                'gender' => 'required|max:255',
                'date_of_birth' => 'required|before:18 years ago',
                'blood_group' => 'nullable|max:255',
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'mobile_no' => ['required', 'string', 'unique:users,phone,' . $user->id],
            ];

            if ($application->is_moved_from_intimation == 0) {
                $rules += [
                    'intimation_date' => 'required|before:6 months ago',
                    'llb_passing_year' => 'required|max:255',
                ];
            }

            $messages = [
                'date_of_birth.before' => 'The date of birth is invalid. Please select birth date as per CNIC.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            // $age_cal_date = isset($application->final_submitted_at) ? $application->final_submitted_at : $application->created_at;

            $data = [
                'voter_member_lc' => $request->input('voter_member_lc'),
                'father_name' => $request->input('father_name'),
                'gender' => $request->input('gender'),
                'date_of_birth' => $request->input('date_of_birth'),
                // 'age' =>  Carbon::parse($request->date_of_birth)->diff($age_cal_date)->format('%y.%m'),
                'age' =>  getLcAge($request->date_of_birth, $application),
                'blood' => $request->input('blood_group'),
            ];

            if ($application->is_moved_from_intimation == 0) {
                $data += [
                    'intimation_date' => Carbon::parse($request->input('intimation_date'))->format('Y-m-d'),
                    'llb_passing_year' => $request->input('llb_passing_year'),
                ];
            }

            $application->update($data);

            $user_registration_data = [
                'fname' => $request->input('first_name'),
                'lname' => $request->input('last_name'),
                'email' => $request->input('email'),
                'phone' => $request->input('mobile_no'),
            ];

            $user->update($user_registration_data);

            $nextView = view('frontend.lower-court.create-step-2', compact('application'))->render();
            return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 2]);
        }

        return view('frontend.lower-court.create-step-1', compact('application', 'bars', 'years', 'user'));
    }

    public function createStep2(Request $request, $id)
    {
        $application = LowerCourt::findOrFail($id);
        $user = User::find(Auth::guard('frontend')->user()->id);

        if ($request->isMethod('post')) {

            $rules = [
                // 'cnic_no' => 'required|min:15|unique:lower_courts,cnic_no,' . $application->id,
                'cnic_expiry_date' => 'required',
            ];

            $messages = [
                'cnic_no.required' => 'The cnic number field is required.',
                'cnic_no.min' => 'The cnic number you have entered is invalid.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $data = [
                // 'cnic_no' => $request->input('cnic_no'),
                'cnic_expiry_date' => $request->input('cnic_expiry_date'),
            ];

            $application->update($data);
            // $user->update(['cnic_no' => $request->input('cnic_no')]);

            $countries = Country::select('id', 'name')->orderBy('name', 'asc')->get();
            $provinces = Province::select('id', 'name')->orderBy('name', 'asc')->get();
            $districts = District::select('id', 'name', 'code')->orderBy('name', 'asc')->get();
            $tehsils = Tehsil::select('id', 'name', 'code')->orderBy('name', 'asc')->get();
            $nextView = view('frontend.lower-court.create-step-3', compact('districts', 'tehsils', 'application', 'provinces', 'countries'))->render();

            return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 3]);
        }
        return view('frontend.lower-court.create-step-2', compact('application'));
    }

    public function createStep3(Request $request, $id)
    {
        $application = LowerCourt::findOrFail($id);
        $countries = Country::select('id', 'name')->orderBy('name', 'asc')->get();
        $provinces = Province::select('id', 'name')->orderBy('name', 'asc')->get();
        $districts = District::select('id', 'name', 'code')->orderBy('name', 'asc')->get();
        $tehsils = Tehsil::select('id', 'name', 'code')->orderBy('name', 'asc')->get();

        if ($request->isMethod('post')) {

            $same_as = $request->input('same_address_btn') == 'on' ? true : false;

            $rules = [
                'ha_house_no' => 'required|max:100',
                'ha_str_address' => 'nullable|max:0',
                'ha_town' => 'nullable|max:0',
                'ha_city' => 'required|max:15',
                'ha_postal_code' => 'nullable|max:10',
                'ha_country_id' => 'required|integer',
                'ha_province_id' => 'required_if:ha_country_id,166',
                'ha_district_id' => 'required_if:ha_country_id,166',
                'ha_tehsil_id' => 'required_if:ha_country_id,166',
            ];

            if ($same_as == false) {
                $rules += [
                    'pa_house_no' => 'required|max:100',
                    'pa_str_address' => 'nullable|max:0',
                    'pa_town' => 'nullable|max:0',
                    'pa_city' => 'required|max:15',
                    'pa_postal_code' => 'nullable|max:10',
                    'pa_country_id' => 'required|integer',
                    'pa_province_id' => 'required_if:pa_country_id,166',
                    'pa_district_id' => 'required_if:pa_country_id,166',
                    'pa_tehsil_id' => 'required_if:pa_country_id,166',
                ];
            }

            $messages = [
                'ha_str_address.max' => 'Use house # field only to enter your complete address',
                'ha_town.max' => 'Use house # field only to enter your complete address',
                'pa_str_address.max' => 'Use house # field only to enter your complete address',
                'pa_town.max' => 'Use house # field only to enter your complete address',

                'ha_province_id.required_if' => 'The province field is required when country is Pakistan.',
                'ha_district_id.required_if' => 'The district field is required when country is Pakistan.',
                'ha_tehsil_id.required_if' => 'The tehsil field is required when country is Pakistan.',
                'pa_province_id.required_if' => 'The province field is required when country is Pakistan.',
                'pa_district_id.required_if' => 'The district field is required when country is Pakistan.',
                'pa_tehsil_id.required_if' => 'The tehsil field is required when country is Pakistan.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $data = [
                'lower_court_id' => $application->id,
                'ha_house_no' => $request->input('ha_house_no'),
                'ha_str_address' => $request->input('ha_str_address'),
                'ha_town' => $request->input('ha_town'),
                'ha_city' => $request->input('ha_city'),
                'ha_postal_code' => $request->input('ha_postal_code'),
                'ha_country_id' => $request->input('ha_country_id'),
                'ha_province_id' => $request->input('ha_province_id'),
                'ha_district_id' => $request->input('ha_district_id'),
                'ha_tehsil_id' => $request->input('ha_tehsil_id'),
                'same_as' => $same_as,
            ];

            if ($request->has('same_address_btn')) {
                $data += [
                    'pa_house_no' => $request->input('ha_house_no'),
                    'pa_str_address' => $request->input('ha_str_address'),
                    'pa_town' => $request->input('ha_town'),
                    'pa_city' => $request->input('ha_city'),
                    'pa_postal_code' => $request->input('ha_postal_code'),
                    'pa_country_id' => $request->input('ha_country_id'),
                    'pa_province_id' => $request->input('ha_province_id'),
                    'pa_district_id' => $request->input('ha_district_id'),
                    'pa_tehsil_id' => $request->input('ha_tehsil_id'),
                ];
            } else {
                $data += [
                    'pa_house_no' => $request->input('pa_house_no'),
                    'pa_str_address' => $request->input('pa_str_address'),
                    'pa_town' => $request->input('pa_town'),
                    'pa_city' => $request->input('pa_city'),
                    'pa_postal_code' => $request->input('pa_postal_code'),
                    'pa_country_id' => $request->input('pa_country_id'),
                    'pa_province_id' => $request->input('pa_province_id'),
                    'pa_district_id' => $request->input('pa_district_id'),
                    'pa_tehsil_id' => $request->input('pa_tehsil_id'),
                ];
            }

            $lawyerAddress = LawyerAddress::where('lower_court_id', $application->id)->first();
            ($lawyerAddress == NULL) ? LawyerAddress::create($data) : $lawyerAddress->update($data);

            $application = LowerCourt::findOrFail($id);
            $universities = University::select('id', 'name')->where('type', 1)->orderBy('name', 'asc')->get();
            $affliatedUniversities = University::where('type', 2)->orderBy('name', 'asc')->get();

            $academicRecord = [];
            foreach ($application->educations as $key => $applicationEducation) {
                $academicRecord[] = $applicationEducation->qualification;
            }

            $nextView = view('frontend.lower-court.create-step-4', compact('application', 'universities', 'affliatedUniversities', 'academicRecord'))->render();
            return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 4]);
        }

        return view('frontend.lower-court.create-step-3', compact('districts', 'tehsils', 'application', 'provinces', 'countries'));
    }

    public function createStep4(Request $request, $id)
    {
        $application = LowerCourt::findOrFail($id);
        $universities = University::select('id', 'name')->where('approved', 1)->where('type', 1)->orderBy('name', 'asc')->get();
        $affliatedUniversities = University::where('type', 2)->where('approved', 1)->orderBy('name', 'asc')->get();

        $academicRecord = [];
        foreach ($application->educations as $key => $applicationEducation) {
            $academicRecord[] = $applicationEducation->qualification;
        }

        if ((in_array('1', $academicRecord) && in_array('2', $academicRecord) && in_array('3', $academicRecord) && in_array('4', $academicRecord) &&  in_array('5', $academicRecord) && in_array('6', $academicRecord) && in_array('9', $academicRecord)) || (in_array('1', $academicRecord) && in_array('2', $academicRecord) && in_array('7', $academicRecord) && in_array('9', $academicRecord))) {
            $application->update(['is_academic_record' => TRUE]);
            $academicRecordStatus = TRUE;
        } else {
            $application->update(['is_academic_record' => FALSE]);
            $academicRecordStatus = FALSE;
        }

        if ($request->isMethod('post')) {

            if ($request->has('exemption_reason')) {
                $application->update([
                    'is_exemption' => TRUE,
                    'exemption_reason' => $request->get('exemption_reason')
                ]);
                $bars = Bar::select('id', 'name')->orderBy('name', 'asc')->get();
                $nextView = view('frontend.lower-court.create-step-5', compact('application', 'bars'))->render();
                return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 5]);
            } else {
                $rules = [
                    'qualification' => 'required|string|max:255',

                    'sub_qualification' => [
                        'nullable',
                        'string',
                        'max:255',
                        Rule::requiredIf(
                            $request->qualification == '1' || $request->qualification == '2'
                        )
                    ],

                    'university_id' => [
                        'nullable',
                        'integer',
                        Rule::requiredIf(
                            $request->qualification == '4' ||
                                $request->qualification == '5' ||
                                $request->qualification == '6' ||
                                $request->qualification == '7' ||
                                $request->qualification == '8'
                        )
                    ],
                    'gat_pass' => [
                        'nullable',
                        Rule::requiredIf($request->qualification == 9)
                    ],

                    'institute' => [
                        'nullable',
                        'string',
                        'max:255',
                        Rule::requiredIf(
                            $request->qualification == '1' ||
                                $request->qualification == '2' ||
                                $request->qualification == '3'
                        )
                    ],

                    'total_marks' => 'required|numeric',
                    'obtained_marks' => [
                        'required',
                        'numeric',
                        'lte:total_marks',
                        function ($attribute, $value, $fail) use ($request) {
                            $total = $request->total_marks;
                            if ($request->qualification == 9) {
                                if (($value / $total) * 100 < 50.00) {
                                    $fail('Obtained marks should be at least 50%');
                                }
                            }
                        },
                    ],
                    'roll_no' => 'required|string',
                    'passing_year' => 'required|integer',
                    'certificate_url' => 'required',
                ];

                $messages = [
                    'obtained_marks.lte' => 'The obtained marks must be less than or equal to total marks.',
                    'gat_pass.required' => 'The GAT password is required.',
                ];

                $validator = Validator::make($request->all(), $rules,  $messages);

                if ($validator->fails()) {
                    return response()->json([
                        'errors' => $validator->errors(),
                    ], 400);
                }

                $data = [
                    'lower_court_id' => $application->id,
                    'qualification' => $request->input('qualification'),
                    'sub_qualification' => $request->input('sub_qualification'),
                    'university_id' => $request->input('university_id'),
                    'gat_pass' => $request->input('gat_pass') ?? null,
                    'institute' => $request->input('institute'),
                    'total_marks' => $request->input('total_marks'),
                    'obtained_marks' => $request->input('obtained_marks'),
                    'roll_no' => $request->input('roll_no'),
                    'passing_year' => $request->input('passing_year'),
                ];

                $lawyerEducation = LawyerEducation::updateOrCreate($data);
                uploadLcEducationalCertificate($request, $lawyerEducation->id, $application->id);
            }

            $application = LowerCourt::findOrFail($id);
            $records = view('frontend.lower-court.partials.academic-record', compact('application'))->render();
            return response()->json(['status' => 2, 'message' => 'success', 'academicRecordStatus' => $academicRecordStatus, 'records' => $records]);
        }

        return view('frontend.lower-court.create-step-4', compact('application', 'universities', 'affliatedUniversities', 'academicRecord'));
    }

    public function createStep5(Request $request, $id)
    {
        $application = LowerCourt::with('payments')->findOrFail($id);
        $bars = Bar::select('id', 'name')->orderBy('name', 'asc')->get();
        if ($request->isMethod('post')) {

            $rules = [
                'is_engaged_in_business' => 'required',
                'is_practice_in_pbc' => 'required',
                'practice_place' => 'required_if:is_practice_in_pbc,Yes|max:50',
                'is_declared_insolvent' => 'required',
                'is_dismissed_from_gov' => 'required',
                'dismissed_reason' => 'required_if:is_dismissed_from_gov,Yes|max:50',
                'is_enrolled_as_adv' => 'required',
                'is_offensed' => 'required',
                'offensed_date' => 'required_if:is_offensed,Yes|max:50',
                'offensed_reason' => 'required_if:is_offensed,Yes|max:50',
                'is_prev_rejected' => 'required',
            ];

            if ($application->is_exemption == 0 && $application->is_moved_from_intimation == 0) {
                $rules += [
                    'srl_name' => 'required|max:255',
                    'srl_bar_id' => ['required', 'max:255'],
                    'srl_office_address' => 'required|max:255',
                    'srl_enr_date' => 'required|before:10 years ago',
                    'srl_mobile_no' => 'required|max:255',
                    'srl_cnic_no' => 'required|min:15',
                    'srl_joining_date' => 'required',
                ];
            }

            $current_date = Carbon::now()->format('d-m-Y');
            $prev_month_date = date("Y-m-d", strtotime('-1 month', strtotime($current_date)));
            $messages = [
                'srl_cnic_no.required' => 'The cnic number field is required.',
                'srl_cnic_no.min' => 'The cnic number you have entered is invalid.',
                'srl_cnic_front.required' => 'CNIC or License any one of both images is required',
                'srl_cnic_back.required' => 'CNIC or License any one of both images is required',
                'srl_license_front.required' => 'CNIC or License any one of both images is required',
                'srl_license_back.required' => 'CNIC or License any one of both images is required',
                'practice_place.required_if' => 'Place field is required',
                'dismissed_reason.required_if' => 'Dismissed Reason field is required',
                'offensed_date.required_if' => 'Offense Date field is required',
                'offensed_reason.required_if' => 'Offense Reason field is required',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $data = [
                'is_engaged_in_business' => $request->is_engaged_in_business,
                'is_practice_in_pbc' => $request->is_practice_in_pbc,
                'practice_place' => $request->practice_place,
                'is_declared_insolvent' => $request->is_declared_insolvent,
                'is_dismissed_from_gov' => $request->is_dismissed_from_gov,
                'dismissed_reason' => $request->dismissed_reason,
                'is_enrolled_as_adv' => $request->is_enrolled_as_adv,
                'is_offensed' => $request->is_offensed,
                'offensed_date' => $request->offensed_date,
                'offensed_reason' => $request->offensed_reason,
                'is_prev_rejected' => $request->is_prev_rejected,
            ];

            if ($application->is_exemption == 0 && $application->is_moved_from_intimation == 0) {
                $data += [
                    'srl_name' => $request->input('srl_name'),
                    'srl_bar_id' => $request->input('srl_bar_id'),
                    'srl_office_address' => $request->input('srl_office_address'),
                    'srl_enr_date' => $request->input('srl_enr_date'),
                    'srl_joining_date' => $request->input('srl_joining_date'),
                    'srl_mobile_no' => $request->input('srl_mobile_no'),
                    'srl_cnic_no' => $request->input('srl_cnic_no'),
                ];
            }

            $application->update($data);
            $nextView = view('frontend.lower-court.create-step-6', compact('application'))->render();

            return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 6]);
        }

        return view('frontend.lower-court.create-step-5', compact('application', 'bars'));
    }

    public function createStep6(Request $request, $id)
    {
        $application = LowerCourt::findOrFail($id);
        $this->lowerCourtPayment($application);

        if ($request->isMethod('post')) {
            $rules = [
                'profile_image' => [Rule::requiredIf(function () use ($application) {
                    return $application->uploads->profile_image != NULL ? false : true;
                })],
                'cnic_front' => [Rule::requiredIf(function () use ($application) {
                    return $application->uploads->cnic_front != NULL ? false : true;
                })],
                'cnic_back' => [Rule::requiredIf(function () use ($application) {
                    return $application->uploads->cnic_back != NULL ? false : true;
                })],
                'srl_cnic_front' => [Rule::requiredIf(function () use ($application) {
                    if ($application->uploads->srl_cnic_front != NULL && $application->uploads->srl_cnic_back != NULL) {
                        return false;
                    } elseif ($application->uploads->srl_license_front != NULL && $application->uploads->srl_license_back != NULL) {
                        return false;
                    } else {
                        return true;
                    }
                })],
                'srl_cnic_back' => [Rule::requiredIf(function () use ($application) {
                    if ($application->uploads->srl_cnic_front != NULL && $application->uploads->srl_cnic_back != NULL) {
                        return false;
                    } elseif ($application->uploads->srl_license_front != NULL && $application->uploads->srl_license_back != NULL) {
                        return false;
                    } else {
                        return true;
                    }
                })],
                'srl_license_front' => [Rule::requiredIf(function () use ($application) {
                    if ($application->uploads->srl_cnic_front != NULL && $application->uploads->srl_cnic_back != NULL) {
                        return false;
                    } elseif ($application->uploads->srl_license_front != NULL && $application->uploads->srl_license_back != NULL) {
                        return false;
                    } else {
                        return true;
                    }
                })],
                'srl_license_back' => [Rule::requiredIf(function () use ($application) {
                    if ($application->uploads->srl_cnic_front != NULL && $application->uploads->srl_cnic_back != NULL) {
                        return false;
                    } elseif ($application->uploads->srl_license_front != NULL && $application->uploads->srl_license_back != NULL) {
                        return false;
                    } else {
                        return true;
                    }
                })],
                'certificate_lc' => [Rule::requiredIf(function () use ($application) {
                    return $application->uploads->certificate_lc != NULL ? false : true;
                })],
                'certificate2_lc' => [Rule::requiredIf(function () use ($application) {
                    return $application->uploads->certificate2_lc != NULL ? false : true;
                })],
                'cases_lc' => [Rule::requiredIf(function () use ($application) {
                    return $application->uploads->cases_lc != NULL ? false : true;
                })],
                'org_prov_certificate_lc' => [Rule::requiredIf(function () use ($application) {
                    return $application->uploads->org_prov_certificate_lc != NULL ? false : true;
                })],
                'practice_certificate' => [Rule::requiredIf(function () use ($application) {
                    if ($application->uploads->practice_certificate == NULL && $application->is_exemption == 1 && $application->exemption_reason == 2) {
                        return true;
                    } else {
                        return false;
                    }
                })],
                'judge_certificate' => [Rule::requiredIf(function () use ($application) {
                    if ($application->uploads->judge_certificate == NULL && $application->is_exemption == 1 && $application->exemption_reason == 3) {
                        return true;
                    } else {
                        return false;
                    }
                })],

            ];

            $messages = [

                'srl_cnic_front.required' => 'CNIC or License any one of both images is required',
                'srl_cnic_back.required' => 'CNIC or License any one of both images is required',
                'srl_license_front.required' => 'CNIC or License any one of both images is required',
                'srl_license_back.required' => 'CNIC or License any one of both images is required',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $nextView = view('frontend.lower-court.create-step-7', compact('application'))->render();
            return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 7]);
        }

        return view('frontend.lower-court.create-step-6', compact('application'));
    }

    public function createStep7(Request $request, $id)
    {
        $application = LowerCourt::findOrFail($id);
        $user = User::find($application->user_id);

        if ($request->isMethod('post')) {
            if ($application->final_submitted_at == NULL) {
                $application->update([
                    'final_submitted_at' => Carbon::now(),
                    'is_final_submitted' => true
                ]);
            }

            $application->update([
                'age' => getLcAge($application->date_of_birth, $application)
            ]);

            $this->lowerCourtPayment($application);

            $password = $request->session()->get('password');
            $nextView = \URL::route('frontend.lower-court.show', $application->id);
            return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 9]);
        }

        return view('frontend.lower-court.create-step-7', compact('application'));
    }

    public function show(Request $request, $id)
    {
        $application = LowerCourt::with(['payments', 'additional_notes' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->find($id);

        $payments = $application->payments->where('is_voucher_print', 1)->groupBy('voucher_type');

        return view('frontend.lower-court.show', compact('application', 'payments'));
    }

    public function uploadFile(Request $request, $slug)
    {
        $id = $request->session()->get('lower_court_application');
        $model = LowerCourt::find($id);
        $upload = LawyerUpload::where('lower_court_id', $model->id)->first();
        $directory = 'lower-court/' . $model->id;
        if ($request->hasFile($slug)) {
            $fileName = $request->file($slug)->getClientOriginalName();
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file($slug)));
            ($upload == NULL) ? $upload = LawyerUpload::create(['lower_court_id' => $model->id, $slug => $url]) : $upload->update([$slug => $url]);
        }
    }

    public function destroyFile(Request $request, $slug)
    {
        $id = $request->session()->get('lower_court_application');
        $model = LowerCourt::find($id);
        $destroy = LawyerUpload::where('lower_court_id', $model->id)->first();
        $destroy->update([$slug => NULL]);
        return redirect()->back();
    }

    public function destroyAcademicRecord($id)
    {
        $lawyerEducation = LawyerEducation::find($id);
        $lawyerEducation->delete();
        return true;
    }

    public function lowerCourtPayment($application)
    {
        for ($i = 1; $i <= 6; $i++) {
            $payments = Payment::where('user_id', $application->user_id)
                ->where('lower_court_id', $application->id)
                ->where('application_type', 1)
                ->where('voucher_type', $i)
                ->where('payment_status', 0)->get();

            if ($payments->count() > 0) {
                foreach ($payments as $key => $payment) {
                    $payment->delete();
                }
            }

            $age = getLcAge($application->date_of_birth, $application);

            $paymentAmountDiff = Payment::where('user_id', $application->user_id)
                ->where('lower_court_id', $application->id)
                ->where('application_type', 1)
                ->where('voucher_type', $i)
                ->where('payment_status', 1);


            if ($paymentAmountDiff->count() == 0 || getLcVchAmount($application->id, $i) - $paymentAmountDiff->sum('amount') != 0) {
                $voucher_payment = Payment::create([
                    "lower_court_id" => $application->id,
                    "user_id" => $application->user_id,
                    "application_type" => 1,
                    "application_status" => 7,
                    "payment_status" => 0,
                    "payment_type" => 0,
                    "amount" => 0,
                    "bank_name" => 'HBL',
                    "voucher_name" => getLcVoucherName($i),
                    "voucher_type" => $i,
                ]);
                $voucher_payment->update([
                    "voucher_no" => getLcVoucherNo($voucher_payment->id),
                    "amount" => getLcVchAmount($application->id, $i) - $paymentAmountDiff->sum('amount'),
                    "is_voucher_print" => getLcVchAmount($application->id, $i) - $paymentAmountDiff->sum('amount') == 0 ? 0 : 1,
                ]);
                if ($i == 2) {
                    $voucher_payment->update([
                        "enr_fee_pbc" => getGeneralFund($age, $application)['enrollment_fee'] - $paymentAmountDiff->sum('enr_fee_pbc'),
                        "id_card_fee" => getGeneralFund($age, $application)['id_card_fee'] - $paymentAmountDiff->sum('id_card_fee'),
                        "certificate_fee" => getGeneralFund($age, $application)['certificate_fee'] - $paymentAmountDiff->sum('certificate_fee'),
                        "building_fund" => getGeneralFund($age, $application)['building_fund'] - $paymentAmountDiff->sum('building_fund'),
                        "general_fund" => getGeneralFund($age, $application)['general_fund'] - $paymentAmountDiff->sum('general_fund'),
                        "degree_fee" => getGeneralFund($age, $application)['degree_fee'] - $paymentAmountDiff->sum('degree_fee'),
                    ]);
                }
            }
        }
    }

    public function createStep4Exemption(Request $request, $id)
    {
        $lc = LowerCourt::find($id);

        $data = [
            'is_exemption' => $request->get('exemption_reason') == 1 || $request->get('exemption_reason') == 2 ? true : false,
            'exemption_reason' => $request->get('exemption_reason'),
            'bf_plan' => $request->get('bf_plan'),
            'exemption_form' => TRUE,
        ];

        if ($lc->intimation_degree_fee == 0) {
            $data += [
                'degree_place' => $request->get('degree_place'),
            ];
        }

        $lc->update($data);

        return response()->json(['status' => 1, 'message' => 'Submitted']);
    }

    public function uploadPaymentVoucher(Request $request, $id)
    {
        $payment = Payment::find($id);

        $directory = 'lc-vouchers/' . $payment->lower_court_id;
        if ($request->hasFile('voucher_file_' . $payment->id)) {
            $fileName = $request->file('voucher_file_' . $payment->id)->getClientOriginalName();
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('voucher_file_' . $payment->id)));
            $payment->update([
                'voucher_file' => $url,
                'paid_date' => Carbon::parse(Carbon::now())->format('Y-m-d'),
                'transaction_id' => $payment->voucher_no,
            ]);
        }

        $payment->update([
            'paid_date' => $request->paid_date,
            'transaction_id' => $request->transaction_id,
        ]);

        $application = LowerCourt::where('id', $payment->lower_court_id)->first();

        $c1 = $application->payments->where('is_voucher_print', 1)->where('voucher_file', '!=', NULL)->count();
        $c2 = $application->payments->where('is_voucher_print', 1)->count();

        if ($c1 == $c2) {
            $application->update([
                'final_submitted_at' => setDateFormat($payment->paid_date),
                'age' => getLcAge($application->date_of_birth, $application)
            ]);

            $this->lowerCourtUnpaidPaymentCheck($application);
        }

        if ($application->partial_payment_notify == 1) {

            $data = [
                "phone" => '+92' . $application->active_mobile_no,
                "message" => "KINDLY PAY THE REMAINING DUES",
            ];
            sendMessageAPI($data);

            $application->update([
                'partial_payment_notify' => 0,
            ]);
        }

        return response()->json(['status' => 1, 'message' => 'Submitted']);
    }

    public function destroyPaymentVoucher($id)
    {
        $payment = Payment::find($id);
        $payment->update([
            'voucher_file' => null,
            'paid_date' => null,
            'transaction_id' => null,
        ]);

        return redirect()->back();
    }

    public function lowerCourtUnpaidPaymentCheck($application)
    {
        for ($i = 1; $i <= 6; $i++) {
            $payments = Payment::where('user_id', $application->user_id)
                ->where('lower_court_id', $application->id)
                ->where('application_type', 1)
                ->where('voucher_type', $i)
                ->where('voucher_file', NULL)
                ->get();

            if ($payments->count() > 0) {
                foreach ($payments as $key => $payment) {
                    $payment->delete();
                }
            }

            $age = getLcAge($application->date_of_birth, $application);

            $paymentAmountDiff = Payment::where('user_id', $application->user_id)
                ->where('lower_court_id', $application->id)
                ->where('application_type', 1)
                ->where('voucher_type', $i)
                ->where('voucher_file', "!=", NULL);

            $r1 = getLcVchAmount($application->id, $i) - $paymentAmountDiff->sum('amount');

            if ($r1 > 0) {
                $voucher_payment = Payment::create([
                    "lower_court_id" => $application->id,
                    "user_id" => $application->user_id,
                    "application_type" => 1,
                    "application_status" => 7,
                    "payment_status" => 0,
                    "payment_type" => 0,
                    "amount" => 0,
                    "bank_name" => 'Habib Bank Limited',
                    "voucher_name" => getLcVoucherName($i),
                    "voucher_type" => $i,
                ]);

                $voucher_payment->update([
                    "voucher_no" => getLcVoucherNo($voucher_payment->id),
                    "amount" => getLcVchAmount($application->id, $i) - $paymentAmountDiff->sum('amount'),
                    "is_voucher_print" => getLcVchAmount($application->id, $i) - $paymentAmountDiff->sum('amount') == 0 ? 0 : 1,
                ]);

                if ($i == 2) {
                    $voucher_payment->update([
                        "enr_fee_pbc" => getGeneralFund($age, $application)['enrollment_fee'] - $paymentAmountDiff->sum('enr_fee_pbc'),
                        "id_card_fee" => getGeneralFund($age, $application)['id_card_fee'] - $paymentAmountDiff->sum('id_card_fee'),
                        "certificate_fee" => getGeneralFund($age, $application)['certificate_fee'] - $paymentAmountDiff->sum('certificate_fee'),
                        "building_fund" => getGeneralFund($age, $application)['building_fund'] - $paymentAmountDiff->sum('building_fund'),
                        "general_fund" => getGeneralFund($age, $application)['general_fund'] - $paymentAmountDiff->sum('general_fund'),
                        "degree_fee" => getGeneralFund($age, $application)['degree_fee'] - $paymentAmountDiff->sum('degree_fee'),
                    ]);
                }

                $application->update([
                    'partial_payment_notify' => true,
                    'partial_payment_notify_at' => Carbon::now(),
                ]);
            }
        }
    }
}
