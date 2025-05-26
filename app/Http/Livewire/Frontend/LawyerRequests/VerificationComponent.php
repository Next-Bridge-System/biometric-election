<?php

namespace App\Http\Livewire\Frontend\LawyerRequests;

use App\LawyerRequest;
use Livewire\Component;

class VerificationComponent extends Component
{
    public $cnic_no, $letter_id, $lawyer_request, $record_found;

    public function render()
    {
        return view('livewire.frontend.lawyer-requests.verification-component');
    }

    public function search()
    {
        $data = $this->validate([
            'letter_id' => 'required|integer',
            'cnic_no' => ['required', 'regex:/^\d{5}-\d{7}-\d$/'],
        ], [
            'cnic_no.regex' => 'The cnic no format is invalid. Please enter cnic with dashes e.g 12345-1234567-1',
        ]);

        $this->lawyer_request = null;
        $this->lawyer_request = LawyerRequest::where('cnic_no', $this->cnic_no)->where('id', $this->letter_id)->first();

        if ($this->lawyer_request) {
            $this->record_found = 1;
        } else {
            $this->record_found = 2;
        }
    }
}
