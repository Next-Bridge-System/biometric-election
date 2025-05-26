<?php

namespace App\Http\Livewire\Admin\Complaint;

use App\Complaint;
use App\ComplaintStatus;
use App\ComplaintType;
use Carbon\Carbon;
use Livewire\Component;

class IndexComponent extends Component
{
    public $complaint_types = [], $complaint_statuses = [];
    public $selected_row_id, $rcpt_no, $rcpt_date;

    public $filters = [
        'complaint_id' => "",
        'complaint_status' => "",
        'complainant_name' => "",
        'complainant_cnic' => "",
    ];

    
    public function mount()
    {
        $this->complaint_statuses = ComplaintStatus::get();
        $this->complaint_types = ComplaintType::get();
    }

    public function render()
    {
        $query = Complaint::query();

        if ($this->filters['complaint_id']) {
            $query->where('id', $this->filters['complaint_id']);
        }

        if ($this->filters['complaint_status']) {
            $query->where('complaint_status_id', $this->filters['complaint_status']);
        }

        if ($this->filters['complainant_name']) {
            $query->where('complainant_name', $this->filters['complainant_name']);
        }

        if ($this->filters['complainant_cnic']) {
            $query->where('complainant_cnic', $this->filters['complainant_cnic']);
        }
        
        $records = $query->orderBy('id', 'desc')->paginate(10);

        return view('livewire.admin.complaint.index-component', [
            'records' => $records
        ]);
    }

    public function openRcpt($id)
    {
        $this->selected_row_id = $id;
    }

    public function updateRcpt($id)
    {
        $rcpt_date = Carbon::parse(Carbon::now())->format('Y-m-d');
        $rcpt = Complaint::select('rcpt_no', 'rcpt_date')->orderBy('rcpt_no', 'desc')->whereYear('rcpt_date', Carbon::parse($rcpt_date)->format('Y'))->first();

        if ($rcpt == null) {
            $rcpt_count = 1;
        } else {
            $rcpt_count = $rcpt->rcpt_no  + 1;
        }

        $intimation = Complaint::updateOrCreate(['id' =>  $id], [
            'rcpt_no' => sprintf("%02d", $rcpt_count),
            'rcpt_date' => $rcpt_date,
        ]);

        $this->rcpt_no = $intimation->rcpt_no;
        $this->rcpt_date = getDateFormat($intimation->rcpt_date);
    }

    public function close()
    {
        $this->selected_row_id = null;
        $this->rcpt_no = null;
        $this->rcpt_date = null;

        $this->resetValidation();
    }
}
