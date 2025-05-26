<?php

namespace App\Http\Controllers;

use App\Imports\PaymentImport;
use App\Payment;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $payment = Payment::select('*');
            $admin = Auth::guard('admin')->user();

            return Datatables::of($payment)
                ->addIndexColumn()
                ->addColumn('application_id', function ($payment) {
                    return $payment->application_id + 1000;
                })
                ->addColumn('user_id', function ($payment) {
                    return getLawyerName($payment->application_id) ?? '-';
                })
                ->addColumn('admin_id', function ($payment) {
                    if ($payment->admin_id == NULL && $payment->payment_status == 1) {
                        $res = $payment->bank_name;
                    } else {
                        $res = getAdminName($payment->admin_id);
                    }
                    return $res;
                })
                ->addColumn('application_type', function ($payment) {
                    return getApplicationType($payment->application_id) ?? '-';
                })
                ->addColumn('payment_status', function ($payment) {
                    $res = '<span class="m-1 badge badge-' . getManagePaymentStatus($payment->id)['badge'] . '">' . getManagePaymentStatus($payment->id)['name'] . '</span>' . '<span class="m-1 badge badge-' . getAcctDeptStatus($payment->id)['badge'] . '">' . getAcctDeptStatus($payment->id)['name'] . '</span>';

                    return $res;
                })
                ->addColumn('payment_type', function ($payment) {
                    if ($payment->payment_status == 1) {
                        if ($payment->payment_type == 1) {
                            return '<span class="badge badge-primary">ONLINE</span>';
                        } else {
                            return '<span class="badge badge-primary">OPERATOR</span>';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('approved_by', function ($payment) {
                    return isset($payment->approved_by) && $payment->approved_by != NULL ? getAdminName($payment->approved_by) : '-';
                })
                ->addColumn('paid_date', function ($payment) {
                    return $payment->paid_date ?? '-';
                })
                ->addColumn('transaction_id', function ($payment) {
                    return $payment->transaction_id ?? '-';
                })
                ->rawColumns(['user_id', 'payment_status', 'payment_type', 'approved_by'])
                ->setRowClass(function ($payment) {
                    if ($payment->payment_status == 1 && $payment->acct_dept_payment_status == 1) {
                        $class = 'bg-light-green';
                    } else if ($payment->payment_status == 1 || $payment->acct_dept_payment_status == 1) {
                        $class = 'bg-light-yellow';
                    } else {
                        $class = 'bg-light-red';
                    }
                    return $class;
                })
                ->make(true);
        }

        return view('admin.payments.index');
    }

    public function import()
    {
        Excel::import(new PaymentImport(), request()->file('payment_excel_import_file'));
        return response()->json(['status' => 1, 'message' => 'success']);
    }
}
