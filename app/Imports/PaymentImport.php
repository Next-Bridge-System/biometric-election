<?php

namespace App\Imports;

use App\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PaymentImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $payment = Payment::query()
            ->where('voucher_no', $row['slip_no'])
            ->orWhere('transaction_id', $row['slip_no'])
            ->where('paid_date', $row['paid_date'])
            ->where('amount', $row['amount'])
            ->first();

        if (isset($payment)) {
            $payment->update([
                'acct_dept_payment_status' => TRUE,
                'approved_by' => Auth::guard('admin')->user()->id,
                'approved_at' => Carbon::now(),
            ]);
        }
    }
}
