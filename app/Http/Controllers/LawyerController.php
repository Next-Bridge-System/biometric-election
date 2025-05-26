<?php

namespace App\Http\Controllers;

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

use Carbon\Carbon;
use Validator;
use Log;
use PDF;
use DB;
use Auth;

class LawyerController extends Controller
{
    public function index(Request $request)
    {
        $count= Application::select('*')
        ->whereIn('application_type' , ['1','2'])
        ->where('is_approved', '1')
        ->orderBy('id','DESC')
        ->where('is_accepted', '1')
        ->count();

        if ($request->ajax()) {
            $lawyer = Application::select('*')
            ->whereIn('application_type' , ['1','2'])
            ->where('is_approved', '1')
            ->where('is_accepted', '1')
            ->orderBy('id','DESC');

            $admin = Auth::guard('admin')->user();

            return Datatables::of($lawyer)
                ->addIndexColumn()
                ->addColumn('application_type', function (Application $lawyer) {
                    return $lawyer->application_type == 1 ? 'Lower Court' : 'High Court';
                })
                ->addColumn('active_mobile_no', function (Application $lawyer) {
                    $activeMobileNumber = "+92".$lawyer->active_mobile_no;
                    return $activeMobileNumber;
                })
                ->addColumn('application_status', function (Application $lawyer) {
                    $lawyerStatus = getApplicationStatus($lawyer->id);
                    return $lawyerStatus;
                })
                ->addColumn('card_status', function (Application $lawyer) {
                    $cardStatus = getCardStatus($lawyer->id);
                    return $cardStatus;
                })
                ->addColumn('action', function (Application $lawyer) {

                    $btn='<a href="'.route('lawyers.show',$lawyer->id).'">
                    <span class="badge badge-primary"><i class="fas fa-eye mr-1" aria-hidden="true"></i>View</span></a>';

                    // $btn.='<a href="'.route('applications.edit',$lawyer->id).'"><span class="badge badge-primary"><i class="far fa-edit mr-1" aria-hidden="true"></i>Edit</span></a>';

                    // $btn.=' <a href="'.route('applications.destroy',$lawyer->id).'"><span class="badge badge-danger"><i class="fas fa-trash-alt mr-1" aria-hidden="true"></i>Delete</span></a>';

                    // $btn.='<a href="'.route('applications.pdf-view',['download'=>'pdf','application' => $lawyer]).'">
                    // <span class="badge badge-success"><i class="fas fa-download mr-1" aria-hidden="true"></i>Download PDF</span></a>';

                    return $btn;
                })
                ->filter(function ($instance) use ($request) {
                    if ($request->get('application_type') == '1' || $request->get('application_type') == '2' ) {
                        $instance->where('application_type', $request->get('application_type'));
                    }
                    if ($request->get('application_date')) {
                        if ($request->get('application_date') == '1') {
                            $instance->whereDate('created_at', Carbon::today());
                        }
                        if ($request->get('application_date') == '2') {
                            $instance->whereDate('created_at', Carbon::yesterday());
                        }
                        if ($request->get('application_date') == '3') {
                            $date = Carbon::now()->subDays(7);
                            $instance->where('created_at', '>=', $date);
                        }
                        if ($request->get('application_date') == '4') {
                            $date = Carbon::now()->subDays(30);
                            $instance->where('created_at', '>=', $date);
                        }
                        if ($request->application_date == 5) {
                            if ($request->get('application_date_range')) {
                                $dateRange = explode(' - ', $request->application_date_range);
                                $from = date("Y-m-d", strtotime($dateRange[0]));
                                $to = date("Y-m-d", strtotime($dateRange[1]));
                                $instance->whereBetween('created_at', [$from, $to]);
                            }
                        }
                    }
                    if (!empty($request->get('search'))) {
                        $instance->where(function($query) use($request){
                           $search = $request->get('search');
                           $query->orWhere('advocates_name', 'LIKE', "%$search%")
                                ->orWhere('cnic_no', 'LIKE', "%$search%")
                                ->orWhere('active_mobile_no', 'LIKE', "%$search%")
                                ->orWhere('application_token_no', 'LIKE', "%$search%");
                       });
                   }
                })
                ->rawColumns(['application_type','application_status','card_status','action','active_mobile_no'])
                ->make(true);
        }

        return view('admin.lawyers.index',compact('count'));
    }

    public function unapproved(Request $request)
    {
        $count= Application::select('id')
        ->whereIn('application_type' , ['1','2'])
        ->where('is_approved', '0')
        ->where('is_accepted', '1')
        ->count();

        if ($request->ajax()) {

            $lawyer = Application::select('*')
            ->whereIn('application_type' , ['1','2'])
            ->where('is_approved', '0')
            ->where('is_accepted', '1')
            ->orderBy('id','DESC');

            $admin = Auth::guard('admin')->user();

            return Datatables::of($lawyer)
                ->addIndexColumn()
                ->addColumn('advocates_name', function (Application $lawyer) {
                    return getLawyerName($lawyer->id);
                })
                ->addColumn('application_type', function (Application $lawyer) {
                    return $lawyer->application_type == 1 ? 'Lower Court' : 'High Court';
                })
                ->addColumn('active_mobile_no', function (Application $lawyer) {
                    $activeMobileNumber = "+92".$lawyer->active_mobile_no;
                    return $activeMobileNumber;
                })
                ->addColumn('application_status', function (Application $lawyer) {
                    $lawyerStatus = getApplicationStatus($lawyer->id);
                    return $lawyerStatus;
                })
                ->addColumn('card_status', function (Application $lawyer) {
                    $cardStatus = getCardStatus($lawyer->id);
                    return $cardStatus;
                })
                ->addColumn('action', function (Application $lawyer) {

                    $btn='<a href="'.route('lawyers.show',$lawyer->id).'">
                    <span class="badge badge-primary"><i class="fas fa-eye mr-1" aria-hidden="true"></i>View</span></a>';

                    return $btn;
                })
                ->filter(function ($instance) use ($request) {
                    if ($request->get('application_type') == '1' || $request->get('application_type') == '2' ) {
                        $instance->where('application_type', $request->get('application_type'));
                    }
                    if ($request->get('application_date')) {
                        if ($request->get('application_date') == '1') {
                            $instance->whereDate('created_at', Carbon::today());
                        }
                        if ($request->get('application_date') == '2') {
                            $instance->whereDate('created_at', Carbon::yesterday());
                        }
                        if ($request->get('application_date') == '3') {
                            $date = Carbon::now()->subDays(7);
                            $instance->where('created_at', '>=', $date);
                        }
                        if ($request->get('application_date') == '4') {
                            $date = Carbon::now()->subDays(30);
                            $instance->where('created_at', '>=', $date);
                        }
                        if ($request->application_date == 5) {
                            if ($request->get('application_date_range')) {
                                $dateRange = explode(' - ', $request->application_date_range);
                                $from = date("Y-m-d", strtotime($dateRange[0]));
                                $to = date("Y-m-d", strtotime($dateRange[1]));
                                $instance->whereBetween('created_at', [$from, $to]);
                            }
                        }
                    }
                    if (!empty($request->get('search'))) {
                        $instance->where(function($query) use($request){
                           $search = $request->get('search');
                           $query->orWhere('advocates_name', 'LIKE', "%$search%")
                                ->orWhere('cnic_no', 'LIKE', "%$search%")
                                ->orWhere('active_mobile_no', 'LIKE', "%$search%")
                                ->orWhere('application_token_no', 'LIKE', "%$search%");
                       });
                   }
                })
                ->rawColumns(['application_type','application_status','card_status','action','active_mobile_no'])
                ->make(true);
        }

        return view('admin.lawyers.unapproved',compact('count'));
    }

    public function partial(Request $request)
    {
        $count= Application::select('id')
        ->whereIn('application_type' , ['1','2'])
        ->count();

        if ($request->ajax()) {

            $lawyer = Application::select('*')
            ->whereIn('application_type' , ['1','2'])
            ->orderBy('id','DESC');

            $admin = Auth::guard('admin')->user();

            return Datatables::of($lawyer)
                ->addIndexColumn()
                ->addColumn('advocates_name', function (Application $lawyer) {
                    return getLawyerName($lawyer->id);
                })
                ->addColumn('application_type', function (Application $lawyer) {
                    return $lawyer->application_type == 1 ? 'Lower Court' : 'High Court';
                })
                ->addColumn('active_mobile_no', function (Application $lawyer) {
                    $activeMobileNumber = "+92".$lawyer->active_mobile_no;
                    return $activeMobileNumber;
                })
                ->addColumn('application_status', function (Application $lawyer) {
                    $lawyerStatus = getApplicationStatus($lawyer->id);
                    return $lawyerStatus;
                })
                ->addColumn('card_status', function (Application $lawyer) {
                    $cardStatus = getCardStatus($lawyer->id);
                    return $cardStatus;
                })
                ->addColumn('action', function (Application $lawyer) {

                    $btn='<a href="'.route('lawyers.show',$lawyer->id).'">
                    <span class="badge badge-primary"><i class="fas fa-eye mr-1" aria-hidden="true"></i>View</span></a>';

                    return $btn;
                })
                ->filter(function ($instance) use ($request) {
                    if ($request->get('application_type') == '1' || $request->get('application_type') == '2' ) {
                        $instance->where('application_type', $request->get('application_type'));
                    }
                    if ($request->get('application_date')) {
                        if ($request->get('application_date') == '1') {
                            $instance->whereDate('created_at', Carbon::today());
                        }
                        if ($request->get('application_date') == '2') {
                            $instance->whereDate('created_at', Carbon::yesterday());
                        }
                        if ($request->get('application_date') == '3') {
                            $date = Carbon::now()->subDays(7);
                            $instance->where('created_at', '>=', $date);
                        }
                        if ($request->get('application_date') == '4') {
                            $date = Carbon::now()->subDays(30);
                            $instance->where('created_at', '>=', $date);
                        }
                        if ($request->application_date == 5) {
                            if ($request->get('application_date_range')) {
                                $dateRange = explode(' - ', $request->application_date_range);
                                $from = date("Y-m-d", strtotime($dateRange[0]));
                                $to = date("Y-m-d", strtotime($dateRange[1]));
                                $instance->whereBetween('created_at', [$from, $to]);
                            }
                        }
                    }
                    if (!empty($request->get('search'))) {
                        $instance->where(function($query) use($request){
                           $search = $request->get('search');
                           $query->orWhere('advocates_name', 'LIKE', "%$search%")
                                ->orWhere('cnic_no', 'LIKE', "%$search%")
                                ->orWhere('active_mobile_no', 'LIKE', "%$search%")
                                ->orWhere('application_token_no', 'LIKE', "%$search%");
                       });
                   }
                })
                ->rawColumns(['application_type','application_status','card_status','action','active_mobile_no'])
                ->make(true);
        }

        return view('admin.lawyers.partial',compact('count'));
    }

    public function show(Request $request, $id)
    {
        $lawyer = Application::find($id);
        return view ('admin.lawyers.show',compact('lawyer'));
    }

    public function status(Request $request, $id)
    {
        $application = Application::find($id);

        $application->update([
            'application_status' => $request->application_status,
            'is_approved' => $request->application_status,
        ]);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function createStep1(Request $request)
    {
        if ($request->isMethod('post')) {

            $admin = Auth::guard('admin')->user();

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

            $lawyer = Application::create($data);
            $lawyer->update([
                'application_token_no' => $lawyer->id + 1000,
                'submitted_by' => $admin->id,
            ]);

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

        return view ('admin.lawyers.create-step-1');
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
            ];

            $lawyer->update($data);

            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return view ('admin.lawyers.create-step-2', compact('lawyer'));
    }

    public function createStep3(Request $request, $id)
    {
        $lawyer = Application::findOrFail($id);

        if ($request->isMethod('post')) {

            $rules = [
                'cnic_no' => 'nullable|unique:applications',
                'cnic_expiry_date' => 'nullable',
                'cnic_front_image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'cnic_back_image_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $data = [
                'cnic_no' => $request->input('cnic_no'),
                'cnic_expiry_date' => $request->input('cnic_expiry_date'),
            ];

            $lawyer->update($data);

            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return view ('admin.lawyers.create-step-3',compact('lawyer'));
    }

    public function createStep4(Request $request, $id)
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

        return view ('admin.lawyers.create-step-4',compact('districts','tehsils','lawyer','provinces','countries'));
    }

    public function createStep5(Request $request, $id)
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


        return view ('admin.lawyers.create-step-5', compact('lawyer','universities','affliatedUniversities'));
    }

    public function createStep6(Request $request, $id)
    {
        $lawyer = Application::findOrFail($id);

        if ($request->isMethod('post')) {

            $rules = [
                'license_no_lc' => 'nullable|unique:applications,license_no_lc,'.$lawyer->id,
                'license_no_hc' => 'nullable|unique:applications,license_no_hc,'.$lawyer->id,
                'plj_no_lc' => 'nullable|unique:applications,plj_no_lc,'.$lawyer->id,
                'bf_no_hc' => 'nullable|unique:applications,bf_no_hc,'.$lawyer->id,
                'reg_no_lc' => 'nullable|unique:applications,reg_no_lc,'.$lawyer->id,
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $data = [
                'license_no_lc' => $request->input('license_no_lc'),
                'license_no_hc' => $request->input('license_no_hc'),
                'plj_no_lc' => $request->input('plj_no_lc'),
                'bf_no_hc' => $request->input('bf_no_hc'),
                'reg_no_lc' => $request->input('reg_no_lc'),
            ];

            $lawyer->update($data);

            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return view ('admin.lawyers.create-step-6', compact('lawyer'));

    }

    public function createStep7(Request $request, $id)
    {
        $lawyer = Application::findOrFail($id);

        if ($request->isMethod('post')) {
            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return view ('admin.lawyers.create-step-7', compact('lawyer'));

    }

    public function createStep8(Request $request, $id)
    {
        $lawyer = Application::findOrFail($id);

        if ($request->isMethod('post')) {
            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return view ('admin.lawyers.create-step-8', compact('lawyer'));
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

    // UPLOADS LOWER COURT
    public function uploadCertificateLC(Request $request)
    {
        $id = $request->session()->get('application');
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/'.$model->id;
        if ($request->hasFile('certificate_lc')) {
            $fileName = $request->file('certificate_lc')->getClientOriginalName();
            if(!Storage::exists($directory)){
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('certificate_lc')));
            ($upload == NULL) ? LawyerUpload::create(['application_id'=> $model->id, 'certificate_lc'=> $url]) : $upload->update(['certificate_lc'=> $url]);
        }
    }

    public function uploadAffidavitLC(Request $request)
    {
        $id = $request->session()->get('application');
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/'.$model->id;
        if ($request->hasFile('affidavit_lc')) {
            $fileName = $request->file('affidavit_lc')->getClientOriginalName();
            if(!Storage::exists($directory)){
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('affidavit_lc')));
            ($upload == NULL) ? LawyerUpload::create(['application_id'=> $model->id, 'affidavit_lc'=> $url]) : $upload->update(['affidavit_lc'=> $url]);
        }
    }

    public function uploadCasesLC(Request $request)
    {
        $id = $request->session()->get('application');
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/'.$model->id;
        if ($request->hasFile('cases_lc')) {
            $fileName = $request->file('cases_lc')->getClientOriginalName();
            if(!Storage::exists($directory)){
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('cases_lc')));
            ($upload == NULL) ? LawyerUpload::create(['application_id'=> $model->id, 'cases_lc'=> $url]) : $upload->update(['cases_lc'=> $url]);
        }
    }

    public function uploadVoucherLC(Request $request)
    {
        $id = $request->session()->get('application');
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/'.$model->id;
        if ($request->hasFile('voucher_lc')) {
            $fileName = $request->file('voucher_lc')->getClientOriginalName();
            if(!Storage::exists($directory)){
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('voucher_lc')));
            ($upload == NULL) ? LawyerUpload::create(['application_id'=> $model->id, 'voucher_lc'=> $url]) : $upload->update(['voucher_lc'=> $url]);
        }
    }

    public function uploadGatLC(Request $request)
    {
        $id = $request->session()->get('application');
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/'.$model->id;
        if ($request->hasFile('gat_lc')) {
            $fileName = $request->file('gat_lc')->getClientOriginalName();
            if(!Storage::exists($directory)){
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('gat_lc')));
            ($upload == NULL) ? LawyerUpload::create(['application_id'=> $model->id, 'gat_lc'=> $url]) : $upload->update(['gat_lc'=> $url]);
        }
    }

    // UPLOADS HIGH COURT
    public function uploadCertificateHC(Request $request)
    {
        $id = $request->session()->get('application');
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/'.$model->id;
        if ($request->hasFile('certificate_hc')) {
            $fileName = $request->file('certificate_hc')->getClientOriginalName();
            if(!Storage::exists($directory)){
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('certificate_hc')));
            ($upload == NULL) ? LawyerUpload::create(['application_id'=> $model->id, 'certificate_hc'=> $url]) : $upload->update(['certificate_hc'=> $url]);
        }
    }

    public function uploadAffidavitHC(Request $request)
    {
        $id = $request->session()->get('application');
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/'.$model->id;
        if ($request->hasFile('affidavit_hc')) {
            $fileName = $request->file('affidavit_hc')->getClientOriginalName();
            if(!Storage::exists($directory)){
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('affidavit_hc')));
            ($upload == NULL) ? LawyerUpload::create(['application_id'=> $model->id, 'affidavit_hc'=> $url]) : $upload->update(['affidavit_hc'=> $url]);
        }
    }

    public function uploadCasesHC(Request $request)
    {
        $id = $request->session()->get('application');
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/'.$model->id;
        if ($request->hasFile('cases_hc')) {
            $fileName = $request->file('cases_hc')->getClientOriginalName();
            if(!Storage::exists($directory)){
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('cases_hc')));
            ($upload == NULL) ? LawyerUpload::create(['application_id'=> $model->id, 'cases_hc'=> $url]) : $upload->update(['cases_hc'=> $url]);
        }
    }

    public function uploadVoucherHC(Request $request)
    {
        $id = $request->session()->get('application');
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/'.$model->id;
        if ($request->hasFile('voucher_hc')) {
            $fileName = $request->file('voucher_hc')->getClientOriginalName();
            if(!Storage::exists($directory)){
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('voucher_hc')));
            ($upload == NULL) ? LawyerUpload::create(['application_id'=> $model->id, 'voucher_hc'=> $url]) : $upload->update(['voucher_hc'=> $url]);
        }
    }

    public function uploadGatHC(Request $request)
    {
        $id = $request->session()->get('application');
        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/'.$model->id;
        if ($request->hasFile('gat_hc')) {
            $fileName = $request->file('gat_hc')->getClientOriginalName();
            if(!Storage::exists($directory)){
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('gat_hc')));
            ($upload == NULL) ? LawyerUpload::create(['application_id'=> $model->id, 'gat_hc'=> $url]) : $upload->update(['gat_hc'=> $url]);
        }
    }
}

