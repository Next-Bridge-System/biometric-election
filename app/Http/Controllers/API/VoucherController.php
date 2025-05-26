<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\LawyerRequest;
use Illuminate\Http\Request;
use App\Payment;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class VoucherController extends Controller
{
    /**
     *****************************************************************************
     ******* INTIMATION/LOWER COURT/LAWYER REQUEST/GENERAL ***********************
     *****************************************************************************
     */

    public function payVoucher(Request $request)
    {
        Validator::make($request->all(), [
            'p_VoucherId' => 'required',
            'p_TransactionId' => 'required',
        ]);

        $payment = Payment::where('voucher_no', $request->p_VoucherId)->first();

        if ($payment == NULL) {
            return response()->json([
                'Return Value' => 'INCORRECT_CONSUMER_NO'
            ]);
        }

        if ($payment->payment_status == 1) {
            return response()->json([
                'Return Value' => 'ALREADY_PAID'
            ]);
        }

        $payment->update([
            'transaction_id' => $request->p_TransactionId,
            'payment_status' => 1, // Paid
            'payment_type' => 1, // Online
            'paid_date' => Carbon::now(), // Paid Date
            'online_banking' => TRUE, // Paid Date
        ]);

        // $application = Application::where('id', $payment->application_id)->first();
        // $intimation_date = getIntimationStartDate($application)['intimation_date'];
        // $application->update(['intimation_start_date' => $intimation_date]);

        if ($payment->lawyer_request_id) {
            $lawyer_request = LawyerRequest::find($payment->lawyer_request_id);
            if ($lawyer_request) {
                $lawyer_request->update([
                    'payment_status' => 'paid'
                ]);
            }
        }

        $response = [
            'Return Value' => 'SUCCESS',
        ];

        return response()->json($response, 200);
    }

    public function getVoucher(Request $request)
    {
        Validator::make($request->all(), [
            'p_VoucherId' => 'required',
        ]);

        $payment = Payment::where('voucher_no', $request->p_VoucherId)->first();

        if ($payment == NULL) {
            return response()->json([
                'Return Value' => 'INCORRECT_CONSUMER_NO'
            ]);
        }

        $paymentStatus = $payment->payment_status == 1 ? 'paid' : 'unpaid';

        $user = User::where('id', $payment->user_id)->first();
        $cnic = str_replace('-', '', $user->cnic_no);

        $response = [
            'Return Value' => 'SUCCESS',
            'p_CustomerName' => $user->name,
            'p_Amount' => $payment->amount,
            'p_Type' => $payment->voucher_name,
            'p_PlaceOfBusiness' => 'Punjab Bar Council',
            'p_cnic' => $cnic,
            'staus' => $paymentStatus
        ];

        return response()->json($response, 200);
    }
}
