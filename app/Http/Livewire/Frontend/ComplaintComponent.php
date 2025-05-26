<?php

namespace App\Http\Livewire\Frontend;

use App\Complaint;
use App\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class ComplaintComponent extends Component
{
    use WithFileUploads;

    protected $listeners = [
        'set-additional-info' => 'getAdditionalInfo',
    ];

    public $inputs = [
        'complainant_cnic' => "",
        'complainant_name' => "",
        'complainant_father' => "",
        'complainant_phone' => "",
        'defendant_name' => "",
        'defendant_father' => "",
        'defendant_cnic' => "",
        'additional_info' => "",
    ];

    public $complainant_profile_url, $complainant_cnic_front_url, $complainant_cnic_back_url, $complainant_affidavit_url, $complainant_application_url;

    public $form_step = 1;
    public $complaint, $payment;

    public $complainant_record_found = 0;
    public $defendant_record_found = 0;

    public $complainant_data = NULL;
    public $defendant_data = NULL;

    public $submit_btn = false;

    public function render()
    {
        return view('livewire.frontend.complaint-component');
    }

    public function submit()
    {
        $rules = [
            'inputs.complainant_cnic' => ['required', 'regex:/^\d{5}-\d{7}-\d{1}$/'],
            'inputs.complainant_name' => ['max:25', 'required'],
            'inputs.complainant_father' => ['max:25', 'required'],
            'inputs.complainant_phone' => ['max:25', 'required'],

            'inputs.defendant_cnic' => ['nullable', 'regex:/^\d{5}-\d{7}-\d{1}$/'],
            'inputs.defendant_name' => ['max:25', 'required'],
            'inputs.defendant_father' => ['max:25', 'nullable'],
            'inputs.defendant_phone' => 'nullable|max:25',

            'inputs.additional_info' => 'required|max:500',
        ];

        // if ($this->complainant_record_found == 2) {
        $rules += [
            'complainant_profile_url' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:500',],
            'complainant_cnic_front_url' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:500',],
            'complainant_cnic_back_url' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:500',],

            'complainant_affidavit_url' => ['required', 'mimes:jpeg,jpg,png', 'max:500',],
            'complainant_application_url' => ['nullable', 'mimes:pdf', 'max:3000',],
        ];
        // }

        $this->validate($rules, [
            'required' => 'This field is required.',
            'max' => 'The input may not be greater than :max characters.',
        ]);


        // if ($this->complainant_record_found == 0 || $this->defendant_record_found == 0) {
        //     abort(403);
        // }

        try {
            DB::beginTransaction();

            $this->complaint = Complaint::create($this->inputs);

            if ($this->complainant_profile_url) {
                $this->complaint->update(['complainant_profile_url' => $this->complainant_profile_url->storeAs('complaint-files', $this->complaint->id . '-1.png'),]);
            }
            if ($this->complainant_cnic_front_url) {
                $this->complaint->update(['complainant_cnic_front_url' => $this->complainant_cnic_front_url->storeAs('complaint-files', $this->complaint->id . '-2.png')]);
            }
            if ($this->complainant_cnic_back_url) {
                $this->complaint->update(['complainant_cnic_back_url' => $this->complainant_cnic_back_url->storeAs('complaint-files', $this->complaint->id . '-3.png')]);
            }
            if ($this->complainant_affidavit_url) {
                $this->complaint->update(['complainant_affidavit_url' => $this->complainant_affidavit_url->storeAs('complaint-files', $this->complaint->id . '-4.png')]);
            }
            if ($this->complainant_application_url) {
                $this->complaint->update(['complainant_application_url' => $this->complainant_application_url->storeAs('complaint-files', $this->complaint->id . '-5.pdf')]);
            }

            $this->payment = new Payment();
            $this->payment->complaint_id = $this->complaint->id;
            $this->payment->payment_module = "COMPLAINTS";
            $this->payment->voucher_name = "LAWYER COMPLAINTS";
            $this->payment->bank_name = "HBL";
            $this->payment->amount = 1500;
            $this->payment->save();

            $this->payment->voucher_no = get_complaint_voucher_no($this->payment->id);
            $this->payment->update();

            $this->form_step = 2;

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function searchRecord($type)
    {
        if ($type == 'complainant') {

            $this->validate([
                'inputs.complainant_cnic' => ['required', 'regex:/^\d{5}-\d{7}-\d{1}$/'],
            ], [
                'required' => 'The input field is required.',
                'regex' => 'The cnic format is invalid.',
            ]);

            $filter = [
                'cnic_no' => $this->inputs['complainant_cnic'],
                'clean_cnic_no' => preg_replace('/[^0-9]/', '', $this->inputs['complainant_cnic']),
            ];

            $records = generalSearchQuery($filter)->get()->toArray();

            if ($records && isset($records[1]['cnic']) && $records[1]['cnic'] != NULL) {
                $this->complainant_record_found = 1;
                $this->inputs['complainant_cnic'] = $records[1]['cnic'];
                $this->inputs['complainant_name'] = $records[1]['lawyer'];
                $this->inputs['complainant_father'] = $records[1]['father'];
                $this->inputs['complainant_phone'] = $records[1]['phone'];
            } else {
                $this->complainant_record_found = 2;
            }
        }

        if ($type == 'defendant') {

            $this->validate([
                'inputs.defendant_cnic' => ['required', 'regex:/^\d{5}-\d{7}-\d{1}$/'],
            ], [
                'required' => 'The input field is required.',
                'regex' => 'The cnic format is invalid.',
            ]);

            $filter = [
                'cnic_no' => $this->inputs['defendant_cnic'],
                'clean_cnic_no' => preg_replace('/[^0-9]/', '', $this->inputs['defendant_cnic']),
            ];

            $records = generalSearchQuery($filter)->get()->toArray();

            if ($records && isset($records[1]['cnic']) && $records[1]['cnic'] != NULL) {
                $this->defendant_record_found = 1;
                $this->inputs['defendant_cnic'] = $records[1]['cnic'];
                $this->inputs['defendant_name'] = $records[1]['lawyer'];
                $this->inputs['defendant_father'] = $records[1]['father'];
                $this->inputs['defendant_phone'] = $records[1]['phone'];
            } else {
                $this->defendant_record_found = 2;
            }

            $this->submit_btn = true;
        }
    }

    public function setDefendantRecord()
    {
        $this->defendant_record_found = 2;
        $this->resetValidation();
        $this->submit_btn = true;
    }

    public function getAdditionalInfo($contents)
    {
        $this->inputs['additional_info'] = $contents;
    }
}
