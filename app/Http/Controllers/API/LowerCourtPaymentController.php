<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class LowerCourtPaymentController extends Controller
{
    // private function getVoucherGeneralResponse(Request $request, $voucher_type)
    // {
    //     try {
    //         Validator::make($request->all(), [
    //             'p_VoucherId' => 'required',
    //         ]);

    //         $payment = Payment::query()
    //             ->where('amount', '>', 0)
    //             ->where('application_type', 1)
    //             ->where('lower_court_id', '<>', NULL)
    //             ->where('voucher_type', $voucher_type)
    //             ->where('voucher_no', $request->p_VoucherId)
    //             ->first();

    //         if ($payment == NULL) {
    //             return response()->json(['Return Value' => 'INCORRECT_CONSUMER_NO']);
    //         }

    //         $payment_status = $payment->payment_status == 1 ? 'paid' : 'unpaid';

    //         $response = [
    //             'Return Value' => 'SUCCESS',
    //             'p_CustomerName' => $payment->lowerCourtApplication->lawyer_name,
    //             'p_Amount' => $payment->amount,
    //             'p_Type' => 'Lower Court' . ' - ' . $payment->voucher_name,
    //             'p_PlaceOfBusiness' => 'Punjab Bar Council',
    //             'p_cnic' => $payment->lowerCourtApplication->cnic_no,
    //             'staus' => $payment_status
    //         ];

    //         return response()->json($response, 200);
    //     } catch (\Throwable $th) {
    //         return response()->json(['Return Value' => 'OPERATION_FAILED_TO_PERFORM'], 400);
    //     }
    // }

    // private function payVoucherGeneralResponse(Request $request, $voucher_type)
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'p_VoucherId' => 'required',
    //             'p_TransactionId' => 'required',
    //         ]);

    //         $payment = Payment::query()
    //             ->where('amount', '>', 0)
    //             ->where('application_type', 1)
    //             ->where('lower_court_id', '<>', NULL)
    //             ->where('voucher_type', $voucher_type)
    //             ->where('voucher_no', $request->p_VoucherId)
    //             ->first();

    //         if ($payment == NULL) {
    //             return response()->json([
    //                 'Return Value' => 'INCORRECT_CONSUMER_NO'
    //             ]);
    //         }

    //         if ($payment->payment_status == 1) {
    //             return response()->json([
    //                 'Return Value' => 'ALREADY_PAID'
    //             ]);
    //         }

    //         $payment->update([
    //             'transaction_id' => $request->p_TransactionId,
    //             'paid_date' => Carbon::parse(Carbon::now())->format('d-m-Y'),
    //             'online_banking' => 1,
    //             'payment_status' => 1,
    //             'payment_type' => 1,
    //         ]);


    //         $response = [
    //             'Return Value' => 'SUCCESS',
    //         ];

    //         return response()->json($response, 200);
    //     } catch (\Throwable $th) {
    //         return response()->json(['Return Value' => 'OPERATION_FAILED_TO_PERFORM'], 400);
    //     }
    // }

    // /**
    //  *****************************************************************************
    //  ************************** LOWER COURT GET VOUCHERS *************************
    //  *****************************************************************************
    //  */

    // public function getEnr(Request $request)
    // {
    //     return $this->getVoucherGeneralResponse($request, 2);
    // }

    // public function getGi(Request $request)
    // {
    //     return $this->getVoucherGeneralResponse($request, 3);
    // }

    // public function getBf(Request $request)
    // {
    //     return $this->getVoucherGeneralResponse($request, 4);
    // }

    // public function getPlj(Request $request)
    // {
    //     return $this->getVoucherGeneralResponse($request, 5);
    // }

    // /**
    //  *****************************************************************************
    //  ************************** LOWER COURT PAY VOUCHERS *************************
    //  *****************************************************************************
    //  */

    // public function payEnr(Request $request)
    // {
    //     return $this->payVoucherGeneralResponse($request, 2);
    // }

    // public function payGi(Request $request)
    // {
    //     return $this->payVoucherGeneralResponse($request, 3);
    // }

    // public function payBf(Request $request)
    // {
    //     return $this->payVoucherGeneralResponse($request, 4);
    // }

    // public function payPlj(Request $request)
    // {
    //     return $this->payVoucherGeneralResponse($request, 5);
    // }
}
