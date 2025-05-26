<?php

namespace App\Http\Livewire\Frontend;

use App\GcHighCourt;
use App\HighCourt;
use App\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class HighcourtLawyerComponent extends Component
{
    public $cnic_no = "", $record_found;
    public $lawyer, $user, $type;

    public function mount()
    {
        $this->cnic_no = "";

        if ($this->cnic_no) {
            $this->search();
        }
    }

    public function render()
    {
        return view('livewire.frontend.highcourt-lawyer-component');
    }

    public function search()
    {
        $data = $this->validate([
            'cnic_no' => ['nullable', 'regex:/^\d{5}-\d{7}-\d$/']
        ], [
            'cnic_no.regex' => 'The cnic no format is invalid. Please enter cnic with dashes e.g 12345-1234567-1',
        ]);

        // $phone_no = $this->phone_no;
        // if (substr($phone_no, 0, 1) == '0') {
        //     $phone_no = substr($phone_no, 1);
        // }

        $this->lawyer = NULL;
        $this->user = User::query()
            ->when($this->cnic_no, function ($qry) {
                $qry->where('cnic_no', $this->cnic_no);
            })
            ->first();

        if ($this->user) {
            $this->type = $this->user->register_as;

            if ($this->type == 'hc') {
                $this->lawyer = HighCourt::query()
                    ->select(
                        'u.name as lawyer',
                        'u.cnic_no as cnic',
                        'u.father_name as father_husband',
                        'app_status.value as app_status',
                        'app_type.value as app_type',
                        DB::raw('CASE WHEN u.register_as = "hc" THEN "High Court" END as lawyer_type'),
                        'lawyer_uploads.profile_image' // profile image
                    )
                    ->join('users as u', 'u.id', '=', 'high_courts.user_id')
                    ->join('app_statuses as app_status', 'app_status.id', '=', 'high_courts.app_status')
                    ->join('app_types as app_type', 'app_type.id', '=', 'high_courts.app_type')
                    ->join('lawyer_uploads', 'lawyer_uploads.high_court_id', '=', 'high_courts.id')
                    ->where('high_courts.user_id', $this->user->id)
                    ->whereNotIn('high_courts.app_status', [5, 6])
                    ->first();;
            }

            if ($this->type == 'gc_hc') {
                $this->lawyer = GcHighCourt::query()
                    ->select(
                        'u.name as lawyer',
                        'u.cnic_no as cnic',
                        'gc_high_courts.father_name as father_husband',
                        'app_status.value as app_status',
                        'app_type.value as app_type',
                        DB::raw('CASE WHEN u.register_as = "gc_hc" THEN "High Court" END as lawyer_type'),
                    )
                    ->join('users as u', 'u.id', '=', 'gc_high_courts.user_id')
                    ->join('app_statuses as app_status', 'app_status.id', '=', 'gc_high_courts.app_status')
                    ->join('app_types as app_type', 'app_type.id', '=', 'gc_high_courts.app_type')
                    ->where('gc_high_courts.user_id', $this->user->id)
                    ->whereNotIn('gc_high_courts.app_status', [5, 6])
                    ->first();;
            }
        }

        if ($this->lawyer) {
            $this->record_found = 1;
        } else {
            $this->record_found = 2;
        }

        $this->cnic_no = "";
    }

    public function updatedCnicNo($value)
    {
        // dd($this->cnic_no);

        // if (strlen($value) == 15) {
        //     $this->search();
        // }

        if (preg_match('/\d{5}-\d{7}-\d/', $this->cnic_no, $matches)) {
            $this->cnic_no = $matches[0]; // Retain only the CNIC
            $this->search();
        } else {
            $this->cnic_no = ''; // Clear input if no valid CNIC is found
        }
    }
}
