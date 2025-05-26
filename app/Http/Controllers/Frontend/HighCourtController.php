<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Bar;
use App\Country;
use App\District;
use App\LawyerAddress;
use App\LawyerUpload;
use App\HighCourt;
use App\Payment;
use App\Province;
use App\Tehsil;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\GcLowerCourt;

class HighCourtController extends Controller
{
    public function moveToHighCourt($user_id)
    {
        moveToHighCourt($user_id);
        return redirect()->route('frontend.high-court.create-step-1');
    }

    public function createStep1(Request $request)
    {
        $user = User::find(Auth::guard('frontend')->user()->id);

        if (isset($user->hc->id)) {
            $application = HighCourt::find($user->hc->id);
        } else {
            return redirect()->route('frontend.dashboard');
        }

        $bars = Bar::select('id', 'name')->orderBy('name', 'asc')->get();
        $years = range(Carbon::now()->year, 1900);

        $request->session()->forget('high_court_application');
        if (empty($request->session()->get('high_court_application'))) {
            $application->fill(['id' => $application->id]);
            $request->session()->put('high_court_application', $application->id);
        }

        if ($request->isMethod('post') && $request->ajax()) {

            $rules = [
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'father_name' => 'required|max:255',
                'gender' => 'required|max:255',
                'dob' => 'required|before:18 years ago',
                'blood_group' => 'nullable|max:255',
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'mobile_no' => ['required', 'string', 'unique:users,phone,' . $user->id],
            ];

            $messages = [
                'dob.before' => 'The date of birth is invalid. Please select birth date as per CNIC.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $user->update([
                'fname' => $request->input('first_name'),
                'lname' => $request->input('last_name'),
                'email' => $request->input('email'),
                'phone' => $request->input('mobile_no'),
                'father_name' => $request->input('father_name'),
                'gender' => $request->input('gender'),
                'date_of_birth' => setDateFormat($request->input('dob')),
                'blood' => $request->input('blood_group'),
            ]);

            $age_cal_date = isset($application->final_submitted_at) ? $application->final_submitted_at : $application->created_at;

            $application->update([
                'age' =>  Carbon::parse($user->date_of_birth)->diff($age_cal_date)->format('%y.%m'),
            ]);

            $nextView = view('frontend.high-court.create-step-2', compact('application'))->render();
            return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 2]);
        }

        return view('frontend.high-court.create-step-1', compact('application', 'bars', 'years', 'user'));
    }

    public function createStep2(Request $request, $id)
    {
        $application = HighCourt::findOrFail($id);
        $user = User::find(Auth::guard('frontend')->user()->id);

        if ($request->isMethod('post')) {

            $rules = [
                'cnic_exp_date' => 'required',
                'lc_exp_date' => ['required', function ($attribute, $value, $fail) {
                    $endOfYear = Carbon::parse('December 31')->endOfDay();

                    if (Carbon::parse($value)->greaterThan($endOfYear)) {
                        $fail("The lower court expiry date must be before or equal to December 31st of the current year.");
                    }
                }],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $application->update([
                'lc_exp_date' => Carbon::parse($request->input('lc_exp_date'))->format('Y-m-d'),
            ]);

            $application->user->update([
                'cnic_expired_at' => setDateFormat($request->input('cnic_exp_date')),
            ]);

            $countries = Country::select('id', 'name')->orderBy('name', 'asc')->get();
            $provinces = Province::select('id', 'name')->orderBy('name', 'asc')->get();
            $districts = District::select('id', 'name', 'code')->orderBy('name', 'asc')->get();
            $tehsils = Tehsil::select('id', 'name', 'code')->orderBy('name', 'asc')->get();

            $nextView = view('frontend.high-court.create-step-3', compact('districts', 'tehsils', 'application', 'provinces', 'countries'))->render();
            return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 3]);
        }

        return view('frontend.high-court.create-step-2', compact('application'));
    }

    public function createStep3(Request $request, $id)
    {
        $application = HighCourt::findOrFail($id);
        $countries = Country::select('id', 'name')->orderBy('name', 'asc')->get();
        $provinces = Province::select('id', 'name')->orderBy('name', 'asc')->get();
        $districts = District::select('id', 'name', 'code')->orderBy('name', 'asc')->get();
        $tehsils = Tehsil::select('id', 'name', 'code')->orderBy('name', 'asc')->get();

        if ($request->isMethod('post')) {

            $same_as = $request->input('same_address_btn') == 'on' ? true : false;

            $rules = [
                'ha_house_no' => 'required|max:100',
                'ha_city' => 'required|max:15',
                'ha_country_id' => 'required|integer',
                'ha_province_id' => 'required_if:ha_country_id,166',
                'ha_district_id' => 'required_if:ha_country_id,166',
                'ha_tehsil_id' => 'required_if:ha_country_id,166',
            ];

            if ($same_as == false) {
                $rules += [
                    'pa_house_no' => 'required|max:100',
                    'pa_city' => 'required|max:15',
                    'pa_country_id' => 'required|integer',
                    'pa_province_id' => 'required_if:pa_country_id,166',
                    'pa_district_id' => 'required_if:pa_country_id,166',
                    'pa_tehsil_id' => 'required_if:pa_country_id,166',
                ];
            }

            $messages = [
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
                'high_court_id' => $application->id,
                'ha_house_no' => $request->input('ha_house_no'),
                'ha_city' => $request->input('ha_city'),
                'ha_country_id' => $request->input('ha_country_id'),
                'ha_province_id' => $request->input('ha_province_id'),
                'ha_district_id' => $request->input('ha_district_id'),
                'ha_tehsil_id' => $request->input('ha_tehsil_id'),
                'same_as' => $same_as,
            ];

            if ($request->has('same_address_btn')) {
                $data += [
                    'pa_house_no' => $request->input('ha_house_no'),
                    'pa_city' => $request->input('ha_city'),
                    'pa_country_id' => $request->input('ha_country_id'),
                    'pa_province_id' => $request->input('ha_province_id'),
                    'pa_district_id' => $request->input('ha_district_id'),
                    'pa_tehsil_id' => $request->input('ha_tehsil_id'),
                ];
            } else {
                $data += [
                    'pa_house_no' => $request->input('pa_house_no'),
                    'pa_city' => $request->input('pa_city'),
                    'pa_country_id' => $request->input('pa_country_id'),
                    'pa_province_id' => $request->input('pa_province_id'),
                    'pa_district_id' => $request->input('pa_district_id'),
                    'pa_tehsil_id' => $request->input('pa_tehsil_id'),
                ];
            }

            $lawyerAddress = LawyerAddress::where('high_court_id', $application->id)->first();
            ($lawyerAddress == NULL) ? LawyerAddress::create($data) : $lawyerAddress->update($data);

            $application = HighCourt::findOrFail($id);
            $bars = Bar::select('id', 'name')->orderBy('name', 'asc')->get();

            $nextView = view('frontend.high-court.create-step-4', compact('application', 'bars'))->render();
            return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 4]);
        }

        return view('frontend.high-court.create-step-3', compact('districts', 'tehsils', 'application', 'provinces', 'countries'));
    }

    public function createStep4(Request $request, $id)
    {
        $application = HighCourt::with('payments')->findOrFail($id);
        if ($request->isMethod('post')) {

            $rules = [
                'is_any_misconduct' => 'required',
                'is_prev_rejected' => 'required',
                'is_enrolled_as_advocate' => 'required',
                'is_dismissed_from_public_service' => 'required',
                'is_declared_insolvent' => 'required',
                'is_engaged_in_business' => 'required',
                'is_practice_in_pbc' => 'required',
                'paid_upto_date_renewal' => 'required',

            ];


            $current_date = Carbon::now()->format('d-m-Y');
            $prev_month_date = date("Y-m-d", strtotime('-1 month', strtotime($current_date)));


            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $data = [
                'is_any_misconduct' => $request->is_any_misconduct,
                'is_prev_rejected' => $request->is_prev_rejected,
                'is_enrolled_as_advocate' => $request->is_enrolled_as_advocate,
                'is_dismissed_from_public_service' => $request->is_dismissed_from_public_service,
                'is_declared_insolvent' => $request->is_declared_insolvent,
                'is_engaged_in_business' => $request->is_engaged_in_business,
                'is_practice_in_pbc' => $request->is_practice_in_pbc,
                'paid_upto_date_renewal' => $request->paid_upto_date_renewal,

            ];

            $application->update($data);
            $nextView = view('frontend.high-court.create-step-5', compact('application'))->render();

            return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 5]);
        }

        return view('frontend.high-court.create-step-4', compact('application'));
    }

    public function createStep5(Request $request, $id)
    {
        $application = HighCourt::findOrFail($id);

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

                'lc_card_front' => [Rule::requiredIf(function () use ($application) {
                    return $application->uploads->lc_card_front != NULL ? false : true;
                })],

                'lc_card_back' => [Rule::requiredIf(function () use ($application) {
                    return $application->uploads->lc_card_back != NULL ? false : true;
                })],

                'certificate_hc' => [Rule::requiredIf(function () use ($application) {
                    return $application->uploads->certificate_hc != NULL ? false : true;
                })],

                'certificate2_hc' => [Rule::requiredIf(function () use ($application) {
                    return $application->uploads->certificate2_hc != NULL ? false : true;
                })],

                'cases_lc' => [Rule::requiredIf(function () use ($application) {
                    return $application->uploads->cases_lc != NULL ? false : true;
                })],

                'affidavit_hc' => [Rule::requiredIf(function () use ($application) {
                    return $application->uploads->affidavit_hc != NULL ? false : true;
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

            $nextView = view('frontend.high-court.create-step-6', compact('application'))->render();
            return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 6]);
        }

        return view('frontend.high-court.create-step-5', compact('application'));
    }

    public function createStep6(Request $request, $id)
    {
        $application = HighCourt::findOrFail($id);
        $user = User::find($application->user_id);
        if ($request->isMethod('post')) {
            if ($application->final_submitted_at == NULL) {
                $application->update([
                    'final_submitted_at' => Carbon::now(),
                    'is_final_submitted' => 1
                ]);
            }

            $this->highCourtPayment($application);

            return response()->json(['status' => 1, 'message' => 'success', 'step' => 7]);
        }

        return view('frontend.high-court.create-step-6', compact('application'));
    }

    public function view($user_id)
    {
        $user = Auth::guard('frontend')->user();
        $data = [
            'sr_no_hc' => $user->sr_no_lc,
            'is_frontend' => true,
            'user_id' => $user->id,
        ];

        $application = HighCourt::where('user_id', $user->id)->first();

        if ($application == NULL) {
            $GcCourtData = GcLowerCourt::where('user_id', $user->id)->first();


            if (isset($GcCourtData->date_of_enrollment_lc)) {
                $data['enr_date_lc'] = $GcCourtData->date_of_enrollment_lc;
            }

            $application = HighCourt::create($data);
            // $user = User::find($user_id); // Find the user by ID
            // $user_data_update = [
            //     "register_as" => 'gc_hc',
            //     "sr_no_hc" => $application->sr_no_hc,
            //     "gc_status" => 'pending',
            // ];
            // $user->update($user_data_update);
        }

        return view('frontend.high-court.view', compact('application'));
    }

    public function show(Request $request, $id)
    {
        $application = HighCourt::with(['payments', 'additional_notes' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->find($id);

        $payments = $application->payments->where('is_voucher_print', 1)->groupBy('voucher_type');

        return view('frontend.high-court.show', compact('application', 'payments'));
    }

    public function uploadFile(Request $request, $slug)
    {
        $id = $request->session()->get('high_court_application');
        $model = HighCourt::find($id);
        $upload = LawyerUpload::where('high_court_id', $model->id)->first();
        $directory = 'high-court/' . $model->id;
        if ($request->hasFile($slug)) {
            $fileName = $request->file($slug)->getClientOriginalName();
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file($slug)));
            ($upload == NULL) ? $upload = LawyerUpload::create(['high_court_id' => $model->id, $slug => $url]) : $upload->update([$slug => $url]);
        }
    }

    public function destroyFile(Request $request, $slug)
    {
        $id = $request->session()->get('high_court_application');
        $model = HighCourt::find($id);
        $destroy = LawyerUpload::where('high_court_id', $model->id)->first();
        $destroy->update([$slug => NULL]);
        return redirect()->back();
    }

    public function highCourtPayment($application)
    {
        for ($i = 1; $i <= 7; $i++) {
            $payments = Payment::where('user_id', $application->user_id)
                ->where('high_court_id', $application->id)
                ->where('application_type', 2)
                ->where('voucher_type', $i)
                ->where('payment_status', 0)->get();

            if ($payments->count() > 0) {
                foreach ($payments as $key => $payment) {
                    $payment->delete();
                }
            }

            $age_cal_date = isset($application->final_submitted_at) ? $application->final_submitted_at : $application->created_at;
            $age = Carbon::parse($application->user->date_of_birth)->diff($age_cal_date)->format('%y.%m');

            $paymentAmountDiff = Payment::where('user_id', $application->user_id)
                ->where('high_court_id', $application->id)
                ->where('application_type', 2)
                ->where('voucher_type', $i)
                ->where('payment_status', 1);


            if ($paymentAmountDiff->count() == 0 || getHcVchAmount($application->id, $i) - $paymentAmountDiff->sum('amount') != 0) {
                $voucher_payment = Payment::create([
                    "high_court_id" => $application->id,
                    "user_id" => $application->user_id,
                    "application_type" => 2,
                    "application_status" => 7,
                    "payment_status" => 0,
                    "payment_type" => 0,
                    "amount" => 0,
                    "bank_name" => 'Habib Bank Limited',
                    "voucher_name" => getHcVoucherName($i),
                    "voucher_type" => $i,
                ]);
                $voucher_payment->update([
                    "voucher_no" => getHcVoucherNo($voucher_payment->id),
                    "amount" => getHcVchAmount($application->id, $i) - $paymentAmountDiff->sum('amount'),
                    "is_voucher_print" => getHcVchAmount($application->id, $i) - $paymentAmountDiff->sum('amount') == 0 ? 0 : 1,
                ]);
                if ($i == 2) {
                    $voucher_payment->update([
                        "enr_fee_pbc" => getHcGeneralFund($application)['enrollment_fee'] - $paymentAmountDiff->sum('enr_fee_pbc'),
                        "id_card_fee" => getHcGeneralFund($application)['id_card_fee'] - $paymentAmountDiff->sum('id_card_fee'),
                        "certificate_fee" => getHcGeneralFund($application)['certificate_fee'] - $paymentAmountDiff->sum('certificate_fee'),
                        "building_fund" => getHcGeneralFund($application)['building_fund'] - $paymentAmountDiff->sum('building_fund'),
                        "general_fund" => getHcGeneralFund($application)['general_fund'] - $paymentAmountDiff->sum('general_fund'),
                        "degree_fee" => getHcGeneralFund($application)['degree_fee'] - $paymentAmountDiff->sum('degree_fee'),
                        "exemption_fee" => getHcGeneralFund($application)['exemption_fee'] - $paymentAmountDiff->sum('exemption_fee'),
                    ]);
                }
            }
        }
    }

    public function uploadPaymentVoucher(Request $request, $id)
    {        
        $payment = Payment::find($id);

        $directory = 'lc-vouchers/' . $payment->high_court_id;
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
}
