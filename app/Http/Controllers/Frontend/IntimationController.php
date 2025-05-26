<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use App\Application;
use App\District;
use App\Tehsil;
use App\LawyerAddress;
use App\LawyerEducation;
use App\University;
use App\Country;
use App\Province;
use App\LawyerUpload;
use App\Bar;
use App\LowerCourt;
use App\Payment;
use App\Policy;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;
use PDF;
use Illuminate\Support\Facades\URL as FacadesURL;

class IntimationController extends Controller
{
    public function show($id)
    {
        $application = Application::find($id);
        $payments = Payment::where('user_id', $application->user_id)->where('application_type', 6)->get();
        return view('frontend.intimation.show', compact('application', 'payments'));
    }

    public function create(Request $request)
    {
        $user = Auth::guard('frontend')->user();

        // checkCnicExist($user->cnic_no);

        $data = [
            'application_type' => 6, // Intimation Application
            'app_type' => 1, // DIRECT ENTRY
            'is_approved' => 0, // Not Approved
            'application_status' => 6, // Pending Application
            'user_id' => $user->id, // Login User ID
            'is_frontend' => true,
            'cnic_no' => $user->cnic_no,
            'active_mobile_no' => $user->phone,
            'email' => $user->email,
        ];

        $application = Application::where('user_id', $user->id)->first();

        if ($application == NULL) {
            $application = Application::create($data);
            $application->update([
                'application_token_no' => $application->id + 1000,
            ]);
        }

        $request->session()->forget('intimation_application');
        if (empty($request->session()->get('intimation_application'))) {
            $application->fill(['application_id' => $application->id]);
            $request->session()->put('intimation_application', $application->id);
        }

        return redirect()->route('frontend.intimation.create-step-1', $application->id);
    }

    public function createStep1(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $bars = Bar::select('id', 'name')->orderBy('name', 'asc')->get();
        $years = range(Carbon::now()->year, 1900);

        if ($request->isMethod('post')) {

            $rules = [
                'llb_passing_year' => 'required|max:255',
                'rcard_date' => 'required|date|before_or_equal:today',
                'bar_association' => 'required|max:255',
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'father_name' => 'required|max:255',
                'gender' => 'required|max:255',
                'date_of_birth' => 'required|before:18 years ago',
                'blood_group' => 'nullable|max:255',
            ];

            $messages = [
                'date_of_birth.before' => 'The date of birth is invalid. Please select birth date as per CNIC.',
                'rcard_date.before_or_equal' => 'Your are not eligible for intimation application.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $age_cal_date = isset($application->final_submitted_at) ? $application->final_submitted_at : $application->created_at;

            $data = [
                'llb_passing_year' => $request->input('llb_passing_year'),
                'rcard_date' => setDateFormat($request->input('rcard_date')),
                'bar_association' => $request->input('bar_association'),
                'advocates_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'so_of' => $request->input('father_name'),
                'gender' => $request->input('gender'),
                'date_of_birth' => $request->input('date_of_birth'),
                'email' => $request->input('email'),
                'active_mobile_no' => $request->input('active_mobile_no'),
                'age' =>  Carbon::parse($request->date_of_birth)->diff($age_cal_date)->format('%y.%m'),
                'blood' => $request->input('blood_group'),
            ];

            $application->update($data);
            $this->intimationPaymentVoucher($request, $application);

            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return view('frontend.intimation.create-step-1', compact('application', 'bars', 'years'));
    }

    public function createStep2(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        if ($request->isMethod('post')) {

            $date = date("d-m-Y", strtotime($request->input('cnic_expiry_date')));

            $rules = [
                'cnic_expiry_date' => 'required|date|date_format:d-m-Y|after:01-01-1900',
                'degree_place' => $application->final_submitted_at == NULL ? 'required|in:1,2,3' : 'nullable|in:1,2,3',
            ];

            $messages = [
                'cnic_no.required' => 'The cnic number field is required.',
                'cnic_no.min' => 'The cnic number you have entered is invalid.',
                'cnic_expiry_date.date_format' => 'The cnic expiry date you have entered is invalid',
                'cnic_expiry_date.after' => 'The cnic expiry date you have entered is invalid',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $data = [
                'cnic_expiry_date' => $date,
                'degree_place' => $request->input('degree_place'),
            ];

            $application->update($data);
            $this->intimationPaymentVoucher($request, $application);

            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return view('frontend.intimation.create-step-2', compact('application'));
    }

    public function createStep3(Request $request, $id)
    {
        $application = Application::findOrFail($id);
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
                    'pa_str_address' => 'required|max:0',
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
                'application_id' => $application->id,
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

            $lawyerAddress = LawyerAddress::where('application_id', $application->id)->first();
            ($lawyerAddress == NULL) ? LawyerAddress::create($data) : $lawyerAddress->update($data);

            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return view('frontend.intimation.create-step-3', compact('districts', 'tehsils', 'application', 'provinces', 'countries'));
    }

    public function createStep4(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $universities = University::select('id', 'name')->where('approved', 1)->where('type', 1)->orderBy('name', 'asc')->get();
        $affliatedUniversities = University::where('type', 2)->where('approved', 1)->orderBy('name', 'asc')->get();

        $academicRecord = [];
        foreach ($application->educations as $key => $applicationEducation) {
            $academicRecord[] = $applicationEducation->qualification;
        }

        if ((in_array('1', $academicRecord) && in_array('2', $academicRecord) && in_array('3', $academicRecord) && in_array('4', $academicRecord) &&  in_array('5', $academicRecord) && in_array('6', $academicRecord)) || (in_array('1', $academicRecord) && in_array('2', $academicRecord) && in_array('7', $academicRecord))) {
            $application->update(['is_academic_record' => TRUE]);
        } else {
            $application->update(['is_academic_record' => FALSE]);
        }

        if ($request->isMethod('post')) {

            $rules = [
                'qualification' => 'required|string|max:255',

                'sub_qualification' => [
                    'nullable', 'string', 'max:255',
                    Rule::requiredIf(
                        $request->qualification == '1' || $request->qualification == '2'
                    )
                ],

                'university_id' => [
                    'nullable', 'integer',
                    Rule::requiredIf(
                        $request->qualification == '4' ||
                            $request->qualification == '5' ||
                            $request->qualification == '6' ||
                            $request->qualification == '7' ||
                            $request->qualification == '8'
                    )
                ],

                'institute' => [
                    'nullable', 'string', 'max:255',
                    Rule::requiredIf(
                        $request->qualification == '1' ||
                            $request->qualification == '2' ||
                            $request->qualification == '3' ||
                            $request->university_id == 'other'
                    )
                ],

                'total_marks' => 'required|numeric',
                'obtained_marks' => 'required|numeric|lte:total_marks',
                'roll_no' => 'required|string',
                'passing_year' => 'required|integer',
                'certificate_url' => 'required|min:50',
            ];

            $messages = [
                'obtained_marks.lte' => 'The obtained marks must be less than or equal to total marks.',
                'certificate_url.min' => 'The file must be greater than 50 kb.',
            ];

            $validator = Validator::make($request->all(), $rules,  $messages);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $data = [
                'application_id' => $application->id,
                'qualification' => $request->input('qualification'),
                'sub_qualification' => $request->input('sub_qualification'),
                'university_id' => $request->input('university_id'),
                'institute' => $request->input('institute'),
                'total_marks' => $request->input('total_marks'),
                'obtained_marks' => $request->input('obtained_marks'),
                'roll_no' => $request->input('roll_no'),
                'passing_year' => $request->input('passing_year'),
            ];

            $lawyerEducation = LawyerEducation::updateOrCreate($data);
            uploadEducationalCertificate($request, $lawyerEducation->id, $application->id);

            $user = Auth::guard('frontend')->user();
            if ($request->university_id == 0) {
                $university = University::create([
                    'name' => $request->institute,
                    'approved' => false,
                    'application_id' => $application->id,
                    'user_id' => $user->id,
                ]);
                $lawyerEducation->update(['university_id' => $university->id]);
            }

            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return view('frontend.intimation.create-step-4', compact('application', 'universities', 'affliatedUniversities', 'academicRecord'));
    }

    public function createStep5(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $bars = Bar::select('id', 'name')->orderBy('name', 'asc')->get();

        if ($request->isMethod('post')) {

            $seniorLawyer = Application::where('srl_cnic_no', $request->srl_cnic_no)->where('is_intimation_completed', 0)->count();
            if ($seniorLawyer >= 3 && $application->srl_cnic_no != $request->srl_cnic_no) {
                return response()->json([
                    'status' => false,
                    'error' => 'This senior lawyer already be assigned to 3 applications. Please choose someone other for your process.',
                ], 400);
            }

            $rules = [
                'srl_name' => 'required|max:255',
                'srl_bar_name' => 'required|max:255',
                'srl_office_address' => 'required|max:255',
                'srl_enr_date' => 'required|before:10 years ago',
                'srl_mobile_no' => 'required|max:255',
                'srl_cnic_no' => 'required|min:15',
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
            ];


            if ($application->srl_joining_date == NULL) {
                $current_date = Carbon::now()->format('d-m-Y');
                $prev_month_date = date("Y-m-d", strtotime('-1 month', strtotime($current_date)));
                $rules += [
                    'srl_joining_date' => 'required|before_or_equal:today|after_or_equal:' . $prev_month_date,
                ];
            }

            $messages = [
                'srl_cnic_no.required' => 'The cnic number field is required.',
                'srl_cnic_no.min' => 'The cnic number you have entered is invalid.',
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

            $data = [
                'srl_name' => $request->input('srl_name'),
                'srl_bar_name' => $request->input('srl_bar_name'),
                'srl_office_address' => $request->input('srl_office_address'),
                'srl_enr_date' => $request->input('srl_enr_date'),
                'srl_mobile_no' => $request->input('srl_mobile_no'),
                'srl_cnic_no' => $request->input('srl_cnic_no'),
            ];

            $application->update($data);
            if ($application->srl_joining_date == NULL) {
                $application->update(['srl_joining_date' => $request->input('srl_joining_date')]);
            }

            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return view('frontend.intimation.create-step-5', compact('application', 'bars'));
    }

    public function createStep6(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        if ($request->isMethod('post')) {

            $user = User::find($application->user_id);
            $rules = [
                'bank_name' => [Rule::requiredIf($application->paymentVoucher == NULL)],
                'otp' => ['required', 'digits:6', 'numeric']
            ];

            $messages = [
                'bank_name.required' => 'One of these options is required',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            if ($user->otp == $request->otp) {
                $application->update(['is_accepted' => TRUE]);
                if ($application->final_submitted_at == NULL) {
                    $application->update(['final_submitted_at' => Carbon::now()]);
                }
                $user->update(['otp' => null]);
            } else {
                return response()->json(['errors' => ['otp' => ['The One Time Password is invalid']]], 400);
            }

            $this->intimationPaymentVoucher($request, $application);

            // $currentDate = Carbon::now();
            // $vocuherNumber = 1000000000000 + strtotime($currentDate) + $application->application_token_no;

            // $data = [
            //     'application_id' => $application->id,
            //     'user_id' => $application->user_id,
            //     'application_type' => 6, // Intimation
            //     'application_status' => 7, // Pending
            //     'payment_status' => 0, // Unpaid
            //     'payment_type' => 0,
            //     'amount' => getVoucherAmount($application->id),
            //     'bank_name' => $request->has('bank_name') ? $request->bank_name : 'Habib Bank Limited',
            //     'voucher_no' => $vocuherNumber,
            // ];

            // $payment = Payment::where('user_id', $application->user_id)->where('application_type', 6)->first();
            // if ($payment == NULL) {
            //     Payment::create($data);
            // } else {
            //     $payment->update($data);
            // }

            $data = [
                "phone" => '+92' . $application->active_mobile_no,
                "message" => 'Dear Applicant, Your application has been submitted. Application No: ' . $application->application_token_no,
            ];

            // sendMessageAPI($data);

            $user = User::find($application->user_id);
            $mail = [
                'subject' => 'PUNJAB BAR COUNCIL - APPLICATION SUBMITTED',
                'name' => getLawyerName($application->id),
                'description' => '<p>Your application has been submitted. Application No: ' . $application->application_token_no . '</p>',
                'url' => route('frontend.login'),
            ];
            // sendMailToUser($mail, $user);

            $request->session()->forget('payment_step_7');
            if (empty($request->session()->get('payment_step_7'))) {
                $application->fill(['application_id' => $application->id]);
                $request->session()->put('payment_step_7', $application->id);
            }

            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return view('frontend.intimation.create-step-6', compact('application'));
    }

    public function createStep7(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        if ($request->isMethod('post')) {
            return response()->json(['status' => 1, 'message' => 'success']);
        }

        $paymentStep7 = $request->session()->get('payment_step_7');
        if ($paymentStep7 == NULL) {
            return redirect()->route('home');
        }

        return view('frontend.intimation.create-step-7', compact('application'));
    }

    public function print(Request $request)
    {
        $application = Application::find($request->application);
        view()->share('application', $application);
        if ($request->has('download')) {
            $pdf = PDF::loadView('frontend.intimation.pdf');
            $pdf->setPaper('A4', 'portrait');
            return $pdf->download('APPLICATION-' . $application->application_token_no . '.pdf');
        }
        return view('frontend.intimation.pdf');
    }

    public function pdf(Request $request)
    {
        $application = Application::find($request->application);

        $profile_image = isset($application->uploads->profile_image) ? asset('storage/app/public/' . $application->uploads->profile_image) : '';

        $finaL_submission_date = Carbon::parse($application->final_submitted_at)->format('Y-m-d');
        $policy = Policy::with('policyFees')->whereDate('start_date', '<=', $finaL_submission_date)
            ->whereDate('end_date', '>=', $finaL_submission_date)
            ->first();

        if ($policy == null) {
            abort(403, 'Unauthorized action.');
        }

        view()->share([
            'application' => $application,
            'policy' => $policy,
            'profile_image' => $profile_image,
        ]);

        if ($request->has('download')) {
            $pdf = PDF::loadView('frontend.intimation.pdf');
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('APPLICATION-' . $application->application_token_no . '.pdf');
        }

        return view('frontend.intimation.pdf');
    }

    public function voucher(Request $request)
    {
        $application = Application::find($request->application);
        view()->share('application', $application);
        if ($request->has('download')) {
            $pdf = PDF::loadView('frontend.intimation.voucher');
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('HBL-Bank-Fee-Voucher-' . $application->application_token_no . '.pdf', array("Attachment" => false));
        }
        return view('frontend.intimation.voucher');
    }

    public function uploadProfileImage(Request $request)
    {
        $id = $request->session()->get('intimation_application');
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/' . $model->id;
        if ($request->hasFile('profile_image')) {
            $fileName = $request->file('profile_image')->getClientOriginalName();
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('profile_image')));
            ($upload == NULL) ? LawyerUpload::create(['application_id' => $model->id, 'profile_image' => $url]) : $upload->update(['profile_image' => $url]);
        }
    }

    public function destroyProfileImage(Request $request)
    {
        $id = $request->session()->get('intimation_application');
        $model = Application::find($id);
        $destroy = LawyerUpload::where('application_id', $model->id)->first();
        $destroy->update(['profile_image' => NULL]);

        return redirect()->back();
    }

    public function uploadCnicFront(Request $request)
    {
        $id = $request->session()->get('intimation_application');
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/' . $model->id;
        if ($request->hasFile('cnic_front')) {
            $fileName = $request->file('cnic_front')->getClientOriginalName();
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('cnic_front')));
            ($upload == NULL) ? LawyerUpload::create(['application_id' => $model->id, 'cnic_front' => $url]) : $upload->update(['cnic_front' => $url]);
        }
    }

    public function destroyCnicFront(Request $request)
    {
        $id = $request->session()->get('intimation_application');
        $model = Application::find($id);
        $destroy = LawyerUpload::where('application_id', $model->id)->first();
        $destroy->update(['cnic_front' => NULL]);

        return redirect()->back();
    }

    public function uploadCnicBack(Request $request)
    {
        $id = $request->session()->get('intimation_application');
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/' . $model->id;
        if ($request->hasFile('cnic_back')) {
            $fileName = $request->file('cnic_back')->getClientOriginalName();
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('cnic_back')));
            ($upload == NULL) ? LawyerUpload::create(['application_id' => $model->id, 'cnic_back' => $url]) : $upload->update(['cnic_back' => $url]);
        }
    }

    public function destroyCnicBack(Request $request)
    {
        $id = $request->session()->get('intimation_application');
        $model = Application::find($id);
        $destroy = LawyerUpload::where('application_id', $model->id)->first();
        $destroy->update(['cnic_back' => NULL]);

        return redirect()->back();
    }

    public function uploadSrlCnicFront(Request $request)
    {
        $id = $request->session()->get('intimation_application');
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/' . $model->id;
        if ($request->hasFile('srl_cnic_front')) {
            $fileName = $request->file('srl_cnic_front')->getClientOriginalName();
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('srl_cnic_front')));
            ($upload == NULL) ? LawyerUpload::create(['application_id' => $model->id, 'srl_cnic_front' => $url]) : $upload->update(['srl_cnic_front' => $url]);
        }
    }

    public function destroySrlCnicFront(Request $request)
    {
        $id = $request->session()->get('intimation_application');
        $model = Application::find($id);
        $destroy = LawyerUpload::where('application_id', $model->id)->first();
        $destroy->update(['srl_cnic_front' => NULL]);

        return redirect()->back();
    }

    public function uploadSrlCnicBack(Request $request)
    {
        $id = $request->session()->get('intimation_application');
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/' . $model->id;
        if ($request->hasFile('srl_cnic_back')) {
            $fileName = $request->file('srl_cnic_back')->getClientOriginalName();
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('srl_cnic_back')));
            ($upload == NULL) ? LawyerUpload::create(['application_id' => $model->id, 'srl_cnic_back' => $url]) : $upload->update(['srl_cnic_back' => $url]);
        }
    }

    public function destroySrlCnicBack(Request $request)
    {
        $id = $request->session()->get('intimation_application');
        $model = Application::find($id);
        $destroy = LawyerUpload::where('application_id', $model->id)->first();
        $destroy->update(['srl_cnic_back' => NULL]);

        return redirect()->back();
    }

    public function uploadSrlLicenseFront(Request $request)
    {
        $id = $request->session()->get('intimation_application');
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/' . $model->id;
        if ($request->hasFile('srl_license_front')) {
            $fileName = $request->file('srl_license_front')->getClientOriginalName();
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('srl_license_front')));
            ($upload == NULL) ? LawyerUpload::create(['application_id' => $model->id, 'srl_license_front' => $url]) : $upload->update(['srl_license_front' => $url]);
        }
    }

    public function destroySrlLicenseFront(Request $request)
    {
        $id = $request->session()->get('intimation_application');
        $model = Application::find($id);
        $destroy = LawyerUpload::where('application_id', $model->id)->first();
        $destroy->update(['srl_license_front' => NULL]);

        return redirect()->back();
    }

    public function uploadSrlLicenseBack(Request $request)
    {
        $id = $request->session()->get('intimation_application');
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/' . $model->id;
        if ($request->hasFile('srl_license_back')) {
            $fileName = $request->file('srl_license_back')->getClientOriginalName();
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('srl_license_back')));
            ($upload == NULL) ? LawyerUpload::create(['application_id' => $model->id, 'srl_license_back' => $url]) : $upload->update(['srl_license_back' => $url]);
        }
    }

    public function destroySrlLicenseBack(Request $request)
    {
        $id = $request->session()->get('intimation_application');
        $model = Application::find($id);
        $destroy = LawyerUpload::where('application_id', $model->id)->first();
        $destroy->update(['srl_license_back' => NULL]);

        return redirect()->back();
    }

    public function destroyAcademicRecord($id)
    {
        LawyerEducation::find($id)->delete();
        return redirect()->back();
    }

    public function bankIslamiVoucher(Request $request)
    {
        $application = Application::find($request->application);
        $payment = Payment::where('user_id', $application->user_id)->where('application_type', 6)->first();

        view()->share([
            'application' => $application,
            'payment' => $payment,
        ]);

        if ($request->has('download')) {
            $pdf = PDF::loadView('frontend.intimation.vouchers.bank-islami');
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('Bank-Islami-Voucher-' . $application->application_token_no . '.pdf', array("Attachment" => false));
        }

        return view('frontend.intimation.voucher');
    }

    public function habibBankLimitedVoucher(Request $request)
    {
        $application = Application::find($request->application);
        $payment = Payment::where('user_id', $application->user_id)->where('application_type', 6)->first();

        view()->share([
            'application' => $application,
            'payment' => $payment,
        ]);

        if ($request->has('download')) {
            $pdf = PDF::loadView('frontend.intimation.vouchers.hbl');
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('Habib-Bank-Limited-Voucher-' . $application->application_token_no . '.pdf', array("Attachment" => false));
        }

        return view('frontend.intimation.voucher');
    }

    public function sendOTP($id)
    {
        $application = Application::find($id);
        $user = User::find($application->user_id);
        $digits = 6;
        $otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $user->update(['otp' => $otp]);
        $data = [
            "phone" => '+92' . $user->phone,
            "otp" => $user->otp,
            "event_id" => 79,
        ];
        sendMessageAPI($data);
    }

    public function transfer(Request $request)
    {
        $application = Application::find($request->id);
        $response = validateIntimationApplication($application, 0);

        if (count($response['errors']) > 0) {
            return response()->json(
                [
                    'status' => true,
                    'code' => 400,
                    'icon' => 'warning',
                    'title' => 'Not Eligible',
                    'url' => FacadesURL::route('frontend.dashboard'),
                    'message' => 'Application is not eligible to lower Court. Please see the reasons.',
                ]
            );
        } else {
            $application->update([
                'objections' => NULL,
                'error_logs' => NULL,
            ]);

            $lc_id = validateIntimationApplication($application, 1)['lowerCourtID'];
            $lc = LowerCourt::find($lc_id);

            return response()->json(
                [
                    'status' => true,
                    'code' => 200,
                    'icon' => 'success',
                    'title' => 'Application Eligible',
                    'url' => FacadesURL::route('frontend.dashboard'),
                    'message' => 'Application is eligible to move to lower Court.',
                ]
            );
        }
    }

    public function intimationPaymentVoucher($request, $application)
    {
        $deletePayments = Payment::where('user_id', $application->user_id)
            ->where('application_id', $application->id)
            ->where('application_type', 6)
            ->where('payment_status', 0)->get();

        if ($deletePayments->count() > 0) {
            foreach ($deletePayments as $key => $deletePayment) {
                $deletePayment->delete();
            }
        }

        $currentDate = Carbon::now();
        $vch_no = 1000000000000 + strtotime($currentDate) + $application->application_token_no;
        $payments = Payment::where('user_id', $application->user_id)->where('application_type', 6)->get();
        $paidPayments = Payment::select(DB::raw("SUM(amount) as paid_amount"))->where('payment_status', 1)->where('user_id', $application->user_id)->where('application_type', 6)->get()->toArray();

        $get_vch_amount =  getVoucherAmount($application->id);
        $totalAmount =  $get_vch_amount['total_amount'];
        $paidAmount = $paidPayments[0]['paid_amount'];

        $data = [
            'application_id' => $application->id,
            'user_id' => $application->user_id,
            'application_type' => 6,
            'application_status' => 7,
            'payment_status' => 0,
            'payment_type' => 0,
            'amount' =>  $totalAmount - $paidAmount,
            'degree_fee' => $get_vch_amount['degree_fee'],
            'enr_fee_pbc' => $get_vch_amount['enr_fee_pbc'],
            'bank_name' => 'HBL',
            'voucher_no' => $vch_no,
        ];

        if (isset($payments) && $payments->count() > 0) {
            if ($totalAmount != $paidAmount) {
                foreach ($payments as $key => $payment) {
                    $payment = Payment::where('user_id', $application->user_id)
                        ->where('application_type', 6)
                        ->where('payment_status', 0)
                        ->first();
                    if ($payment == NULL) {
                        $payment = Payment::create($data);
                        createActivity($payment, 'Payment');
                    }
                }
            }
        } else if ($payments->count() == 0) {
            $payment = Payment::create($data);
            createActivity($payment, 'Payment');
        }
    }
}
