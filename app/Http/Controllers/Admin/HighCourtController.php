<?php

namespace App\Http\Controllers\Admin;

use App\AppStatus;
use App\GcLowerCourt;
use App\HighCourt;
use App\Http\Controllers\Controller;
use App\Imports\HighCourtImport;
use App\LawyerUpload;
use App\LowerCourt;
use App\Note;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use App\Payment;
use App\Policy;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\File;

class HighCourtController extends Controller
{
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
            $age = Carbon::parse($application->date_of_birth)->diff($age_cal_date)->format('%y.%m');

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
                    "admin_id" => Auth::guard('admin')->user()->id,
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

                if ($voucher_payment->amount == 0) {
                    $voucher_payment->update(['payment_status' => 1]);
                }
            }
        }
    }

    private function highCourtListing(Request $request)
    {
        if (Route::currentRouteName() == 'high-court.index') {
            $application = HighCourt::orderBy('id', 'desc')->where('is_final_submitted', 1);
        } else {
            $application = HighCourt::orderBy('id', 'desc')->where('is_final_submitted', 0);
        }

        $admin = auth()->guard('admin')->user();

        if ($admin->is_super == 0) {
            $application->where('voter_member_lc', $admin->bar_id);
        }

        return Datatables::of($application)
            ->addIndexColumn()
            ->addColumn('app_status', function ($application) {
                $status = appStatus($application->app_status, $application->app_type);
                return $status;
            })
            ->addColumn('created_by', function ($application) {
                $created_by = NULL;
                if ($application->is_frontend == 1 && $application->created_by == NULL) {
                    $created_by = $application->lawyer_name . '<br><span class="badge badge-success">Online</span>';
                } else if ($application->is_frontend == 0 && $application->created_by != NULL) {
                    $created_by = getAdminName($application->created_by) . '<br><span class="badge badge-info">Operator</span> ';
                } else {
                    $created_by = '-';
                }
                return $created_by;
            })
            ->addColumn('action', function ($application) {
                $btn = '<a href="' . route('high-court.show', $application->id) . '">
                    <span class="badge badge-primary"><i class="fas fa-list mr-1" aria-hidden="true"></i>Detail</span></a>';
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
                        $payments = Payment::where('payment_status', 1)->pluck('high_court_id')->toArray();
                        $instance->whereIn('id', $payments);
                    }
                    if ($request->get('payment_status') == 'unpaid') {
                        $payments = Payment::where('payment_status', 0)->pluck('high_court_id')->toArray();
                        $instance->whereIn('id', $payments);
                    }
                }

                if ($request->get('payment_type')) {
                    if ($request->get('payment_type') == 'online') {
                        $payments = Payment::where('online_banking', 1)->pluck('high_court_id')->toArray();
                        $instance->whereIn('id', $payments);
                    }
                    if ($request->get('payment_type') == 'operator') {
                        $payments = Payment::where('online_banking', 0)->pluck('high_court_id')->toArray();
                        $instance->whereIn('id', $payments);
                    }
                }

                if (!empty($request->get('search'))) {
                    $instance->where(function ($query) use ($request) {
                        $search = $request->get('search');
                        $query->where('id', "%$search%")
                            ->orWhere('user_id', "%$search%");
                    });
                }
            })
            // ->rawColumns(['name', 'application_type', 'application_status', 'action', 'active_mobile_no', 'payment_status', 'created_by'])
            ->rawColumns(['action', 'app_status', 'created_by', 'payment_status'])
            ->make(true);
    }

    public function index($slug)
    {
        return view('admin.high-court.index', compact('slug'));
    }

    public function partial(Request $request)
    {
        if ($request->ajax()) {
            return $this->highCourtListing($request);
        }

        return view('admin.high-court.partial');
    }

    public function show(Request $request, $id)
    {
        $application = HighCourt::find($id);
        $payments = $application->payments->where('is_voucher_print', 1)->groupBy('voucher_type');
        $app_status = AppStatus::where('status', 1)->get();

        $request->session()->forget('high_court_application');
        if (empty($request->session()->get('high_court_application'))) {
            $application->fill(['id' => $application->id]);
            $request->session()->put('high_court_application', $application->id);
        }

        return view('admin.high-court.show', compact('application', 'payments', 'app_status'));
    }

    public function payment(Request $request, $id, $voucherType)
    {
        $lc = HighCourt::find($id);
        $payment = Payment::where('high_court_id', $lc->id)->where('voucher_type', $voucherType)->where('payment_status', 0)->first();
        $now = Carbon::now();
        abort_if($payment == NULL, 403);

        $request->session()->forget('high_court_payment');
        if (empty($request->session()->get('high_court_payment'))) {
            $payment->fill(['high_court_id' => $lc->id]);
            $request->session()->put('high_court_payment', $payment->id);
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
                ->where('application_type', 2)
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
                        'high_court_id' => $lc->id,
                        'user_id' => $lc->user_id,
                        'payment_status' => 0,
                        'payment_type' => 0,
                        'amount' => getHcVchAmount($lc->id, $payment->voucher_type) - $request->amount,
                        'bank_name' => $request->bank_name,
                        'admin_id' => Auth::guard('admin')->user()->id,
                        'application_type' => 1,
                        'application_status' => $lc->app_status,
                        'voucher_name' => $payment->voucher_name,
                        'voucher_type' => $payment->voucher_type,
                    ];
                    $newPayment = Payment::create($data);
                    $newPayment->update(['voucher_no' => getHcVoucherNo($newPayment->id)]);
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
                $updateSubSection = Payment::where('high_court_id', $payment->high_court_id)
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

        return view('admin.high-court.payment', compact('lc', 'payment'));
    }

    public function uploadPaymentVoucher(Request $request)
    {
        $id = $request->session()->get('high_court_payment');
        $payment = Payment::find($id);
        $directory = 'lc-vouchers/' . $payment->high_court_id;
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
        $id = $request->session()->get('high_court_payment');
        $payment = Payment::find($id);
        $payment->update(['voucher_file' => NULL]);
        return redirect()->back();
    }

    public function resetPayments(Request $request)
    {
        $application = HighCourt::find($request->high_court_id);
        $this->highCourtPayment($application);
        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function deletePayment(Request $request)
    {
        $application = HighCourt::find($request->high_court_id);
        $payment = Payment::find($request->payment_id);
        $payment->delete();

        $this->highCourtPayment($application);

        return redirect()->route('high-court.show', $request->high_court_id);
    }

    public function status(Request $request, $id)
    {
        $application = HighCourt::find($id);

        if ($request->application_status == 1) {
            if (getHcPaymentStatus($application->id)['name'] == 'Unpaid') {
                return response()->json([
                    'title' => 'Payment Verification!',
                    'message' => 'You cannot not change status of this lower court application until the payment is pending or unpaid',
                ], 400);
            }
        }

        $application->update([
            'app_status' => $request->application_status,
        ]);

        if ($application->app_status == 1) {

            $gc_lc = GcLowerCourt::where('user_id', $application->user_id)->first();
            if ($gc_lc) {
                if ($gc_lc->app_type == 7) {
                    $gc_lc->update(['app_type' => 4]);
                }
            }

            $lc = LowerCourt::where('user_id', $application->user_id)->first();
            if ($lc) {
                if ($lc->app_type == 7) {
                    $lc->update(['app_type' => 4]);
                }
            }

            if (isset($application->user)) {
                $application->user->update(['gc_requested_at' => setDateFormat(Carbon::now())]);
            }
        }

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function updateStatus(Request $request)
    {
        $application = HighCourt::find($request->high_court_id);

        if (getHcPaymentStatus($application->id)['name'] == 'Unpaid') {
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

        if ($application->app_status == 1) {

            $gc_lc = GcLowerCourt::where('user_id', $application->user_id)->first();
            if ($gc_lc) {
                if ($gc_lc->app_type == 7) {
                    $gc_lc->update(['app_type' => 4]);
                }
            }

            $lc = LowerCourt::where('user_id', $application->user_id)->first();
            if ($lc) {
                if ($lc->app_type == 7) {
                    $lc->update(['app_type' => 4]);
                }
            }

            if (isset($application->user)) {
                $application->user->update(['gc_requested_at' => setDateFormat(Carbon::now())]);
            }
        }

        Note::create([
            'application_id' => $application->id,
            'application_type' => 'HC',
            'note' => $request->app_status_reason,
            'admin_id' => Auth::guard('admin')->user()->id,
        ]);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function permanentDelete($id)
    {
        try {
            DB::beginTransaction();

            $hc = HighCourt::findOrFail($id);
            $user = User::where('id', $hc->user_id)->first();

            $application_delete = false;

            if ($user->sr_no_lc) {
                $gc_lc = GcLowerCourt::where('sr_no_lc', $user->sr_no_lc)->first();
                if ($gc_lc) {
                    $gc_lc->update(['app_type' => 5]);
                    $user->update(['register_as' => 'gc_lc']);
                    $application_delete = true;
                }
            } else {
                $lc = LowerCourt::where('user_id', $user->id)->first();
                if ($lc) {
                    $lc->update(['app_type' => 5]);
                    $user->update(['register_as' => 'lc']);
                    $application_delete = true;
                }
            }

            if ($application_delete == true) {
                Payment::where('high_court_id', $hc->id)->delete();
                $hc->delete();
            }

            DB::commit();

            return response()->json(['status' => 1, 'message' => 'success',]);
        } catch (\Throwable $th) {
            DB::rollBack();

            throw $th;
        }
    }

    public function notes(Request $request)
    {
        $application = HighCourt::where('id', $request->high_court_id)->first();

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
            'application_type' => 'HC',
            'note' => $request->notes,
            'admin_id' => Auth::guard('admin')->user()->id,
        ]);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function objections(Request $request, $id)
    {
        $application = HighCourt::findOrFail($id);
        if ($request->has('objections') && !empty($request->objections)) {
            $application->update(['objections' => json_encode($request->objections)]);
        } else {
            $application->update(['objections' => NULL]);
        }
    }

    public function rcptDate(Request $request)
    {
        if ($request->has('rcpt_date') && !empty($request->rcpt_date)) {
            $rcpt_date = Carbon::parse($request->rcpt_date)->format('Y-m-d');
        } else {
            $rcpt_date = Carbon::parse(Carbon::now())->format('Y-m-d');
        }

        $rcpt = HighCourt::select('rcpt_no_hc', 'rcpt_date')->orderBy('rcpt_no_hc', 'desc')->whereYear('rcpt_date', Carbon::parse($rcpt_date)->format('Y'))->first();

        if ($rcpt == null) {
            $rcpt_count = 1;
        } else {
            $rcpt_count = $rcpt->rcpt_no_hc  + 1;
        }

        HighCourt::updateOrCreate(['id' =>  $request->high_court_id], [
            'rcpt_no_hc' => sprintf("%02d", $rcpt_count),
            'rcpt_date' => $rcpt_date,
        ]);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function updateHcDate(Request $request)
    {
        $hc = HighCourt::find($request->high_court_id);
        $hc->update([
            'enr_date_hc' => setDateFormat($request->hc_date)
        ]);

        if (isset($hc->user)) {
            $hc->user->update(['gc_requested_at' => setDateFormat(Carbon::now())]);
        }

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function updateLcDate(Request $request)
    {
        $hc = HighCourt::find($request->high_court_id);
        $hc->update([
            'enr_date_lc' => setDateFormat($request->enr_date_lc)
        ]);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function updateLcExpDate(Request $request)
    {
        $rules = [
            'lc_exp_date' => ['required', function ($attribute, $value, $fail) {
                $endOfYear = Carbon::parse('December 31')->endOfDay();

                if (Carbon::parse($value)->greaterThan($endOfYear)) {
                    $fail("The $attribute must be before or equal to December 31st of the current year.");
                }
            }],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $application = HighCourt::find($request->high_court_id);
        $application->update([
            'lc_exp_date' => setDateFormat($request->lc_exp_date)
        ]);

        $this->highCourtPayment($application);

        return response()->json(['status' => 1, 'message' => 'success']);
    }

    public function updateNumber(Request $request)
    {
        try {
            $rules = [
                'type' => 'in:license_no_hc,hcr_no_hc,bf_no_hc,lc_lic,lc_ledger,bf_ledger_no',
                'hc_number' => ['required', 'max:25'],
            ];

            $messages = [
                'hc_number.required' => 'The ' . $request->type . ' field is required.',
                'hc_number.max' => 'The ' . $request->type . ' may not be greater than 25 characters.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $lc = HighCourt::updateOrCreate(['id' => $request->application_id], [
                $request->type => $request->hc_number
            ]);

            return response()->json(['status' => 1, 'message' => 'success']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 0, 'message' => 'There might be configuration error. Try again later, or contact the support.'], 401);
        }
    }

    public function uploadFile(Request $request, $slug)
    {
        if (!permission('edit-high-court')) {
            abort(403);
        }

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
        if (!permission('edit-high-court')) {
            abort(403);
        }

        $id = $request->session()->get('high_court_application');
        $model = HighCourt::find($id);
        $destroy = LawyerUpload::where('high_court_id', $model->id)->first();
        $destroy->update([$slug => NULL]);
        return redirect()->back();
    }
}
