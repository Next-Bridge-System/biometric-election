<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Bar;
use App\Policy;
use App\User;
use Illuminate\Http\Request;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Route;

use App\Application;
use App\AppStatus;
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
use App\PoliceVerification;
use App\PrintCertificate;
use App\PrintSecureCard;
use Carbon\Carbon;
use Validator;
use PDF;
use DB;
use Auth;

class IntimationController extends Controller
{
    public function index(Request $request, $slug)
    {
        if ($request->ajax()) {

            if ($slug == 'final') {
                $application = Application::intimationApplication();
            }

            if ($slug == 'partial') {
                $application = Application::intimationPartialApplication();
            }

            if ($slug == 'list') {
                $application = Application::intimationAllApplication();
            }

            $admin = Auth::guard('admin')->user();
            if ($admin->is_super == 0) {
                $application->where('bar_association', $admin->bar_id);
            }

            return Datatables::of($application)
                ->addIndexColumn()
                ->addColumn('name', function (Application $application) {
                    return getLawyerName($application->id);
                })
                ->addColumn('sdw', function (Application $application) {
                    return $application->so_of;
                })
                ->addColumn('active_mobile_no', function (Application $application) {
                    $activeMobileNumber = "+92" . $application->active_mobile_no;
                    return $activeMobileNumber;
                })
                ->addColumn('application_status', function (Application $application) {
                    return appStatus($application->application_status, $application->app_type);
                })
                ->addColumn('payment_status', function (Application $application) {
                    return '<span class="badge badge-' . getPaymentStatus($application->id)['badge'] . '">' . getPaymentStatus($application->id)['name'] . '</span>';
                })
                ->addColumn('submitted_by', function (Application $application) {
                    $submittedBy = NULL;
                    if ($application->is_frontend == 1 && $application->submitted_by == NULL) {
                        $submittedBy = getLawyerName($application->id) . '<br><span class="badge badge-success">Online</span>';
                    } else if ($application->is_frontend == 0 && $application->submitted_by != NULL) {
                        $submittedBy = getAdminName($application->submitted_by) . '<br><span class="badge badge-info">Operator</span> ';
                    } else {
                        $submittedBy = '(Unknown)';
                    }
                    return $submittedBy;
                })
                ->addColumn('rcpt', function ($application) {
                    if (isset($application->rcpt_date)) {
                        return '<span>' . $application->rcpt_date . '</span> <br>
                    <span class="badge badge-secondary">' . $application->rcpt_no . '</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('action', function (Application $application) {

                    $btn = null;

                    $btn .= '<a href="' . route('intimations.show', $application->id) . '"><span class="badge badge-primary m-1"><i class="fas fa-copy mr-1" aria-hidden="true"></i>Detail</span></a>';

                    if ($application->is_intimation_completed == 0) {

                        $btn .= '<a href="' . route('intimations.token', ['download' => 'pdf', 'application' => $application]) . '" target="_blank"><span class="badge badge-primary mr-1"><i class="fas fa-print mr-1" aria-hidden="true"></i>Token</span></a>';

                        if (Auth::guard('admin')->user()->hasPermission('intimation-activity-log')) {
                            $btn .= '<a href="' . route('activity-log.index', $application->user_id) . '"><span class="badge badge-primary mr-1"><i class="fas fa-folder mr-1" aria-hidden="true"></i>Log</span></a>';
                        }

                        if (Route::currentRouteName() == 'intimations.index' && Auth::guard('admin')->user()->hasPermission('add-intimation-rcpt-date')) {
                            if ($application->rcpt_date == NULL) {
                                $btn .= ' <a href="javascript:void(0)" data-action="' . route('intimations.rcpt-date', ['application_id' => $application->id]) . '" onclick="rcptDate(this,' . $application->application_token_no . ')"><span class="badge badge-primary mr-1"><i class="fas fa-plus mr-1" aria-hidden="true"></i>RCPT NO</span></a>';
                            } else {
                                $btn .= '<span class="badge badge-success mr-1"><i class="fas fa-check mr-1" aria-hidden="true"></i>RCPT NO</span>';
                            }
                        }
                    }

                    if ($application->is_intimation_completed == 1) {
                        $btn .= '<a href="' . route('intimations.export.form-b', ['application_id' => $application->id]) . '" target="_blank" ><span class="badge badge-success mr-1"><i class="fas fa-download mr-1" aria-hidden="true"></i>Training Certificate</span></a>';
                    }

                    $edit_final = Route::currentRouteName() == 'intimations.index' && Route::current()->parameters()['slug'] == 'final' && Auth::guard('admin')->user()->hasPermission('edit-intimations');

                    $edit_partial = Route::currentRouteName() == 'intimations.index' && Route::current()->parameters()['slug'] == 'partial' && Auth::guard('admin')->user()->hasPermission('edit-partial-intimation');

                    if ($edit_final || $edit_partial) {
                        if ($application->is_intimation_completed == 0) {
                            $btn .= '<a href="' . route('intimations.create-step-1', $application->id) . '"><span class="badge badge-primary mr-1"><i class="fas fa-edit mr-1" aria-hidden="true"></i>Edit</span></a>';
                        }
                    }

                    return $btn;
                })
                ->setRowClass(function ($application) {
                    $today = Carbon::parse(Carbon::now())->format('d-m-Y');

                    if ($application->is_intimation_completed == 1) {
                        $class = 'bg-light-green';
                    } else if ($application->objections != NULL) {
                        $class = 'bg-light-red';
                    } else if (getIntimationDuration($application->intimation_start_date) >= 6) {
                        $class = 'bg-light-blue';
                    } else {
                        $class = '';
                    }
                    return $class;
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
                            $instance->where('is_frontend', 1)->where('submitted_by', null);
                        }
                        if ($request->get('application_submitted_by') == 'operator') {
                            $instance->where('is_frontend', 0)->where('submitted_by', '!=', null);
                        }
                    }

                    if ($request->get('intimation_operator')) {
                        $instance->where('submitted_by', $request->intimation_operator)->where('is_frontend', 0);
                    }

                    if ($request->get('payment_status')) {
                        if ($request->get('payment_status') == 'paid') {
                            $payments = Payment::where('payment_status', 1)->pluck('application_id')->toArray();
                            $instance->whereIn('id', $payments);
                        }
                        if ($request->get('payment_status') == 'unpaid') {
                            $payments = Payment::where('payment_status', 0)->pluck('application_id')->toArray();
                            $instance->whereIn('id', $payments);
                        }
                    }

                    if ($request->get('payment_type')) {
                        if ($request->get('payment_type') == 'online') {
                            $payments = Payment::where('online_banking', 1)->pluck('application_id')->toArray();
                            $instance->whereIn('id', $payments);
                        }
                        if ($request->get('payment_type') == 'operator') {
                            $payments = Payment::where('online_banking', 0)->pluck('application_id')->toArray();
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

                    if (!empty($request->get('search'))) {
                        $instance->where(function ($query) use ($request) {
                            $search = $request->get('search');
                            $query
                                ->orWhere(DB::raw('CONCAT(advocates_name," ",last_name)'), 'LIKE', '%' . $search . '%')
                                ->orWhere('last_name', 'LIKE', "%$search%")
                                ->orWhere('cnic_no', 'LIKE', "%$search%")
                                ->orWhere(DB::raw('CONCAT("+92",active_mobile_no)'), 'LIKE', '%' . $search . '%')
                                ->orWhere(DB::raw('CONCAT("0",active_mobile_no)'), 'LIKE', '%' . $search . '%')
                                ->orWhere('user_id', "$search")
                                ->orWhere('application_token_no', "$search");
                        });
                    }
                })
                ->rawColumns(['name', 'application_type', 'application_status', 'action', 'active_mobile_no', 'payment_status', 'submitted_by', 'rcpt'])
                ->make(true);
        }

        $operators = Admin::select('id', 'name')->get();
        $universities = University::select('id', 'name')->where('type', 1)->orderBy('name', 'asc')->get();


        return view('admin.intimations.index', compact('slug', 'operators', 'universities'));
    }

    public function show(Request $request, $id)
    {
        $application = Application::with(['additional_notes' => function ($query) {
            $query->where('application_type', 'INTIMATION')->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        // abort_if($application->is_intimation_completed == 1, 403, 'You don\'t have permission to access this.');

        $payments = Payment::where('user_id', $application->user_id)->where('application_type', 6)->get();
        $universities = University::select('id', 'name')->where('approved', 1)->where('type', 1)->orderBy('name', 'asc')->get();
        $affliatedUniversities = University::where('type', 2)->where('approved', 1)->orderBy('name', 'asc')->get();
        $bars = Bar::select('id', 'name')->orderBy('name', 'asc')->get();

        $request->session()->forget('intimation_application');
        if (empty($request->session()->get('intimation_application'))) {
            $application->fill(['application_id' => $application->id]);
            $request->session()->put('intimation_application', $application->id);
        }

        $app_status = AppStatus::where('status', 1)->get();

        return view('admin.intimations.show', compact('application', 'payments', 'universities', 'affliatedUniversities', 'bars', 'app_status'));
    }

    public function payment(Request $request, $id)
    {
        $application = Application::find($id);
        $payment = Payment::where('user_id', $application->user_id)->where('application_type', 6)->where('payment_status', 0)->first();
        $finaL_submission_date = Carbon::parse($application->final_submitted_at)->format('Y-m-d');

        if ($finaL_submission_date == null) {
            return redirect()->back()->with('error', 'The final submission is missing in this application.');
        }

        abort_if($payment == NULL, 403);

        $request->session()->forget('intimation_payment');
        if (empty($request->session()->get('intimation_payment'))) {
            $payment->fill(['application_id' => $application->id]);
            $request->session()->put('intimation_payment', $payment->id);
        }

        if ($request->isMethod('post')) {

            $rules = [
                'paid_date' => 'required|max:255',
                'bank_name' => 'required|max:255',
                'transaction_id' => 'required|max:255',
                'voucher_file' => [Rule::requiredIf($payment->voucher_file == NULL || $payment->voucher_file == NULL), 'max:255'],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                    'voucher_file' => 'The bank voucher file is required.',
                ], 400);
            }

            $policy = Policy::whereDate('start_date', '<=', $finaL_submission_date)->whereDate('end_date', '>=', $finaL_submission_date)->first();
            if ($policy == null) {
                return response()->json([
                    'policy' => 'No Policy Found Against Payment',
                ], 400);
            }

            $paidPayments = Payment::select(DB::raw("SUM(amount) as paid_amount"))->where('payment_status', 1)->where('user_id', $application->user_id)->where('application_type', 6)->get()->toArray();
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
                createActivity($payment, 'Payment');
                if ($request->amount > $payment->amount) {
                } else {
                    $currentDate = Carbon::now();
                    $vch_no = 1000000000000 + strtotime($currentDate) + $application->application_token_no;
                    $get_vch_amount =  getVoucherAmount($application->id);

                    $enr_fee_paid_sum = Payment::where('application_id', $application->id)
                        ->where('payment_status', 1)
                        ->sum('enr_fee_pbc');

                    $enr_fee_paid_sum = $enr_fee_paid_sum ?: 0;

                    $degree_fee_paid_sum = Payment::where('application_id', $application->id)
                        ->where('payment_status', 1)
                        ->sum('degree_fee');

                    $degree_fee_paid_sum = $degree_fee_paid_sum ?: 0;

                    $data = [
                        'application_id' => $application->id,
                        'user_id' => $application->user_id,
                        'application_type' => 6, // Intimation
                        'application_status' => 7, // Pending
                        'payment_status' => 0, // Unpaid
                        'payment_type' => 0,
                        'amount' => $get_vch_amount['total_amount'] - $request->amount - $paidAmount,
                        'degree_fee' => $get_vch_amount['degree_fee'] - $degree_fee_paid_sum,
                        'enr_fee_pbc' => $get_vch_amount['enr_fee_pbc'] - $enr_fee_paid_sum,
                        'bank_name' => $request->bank_name,
                        'voucher_no' => $vch_no,
                    ];
                    $newPayment = Payment::create($data);
                    createActivity($newPayment, 'Payment');
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

                createActivity($payment, 'Payment');
            }

            // if ($request->has('paid_date') && $application->intimation_start_date == NULL) {
            //     $application->update(['intimation_start_date' => $request->paid_date]);
            // }

            $intimation_date = getIntimationStartDate($application)['intimation_date'];
            $application->update(['intimation_start_date' => $intimation_date]);

            return response()->json(['status' => 1, 'message' => 'success']);
        }

        return view('admin.intimations.payment', compact('application', 'payment'));
    }

    public function status(Request $request, $id)
    {
        $application = Application::find($id);
        $payment = Payment::where('user_id', $application->user_id)->where('application_type', 6)->first();

        if (isset($payment) && $payment->payment_status == 0 && $request->application_status == 1) {
            return response()->json([
                'error' => 'You cannot not change status of this intimation application until the payment is pending or unpaid.',
            ], 400);
        }

        if ($request->application_status == 1) {
            $application->update(['is_approved' => 1]);
        }

        $application->update(['application_status' => $request->application_status]);
        createActivity($application, 'Application');

        $this->intimationPaymentVoucher($request, $application);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function updateStatus(Request $request)
    {
        $application = Application::find($request->intimation_id);
        $payment = Payment::where('user_id', $application->user_id)->where('application_type', 6)->first();

        if (isset($payment) && $payment->payment_status == 0) {
            return response()->json([
                'error' => 'You cannot not change status of this intimation application until the payment is pending or unpaid.',
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

        $application->update(['application_status' => $request->app_status]);

        Note::create([
            'application_id' => $application->id,
            'application_type' => 'INTIMATION',
            'note' => $request->app_status_reason,
            'admin_id' => Auth::guard('admin')->user()->id,
        ]);

        if ($request->app_status == 1) {
            $application->update(['is_approved' => 1]);
        }

        $this->intimationPaymentVoucher($request, $application);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function print(Request $request)
    {
        $application = Application::find($request->application);
        $payments = Payment::where('user_id', $application->user_id)->where('application_type', 6)->get();

        view()->share([
            'application' => $application,
            'payments' => $payments,
        ]);

        if ($request->has('download')) {
            $pdf = PDF::loadView('admin.intimations.print-detail');
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('APPLICATION-' . $application->application_token_no . '.pdf', array("Attachment" => false));
        }
    }

    public function uploadPaymentVoucher(Request $request)
    {
        $id = $request->session()->get('intimation_payment');
        $payment = Payment::find($id);
        $directory = 'applications/vouchers/' . $payment->application_id;
        if ($request->hasFile('voucher_file')) {
            $fileName = $request->file('voucher_file')->getClientOriginalName();
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('voucher_file')));
            $payment->update(['voucher_file' => $url]);
            createUploadActivity($payment, 'Payment');
        }
    }

    public function destroyPaymentVoucher(Request $request)
    {
        $id = $request->session()->get('intimation_payment');
        $payment = Payment::find($id);
        $payment->update(['voucher_file' => NULL]);
        createUploadActivity($payment, 'Payment');
        return redirect()->back();
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
            'application_type' => 6, // Intimation Application
            'app_type' => 1, // DIRECT ENTRY
            'is_approved' => 0, // Not Approved
            'application_status' => 6, // Pending Application
            'submitted_by' => $admin->id,
            'is_frontend' => false,
            'bar_association' => $admin->bar_id,
            // REGISTER USER
            'user_id' => $user->id,
            'advocates_name' => $user->fname,
            'last_name' => $user->lname,
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
        createActivity($application, 'Application');

        return [
            'status' => true,
            'url' => \URL::route('intimations.create-step-1', $application->id)
        ];
    }

    public function createStep1(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $user = User::find($application->user_id);
        $bars = Bar::select('id', 'name')->orderBy('name', 'asc')->get();
        $years = range(Carbon::now()->year, 1900);
        $admin = Auth::guard('admin')->user();

        if ($admin->is_super == 0) {
            if (Auth::guard('admin')->user()->hasPermission('edit-intimations') && $application->bar_association == $admin->bar_id) {
            } else if (Auth::guard('admin')->user()->hasPermission('edit-partial-intimation') && $application->bar_association ==  $admin->bar_id && $application->is_accepted == 0) {
            } else {
                abort(403, 'You dont have permission to access this application');
            }
        }

        $request->session()->forget('intimation_application');
        if (empty($request->session()->get('intimation_application'))) {
            $request->session()->put('intimation_application', $application->id);
        }

        if ($request->isMethod('post')) {

            $rules = [
                'llb_passing_year' => 'required|max:255',
                'rcard_date' => 'required|date|before_or_equal:today',
                'bar_association' => [Rule::requiredIf($admin->is_super == 1), 'max:255'],
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'father_name' => 'required|max:255',
                'gender' => 'required|max:255',
                'date_of_birth' => 'required|before:18 years ago',
                'blood_group' => 'nullable|max:255',
                'email' => ['required', 'string', 'email', 'max:255', 'unique:applications,email,' . $application->id],
                'active_mobile_no' => ['required', 'string', 'unique:applications,active_mobile_no,' . $application->id],
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
                'bar_association' => $request->has('bar_association') ? $request->input('bar_association') : $admin->bar_id,
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
            createActivity($application, 'Application');

            $nextView = view('admin.intimations.steps.create-step-2', compact('application'))->render();
            return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 2]);
        }

        return view('admin.intimations.create', compact('application', 'bars', 'years', 'user'));
    }

    public function createStep2(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        if ($request->isMethod('post')) {
            // dd($request->all());

            $rules = [
                'cnic_no' => 'required|min:15|unique:applications,cnic_no,' . $application->id,
                'cnic_expiry_date' => 'required',
                'degree_place' => $application->final_submitted_at == NULL ? 'required|in:1,2,3' : 'nullable|in:1,2,3',
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
                'cnic_no' => $request->input('cnic_no'),
                'cnic_expiry_date' => $request->input('cnic_expiry_date'),
                'degree_place' => $request->input('degree_place'),
            ];

            $application->update($data);
            createActivity($application, 'Application');
            $this->intimationPaymentVoucher($request, $application);

            // for step 3
            $countries = Country::select('id', 'name')->orderBy('name', 'asc')->get();
            $provinces = Province::select('id', 'name')->orderBy('name', 'asc')->get();
            $districts = District::select('id', 'name', 'code')->orderBy('name', 'asc')->get();
            $tehsils = Tehsil::select('id', 'name', 'code')->orderBy('name', 'asc')->get();
            $nextView = view('admin.intimations.steps.create-step-3', compact('districts', 'tehsils', 'application', 'provinces', 'countries'))->render();

            return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 3]);
        }

        return view('admin.intimations.steps.create-step-2', compact('application'));
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

            $application = Application::findOrFail($id);
            $universities = University::select('id', 'name')->where('type', 1)->orderBy('name', 'asc')->get();
            $affliatedUniversities = University::where('type', 2)->orderBy('name', 'asc')->get();

            $academicRecord = [];
            foreach ($application->educations as $key => $applicationEducation) {
                $academicRecord[] = $applicationEducation->qualification;
            }

            if ((in_array('1', $academicRecord) && in_array('2', $academicRecord) && in_array('3', $academicRecord) && in_array('4', $academicRecord) &&  in_array('5', $academicRecord) && in_array('6', $academicRecord)) || (in_array('1', $academicRecord) && in_array('2', $academicRecord) && in_array('7', $academicRecord))) {
                $application->update(['is_academic_record' => TRUE]);
            } else {
                $application->update(['is_academic_record' => FALSE]);
            }
            createActivity($application, 'Application');
            $nextView = view('admin.intimations.steps.create-step-4', compact('application', 'universities', 'affliatedUniversities', 'academicRecord'))->render();
            return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 4]);
        }



        return view('admin.intimations.steps.create-step-3', compact('districts', 'tehsils', 'application', 'provinces', 'countries'));
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
            $academicRecordStatus = TRUE;
        } else {
            $application->update(['is_academic_record' => FALSE]);
            $academicRecordStatus = FALSE;
        }
        createActivity($application, 'Application');

        if ($request->isMethod('post')) {

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
            createActivity($lawyerEducation, 'Application');
            uploadEducationalCertificate($request, $lawyerEducation->id, $application->id);

            $application = Application::findOrFail($id);
            $records = view('admin.intimations.partials.academic-record', compact('application'))->render();
            return response()->json(['status' => 2, 'message' => 'success', 'academicRecordStatus' => $academicRecordStatus, 'records' => $records]);
        }

        return view('admin.intimations.steps.create-step-4', compact('application', 'universities', 'affliatedUniversities', 'academicRecord'));
    }

    public function createStep5(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $bars = Bar::select('id', 'name')->orderBy('name', 'asc')->get();
        $admin = Auth::guard('admin')->user();
        if ($request->isMethod('post')) {

            $rules = [
                'srl_name' => 'required|max:255',
                'srl_bar_name' => [Rule::requiredIf($admin->is_super == 1), 'max:255'],
                'srl_office_address' => 'required|max:255',
                'srl_enr_date' => 'required|before:10 years ago',
                'srl_mobile_no' => 'required|max:255',
                'srl_cnic_no' => 'required|min:15',
            ];

            if (Auth::guard('admin')->user()->hasPermission('intimation-srl-joining-date')) {
                $current_date = Carbon::now()->format('d-m-Y');
                $prev_month_date = date("Y-m-d", strtotime('-1 month', strtotime($current_date)));
                if (!Auth::guard('admin')->user()->hasPermission('intimation-srl-joining-date-validation')) {
                    $rules += [
                        'srl_joining_date' => 'required|before_or_equal:today|after_or_equal:' . $prev_month_date,
                    ];
                }
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
                'srl_bar_name' => $request->has('srl_bar_name') ? $request->input('srl_bar_name') : $admin->bar_id,
                'srl_office_address' => $request->input('srl_office_address'),
                'srl_enr_date' => $request->input('srl_enr_date'),
                'srl_mobile_no' => $request->input('srl_mobile_no'),
                'srl_cnic_no' => $request->input('srl_cnic_no'),
            ];

            $application->update($data);
            if (Auth::guard('admin')->user()->hasPermission('intimation-srl-joining-date')) {
                $application->update(['srl_joining_date' => $request->input('srl_joining_date')]);
            }
            createActivity($application, 'Application');
            $nextView = view('admin.intimations.steps.create-step-6', compact('application'))->render();
            return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 6]);
        }

        return view('admin.intimations.steps.create-step-5', compact('application', 'bars'));
    }

    public function createStep6(Request $request, $id)
    {
        $application = Application::findOrFail($id);
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

            $nextView = view('admin.intimations.steps.create-step-7', compact('application'))->render();
            return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 7]);
        }
        return view('admin.intimations.steps.create-step-6', compact('application'));
    }

    public function createStep7(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $user = User::find($application->user_id);
        if ($request->isMethod('post')) {

            $rules = [
                'final_submission' => [Rule::requiredIf($application->paymentVoucher == NULL)],
                'bank_name' => [Rule::requiredIf($application->paymentVoucher == NULL)],
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
            $application->update(['is_accepted' => 1]);
            if ($application->final_submitted_at == NULL) {
                $application->update(['final_submitted_at' => Carbon::now()]);
            }

            $this->intimationPaymentVoucher($request, $application);
            $password = $request->session()->get('password');

            $data = [
                "phone" => '+92' . $application->active_mobile_no,
                "code" => $application->application_token_no,
                "email" => $application->email,
                "passcode" => $application->passcode,
                "event_id" => 141,
            ];

            sendMessageAPI($data);

            $user = User::find($application->user_id);
            $mailData = [
                'subject' => 'PUNJAB BAR COUNCIL - Application Submit Successfully',
                'name' => $user->name,
                'description' => '<p>Dear Applicant, Your application has been submitted. </br> Email:' . $application->email . ' </br> Password:' . $password . '</p>',
            ];
            sendMailToUser($mailData, $user);

            if ($request->has('final_submission')) {
                $application = Application::findOrFail($id);
                if ($request->final_submission == 2) {
                    $nextView = \URL::route('intimations.payment', $application->id);
                    return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 9]);
                } else {
                    $nextView = view('admin.intimations.steps.create-step-8', compact('application'))->render();
                    if ($request->bank_name == "Bank Islami") {
                        $url = route('intimations.bank-islami-voucher', ['download' => 'pdf', 'application' => $application]);
                    } else {
                        $url = route('intimations.habib-bank-limited-voucher', ['download' => 'pdf', 'application' => $application]);
                    }
                    return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 8, 'print_voucher_url' => $url]);
                }
            } else {
                $nextView = view('admin.intimations.steps.create-step-8', compact('application'))->render();
                return response()->json(['status' => 1, 'message' => 'success', 'nextStep' => $nextView, 'step' => 8]);
            }
        }

        return view('admin.intimations.steps.create-step-7', compact('application'));
    }

    public function uploadProfileImage(Request $request)
    {
        if ($request->has('app_id')) {
            $id = $request->get('app_id');
        } else {
            $id = $request->session()->get('intimation_application');
        }

        $model = Application::find($id);
        $upload = LawyerUpload::where('application_id', $model->id)->first();
        $directory = 'applications/uploads/' . $model->id;
        if ($request->hasFile('profile_image')) {
            $fileName = $request->file('profile_image')->getClientOriginalName();
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
            $url = Storage::putFile($directory, new File($request->file('profile_image')));
            ($upload == NULL) ? $upload = LawyerUpload::create(['application_id' => $model->id, 'profile_image' => $url]) : $upload->update(['profile_image' => $url]);
            createUploadActivity($upload, 'Application');
        }
    }

    public function destroyProfileImage(Request $request)
    {
        if ($request->has('app_id')) {
            $id = $request->get('app_id');
        } else {
            $id = $request->session()->get('intimation_application');
        }
        $model = Application::find($id);
        $destroy = LawyerUpload::where('application_id', $model->id)->first();
        $destroy->update(['profile_image' => NULL]);
        createUploadActivity($destroy, 'Application');
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
            ($upload == NULL) ? $upload = LawyerUpload::create(['application_id' => $model->id, 'cnic_front' => $url]) : $upload->update(['cnic_front' => $url]);
            createUploadActivity($upload, 'Application');
        }
    }

    public function destroyCnicFront(Request $request)
    {
        $id = $request->session()->get('intimation_application');
        $model = Application::find($id);
        $destroy = LawyerUpload::where('application_id', $model->id)->first();
        $destroy->update(['cnic_front' => NULL]);
        createUploadActivity($destroy, 'Application');
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
            ($upload == NULL) ? $upload = LawyerUpload::create(['application_id' => $model->id, 'cnic_back' => $url]) : $upload->update(['cnic_back' => $url]);
            createUploadActivity($upload, 'Application');
        }
    }

    public function destroyCnicBack(Request $request)
    {
        $id = $request->session()->get('intimation_application');
        $model = Application::find($id);
        $destroy = LawyerUpload::where('application_id', $model->id)->first();
        $destroy->update(['cnic_back' => NULL]);
        createUploadActivity($destroy, 'Application');
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
            ($upload == NULL) ? $upload = LawyerUpload::create(['application_id' => $model->id, 'srl_cnic_front' => $url]) : $upload->update(['srl_cnic_front' => $url]);
            createUploadActivity($upload, 'Application');
        }
    }

    public function destroySrlCnicFront(Request $request)
    {
        $id = $request->session()->get('intimation_application');
        $model = Application::find($id);
        $destroy = LawyerUpload::where('application_id', $model->id)->first();
        $destroy->update(['srl_cnic_front' => NULL]);
        createUploadActivity($destroy, 'Application');
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
            ($upload == NULL) ? $upload = LawyerUpload::create(['application_id' => $model->id, 'srl_cnic_back' => $url]) : $upload->update(['srl_cnic_back' => $url]);
            createUploadActivity($upload, 'Application');
        }
    }

    public function destroySrlCnicBack(Request $request)
    {
        $id = $request->session()->get('intimation_application');
        $model = Application::find($id);
        $destroy = LawyerUpload::where('application_id', $model->id)->first();
        $destroy->update(['srl_cnic_back' => NULL]);
        createUploadActivity($destroy, 'Application');
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
            ($upload == NULL) ? $upload = LawyerUpload::create(['application_id' => $model->id, 'srl_license_front' => $url]) : $upload->update(['srl_license_front' => $url]);
            createUploadActivity($upload, 'Application');
        }
    }

    public function destroySrlLicenseFront(Request $request)
    {
        $id = $request->session()->get('intimation_application');
        $model = Application::find($id);
        $destroy = LawyerUpload::where('application_id', $model->id)->first();
        $destroy->update(['srl_license_front' => NULL]);
        createUploadActivity($destroy, 'Application');
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
            ($upload == NULL) ? $upload = LawyerUpload::create(['application_id' => $model->id, 'srl_license_back' => $url]) : $upload->update(['srl_license_back' => $url]);
            createUploadActivity($upload, 'Application');
        }
    }

    public function destroySrlLicenseBack(Request $request)
    {
        $id = $request->session()->get('intimation_application');
        $model = Application::find($id);
        $destroy = LawyerUpload::where('application_id', $model->id)->first();
        $destroy->update(['srl_license_back' => NULL]);
        createUploadActivity($destroy, 'Application');
        return redirect()->back();
    }

    public function destroyAcademicRecord($id)
    {
        $lawyerEducation = LawyerEducation::find($id);
        createDeleteActivity($lawyerEducation->application_id, 'Application', 'Education Record Deleted');
        $lawyerEducation->delete();
        return true;
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

    public function token(Request $request)
    {
        $application = Application::find($request->application);
        view()->share('application', $application);
        if ($request->has('download')) {
            $pdf = PDF::loadView('admin.intimations.token');
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('Intimation-Token-' . $application->application_token_no . '.pdf', array("Attachment" => false));
        }
        return view('admin.intimations.token');
    }

    public function sendOTP(Request $request, $id)
    {
        $application = Application::find($id);
        $user = User::find($application->user_id);
        $digits = 5;
        $otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $user->update(['otp' => $otp]);
        $data = [
            "phone" => '+92' . $user->phone,
            "message" => 'Dear Applicant, Your OTP is ' . $otp . '. Please do not share this OTP with others for your privacy.',
        ];
        sendMessageAPI($data);
    }

    public function updateIntimationDate(Request $request)
    {
        $application = Application::find($request->application_id);
        $application->update(['intimation_start_date' => $request->intimation_start_date]);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function rcptDate(Request $request)
    {
        if ($request->has('rcpt_date') && !empty($request->rcpt_date)) {
            $rcpt_date = Carbon::parse($request->rcpt_date)->format('Y-m-d');
        } else {
            $rcpt_date = Carbon::parse(Carbon::now())->format('Y-m-d');
        }

        $rcpt = Application::select('rcpt_no', 'rcpt_date')->orderBy('rcpt_no', 'desc')->whereYear('rcpt_date', Carbon::parse($rcpt_date)->format('Y'))->first();

        if ($rcpt == null) {
            $rcpt_count = 1;
        } else {
            $rcpt_count = $rcpt->rcpt_no  + 1;
        }

        Application::updateOrCreate(['id' =>  $request->application_id], [
            'rcpt_no' => sprintf("%02d", $rcpt_count),
            'rcpt_date' => $rcpt_date,
        ]);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function printBankVoucher(Request $request)
    {
        // dd($request->all());

        $application = Application::find($request->application_id);
        $payment = Payment::find($request->payment_id);

        // if (Auth::guard('frontend')->check()) {
        //     $user = Auth::guard('frontend')->user();
        //     if ($user->id != $application->user_id) {
        //         abort(403, 'You don\'t have permission to open this file.');
        //     }
        // }

        view()->share([
            'application' => $application,
            'payment' => $payment,
        ]);

        $pdf = PDF::loadView('frontend.intimation.vouchers.hbl');
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('HBL-INTIMATION-' . $application->application_token_no . '.pdf', array("Attachment" => false));
    }

    public function notes(Request $request)
    {
        $application = Application::where('id', $request->application_id)->first();

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
            'note' => $request->notes,
            'admin_id' => Auth::guard('admin')->user()->id,
        ]);

        createActivity($application, 'Application');

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function reportPdf(Request $request)
    {
        $application_type = 6;
        $query = Application::select('*')->where('is_accepted', 1);

        if (!empty($request->report_application_type)) {
            $query->where('application_type', $application_type);
        }

        if (!empty($request->report_application_date)) {
            if ($request->get('report_application_date') == '1') {
                $query->whereDate('created_at', Carbon::today());
            }
            if ($request->get('report_application_date') == '2') {
                $query->whereDate('created_at', Carbon::yesterday());
            }
            if ($request->get('report_application_date') == '3') {
                $date = Carbon::now()->subDays(7);
                $query->where('created_at', '>=', $date);
            }
            if ($request->get('report_application_date') == '4') {
                $date = Carbon::now()->subDays(30);
                $query->where('created_at', '>=', $date);
            }
            if ($request->report_application_date == 5) {
                if ($request->get('report_application_date_range')) {
                    $dateRange = explode(' - ', $request->report_application_date_range);
                    $from = date("Y-m-d", strtotime($dateRange[0]));
                    $to = date("Y-m-d", strtotime($dateRange[1]));
                    $query->whereBetween('created_at', [$from, $to]);
                }
            }
        }

        $applications = $query->get();

        view()->share([
            'applications' => $applications,
            'application_type' => $application_type,
        ]);

        $pdf = PDF::loadView('admin.reports.pdf');
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('Reports-' . Carbon::now() . '.pdf', array("Attachment" => false));
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

        $enr_fee_paid_sum = Payment::where('application_id', $application->id)
            ->where('payment_status', 1)
            ->sum('enr_fee_pbc');

        $enr_fee_paid_sum = $enr_fee_paid_sum ?: 0;

        $degree_fee_paid_sum = Payment::where('application_id', $application->id)
            ->where('payment_status', 1)
            ->sum('degree_fee');

        $degree_fee_paid_sum = $degree_fee_paid_sum ?: 0;

        $data = [
            'application_id' => $application->id,
            'user_id' => $application->user_id,
            'application_type' => 6,
            'application_status' => 7,
            'payment_status' => 0,
            'payment_type' => 0,
            'amount' =>  $totalAmount - $paidAmount,
            'degree_fee' => $get_vch_amount['degree_fee'] - $degree_fee_paid_sum,
            'enr_fee_pbc' => $get_vch_amount['enr_fee_pbc'] - $enr_fee_paid_sum,
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

    public function deletePayment(Request $request)
    {
        $application = Application::find($request->application_id);
        $payment = Payment::find($request->payment_id);
        $payment->delete();
        $this->intimationPaymentVoucher($request, $application);
        return redirect()->route('intimations.show', $request->application_id);
    }

    public function exportFormB(Request $request)
    {
        $application = Application::find($request->application_id);

        if ($application == null) {
            return back()->with('error', 'No Record found');
        } elseif (!$application->is_intimation_completed) {
            return back()->with('error', 'Not eligible for the certificate');
        }


        view()->share([
            'application' => $application,
        ]);

        $pdf = PDF::loadView('pdf.lower-court.form-B');
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream('Certificate-of-training-' . Carbon::now() . '.pdf', array("Attachment" => false));
    }

    public function acctDeptPaymentStatus(Request $request)
    {
        Application::find($request->application_id);
        $payment = Payment::find($request->payment_id);
        $payment->update(['acct_dept_payment_status' => $request->status]);
        createActivity($payment, 'Payment');
    }

    public function objections(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        if ($request->has('objections') && !empty($request->objections)) {
            $application->update(['objections' => json_encode($request->objections)]);
        } else {
            $application->update(['objections' => NULL]);
        }

        createActivity($application, 'Application');
    }

    public function updateAcademicRecord(Request $request)
    {
        $education = LawyerEducation::find($request->education_id);

        $rules = [
            'university_id' => [
                'nullable',
                'integer',
                Rule::requiredIf(
                    $education->qualification == '4' ||
                        $education->qualification == '5' ||
                        $education->qualification == '6' ||
                        $education->qualification == '7' ||
                        $education->qualification == '8'
                )
            ],

            'institute' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(
                    $education->qualification == '1' ||
                        $education->qualification == '2' ||
                        $education->qualification == '3'
                )
            ],

            'total_marks' => 'required|numeric',
            'obtained_marks' => 'required|numeric|lte:total_marks',
            'roll_no' => 'required|string',
            'passing_year' => 'required|integer',
            // 'certificate_url' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $education->update([
            'university_id' => $request->input('university_id'),
            'institute' => $request->input('institute'),
            "total_marks" => $request->total_marks,
            "obtained_marks" => $request->obtained_marks,
            "passing_year" => $request->passing_year,
            "roll_no" => $request->roll_no,
        ]);

        uploadEducationalCertificate($request, $education->id, $education->application_id);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function updateSeniorLawyer(Request $request)
    {
        $application = Application::find($request->application_id);
        $admin = Auth::guard('admin')->user();
        $bars = Bar::select('id', 'name')->orderBy('name', 'asc')->get();

        $rules = [
            'srl_name' => 'required|max:255',
            'srl_bar_name' => [Rule::requiredIf($admin->is_super == 1), 'max:255'],
            'srl_office_address' => 'required|max:255',
            'srl_enr_date' => 'required|before:10 years ago',
            'srl_mobile_no' => 'required|max:255',
            'srl_cnic_no' => 'required|min:15',
        ];

        if (Auth::guard('admin')->user()->hasPermission('intimation-srl-joining-date')) {
            $current_date = Carbon::now()->format('d-m-Y');
            $prev_month_date = date("Y-m-d", strtotime('-1 month', strtotime($current_date)));
            if (!Auth::guard('admin')->user()->hasPermission('intimation-srl-joining-date-validation')) {
                $rules += [
                    'srl_joining_date' => 'required|before_or_equal:today|after_or_equal:' . $prev_month_date,
                ];
            }
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
            'srl_bar_name' => $request->has('srl_bar_name') ? $request->input('srl_bar_name') : $admin->bar_id,
            'srl_office_address' => $request->input('srl_office_address'),
            'srl_enr_date' => $request->input('srl_enr_date'),
            'srl_mobile_no' => $request->input('srl_mobile_no'),
            'srl_cnic_no' => $request->input('srl_cnic_no'),
        ];

        $application->update($data);
        if (Auth::guard('admin')->user()->hasPermission('intimation-srl-joining-date')) {
            $application->update(['srl_joining_date' => $request->input('srl_joining_date')]);
        }

        $response_view = view('admin.intimations.partials.senior-lawyer-section', compact('application', 'bars'))->render();

        return response()->json([
            'status' => 1,
            'message' => 'success',
            'response_view' => $response_view,
        ]);
    }

    public function permanentDelete($id)
    {
        $application = Application::findOrFail($id);
        LawyerAddress::where('application_id', $application->id)->delete();
        LawyerEducation::where('application_id', $application->id)->delete();
        LawyerUpload::where('application_id', $application->id)->delete();
        Note::where('application_id', $application->id)->delete();
        PoliceVerification::where('application_id', $application->id)->delete();
        PrintCertificate::where('application_id', $application->id)->delete();
        PrintSecureCard::where('application_id', $application->id)->delete();
        Payment::where('application_id', $application->id)->delete();
        $application->delete();

        return response()->json(['status' => 1, 'message' => 'success',]);
    }
}
