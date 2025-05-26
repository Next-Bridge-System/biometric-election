<?php

namespace App\Http\Livewire\Frontend;

use App\GcHighCourt;
use App\GcLowerCourt;
use App\HighCourt;
use App\LowerCourt;
use App\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class LahoreBarComponent extends Component
{
    public $cnic_no, $phone_no, $record_found;
    public $lawyer, $user, $type;

    public function mount()
    {
        if ($this->cnic_no || $this->phone_no) {
            $this->search();
        }
    }

    public function render()
    {
        return view('livewire.frontend.lahore-bar-component');
    }

    public function search()
    {
        $data = $this->validate([
            'cnic_no' => ['nullable', 'regex:/^\d{5}-\d{7}-\d$/'],
            'phone_no' => ['nullable', 'regex:/(03)[0-9]{9}/'],
        ], [
            'cnic_no.regex' => 'The cnic no format is invalid. Please enter cnic with dashes e.g 12345-1234567-1',
        ]);

        $phone_no = $this->phone_no;
        if (substr($phone_no, 0, 1) == '0') {
            $phone_no = substr($phone_no, 1);
        }

        $this->lawyer = NULL;
        $this->user = User::query()
            ->when($this->cnic_no, function ($qry) {
                $qry->where('cnic_no', $this->cnic_no);
            })
            ->when($this->phone_no, function ($qry) use ($phone_no) {
                $qry->where('phone', $phone_no);
            })
            ->first();

        if ($this->user) {
            $this->type = $this->user->register_as;

            if ($this->type == 'lc') {
                $this->lawyer = LowerCourt::query()
                    ->select(
                        'u.name as lawyer',
                        'u.cnic_no as cnic',
                        'lower_courts.father_name as father_husband',
                        'app_status.value as app_status',
                        'app_type.value as app_type',
                        DB::raw('CASE WHEN u.register_as = "lc" THEN "Lower Court" END as lawyer_type'),
                        'lawyer_uploads.profile_image' // profile image
                    )
                    ->join('users as u', 'u.id', '=', 'lower_courts.user_id')
                    ->join('app_statuses as app_status', 'app_status.id', '=', 'lower_courts.app_status')
                    ->join('app_types as app_type', 'app_type.id', '=', 'lower_courts.app_type')
                    ->join('lawyer_uploads', 'lawyer_uploads.lower_court_id', '=', 'lower_courts.id')
                    ->where('lower_courts.user_id', $this->user->id)
                    ->where('lower_courts.voter_member_lc', 6) // Lahore Bar
                    ->whereNotIn('lower_courts.app_status', [5, 6])
                    ->first();
            }

            if ($this->type == 'gc_lc') {
                $this->lawyer = GcLowerCourt::query()
                    ->select(
                        'u.name as lawyer',
                        'u.cnic_no as cnic',
                        'gc_lower_courts.father_name as father_husband',
                        'app_status.value as app_status',
                        'app_type.value as app_type',
                        DB::raw('CASE WHEN u.register_as = "gc_lc" THEN "Lower Court" END as lawyer_type'),
                    )
                    ->join('users as u', 'u.id', '=', 'gc_lower_courts.user_id')
                    ->join('app_statuses as app_status', 'app_status.id', '=', 'gc_lower_courts.app_status')
                    ->join('app_types as app_type', 'app_type.id', '=', 'gc_lower_courts.app_type')
                    ->where('gc_lower_courts.user_id', $this->user->id)
                    ->where('gc_lower_courts.voter_member_lc', 6) // Lahore Bar
                    ->whereNotIn('gc_lower_courts.app_status', [5, 6])
                    ->first();;
            }

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
                    ->where('high_courts.voter_member_hc', 6) // Lahore Bar
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
                    ->where('gc_high_courts.voter_member_hc', 6) // Lahore Bar
                    ->whereNotIn('gc_high_courts.app_status', [5, 6])
                    ->first();;
            }
        }

        if ($this->lawyer) {
            $this->record_found = 1;
        } else {
            $this->record_found = 2;
        }
    }
}
