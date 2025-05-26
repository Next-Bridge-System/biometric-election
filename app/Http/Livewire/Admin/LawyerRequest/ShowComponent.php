<?php

namespace App\Http\Livewire\Admin\LawyerRequest;

use App\LawyerRequest;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ShowComponent extends Component
{
    use WithFileUploads;

    public
        $payment,
        $lawyer_request,
        $lawyer_request_id,
        $lawyer_request_file;

    public
        $paid_date,
        $transaction_id,
        $lawyer_request_voucher_file;

    protected $listeners = [
        'lawyer-request-reset' => 'resets',
    ];

    public function render()
    {
        $this->lawyer_request = LawyerRequest::with('lawyer_request_category', 'lawyer_request_sub_category')->find($this->lawyer_request_id);
        $this->payment = Payment::where('lawyer_request_id', $this->lawyer_request->id)->first();

        $this->transaction_id = $this->payment->transaction_id;
        $this->paid_date = $this->payment->paid_date;

        return view('livewire.admin.lawyer-request.show-component', [
            'lawyer_request' => $this->lawyer_request,
            'payment' => $this->payment,
        ]);
    }

    public function change_status($id, $approved)
    {
        $lawyer_request = LawyerRequest::find($id);
        $lawyer_request->update(['approved' => $approved]);
        $payment = Payment::where('lawyer_request_id', $lawyer_request->id)->first();
        $payment->update([
            'approved' => $approved
        ]);
    }

    public function upload_lawyer_request_file()
    {
        $this->validate([
            'lawyer_request_file' => 'required|mimes:pdf|max:2000',
        ]);

        $this->lawyer_request->addMedia($this->lawyer_request_file)->toMediaCollection('lawyer_request_file');
    }

    public function add_payment()
    {
        $this->validate([
            'lawyer_request_voucher_file' => 'nullable|image|max:2000',
            'transaction_id' => 'required',
            'paid_date' => 'required',
        ]);

        $this->payment->update([
            'payment_status' => 1,
            'bank_name' => "HBL",
            'transaction_id' => $this->transaction_id,
            'paid_date' => $this->paid_date,
            'admin_id' => Auth::guard('admin')->user()->id,
            'policy_id' => 0,
        ]);

        if ($this->lawyer_request_voucher_file) {
            $this->payment->update([
                'voucher_file' => $this->lawyer_request_voucher_file->store('lawyer-requests-vouchers'),
            ]);

            if ($this->payment->voucher_file) {
                $this->lawyer_request->update([
                    'voucher_file' => 'attached'
                ]);
            }
        }

        $this->lawyer_request->update([
            'payment_status' => 'paid'
        ]);

        $this->emit('hide_modal');
    }

    public function resets()
    {
        $record = $this->lawyer_request->getMedia('lawyer_request_file')->first();
        $record->delete();
    }

    public function resetPayment()
    {
        $this->payment->update([
            'payment_status' => 0,
            'transaction_id' => NULL,
            'paid_date' => NULL,
            'admin_id' => NULL,
            'voucher_file' => NULL,
        ]);


        $this->payment->update([
            'reset_by' => Auth::guard('admin')->user()->id,
            'reset_at' => Carbon::now(),
        ]);
    }
}
