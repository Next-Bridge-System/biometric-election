<?php

namespace App\Http\Livewire\Admin\Complaint;

use App\Complaint;
use App\ComplaintNotice;
use App\ComplaintStatus;
use App\ComplaintType;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;

class ShowComponent extends Component
{
    use WithFileUploads;

    public $complaint_id, $complaint, $payment;
    public $paid_date, $transaction_id, $voucher_file;

    public $selected_row_id, $rcpt_no, $rcpt_date;
    public $complaint_notice_desc, $complaint_notice_list = [];

    public $selected_row_notice_type = NULL;
    public $complaint_statuses = [], $complaint_status_id;
    public $complaint_types = [], $complaint_type_id = NULL;

    protected $listeners = [
        'set-complaint-notice' => 'getComplaintNotice',
    ];

    public function mount()
    {
        $this->complaint = Complaint::find($this->complaint_id);
        $this->payment = Payment::where('complaint_id', $this->complaint_id)->first();

        $this->complaint_statuses = ComplaintStatus::get();
        $this->complaint_types = ComplaintType::get();

        $this->complaint_status_id = $this->complaint->complaint_status_id;
        $this->complaint_type_id = $this->complaint->complaint_type_id;
    }

    public function render()
    {
        return view('livewire.admin.complaint.show-component');
    }

    public function savePayment()
    {
        $this->validate([
            'voucher_file' => 'required|image|max:2000',
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

        if ($this->voucher_file) {
            $this->payment->update([
                'voucher_file' => $this->voucher_file->storeAs('complaint-vouchers', $this->payment->id . '.png'),
            ]);

            if ($this->payment->voucher_file) {
                $this->complaint->update(['voucher_status' => 'attached']);
            }
        }

        $this->complaint->update(['payment_status' => 'paid']);

        $this->emit('hide_modal');
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

    public function openNotes($notice_type)
    {
        $this->selected_row_notice_type = $notice_type;
        $this->complaint_notice_list = ComplaintNotice::where('complaint_id', $this->complaint->id)->where('notice_type', $notice_type)->get();
    }

    public function getComplaintNotice($contents)
    {
        $this->complaint_notice_desc = $contents;
    }

    public function saveNotes()
    {
        $rules = [
            'complaint_notice_desc' => 'required',
        ];

        $data = [
            'complaint_notice_desc' => $this->complaint_notice_desc,
        ];

        $validator = Validator::make($data, $rules);

        ComplaintNotice::create([
            'complaint_id' => $this->complaint->id,
            'notice_type' => $this->selected_row_notice_type,
            'description' => $this->complaint_notice_desc,
            'admin_id' => Auth::guard('admin')->user()->id,
        ]);

        $this->alert('Notice Added!', 'The notice have been added successfully.');
        // $this->emit('hide_modal');
        $this->openNotes($this->selected_row_notice_type);

        $this->complaint_notice_desc = NULL;
    }

    public function alert($title, $text)
    {
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => $title,
            'text' =>  $text
        ]);
    }

    public function changeComplaintStatus()
    {
        $this->complaint->update(['complaint_status_id' => $this->complaint_status_id]);
        $this->mount();
    }

    public function changeComplaintType()
    {
        $this->complaint->update(['complaint_type_id' => $this->complaint_type_id]);
        $this->mount();
    }
}
