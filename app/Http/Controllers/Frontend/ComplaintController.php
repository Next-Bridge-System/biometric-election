<?php

namespace App\Http\Controllers\Frontend;

use App\Complaint;
use App\Http\Controllers\Controller;
use App\Payment;
use PDF;

class ComplaintController extends Controller
{
    public function index()
    {
        return view('frontend.lawyer-complaint.index');
    }

    public function create()
    {
        return view('frontend.lawyer-complaint.complaint');
    }

    public function voucher($payment_id)
    {
        $payment = Payment::where('id', $payment_id)->first();
        $complaint = Complaint::where('id', $payment->complaint_id)->first();

        view()->share([
            'payment' => $payment,
            'complaint' => $complaint,
        ]);

        $pdf = PDF::loadView('frontend.lawyer-complaint.complaint-voucher');
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('HBL-' . $payment->voucher_no . '.pdf', array("Attachment" => false));

        return view('frontend.lawyer-complaint.complaint-voucher');
    }
}
