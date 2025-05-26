<?php

namespace App\Http\Livewire\Frontend\LawyerRequests;

use App\LawyerRequest;
use App\Payment;
use Livewire\Component;
use Livewire\WithFileUploads;

class ShowComponent extends Component
{
    use WithFileUploads;

    public
        $lawyer_request_id,
        $lawyer_request, $payment,
        $voucher_file, $transaction_id, $paid_date;

    public function render()
    {
        $this->lawyer_request = LawyerRequest::with('lawyer_request_category', 'lawyer_request_sub_category')->find($this->lawyer_request_id);
        $this->payment = Payment::where('lawyer_request_id', $this->lawyer_request->id)->first();

        return view('livewire.frontend.lawyer-requests.show-component', [
            'lawyer_request' => $this->lawyer_request
        ]);
    }

    public function upload_voucher()
    {
        $this->validate([
            'voucher_file' => 'image|max:2000',
            'transaction_id' => 'required',
            'paid_date' => 'required',
        ]);

        $this->payment->update([
            'voucher_file' => $this->voucher_file->store('lawyer-requests-vouchers'),
            'transaction_id' => $this->transaction_id,
            'paid_date' => $this->paid_date,
        ]);

        $this->lawyer_request->update([
            'voucher_file' => 'attached'
        ]);
    }
}
