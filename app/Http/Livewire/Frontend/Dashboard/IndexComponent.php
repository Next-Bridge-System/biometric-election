<?php

namespace App\Http\Livewire\Frontend\Dashboard;

use App\Application;
use App\GcHighCourt;
use App\GcLowerCourt;
use App\HighCourt;
use App\LowerCourt;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class IndexComponent extends Component
{
    use WithFileUploads;

    public $user;
    public $register_as;
    public $application_submit = false;
    public $gc_lawyer_type, $reg_no_lc, $hcr_no_hc, $enr_date_hc, $search_sr_no_hc, $gc_record_match;
    public $sr_no_hc, $sr_no_lc;
    public $gc_high_court, $gc_lower_court;
    public $profile_image, $cnic_front_image, $cnic_back_image, $license_front_image, $license_back_image;

    protected $listeners = [
        'reset-dashboard' => 'resetDashboard',
    ];

    public function mount()
    {
        $this->user = Auth::guard('frontend')->user();
        $this->register_as = $this->user->register_as;

        if ($this->user->intimation && $this->user->intimation->is_accepted == 1) {
            $this->application_submit = true;
        }

        if ($this->user->lc && $this->user->lc->is_final_submitted == 1) {
            $this->application_submit = true;
        }
    }

    public function render()
    {
        $gc_lower_court = GcLowerCourt::where('cnic_no', $this->user->cnic_no)->first();
        $gc_high_court = GcHighCourt::where('cnic_no', $this->user->cnic_no)->first();

        if ($gc_high_court) {
            $lawyer_type = 2;
        } else if ($gc_lower_court) {
            $lawyer_type = 1;
        } else {
            $lawyer_type = 0;
        }

        $lawyer_application_id = NULL;
        if ($this->user->register_as == 'lc') {
            $lawyer_application_id = LowerCourt::select('id')->where('user_id', $this->user->id)->where('app_status', 1)->first();
        } else if ($this->user->register_as == 'gc_lc') {
            $lawyer_application_id = GcLowerCourt::select('id')->where('user_id', $this->user->id)->where('app_status', 1)->first();
        } else if ($this->user->register_as == 'hc') {
            $lawyer_application_id = HighCourt::select('id')->where('user_id', $this->user->id)->where('app_status', 1)->first();
        } else if ($this->user->register_as == 'gc_hc') {
            $lawyer_application_id = GcHighCourt::select('id')->where('user_id', $this->user->id)->where('app_status', 1)->first();
        }

        return view('livewire.frontend.dashboard.index-component', [
            'lawyer_type' => $lawyer_type,
            'lawyer_application_id' => $lawyer_application_id,
        ]);
    }

    public function changeApplicationType()
    {
        checkCnicExist($this->user->cnic_no);

        $this->user->update(['register_as' => $this->register_as]);
    }

    public function search()
    {
        gcCheckCnicExist($this->user->cnic_no);

        if ($this->gc_lawyer_type == 2) {
            $this->validate([
                'hcr_no_hc' => Rule::requiredIf($this->search_sr_no_hc == null),
                'enr_date_hc' => Rule::requiredIf($this->search_sr_no_hc == null),
                'search_sr_no_hc' => Rule::requiredIf($this->hcr_no_hc == null && !$this->enr_date_hc == null),
            ]);

            $gc_high_court = GcHighCourt::where('hcr_no_hc', $this->hcr_no_hc)->count();
            if ($gc_high_court > 1) {
                $this->alert('warning', 'Duplicate Record!', 'Please contact with Punjab Bar Council to verify your data.');
                $this->gc_record_match = 2;
            } else {
                $gc_high_court_query = GcHighCourt::query();

                if ($this->search_sr_no_hc) {
                    $gc_high_court_query->where(function ($query) {
                        $query->where('sr_no_hc', $this->search_sr_no_hc);
                    });
                } else {
                    $gc_high_court_query->where(function ($query) {
                        $query->where('hcr_no_hc', $this->hcr_no_hc);
                        $query->whereDate('enr_date_hc', $this->enr_date_hc);
                    });
                }

                $gc_high_court_query->whereIn('app_status', [1, 8]);

                $this->gc_high_court = $gc_high_court_query->first();

                if ($this->gc_high_court) {
                    $this->sr_no_hc = $this->gc_high_court->sr_no_hc;
                    $this->gc_record_match = 1;
                } else {
                    $this->gc_record_match = 2;
                }
            }
        }

        if ($this->gc_lawyer_type == 1) {
            $this->validate([
                'reg_no_lc' => 'required',
            ]);

            $gc_lower_court = GcLowerCourt::select('reg_no_lc')->where('reg_no_lc', $this->reg_no_lc)->count();
            if ($gc_lower_court > 1) {
                $this->alert('warning', 'Duplicate Record!', 'Please contact with Punjab Bar Council to verify your data.');
                $this->gc_record_match = 2;
            } else {
                $this->gc_lower_court = GcLowerCourt::whereIn('app_status', [1, 8])->where('app_type', '!=', 4)->where('reg_no_lc', $this->reg_no_lc)->first();
                if ($this->gc_lower_court) {
                    $this->sr_no_lc = $this->gc_lower_court->sr_no_lc;
                    $this->gc_record_match = 1;
                } else {
                    $this->gc_record_match = 2;
                }
            }
        }
    }

    public function store()
    {
        $this->validate([
            'profile_image' => 'required|image|max:500',
            'cnic_front_image' => 'required|image|max:500',
            'cnic_back_image' => 'required|image|max:500',
            'license_front_image' => 'required|image|max:500',
            'license_back_image' => 'required|image|max:500',
        ]);

        if ($this->gc_lawyer_type == 1) {
            $this->user->update([
                "register_as" => 'gc_lc',
                "sr_no_lc" => $this->gc_lower_court->sr_no_lc,
                "gc_status" => 'pending',
            ]);
        }

        if ($this->gc_lawyer_type == 2) {
            $this->user->update([
                "register_as" => 'gc_hc',
                "sr_no_hc" => $this->gc_high_court->sr_no_hc,
                "gc_status" => 'pending',
            ]);
        }

        $this->user->addMedia($this->profile_image)->toMediaCollection('gc_profile_image');
        $this->user->addMedia($this->cnic_front_image)->toMediaCollection('gc_cnic_front');
        $this->user->addMedia($this->cnic_back_image)->toMediaCollection('gc_cnic_back');
        $this->user->addMedia($this->license_front_image)->toMediaCollection('gc_license_front');
        $this->user->addMedia($this->license_back_image)->toMediaCollection('gc_license_back');

        $this->user->update(["gc_requested_at" => Carbon::parse(Carbon::now())->format('Y-m-d')]);
    }

    public function alert($type, $title, $text)
    {
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => $type,
            'title' => $title,
            'text' =>  $text
        ]);
    }

    public function changeLawyerType()
    {
        $this->reg_no_lc = '';
        $this->hcr_no_hc = '';
        $this->gc_record_match = '';
    }

    public function resetDashboard()
    {
        LowerCourt::where('user_id', $this->user->id)->where('is_final_submitted', 0)->delete();
        Application::where('user_id', $this->user->id)->where('is_accepted', 0)->delete();

        $this->user->update([
            'register_as' => NULL,
        ]);

        $this->gc_lawyer_type =  NULL;
        $this->register_as =  NULL;
        $this->reg_no_lc = NULL;
        $this->hcr_no_hc = NULL;
        $this->gc_record_match = NULL;
    }
}
