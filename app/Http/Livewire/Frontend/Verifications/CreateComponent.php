<?php

namespace App\Http\Livewire\Frontend\Verifications;

use App\Complaint;
use App\GcHighCourt;
use App\GcLowerCourt;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateComponent extends Component
{
    use WithFileUploads;

    public $lawyer_name, $father_name, $cnic_no, $lawyer_type, $reg_no_lc, $hcr_no_hc, $mobile_no;
    public $search_data = NULL;
    public $gc_lower_court_id, $gc_high_court_id;
    public $code, $otp, $otp_verified = false;
    public $submit_data = false;
    public $cnic_front_image, $cnic_back_image;

    public function mount()
    {
        $this->resets();
    }

    public function resets()
    {
        $this->search_data = null;
        $this->lawyer_name = null;
        $this->father_name = null;
        $this->mobile_no = null;
        $this->cnic_no = null;
        $this->gc_lower_court_id = null;
        $this->gc_high_court_id = null;
        $this->otp = null;
        $this->code = null;
    }

    public function render()
    {
        return view('livewire.frontend.verifications.create-component');
    }

    public function search()
    {
        if ($this->lawyer_type == 1) {
            $data = GcLowerCourt::where('reg_no_lc', '<>', NULL)->where('reg_no_lc', $this->reg_no_lc)->first();
            if ($data) {
                $this->gc_lower_court_id = $data->id;
            }
        } else {
            $data = GcHighCourt::where('hcr_no_hc', '<>', NULL)->where('hcr_no_hc', $this->hcr_no_hc)->first();
            if ($data) {
                $this->gc_high_court_id = $data->id;
            }
        }

        if ($data) {
            $this->lawyer_name = $data->lawyer_name;
            $this->father_name = $data->father_name;
            $this->search_data = true;
        } else {
            $this->search_data = false;
            $this->alert('warning', 'Not Found', 'No record found');
        }
    }

    public function changeLawyerType()
    {
        $this->resets();
    }

    public function store()
    {
        // $complaint = Complaint::where('reg_no_lc', $this->reg_no_lc)->orWhere('hcr_no_hc', $this->hcr_no_hc)->first();
        // if ($complaint) {
        //     $this->alert('warning', 'Already Submitted', 'The data have been already submitted.');
        // } else {
        $data = $this->validate([
            'lawyer_type' => 'required',
            'cnic_no' => 'required',
            'mobile_no' => 'required',
            'cnic_front_image' => 'image|max:500',
            'cnic_back_image' => 'image|max:500',
        ]);

        $this->otp = otp();

        $data = array(
            "phone" => '+92' . $this->mobile_no,
            "otp" => $this->otp,
            "event_id" => 79,
        );

        sendMessageAPI($data);
        // }
    }

    public function verify_otp()
    {
        // if ($this->code == $this->otp) {
        $complaint = Complaint::create([
            "type" => 'DATA VERIFICATION',
            "name" => $this->lawyer_name,
            "gc_lower_court_id" => $this->gc_lower_court_id,
            "gc_high_court_id" => $this->gc_high_court_id,
            "lawyer_type" => $this->lawyer_type,
            "cnic_no" => $this->cnic_no,
            "phone" => $this->mobile_no,
        ]);

        $complaint->addMedia($this->cnic_front_image)->toMediaCollection();
        $complaint->addMedia($this->cnic_back_image)->toMediaCollection();


        $this->submit_data = true;
        $this->alert('success', 'Submit Successfully', 'The data have been submitted successfully.');
        $this->resets();
        // } else {
        //     $this->alert('warning', 'Not Matched', 'The otp you have entered is not matched.');
        // }
    }

    public function alert($type, $title, $text)
    {
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => $type,
            'title' => $title,
            'text' =>  $text
        ]);
    }
}
