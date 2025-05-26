<?php

namespace App\Http\Controllers\Admin;

use App\Application;
use App\AppStatus;
use App\AssignMember;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use PDF;
use DB;

use App\District;
use App\Tehsil;
use App\LawyerAddress;
use App\LawyerEducation;
use App\University;
use App\Country;
use App\Province;
use App\LawyerUpload;
use App\Payment;
use App\Note;
use App\Bar;
use App\GroupInsurance;
use App\Imports\LowerCourtImport;
use App\LowerCourt;
use App\Policy;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

class LowerCourtController extends Controller
{
    public function indexv2($slug)
    {
        return view('admin.lower-court.list', compact('slug'));
    }

    private function academicRecordStatusCheck($application)
    {
        $academicRecord = [];
        foreach ($application->educations as $key => $applicationEducation) {
            $academicRecord[] = $applicationEducation->qualification;
        }

        $condition_01 =
            in_array('1', $academicRecord) &&
            in_array('2', $academicRecord) &&
            in_array('3', $academicRecord) &&
            in_array('4', $academicRecord) &&
            in_array('5', $academicRecord) &&
            in_array('6', $academicRecord) &&
            in_array('9', $academicRecord);

        $condition_02 =
            in_array('1', $academicRecord) &&
            in_array('2', $academicRecord) &&
            in_array('7', $academicRecord) &&
            in_array('9', $academicRecord);

        $condition_03 =
            in_array('1', $academicRecord) &&
            in_array('2', $academicRecord) &&
            in_array('3', $academicRecord) &&
            in_array('4', $academicRecord) &&
            in_array('5', $academicRecord) &&
            in_array('6', $academicRecord) &&
            in_array('9', $academicRecord) &&
            in_array('10', $academicRecord);

        $condition_04 =
            in_array('1', $academicRecord) &&
            in_array('2', $academicRecord) &&
            in_array('7', $academicRecord) &&
            in_array('9', $academicRecord) &&
            in_array('10', $academicRecord);

        $condition_05 =
            in_array('1', $academicRecord) &&
            in_array('2', $academicRecord) &&
            in_array('3', $academicRecord) &&
            in_array('4', $academicRecord) &&
            in_array('5', $academicRecord) &&
            in_array('6', $academicRecord);

        $condition_06 =
            in_array('1', $academicRecord) &&
            in_array('2', $academicRecord) &&
            in_array('7', $academicRecord);

        $condition_07 =
            in_array('1', $academicRecord) &&
            in_array('2', $academicRecord) &&
            in_array('3', $academicRecord) &&
            in_array('4', $academicRecord) &&
            in_array('5', $academicRecord) &&
            in_array('6', $academicRecord) &&
            in_array('10', $academicRecord);

        $condition_08 =
            in_array('1', $academicRecord) &&
            in_array('2', $academicRecord) &&
            in_array('7', $academicRecord) &&
            in_array('10', $academicRecord);

        $application->update(['is_academic_record' => false]);
        $academicRecordStatus = false;

        if ($application->exemption_reason == 1 || $application->exemption_reason == 0) {
            if ($application->degree_place == 1 || $application->degree_place == 2) {
                if ($condition_01 || $condition_02) {
                    $application->update(['is_academic_record' => true]);
                    $academicRecordStatus = true;
                }
            }

            if ($application->degree_place == 3) {
                if ($condition_03 || $condition_04) {
                    $application->update(['is_academic_record' => true]);
                    $academicRecordStatus = true;
                }
            }
        }

        if ($application->exemption_reason == 2) {
            if ($application->degree_place == 1 || $application->degree_place == 2) {
                if ($condition_05 || $condition_06) {
                    $application->update(['is_academic_record' => true]);
                    $academicRecordStatus = true;
                }
            }

            if ($application->degree_place == 3) {
                if ($condition_07 || $condition_08) {
                    $application->update(['is_academic_record' => true]);
                    $academicRecordStatus = true;
                }
            }
        }

        return $academicRecordStatus;
    }

    public function index(Request $request, $slug = null)
    {
        if ($request->ajax()) {
            if ($slug == 'final') {
                $application = LowerCourt::orderBy('id', 'desc')
                    ->where('is_excel', 0)
                    ->where('is_final_submitted', 1);
            }

            if ($slug == 'partial') {
                $application = LowerCourt::orderBy('id', 'desc')
                    ->where('is_excel', 0)
                    ->where('is_final_submitted', 0);
            }

            if ($slug == 'move-from-intimation') {
                $application = LowerCourt::orderBy('id', 'desc')
                    ->where('is_excel', 0)
                    ->where('is_moved_from_intimation', 1);
            }

            if ($slug == 'direct-entry') {
                $application = LowerCourt::orderBy('id', 'desc')
                    ->where('is_excel', 0)
                    ->where('is_moved_from_intimation', 0);
            }

            if ($slug == 'excel-import') {
                $application = LowerCourt::orderBy('id', 'desc')->where('is_excel', 1);
            }

            if ($slug == 'list') {
                $application = LowerCourt::orderBy('id', 'desc');
            }

            $admin = Auth::guard('admin')->user();

            if ($admin->is_super == 0) {
                $application->where('voter_member_lc', $admin->bar_id)->orWhere('created_by', $admin->id);
            }

            return Datatables::of($application)
                ->addIndexColumn()
                ->addColumn('app_status', function ($application) {
                    $result = appStatus($application->app_status, $application->app_type);
                    $result .= '<span class="badge badge-' . getLcPaymentStatus($application->id)['badge'] . '"> ' . getLcPaymentStatus($application->id)['name'] . '</span>';
                    return $result;
                })
                ->addColumn('created_by', function ($application) {
                    if ($application->is_frontend == 1) {
                        $created_by =  '<span title="' . $application->lawyer_name . '" class="badge badge-success">Online</span>';
                    } else if ($application->is_frontend == 0) {
                        $created_by = '<span title="' . getAdminName($application->created_by) . '" class="badge badge-info">Operator</span> ';
                    } else {
                        $created_by = '-';
                    }
                    return $created_by;
                })
                ->addColumn('rcpt', function ($application) {
                    if (isset($application->rcpt_date)) {
                        return '<span>' . $application->rcpt_date . '</span> <br> <span class="badge badge-secondary">' . $application->rcpt_no_lc . '</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('dob', function ($application) {
                    return getDateFormat($application->date_of_birth);
                })
                ->addColumn('enr_date_lc', function ($application) {
                    return getDateFormat($application->lc_date);
                })
                ->addColumn('photo', function ($application) {
                    if (isset($application->uploads->profile_image) && Storage::disk('public')->exists($application->uploads->profile_image)) {
                        $res =  '<img style="width: 50px;height:50px" src="' . asset('storage/app/public/' . $application->uploads->profile_image) . '">';
                    } else {
                        $res =  '<img style="width: 50px;height:50px" src="' . asset('public/admin/images/no-image.png') . '">';
                    }

                    return $res;
                })
                ->addColumn('action', function ($application) {
                    $btn = '';

                    if (
                        Route::currentRouteName() == 'lower-court.index' &&
                        Route::current()->parameters()['slug'] == 'final' &&
                        permission('edit-lower-court')
                    ) {
                        $btn .= '<a href="' . route('lower-court.create-step-1', $application->id) . '">
                        <span class="badge badge-primary mr-1"><i class="fas fa-edit mr-1" aria-hidden="true"></i>Edit</span></a>';
                    }

                    if (
                        Route::currentRouteName() == 'lower-court.index' &&
                        Route::current()->parameters()['slug'] == 'partial' &&
                        permission('edit-partial-lower-court')
                    ) {
                        $btn .= '<a href="' . route('lower-court.create-step-1', $application->id) . '">
                        <span class="badge badge-primary mr-1"><i class="fas fa-edit mr-1" aria-hidden="true"></i>Edit Partial</span></a>';
                    }

                    if (permission('print-detail-lc')) {
                        $btn .= '<a href="' . route('lower-court.show', $application->id) . '"><span class="badge badge-primary">
                        <i class="fas fa-list mr-1" aria-hidden="true"></i>Detail</span></a>';

                        $btn .= '<a target="_blank" href="' . route('lower-court.prints.short-detail', ['id' => $application->id, 'type' => 'lc']) . '">
                        <span class="badge badge-primary mr-1"><i class="fas fa-print mr-1" aria-hidden="true"></i>Report</span></a>';
                    }

                    if (Route::currentRouteName() == 'lower-court.index') {
                        if ($application->rcpt_date == NULL) {
                            $btn .= ' <a href="javascript:void(0)" data-action="' . route('lower-court.rcpt-date', ['lc_id' => $application->id]) . '" onclick="rcptDate(this,' . $application->id . ')"><span class="badge badge-primary mr-1"><i class="fas fa-plus mr-1" aria-hidden="true"></i>RCPT NO</span></a>';
                        } else {
                            $btn .= '<span class="badge badge-success mr-1"><i class="fas fa-check mr-1" aria-hidden="true"></i>RCPT NO</span>';
                        }
                    }

                    return $btn;
                })
                ->filter(function ($instance) use ($request) {

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

                    if ($request->get('application_submitted_by')) {
                        if ($request->get('application_submitted_by') == 'frontend') {
                            $instance->where('is_frontend', 1)->where('created_by', null);
                        }
                        if ($request->get('application_submitted_by') == 'operator') {
                            $instance->where('is_frontend', 0)->where('created_by', '!=', null);
                        }
                    }

                    if ($request->get('payment_status')) {
                        if ($request->get('payment_status') == 'paid') {
                            $payments = Payment::where('payment_status', 1)->pluck('lower_court_id')->toArray();
                            $instance->whereIn('id', $payments);
                        }
                        if ($request->get('payment_status') == 'unpaid') {
                            $payments = Payment::where('payment_status', 0)->pluck('lower_court_id')->toArray();
                            $instance->whereIn('id', $payments);
                        }
                    }

                    if ($request->get('payment_type')) {
                        if ($request->get('payment_type') == 'online') {
                            $payments = Payment::where('online_banking', 1)->pluck('lower_court_id')->toArray();
                            $instance->whereIn('id', $payments);
                        }
                        if ($request->get('payment_type') == 'operator') {
                            $payments = Payment::where('online_banking', 0)->pluck('lower_court_id')->toArray();
                            $instance->whereIn('id', $payments);
                        }
                    }

                    if ($request->get('age_from') && !empty($request->get('age_from')) && $request->get('age_to') && !empty($request->get('age_to'))) {
                        $ageFrom = $request->get('age_from');
                        $ageTo = $request->get('age_to');

                        if ($ageFrom <= $ageTo) {
                            $instance->where('age', '>=', $ageFrom)->where('age', '<=', $ageTo);
                        }
                    }

                    if ($request->get('university')) {
                        $instance->whereHas('educations', function ($q) use ($request) {
                            $q->where('university_id', $request->university);
                        });
                    }

                    if ($request->get('bar_id')) {
                        $instance->where('voter_member_lc', $request->bar_id);
                    }

                    if ($request->get('app_system_status')) {
                        if ($request->app_system_status == 'direct-entry') {
                            $instance->where('is_moved_from_intimation', 0);
                        }
                        if ($request->app_system_status == 'move-from-intimation') {
                            $instance->where('is_moved_from_intimation', 1);
                        }
                    }

                    if (!empty($request->get('search'))) {
                        $instance->where(function ($query) use ($request) {
                            $search = $request->get('search');
                            $query
                                ->with('user')
                                ->orWhereHas('user', function ($q) use ($search) {
                                    $q->where('name', 'like', "%$search%")
                                        ->orWhere(FacadesDB::raw('CONCAT(fname," ",lname)'), 'LIKE', '%' . $search . '%')
                                        ->orWhere(FacadesDB::raw('CONCAT("+92",phone)'), 'LIKE', '%' . $search . '%')
                                        ->orWhere(FacadesDB::raw('CONCAT("0",phone)'), 'LIKE', '%' . $search . '%')
                                        ->orWhere('cnic_no', 'LIKE', "%$search%");
                                })
                                ->orWhere('user_id', $search)
                                ->orWhere('cnic_no', 'LIKE', "%$search%")
                                ->orWhere('id', $search);
                        });
                    }

                    if (!empty($request->find_by) && !empty($request->find_data)) {
                        $find_data = $request->find_data;
                        if ($request->find_by == 'lic_no') {
                            $instance->where('license_no_lc', 'LIKE', "%$find_data%");
                        }
                        if ($request->find_by == 'name') {
                            $instance->whereHas('user', function ($q) use ($find_data) {
                                $q->where('name', 'like', "%$find_data%")
                                    ->orWhere(FacadesDB::raw('CONCAT(fname," ",lname)'), 'LIKE', '%' . $find_data . '%');
                            });
                        }
                        if ($request->find_by == 'father') {
                            $instance->where('father_name', 'LIKE', "%$find_data%");
                        }
                        if ($request->find_by == 'dob') {
                            $instance->where('date_of_birth', Carbon::parse($find_data)->format('d-m-Y'));
                        }
                        if ($request->find_by == 'leg_no') {
                            $instance->where('reg_no_lc', 'LIKE', "%$find_data%");
                        }
                        if ($request->find_by == 'cnic') {
                            $instance->where('cnic_no', 'LIKE', "%$find_data%");
                        }
                        if ($request->find_by == 'enr_date') {
                            $instance->where('lc_date', Carbon::parse($find_data)->format('Y-m-d'));
                        }
                        if ($request->find_by == 'bf_no') {
                            $instance->where('bf_no_lc', 'LIKE', "%$find_data%");
                        }
                    }
                })
                ->rawColumns(['app_status', 'created_by', 'rcpt', 'action', 'photo'])
                ->make(true);
        }

        $universities = University::select('id', 'name')->where('type', 1)->orderBy('name', 'asc')->get();

        return view('admin.lower-court.index', compact('slug', 'universities'));
    }

    public function initialStep(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $rules = [
                'search_cnic_no' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $lowerCourtApplication = LowerCourt::with('user')->where('cnic_no', $request->search_cnic_no)->first();

            if ($lowerCourtApplication == NULL) {

                $application = Application::with('user')
                    ->where('application_type', 6)
                    ->where('is_intimation_completed', 0)
                    ->where('cnic_no', $request->search_cnic_no)
                    ->first();

                if ($application == NULL) {
                    return response()->json(
                        [
                            'status' => false,
                            'code' => 404,
                            'message' => 'No Application Found',
                        ]
                    );
                }

                $response = validateIntimationApplication($application, 0);

                if (count($response['errors']) > 0 || ($application->objections != null && count(json_decode($application->objections, true)) > 0)) {
                    $response['application'] = $application;

                    return response()->json(
                        [
                            'status' => true,
                            'code' => 400,
                            'message' => 'Application is not eligible to lower Court. Please see the reasons behind',
                            'data' => $response
                        ]
                    );
                }


                $response['application'] = $application;
                $response['move_application'] = true;

                return response()->json(
                    [
                        'status' => true,
                        'code' => 200,
                        'message' => 'Application is eligible to move to lower Court',
                        'data' => $response
                    ]
                );
            }

            $response['application'] = $lowerCourtApplication;

            return response()->json(
                [
                    'status' => true,
                    'code' => 200,
                    'message' => 'Application already in lower court',
                    'data' => $response
                ]
            );
        }

        return view('admin.lower-court.initial');
    }

    public function registerUser(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {

            $rules = [
                'fname' => ['required', 'string', 'max:255'],
                'lname' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone' => ['required', 'unique:users', 'regex:/(3)[0-9]{9}/'],
                'cnic_no' => ['required', 'unique:users', 'min:15'],
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

            $name = $request->fname . ' ' . $request->lname;

            $password = Str::random(10);

            $data = [
                'name' => $name,
                'fname' => $request->fname,
                'lname' => $request->lname,
                'email' => $request->email,
                'phone' => $request->phone,
                'cnic_no' => $request->cnic_no,
                'password' => \Hash::make($password),
                'access_token' => getAccessToken(),
            ];

            try {
                checkCnicExist($request->cnic_no);
            } catch (\Throwable $th) {
                throw $th;
            }

            $user = User::create($data);
            $response = $this->create($user, $request);

            $request->session()->forget('password');
            if (empty($request->session()->get('password'))) {
                $user->fill(['password' => $password]);
                $request->session()->put('password', $password);
            }

            return response()->json(['status' => 1, 'message' => 'success', 'url' => $response['url']]);
        }

        return redirect()->back()->with('error', 'Something went wrong');
    }

    public function create($user, Request $request)
    {
        try {
            checkCnicExist($user->cnic_no);
        } catch (\Throwable $th) {
            throw $th;
        }

        $admin = Auth::guard('admin')->user();

        $data = [
            'is_approved' => 0, // Not Approved
            'app_status' => 6, // Pending Application
            'app_type' => 1, // DIRECT ENTRY
            'created_by' => $admin->id,
            'is_frontend' => false,
            'voter_member_lc' => $admin->bar_id,
            'user_id' => $user->id,
        ];

        $application = LowerCourt::where('user_id', $user->id)->first();

        if ($application == NULL) {
            $application = LowerCourt::create($data);
        } else {
            $application->update(['updated_by' => $admin->id]);
        }

        if ($application->created_at == null) {
            $application->update(['created_by' => $admin->id]);
        }

        $request->session()->forget('lower_court_application');
        if (empty($request->session()->get('lower_court_application'))) {
            $application->fill(['lower_court_id' => $application->id]);
            $request->session()->put('lower_court_application', $application->id);
        }

        return [
            'status' => true,
            'url' => \URL::route('lower-court.create-step-1', $application->id)
        ];
    }

    public function createStep1(Request $request, $id)
    {
        $application = LowerCourt::findOrFail($id);
        $user = User::find($application->user_id);
        $bars = Bar::select('id', 'name')->orderBy('name', 'asc')->get();
        $years = range(Carbon::now()->year, 1900);
        $admin = Auth::guard('admin')->user();

        $request->session()->forget('lower_court_application');
        if (empty($request->session()->get('lower_court_application'))) {
            $request->session()->put('lower_court_application', $application->id);
        }

        if ($request->isMethod('post') && $request->ajax()) {
            $rules = [
                'intimation_date' => 'nullable',
                'llb_passing_year' => 'required|max:255',
                'voter_member_lc' => [Rule::requiredIf($admin->is_super == 1), 'max:255'],
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'father_name' => 'required|max:255',
                'gender' => 'required|max:255',
                'date_of_birth' => 'required|before:18 years ago',
                'blood_group' => 'nullable|max:255',
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'mobile_no' => ['required', 'string', 'unique:users,phone,' . $user->id],
            ];

            $messages = [
                'date_of_birth.before' => 'The date of birth is invalid. Please select birth date as per CNIC.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $data = [
                'intimation_date' => setDateFormat($request->intimation_date),
                // 'lc_date' => Carbon::parse($request->input('lc_date'))->format('Y-m-d'),
                'llb_passing_year' => $request->input('llb_passing_year'),
                'voter_member_lc' => $request->has('voter_member_lc') ? $request->input('voter_member_lc') : $admin->bar_id,
                'father_name' => $request->input('father_name'),
                'gender' => $request->input('gender'),
                'date_of_birth' => $request->input('date_of_birth'),
                'age' =>  getLcAge($request->date_of_birth, $application),
                'blood' => $request->input('blood_group'),
            ];

            $user_registration_data = [
                'fname' => $request->input('first_name'),
                'lname' => $request->input('last_name'),
                'email' => $request->input('email'),
                'phone' => $request->input('mobile_no'),
            ];

            $user->update($user_registration_data);
            $application->update($data);

            // ADMIN ID
            if ($application->is_frontend == 0) {
                if ($application->created_by == null) {
                    $application->update(['created_by' => auth()->guard('admin')->user()->id]);
                }
                $application->update(['updated_by' => auth()->guard('admin')->user()->id]);
            }

            // Payment
            $application->application_type = $request->application_type;
            $this->lowerCourtPayment($application);

            $nextView = view('admin.lower-court.steps.create-step-2', compact('application'))->render();
            return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 2]);
        }

        return view('admin.lower-court.create', compact('application', 'bars', 'years', 'user'));
    }

    public function createStep2(Request $request, $id)
    {
        $application = LowerCourt::findOrFail($id);

        if ($request->isMethod('post')) {

            $rules = [
                'cnic_no' => 'required|min:15|unique:lower_courts,cnic_no,' . $application->id,
                'cnic_expiry_date' => 'required',
                'degree_place' => $application->intimation_degree_fee == 0 ? 'required|in:1,2,3' : 'nullable|in:1,2,3',
                'exemption_reason' => 'nullable',
                'bf_plan' => 'required',
            ];

            $messages = [
                'cnic_no.required' => 'The cnic number field is required.',
                'cnic_no.min' => 'The cnic number you have entered is invalid.',
            ];

            $validator = FacadesValidator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $data = [
                'cnic_no' => $request->input('cnic_no'),
                'cnic_expiry_date' => $request->input('cnic_expiry_date'),
                'is_exemption' => $request->get('exemption_reason') == 1 || $request->get('exemption_reason') == 2 ? true : false,
                'exemption_reason' => $request->get('exemption_reason'),
                'bf_plan' => $request->get('bf_plan'),
                'exemption_form' => false,
            ];
            
            if ($application->intimation_degree_fee == 0) {
                $data += [
                    'degree_place' => $request->get('degree_place'),
                ];
            }

            $application->update($data);
            $this->academicRecordStatusCheck($application);
            $this->lowerCourtPayment($application);

            // for step 3
            $countries = Country::select('id', 'name')->orderBy('name', 'asc')->get();
            $provinces = Province::select('id', 'name')->orderBy('name', 'asc')->get();
            $districts = District::select('id', 'name', 'code')->orderBy('name', 'asc')->get();
            $tehsils = Tehsil::select('id', 'name', 'code')->orderBy('name', 'asc')->get();
            $nextView = view('admin.lower-court.steps.create-step-3', compact('districts', 'tehsils', 'application', 'provinces', 'countries'))->render();

            return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 3]);
        }
        return view('admin.lower-court.steps.create-step-2', compact('application'));
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

            $application->update(['exemption_form' => 0]);
            $nextView = view('admin.lower-court.steps.create-step-4', compact('application', 'universities', 'affliatedUniversities', 'academicRecord'))->render();
            return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 4]);
        }

        return view('admin.lower-court.steps.create-step-3', compact('districts', 'tehsils', 'application', 'provinces', 'countries'));
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
        $this->academicRecordStatusCheck($application);

        if ($request->isMethod('post')) {

            if (($request->has('qualification') && $request->get('qualification') != null) || ($request->has('total_marks') && $request->get('total_marks') != null) || ($request->has('obtained_marks') && $request->get('obtained_marks') != null)) {
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
            } else {
                return response()->json([
                    'error' => 'Operation failed to perform. Please try again later.',
                ], 400);
            }

            $application = LowerCourt::findOrFail($id);
            $records = view('admin.lower-court.partials.academic-record', compact('application'))->render();
            return response()->json([
                'status' => 2,
                'records' => $records,
                'message' => 'success',
                'academicRecordStatus' => $this->academicRecordStatusCheck($application),
            ]);
        }

        return view('admin.lower-court.steps.create-step-4', compact('application', 'universities', 'affliatedUniversities', 'academicRecord'));
    }

    public function createStep5(Request $request, $id)
    {
        $application = LowerCourt::findOrFail($id);
        $bars = Bar::select('id', 'name')->orderBy('name', 'asc')->get();
        $admin = Auth::guard('admin')->user();
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

            if ($application->is_exemption == 0) {
                $rules += [
                    'srl_name' => 'required|max:255',
                    'srl_bar_id' => [Rule::requiredIf($admin->is_super == 1), 'max:255'],
                    'srl_office_address' => 'required|max:255',
                    'srl_enr_date' => 'required|before:10 years ago',
                    'srl_mobile_no' => 'required|max:255',
                    'srl_cnic_no' => 'required|min:15',
                    'srl_joining_date' => 'required',
                ];
            }

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
                'srl_name' => $request->input('srl_name'),
                'srl_bar_id' => $request->has('srl_bar_id') ? $request->input('srl_bar_id') : $admin->bar_id,
                'srl_office_address' => $request->input('srl_office_address'),
                'srl_enr_date' => $request->input('srl_enr_date'),
                'srl_mobile_no' => $request->input('srl_mobile_no'),
                'srl_cnic_no' => $request->input('srl_cnic_no'),
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
                'srl_joining_date' => $request->srl_joining_date,
            ];

            $application->update($data);
            $nextView = view('admin.lower-court.steps.create-step-6', compact('application'))->render();

            return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 6]);
        }

        return view('admin.lower-court.steps.create-step-5', compact('application', 'bars'));
    }

    public function createStep6(Request $request, $id)
    {
        $application = LowerCourt::findOrFail($id);
        $user = User::find($application->user_id);
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
                'org_prov_certificate_lc' => [Rule::requiredIf(function () use ($application) {
                    return $application->uploads->org_prov_certificate_lc != NULL ? false : true;
                })],
                'cases_lc' => [Rule::requiredIf(function () use ($application) {
                    if ($application->uploads->cases_lc == NULL && $application->is_exemption == 0) {
                        return true;
                    } else {
                        return false;
                    }
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



            $nextView = view('admin.lower-court.steps.create-step-7', compact('application'))->render();
            return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 7]);
        }
        return view('admin.lower-court.steps.create-step-6', compact('application'));
    }

    public function createStep7(Request $request, $id)
    {
        $application = LowerCourt::findOrFail($id);
        $user = User::find($application->user_id);
        if ($request->isMethod('post')) {

            $rules = [
                'final_submission' => [Rule::requiredIf($application->paymentVoucher == NULL)],
            ];

            if ($request->has('is_otp')) {
                $rules['otp'] = [Rule::requiredIf($request->is_otp == NULL), 'digits:5', 'numeric'];
            }

            $messages = [
                'final_submission.required' => 'One of these options is required',
                'bank_name.required' => 'One of these options is required',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            if ($request->has('is_otp')) {
                if ($user->otp == $request->otp) {

                    $user->update(['otp' => null]);
                } else {
                    return response()->json(['errors' => ['otp' => ['The One Time Password is invalid']]], 400);
                }
            }
            $application->update(['is_final_submitted' => 1]);
            if ($application->final_submitted_at == NULL) {
                $application->update(['final_submitted_at' => Carbon::now()]);
            }

            $application->update([
                'age' => getLcAge($application->date_of_birth, $application)
            ]);

            $this->lowerCourtPayment($application);

            // INTIMATION PAYMENT VOUCHER
            // $this->intimationPaymentVoucher($request, $application);

            $password = $request->session()->get('password');

            $data = [
                "phone" => '+92' . $application->active_mobile_no,
                "message" => 'Dear Advocate, Your application has been submitted successfully.<br>Application No: ' . $application->application_token_no . '<br>Email:' . $application->email . '<br>Password:' . $password . '.',
            ];
            sendMessageAPI($data);

            $user = User::find($application->user_id);
            $mailData = [
                'subject' => 'PUNJAB BAR COUNCIL - Application Submit Successfully',
                'name' => $user->name,
                'description' => '<p>Dear Advocate, Your application has been submitted. </br> Email:' . $application->email . ' </br> Password:' . $password . '</p>',
            ];
            sendMailToUser($mailData, $user);

            if ($request->has('final_submission')) {
                $application = LowerCourt::findOrFail($id);
                if ($request->final_submission == 2) {
                    $nextView = \URL::route('lower-court.payment', $application->id);
                    return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 9]);
                } else {
                    /*$nextView = view('admin.lower-court.steps.create-step-8', compact('application'))->render();
                    if ($request->bank_name == "Bank Islami") {
                        $url = route('lower-court.bank-islami-voucher', ['download' => 'pdf', 'application' => $application]);
                    } else {
                        $url = route('lower-court.habib-bank-limited-voucher', ['download' => 'pdf', 'application' => $application]);
                    }*/
                    $nextView = \URL::route('lower-court.show', $application->id);
                    return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 9]);
                }
            } else {
                $nextView = view('admin.lower-court.steps.create-step-8', compact('application'))->render();
                return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 8]);
            }
        }

        return view('admin.lower-court.steps.create-step-7', compact('application'));
    }

    public function voucher(Request $request)
    {
        $application = LowerCourt::find($request->application);
        view()->share('application', $application);
        if ($request->has('download')) {
            $pdf = PDF::loadView('frontend.intimation.voucher');
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('HBL-Bank-Fee-Voucher-' . $application->application_token_no . '.pdf', array("Attachment" => false));
        }
        return view('frontend.intimation.voucher');
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

    public function sendOTP(Request $request, $id)
    {
        $application = LowerCourt::find($id);
        $user = User::find($application->user_id);
        $digits = 5;
        $otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $user->update(['otp' => $otp]);
        $data = [
            "phone" => '+92' . $user->phone,
            "message" => 'Dear Advocate, Your OTP is ' . $otp . '. Please do not share this OTP with others for your privacy.',
        ];
        sendMessageAPI($data);
    }

    public function rcptDate(Request $request)
    {
        if ($request->has('rcpt_date') && !empty($request->rcpt_date)) {
            $rcpt_date = Carbon::parse($request->rcpt_date)->format('Y-m-d');
        } else {
            $rcpt_date = Carbon::parse(Carbon::now())->format('Y-m-d');
        }

        $rcpt = LowerCourt::select('rcpt_no_lc', 'rcpt_date')->orderBy('rcpt_no_lc', 'desc')->whereYear('rcpt_date', Carbon::parse($rcpt_date)->format('Y'))->first();

        if ($rcpt == null) {
            $rcpt_count = 1;
        } else {
            $rcpt_count = $rcpt->rcpt_no_lc  + 1;
        }

        LowerCourt::updateOrCreate(['id' =>  $request->lc_id], [
            'rcpt_no_lc' => sprintf("%02d", $rcpt_count),
            'rcpt_date' => $rcpt_date,
        ]);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function downloadVoucher(Request $request)
    {
        $application = LowerCourt::find($request->lower_court_id);
        $payments = Payment::where('lower_court_id', $application->id)->where('is_voucher_print', 1)->get();

        view()->share([
            'application' => $application,
            'payments' => $payments,
        ]);
        if ($request->has('download')) {
            $pdf = PDF::loadView('admin.lower-court.prints.payment-vouchers');
            $pdf->setPaper('A4', 'landscape');
            return $pdf->stream('Habib-Bank-Limited-Voucher-' . $application->id . '.pdf', array("Attachment" => false));
        }
        return back()->with('error', 'No Payment To Download');
    }

    public function notes(Request $request)
    {
        $application = LowerCourt::where('id', $request->lower_court_id)->first();

        $rules = [
            'notes' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        Note::create([
            'application_id' => $application->id,
            'application_type' => 'LC',
            'note' => $request->notes,
            'admin_id' => Auth::guard('admin')->user()->id,
        ]);

        return response()->json(['status' => 1, 'message' => 'success']);
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

            $age_cal_date = isset($application->final_submitted_at) ? $application->final_submitted_at : $application->created_at;
            $age = Carbon::parse($application->date_of_birth)->diff($age_cal_date)->format('%y.%m');

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
                    "bank_name" => 'Habib Bank Limited',
                    "admin_id" => Auth::guard('admin')->user()->id,
                    // "voucher_no" => getLcVoucherNo($application->id) + $i,
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
                        "exemption_fee" => getGeneralFund($age, $application)['exemption_fee'] - $paymentAmountDiff->sum('exemption_fee'),
                        // "lwf" => getGeneralFund($age, $application)['lwf'] - $paymentAmountDiff->sum('lwf'),
                    ]);
                }

                if ($voucher_payment->amount == 0) {
                    $voucher_payment->update(['payment_status' => 1]);
                }
            }
        }
    }

    public function deletePayment(Request $request)
    {
        $application = LowerCourt::find($request->lower_court_id);
        $payment = Payment::find($request->payment_id);
        $payment->delete();

        $this->lowerCourtPayment($application);

        return redirect()->route('lower-court.show', $request->lower_court_id);
    }

    public function show(Request $request, $id)
    {
        $application = LowerCourt::with(['payments', 'additional_notes' => function ($query) {
            $query->where('application_type', 'LC')->orderBy('created_at', 'desc');
        }])->find($id);

        $payments = $application->payments->where('is_voucher_print', 1)->groupBy('voucher_type');

        $request->session()->forget('lower_court_application');
        if (empty($request->session()->get('lower_court_application'))) {
            $request->session()->put('lower_court_application', $application->id);
        }

        $app_status = AppStatus::where('status', 1)->get();
        $bars = Bar::orderBy('id', 'asc')->get();

        return view('admin.lower-court.show', compact('application', 'payments', 'app_status', 'bars'));
    }

    public function payment(Request $request, $id, $voucherType)
    {
        $lc = LowerCourt::find($id);
        $payment = Payment::where('lower_court_id', $lc->id)->where('voucher_type', $voucherType)->where('payment_status', 0)->first();
        $now = Carbon::now();
        abort_if($payment == NULL, 403);

        $request->session()->forget('lower_court_payment');
        if (empty($request->session()->get('lower_court_payment'))) {
            $payment->fill(['lower_court_id' => $lc->id]);
            $request->session()->put('lower_court_payment', $payment->id);
        }

        if ($request->isMethod('post')) {

            $policy = Policy::whereDate('start_date', '<=', $now)->whereDate('end_date', '>=', $now)->first();
            if ($policy == null) {
                return response()->json([
                    'policy' => 'No Policy Found Against Payment',
                ], 400);
            }

            $rules = [
                'amount' => 'sometimes|numeric',
                'paid_date' => 'required|max:255',
                'bank_name' => 'required|max:255',
                'transaction_id' => 'required|max:255',
                // 'voucher_file' => ['required', 'max:255'],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                    'voucher_file' => 'The bank voucher file is required.',
                ], 400);
            }


            $paidPayments = Payment::select(DB::raw("SUM(amount) as paid_amount"))
                ->where('user_id', $lc->user_id)
                ->where('payment_status', 1)
                ->where('application_type', 1)
                ->get()->toArray();

            $paidAmount = $paidPayments[0]['paid_amount'];

            if ($payment->amount != $request->amount) {
                $payment->update([
                    'payment_status' => 1,
                    'bank_name' => $request->bank_name,
                    'transaction_id' => $request->transaction_id,
                    'paid_date' => $request->paid_date,
                    'amount' => $request->amount,
                    'policy_id' => $policy->id,
                    'admin_id' => Auth::guard('admin')->user()->id,
                ]);
                if ($request->amount > $payment->amount) {
                } else {
                    $currentDate = Carbon::now();
                    $data = [
                        'lower_court_id' => $lc->id,
                        'user_id' => $lc->user_id,
                        'payment_status' => 0,
                        'payment_type' => 0,
                        'amount' => getLcVchAmount($lc->id, $payment->voucher_type) - $request->amount,
                        'bank_name' => $request->bank_name,
                        'admin_id' => Auth::guard('admin')->user()->id,
                        'application_type' => 1,
                        'application_status' => $lc->app_status,
                        'voucher_name' => $payment->voucher_name,
                        'voucher_type' => $payment->voucher_type,
                    ];
                    $newPayment = Payment::create($data);
                    $newPayment->update(['voucher_no' => getLcVoucherNo($newPayment->id)]);
                }
            } else {
                $payment->update([
                    'payment_status' => 1,
                    'bank_name' => $request->bank_name,
                    'transaction_id' => $request->transaction_id,
                    'paid_date' => $request->paid_date,
                    'admin_id' => Auth::guard('admin')->user()->id,
                    'policy_id' => $policy->id,
                ]);
            }

            if ($payment->voucher_type == 2) {
                $updateSubSection = Payment::where('lower_court_id', $payment->lower_court_id)
                    ->where('voucher_type', 0)
                    ->where('payment_status', 0)
                    ->update([
                        'payment_status' => 1,
                        'bank_name' => $request->bank_name,
                        'transaction_id' => $request->transaction_id,
                        'paid_date' => $request->paid_date,
                        'admin_id' => Auth::guard('admin')->user()->id,
                        'policy_id' => $policy->id,
                    ]);
            }

            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return view('admin.lower-court.payment', compact('lc', 'payment'));
    }

    public function status(Request $request, $id)
    {
        $application = LowerCourt::find($id);

        if ($request->application_status == 1) {
            if (getLcPaymentStatus($application->id)['name'] == 'Unpaid') {
                return response()->json([
                    'title' => 'Payment Verification!',
                    'message' => 'You cannot not change status of this lower court application until the payment is pending or unpaid',
                ], 400);
            }

            $application->update(['is_approved' => 1]);
        }

        $application->update([
            'app_status' => $request->application_status,
        ]);

        $this->lowerCourtPayment($application);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function updateStatus(Request $request)
    {
        $application = LowerCourt::find($request->lower_court_id);

        if (getLcPaymentStatus($application->id)['name'] == 'Unpaid') {
            return response()->json([
                'title' => 'Payment Verification!',
                'message' => 'You cannot not change status of this lower court application until the payment is pending or unpaid',
            ], 400);
        }

        $rules = [
            'app_status' => 'required',
            'app_status_reason' => 'required',
        ];


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $application->update([
            'app_status' => $request->app_status,
        ]);

        Note::create([
            'application_id' => $application->id,
            'application_type' => 'LC',
            'note' => $request->app_status_reason,
            'admin_id' => Auth::guard('admin')->user()->id,
        ]);

        if ($request->app_status == 1) {
            $application->update(['is_approved' => 1]);

            if (isset($application->user) && $application->user->gc_requested_at == NULL) {
                $application->user->update(['gc_requested_at' => setDateFormat(Carbon::now())]);
            }
        }

        $this->lowerCourtPayment($application);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function uploadPaymentVoucher(Request $request)
    {
        $id = $request->session()->get('lower_court_payment');
        $payment = Payment::find($id);
        $directory = 'lc-vouchers/' . $payment->lower_court_id;
        if ($request->hasFile('voucher_file')) {
            $fileName = $request->file('voucher_file')->getClientOriginalName();
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('voucher_file')));
            $payment->update(['voucher_file' => $url]);
        }
    }

    public function destroyPaymentVoucher(Request $request)
    {
        $id = $request->session()->get('lower_court_payment');
        $payment = Payment::find($id);
        $payment->update(['voucher_file' => NULL]);
        return redirect()->back();
    }

    public function moveApplication(Request $request)
    {        
        $application = Application::with('user')
            ->where('application_type', 6)
            ->whereNotNull('intimation_start_date')
            ->where('is_intimation_completed', 0)
            ->where('id', $request->id)
            ->first();

        if ($application == NULL) {
            return response()->json(
                [
                    'status' => false,
                    'code' => 404,
                    'message' => 'No Application Found',
                ]
            );
        }

        $response = validateIntimationApplication($application, 1);

        if (count($response['errors']) > 0) {
            $response['application'] = $application;

            return response()->json(
                [
                    'status' => true,
                    'code' => 400,
                    'message' => 'Application is not eligible to lower Court. Please see the reasons behind',
                    'data' => $response
                ]
            );
        } else {
            return response()->json(
                [
                    'status' => true,
                    'code' => 200,
                    'message' => 'Application Moved Successfully',
                    'data' => [
                        'url' => \URL::route('lower-court.show', $response['lowerCourtID'])
                    ]
                ]
            );
        }
    }

    public function assignMember(Request $request)
    {
        AssignMember::create([
            'lower_court_id' => $request->lower_court_id,
            'member_id' => $request->member_1,
            'code' => rand(pow(10, 6 - 1), pow(10, 6) - 1) + $request->lower_court_id,
        ]);

        AssignMember::create([
            'lower_court_id' => $request->lower_court_id,
            'member_id' => $request->member_2,
            'code' => rand(pow(10, 6 - 1), pow(10, 6) - 1)  + $request->lower_court_id,
        ]);

        return response()->json(['status' => true, 'code' => 200, 'message' => 'success']);
    }

    public function assignCodeVerification(Request $request)
    {
        $assign_member = AssignMember::where('lower_court_id', $request->lower_court_id)
            ->where('member_id', $request->member_id)->where('code', $request->verification_code)->first();

        if (isset($assign_member)) {
            $assign_member->update([
                'is_code_verified' => true,
                'code_verified_at' => Carbon::now(),
            ]);
            return response()->json(['status' => true, 'message' => 'The secret code have been matched successfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'The secret code you have entered is not matched. Please enter valid verification code.']);
        }
    }

    public function updateAssignMember(Request $request)
    {
        $assign_member = AssignMember::find($request->assign_member_id);
        $assign_member->update([
            'member_id' => $request->member_id,
            'code' => rand(pow(10, 6 - 1), pow(10, 6) - 1)  + $request->lower_court_id,
        ]);

        $data = [
            "phone" => '+923204650584',
            "candidate_cnic" => '35202-3689597-1',
            "candidate_code" => $assign_member->code,
            "event_id" => 193,
        ];
        sendMessageAPI($data);

        return response()->json(['status' => true, 'code' => 200, 'message' => 'success']);
    }

    public function updateNumber(Request $request)
    {
        try {
            $rules = [
                'type' => 'in:license_no_lc,bf_no_lc,plj_no_lc,gi_no_lc,reg_no_lc,bf_ledger_no',
                'lc_number' => ['required', 'max:25'],
            ];

            $messages = [
                'lc_number.required' => 'The ' . $request->type . ' field is required.',
                'lc_number.max' => 'The ' . $request->type . ' may not be greater than 25 characters.',
            ];

            $validator = FacadesValidator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $lc = LowerCourt::updateOrCreate(['id' => $request->application_id], [
                $request->type => $request->lc_number
            ]);

            if ($request->type == 'gi_no_lc') {
                GroupInsurance::updateOrCreate(['lc_id' => $lc->id, 'year' => Carbon::parse(Carbon::now())->format('Y')], [
                    'lc_id' => $lc->id,
                    'number' => $lc->gi_no_lc,
                    'date' => Carbon::parse(Carbon::now())->format('Y-m-d'),
                    'year' => Carbon::parse(Carbon::now())->format('Y'),
                    'amount' => 0,
                ]);
            }

            return response()->json(['status' => 1, 'message' => 'success']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 0, 'message' => 'There might be configuration error. Try again later, or contact the support.'], 401);
        }
    }

    public function excelImport(Request $request)
    {
        Excel::import(new LowerCourtImport($request->excel_import_app_type), request()->file('excel_import_file'));
        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function update(Request $request, $id)
    {
        $lc = LowerCourt::find($id);
        $user = User::where('id', $lc->user_id)->first();

        $rules = [
            'sr_no' => 'numeric|unique:lower_courts,sr_no_lc,' . $lc->id,
            'name' => 'required',
            'father_name' => 'nullable',
            'gender' => 'required',
            'date_of_birth' => 'required|before:18 years ago',
            'cnic_no' => 'nullable|min:15|unique:lower_courts,cnic_no,' . $lc->id,
            'ledger_no' => 'nullable|unique:lower_courts,reg_no_lc,' . $lc->id,
            'license_no' => 'nullable|unique:lower_courts,license_no_lc,' . $lc->id,
            'bf_no' => 'nullable|unique:lower_courts,bf_no_lc,' . $lc->id,
            'enr_date' => 'nullable',
            'voter_member' => 'nullable',
            'profile_image' => 'nullable',
            'enr_app_sdw' => 'required',
            'enr_status_reason' => 'nullable',
            'enr_plj_check' => 'required',
            'enr_gi_check' => 'required',
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'min:10', 'max:10', 'string', 'unique:users,phone,' . $user->id],
            'address_1' => 'required',
            'address_2' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $lc_data = [
            'father_name' => $request->father_name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'cnic_no' => $request->cnic_no,
            'reg_no_lc' => $request->ledger_no,
            'license_no_lc' => $request->license_no,
            'bf_no_lc' => $request->bf_no,
            'date_of_enrollment_lc' => $request->enr_date,
            'voter_member_lc' => $request->voter_member,
            'enr_app_sdw' => $request->enr_app_sdw,
            'enr_status_reason' => $request->enr_status_reason,
            'enr_plj_check' => $request->enr_plj_check,
            'enr_gi_check' => $request->enr_gi_check,
        ];

        $lc->update($lc_data);

        $user_data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'cnic_no' => $request->cnic_no,
        ];
        $user->update($user_data);

        LawyerAddress::updateOrCreate(['lower_court_id' => $lc->id], [
            'lower_court_id' => $lc->id,
            'ha_str_address' => $request->address_1,
            'ha_town' => $request->address_2,
        ]);

        $upload = LawyerUpload::where('lower_court_id', $lc->id)->first();
        $directory = 'lower-court/' . $lc->id;

        if ($request->hasFile('profile_image')) {
            $request->file('profile_image')->getClientOriginalName();
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('profile_image')));
            ($upload == NULL) ? $upload = LawyerUpload::create(['lower_court_id' => $lc->id, 'profile_image' => $url]) : $upload->update(['profile_image' => $url]);
        }

        return response()->json(['status' => 1, 'message' => 'The application data have been updated successfully.']);
    }

    public function quickCreate(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $rules = [
            'sr_no' => 'required|numeric|unique:lower_courts,sr_no_lc',
            'name' => 'required',
            'father_name' => 'nullable',
            'gender' => 'required',
            'date_of_birth' => 'required',
            'cnic_no' => 'nullable|min:15|unique:lower_courts,cnic_no',
            'ledger_no' => 'nullable|unique:lower_courts,reg_no_lc',
            'license_no' => 'nullable|unique:lower_courts,license_no_lc',
            'bf_no' => 'nullable|unique:lower_courts,bf_no_lc',
            'enr_date' => 'required',
            'voter_member' => 'required',
            'profile_image' => 'required',
            'enr_app_sdw' => 'required',
            'enr_status_reason' => 'nullable',
            'enr_plj_check' => 'required',
            'enr_gi_check' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'min:10', 'max:10', 'string', 'unique:users,phone'],
            'address_1' => 'required',
            'address_2' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $user_data = [
            'name' => $request->name,
            'fname' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'cnic_no' => $request->cnic_no,
            'password' => 'NO-PASSWORD',
        ];

        $user = User::create($user_data);

        $lc_data = [
            'user_id' => $user->id,
            'father_name' => $request->father_name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'cnic_no' => $request->cnic_no,
            'reg_no_lc' => $request->ledger_no,
            'license_no_lc' => $request->license_no,
            'bf_no_lc' => $request->bf_no,
            'date_of_enrollment_lc' => $request->enr_date,
            'voter_member_lc' => $request->voter_member,
            'enr_app_sdw' => $request->enr_app_sdw,
            'enr_status_reason' => $request->enr_status_reason,
            'enr_plj_check' => $request->enr_plj_check,
            'enr_gi_check' => $request->enr_gi_check,
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
            'quick_created' => true,
            'is_final_submitted' => true,
            'final_submitted_at' => Carbon::now(),
        ];

        $lc = LowerCourt::create($lc_data);

        $lc_address_data = [
            'ha_str_address' => $request->address_1,
            'ha_town' => $request->address_2,
        ];
        LawyerAddress::create($lc_address_data);

        $upload = LawyerUpload::where('lower_court_id', $lc->id)->first();
        $directory = 'lower-court/' . $lc->id;

        if ($request->hasFile('profile_image')) {
            $request->file('profile_image')->getClientOriginalName();
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('profile_image')));
            ($upload == NULL) ? $upload = LawyerUpload::create(['lower_court_id' => $lc->id, 'profile_image' => $url]) : $upload->update(['profile_image' => $url]);
        }

        return response()->json(['status' => 1, 'message' => 'The lower court data have been added successfully.']);
    }

    public function updateLcDate(Request $request)
    {
        $lc = LowerCourt::find($request->lc_id);
        $lc->update([
            'lc_date' => setDateFormat($request->lc_date)
        ]);

        $user = User::find($lc->user_id);
        if ($user) {
            if (!$user->gc_requested_at) {
                $user->update(['gc_requested_at' => setDateFormat(Carbon::now())]);
            }
        }

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function permanentDeleteLC($id)
    {
        $lc = LowerCourt::findOrFail($id);
        // LawyerAddress::where('lower_court_id', $lc->id)->delete();
        // LawyerEducation::where('lower_court_id', $lc->id)->delete();
        // LawyerUpload::where('lower_court_id', $lc->id)->delete();
        Payment::where('lower_court_id', $lc->id)->delete();
        $lc->delete();

        return response()->json(['status' => 1, 'message' => 'success',]);
    }

    public function resetPayments(Request $request)
    {
        $application = LowerCourt::find($request->lc_id);
        $this->lowerCourtPayment($application);
        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function objections(Request $request, $id)
    {
        $application = LowerCourt::findOrFail($id);
        if ($request->has('objections') && !empty($request->objections)) {
            $application->update(['objections' => json_encode($request->objections)]);
        } else {
            $application->update(['objections' => NULL]);
        }
    }

    public function pljBloodRelation(Request $request, $id)
    {
        $lc = LowerCourt::find($id);
        if ($request->plj_br_type != NULL) {
            $lc->update([
                'plj_br' => true,
                'plj_br_type' => $request->plj_br_type,
            ]);
        } else {
            $lc->update([
                'plj_br' => false,
                'plj_br_type' => NULL,
            ]);
        }

        $this->lowerCourtPayment($lc);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function updateVoterMember(Request $request)
    {
        $application = LowerCourt::find($request->application_id);

        $rules = [
            'voter_member_lc' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $data = [
            'voter_member_lc' => $request->input('voter_member_lc'),
        ];

        $application->update($data);

        return response()->json([
            'status' => 1,
            'message' => 'success',
        ]);
    }

    public function exemption(Request $request)
    {
        $lc = LowerCourt::find($request->lc_id);
        $lc->update([
            'hc_exemption_at' => Carbon::now(),
        ]);
        return response()->json(['status' => 1, 'message' => 'success']);
    }
}
