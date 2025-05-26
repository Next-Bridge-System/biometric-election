<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ApplicationsExport;
use App\Imports\ApplicationImport;

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

use Carbon\Carbon;
use Validator;
use Log;
use PDF;
use DB;
use Auth;

class LawyerController extends Controller
{
    public function createStep1(Request $request)
    {
        $user = Auth::guard('frontend')->user();
        $lawyer = Application::where('user_id', $user->id)
        ->whereIn('application_type', ['1','2'])
        ->first();

        if ($request->isMethod('post')) {

            $user = Auth::guard('frontend')->user();

            $rules = [
                'application_type' => 'required|integer|in:1,2',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $data = [
                'application_type' => $request->input('application_type'),
            ];

            $lawyer = Application::where('user_id', $user->id)
            ->whereIn('application_type', ['1','2'])
            ->first();

            if ($lawyer == NULL) {
                $application = Application::create($data);
                $application->update([
                    'application_token_no' => $application->id + 1000,
                    'user_id' => $user->id,
                    'is_frontend' => TRUE,
                    'is_approved' => FALSE,
                    'application_status' => 7,
                ]);
            } else {
                $lawyer->update([
                    'application_type' => $request->application_type,
                ]);
            }

            $request->session()->forget('application');
            if(empty($request->session()->get('application'))){
                $lawyer->fill([ 'application_id' => $lawyer->id]);
                $request->session()->put('application', $lawyer->id);
            }

            return response()->json([
                'status' => 1,
                'message' => 'success',
                'lawyer_id' => $lawyer->id,
            ]);
        }

        return view ('frontend.lawyers.create-step-1',compact('lawyer'));
    }

    public function createStep2(Request $request, $id)
    {
        $lawyer = Application::findOrFail($id);

        if ($request->isMethod('post')) {

            $rules = [
                'first_name' => 'nullable|max:255',
                'last_name' => 'nullable|max:255',
                'father_name' => 'nullable|max:255',
                'gender' => 'nullable|max:255',
                'date_of_birth' => 'nullable|max:255',
                'profile_image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'email' => 'nullable|email|max:255|unique:applications',
                'active_mobile_no' => 'nullable|numeric|digits:10|unique:applications',
                'cnic_no' => 'nullable|unique:applications',
                'cnic_expiry_date' => 'nullable',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $data = [
                'advocates_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'so_of' => $request->input('father_name'),
                'gender' => $request->input('gender'),
                'date_of_birth' => $request->input('date_of_birth'),
                'email' => $request->input('email'),
                'active_mobile_no' => $request->input('active_mobile_no'),
                'cnic_no' => $request->input('cnic_no'),
                'cnic_expiry_date' => $request->input('cnic_expiry_date'),
            ];

            $lawyer->update($data);

            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return view ('frontend.lawyers.create-step-2', compact('lawyer'));
    }

    public function createStep3(Request $request, $id)
    {
        $lawyer = Application::findOrFail($id);
        $countries = Country::select('id','name')->orderBy('name','asc')->get();
        $provinces = Province::select('id','name')->orderBy('name','asc')->get();
        $districts = District::select('id','name')->orderBy('name','asc')->get();
        $tehsils = Tehsil::select('id','name')->orderBy('name','asc')->get();

        if ($request->isMethod('post')) {

            $rules = [
                'ha_house_no' => 'nullable|max:255',
                'ha_str_address' => 'nullable|max:255',
                'ha_town' => 'nullable|max:255',
                'ha_city' => 'nullable|max:255',
                'ha_postal_code' => 'nullable|numeric',
                'ha_country_id' => 'nullable|integer',
                'ha_province_id' => 'nullable|integer',
                'ha_district_id' => 'nullable|integer',
                'ha_tehsil_id' => 'nullable|integer',

                'pa_house_no' => 'nullable|max:255',
                'pa_str_address' => 'nullable|max:255',
                'pa_town' => 'nullable|max:255',
                'pa_city' => 'nullable|max:255',
                'pa_postal_code' => 'nullable|numeric',
                'pa_country_id' => 'nullable|integer',
                'pa_province_id' => 'nullable|integer',
                'pa_district_id' => 'nullable|integer',
                'pa_tehsil_id' => 'nullable|integer',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $data = [
                'application_id' => $lawyer->id,
                'ha_house_no' => $request->input('ha_house_no'),
                'ha_str_address' => $request->input('ha_str_address'),
                'ha_town' => $request->input('ha_town'),
                'ha_city' => $request->input('ha_city'),
                'ha_postal_code' => $request->input('ha_postal_code'),
                'ha_country_id' => $request->input('ha_country_id'),
                'ha_province_id' => $request->input('ha_province_id'),
                'ha_district_id' => $request->input('ha_district_id'),
                'ha_tehsil_id' => $request->input('ha_tehsil_id'),
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

            $lawyerAddress = LawyerAddress::where('application_id', $lawyer->id)->first();
            ($lawyerAddress == NULL) ? LawyerAddress::create($data) : $lawyerAddress->update($data);

            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return view ('frontend.lawyers.create-step-3',compact('districts','tehsils','lawyer','provinces','countries'));
    }

    public function createStep4(Request $request, $id)
    {
        $lawyer = Application::findOrFail($id);
        $universities = University::select('id','name')->where('type', 1)->orderBy('name','asc')->get();
        $affliatedUniversities = University::where('type', 2)->orderBy('name','asc')->get();

        if ($request->isMethod('post')) {

            $rules = [
                'qualification' => 'required|string|max:255',
                'sub_qualification' => 'nullable|string|max:255',
                'university_id' => 'nullable|integer',
                'institute' => 'nullable|string',
                'total_marks' => 'required|numeric',
                'obtained_marks' => 'required|numeric|lte:total_marks',
                'roll_no' => 'required|string',
                'passing_year' => 'required|integer',
                'certificate_url' => 'required',
            ];

            $messages = [
                'obtained_marks.lte' => 'The obtained marks must be less than or equal to total marks.',
            ];

            $validator = Validator::make($request->all(), $rules,  $messages);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $data = [
                'application_id' => $lawyer->id,
                'qualification' => $request->input('qualification'),
                'sub_qualification' => $request->input('sub_qualification'),
                'university_id' => $request->input('university_id'),
                'institute' => $request->input('institute'),
                'total_marks' => $request->input('total_marks'),
                'obtained_marks' => $request->input('obtained_marks'),
                'roll_no' => $request->input('roll_no'),
                'passing_year' => $request->input('passing_year'),
            ];

            LawyerEducation::create($data);

            return response()->json(['status' => 1, 'message' => 'success']);
        }


        return view ('frontend.lawyers.create-step-4', compact('lawyer','universities','affliatedUniversities'));
    }

    public function createStep5(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $bars = Bar::select('id','name')->orderBy('name','asc')->get();

        if ($request->isMethod('post')) {

            $rules = [
                'reg_no_lc' => 'nullable|unique:applications,reg_no_lc,'.$application->id,
                'license_no_lc' => 'nullable|unique:applications,license_no_lc,'.$application->id,
                'license_no_hc' => 'nullable|unique:applications,license_no_hc,'.$application->id,
                'bf_no_hc' => 'nullable|unique:applications,bf_no_hc,'.$application->id,
                'date_of_enrollment_lc' => 'nullable',
                'date_of_enrollment_hc' => 'nullable',
                'voter_member_lc' => 'nullable',
                'voter_member_hc' => 'nullable',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $data = [
                'reg_no_lc' => $request->input('reg_no_lc'),
                'license_no_lc' => $request->input('license_no_lc'),
                'license_no_hc' => $request->input('license_no_hc'),
                'bf_no_hc' => $request->input('bf_no_hc'),
                'date_of_enrollment_lc' => $request->input('date_of_enrollment_lc'),
                'date_of_enrollment_hc' => $request->input('date_of_enrollment_hc'),
                'voter_member_lc' => $request->input('voter_member_lc'),
                'voter_member_hc' => $request->input('voter_member_hc'),
            ];

            $application->update($data);

            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return view ('frontend.lawyers.create-step-5', compact('application','bars'));

    }

    public function createStep6(Request $request, $id)
    {
        $lawyer = Application::findOrFail($id);

        if ($request->isMethod('post')) {
            $lawyer->update(['is_accepted' => TRUE]);
            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return view ('frontend.lawyers.create-step-6', compact('lawyer'));
    }

    public function uploadProfileImage(Request $request)
    {
        $id = $request->session()->get('application');
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/'.$model->id;
        if ($request->hasFile('profile_image')) {
            $fileName = $request->file('profile_image')->getClientOriginalName();
            if(!Storage::exists($directory)){
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('profile_image')));
            ($upload == NULL) ? LawyerUpload::create(['application_id'=> $model->id, 'profile_image'=> $url]) : $upload->update(['profile_image'=> $url]);
        }
    }

    public function uploadCnicFront(Request $request)
    {
        $id = $request->session()->get('application');
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/'.$model->id;
        if ($request->hasFile('cnic_front')) {
            $fileName = $request->file('cnic_front')->getClientOriginalName();
            if(!Storage::exists($directory)){
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('cnic_front')));
            ($upload == NULL) ? LawyerUpload::create(['application_id'=> $model->id, 'cnic_front'=> $url]) : $upload->update(['cnic_front'=> $url]);
        }
    }

    public function uploadCnicBack(Request $request)
    {
        $id = $request->session()->get('application');
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/'.$model->id;
        if ($request->hasFile('cnic_back')) {
            $fileName = $request->file('cnic_back')->getClientOriginalName();
            if(!Storage::exists($directory)){
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('cnic_back')));
            ($upload == NULL) ? LawyerUpload::create(['application_id'=> $model->id, 'cnic_back'=> $url]) : $upload->update(['cnic_back'=> $url]);
        }
    }

    public function uploadLicenseFront(Request $request)
    {
        $id = $request->session()->get('application');
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/'.$model->id;
        if ($request->hasFile('license_front')) {
            $fileName = $request->file('license_front')->getClientOriginalName();
            if(!Storage::exists($directory)){
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('license_front')));
            ($upload == NULL) ? LawyerUpload::create(['application_id'=> $model->id, 'license_front'=> $url]) : $upload->update(['license_front'=> $url]);
        }
    }

    public function uploadLicenseBack(Request $request)
    {
        $id = $request->session()->get('application');
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/'.$model->id;
        if ($request->hasFile('license_back')) {
            $fileName = $request->file('license_back')->getClientOriginalName();
            if(!Storage::exists($directory)){
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('license_back')));
            ($upload == NULL) ? LawyerUpload::create(['application_id'=> $model->id, 'license_back'=> $url]) : $upload->update(['license_back'=> $url]);
        }
    }
}
