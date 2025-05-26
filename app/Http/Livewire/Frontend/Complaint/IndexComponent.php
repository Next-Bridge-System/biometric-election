<?php

namespace App\Http\Livewire\Frontend\Complaint;

use App\Complaint;
use Livewire\Component;

class IndexComponent extends Component
{
    public $filters = [
        'applicant_cnic' => "",
    ];

    public function render()
    {
        $query = Complaint::query();

        $query->where('applicant_cnic', $this->filters['applicant_cnic']);

        $records = $query->orderBy('id', 'desc')->get();

        return view('livewire.frontend.complaint.index-component', [
            'records' => $records
        ]);
    }
}
