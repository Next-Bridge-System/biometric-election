<?php

namespace App\Http\Livewire\Admin\Users;

use App\AppStatus;
use App\AppType;
use App\Bar;
use App\GcHighCourt;
use App\GcLowerCourt;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class GcShowComponent extends Component
{
    public $user_id, $user, $application, $gc_status;
    public $admin_audits, $bars, $app_status, $app_types;
    public $cnic_no, $phone;
    public $serial_number, $serial_number_record;

    protected $listeners = [
        'delete-user' => 'delete',
    ];

    public function mount()
    {
        $this->user = User::find($this->user_id);
        $this->gc_status = $this->user->gc_status;
        $this->cnic_no = $this->user->cnic_no;
        $this->phone = $this->user->phone;

        if ($this->user->register_as == 'gc_lc') {
            $this->application = GcLowerCourt::where('sr_no_lc', $this->user->sr_no_lc)->first();
        }

        if ($this->user->register_as == 'gc_hc') {
            $this->application = GcHighCourt::where('sr_no_hc', $this->user->sr_no_hc)->first();
        }

        $this->admin_audits = $this->user->audits()->latest()->get();
        $this->bars = Bar::orderBy('name', 'asc')->get();

        if ($this->user->register_as == 'gc_lc') {
            $this->app_status = AppStatus::lcStatus()->get();
            $this->app_types = AppType::lcType()->get();
        }

        if ($this->user->register_as == 'gc_hc') {
            $this->app_status = AppStatus::hcStatus()->get();
            $this->app_types = AppType::hcType()->get();
        }
    }

    public function render()
    {
        return view('livewire.admin.users.gc-show-component');
    }

    public function updateGcRequestData()
    {
        gcCheckCnicExist($this->cnic_no);

        try {
            DB::beginTransaction();

            $this->validate([
                'cnic_no' => ['required', 'regex:/[0-9]{5}-[0-9]{7}-[0-9]{1}/', Rule::unique('users')->ignore($this->user->id, 'id')],
                'phone' => ['required', 'regex:/(3)[0-9]{9}/', Rule::unique('users')->ignore($this->user->id, 'id')],
            ]);

            $this->user->update([
                'gc_status' => $this->gc_status,
                'gc_approved_by' => Auth::guard('admin')->user()->id,
            ]);

            if ($this->gc_status == 'approved') {
                $this->user->update([
                    'cnic_no' => $this->cnic_no,
                ]);

                $this->application->update([
                    'user_id' => $this->user->id,
                    'cnic_no' => $this->user->cnic_no,
                    'contact_no' => $this->user->phone,
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            //throw $th;
        }
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index', 'gc');
    }

    public function alertConfirm()
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => 'If deleted, you will not be able to recover this imaginary file!'
        ]);
    }

    public function alert($type, $title, $text)
    {
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => $type,
            'title' => $title,
            'text' =>  $text
        ]);
    }

    public function searchBySerialNumber()
    {
        $this->serial_number_record = null;

        if ($this->user->register_as == 'gc_lc') {
            $this->serial_number_record = GcLowerCourt::where('sr_no_lc', $this->serial_number)->first();
        }

        if ($this->user->register_as == 'gc_hc') {
            $this->serial_number_record = GcHighCourt::where('sr_no_hc', $this->serial_number)->first();
        }

        if (!$this->serial_number_record) {
            $this->alert('error', 'Not Match!', 'The record associated with this serial number does not appear to match the provided information.');
        }
    }

    public function replaceRecord()
    {
        $temp_sr_no_lc = $this->user->sr_no_lc;
        $temp_sr_no_hc = $this->user->sr_no_hc;

        $this->user->sr_no_lc = null;
        $this->user->sr_no_hc = null;

        if ($this->user->register_as == 'gc_lc') {
            $this->user->sr_no_lc = $this->serial_number_record->sr_no_lc;
            $gc_lc = GcLowerCourt::where('sr_no_lc', $temp_sr_no_lc)->first();
            $gc_lc->update(['user_id' => NULL]);
        }

        if ($this->user->register_as == 'gc_hc') {
            $this->user->sr_no_hc = $this->serial_number_record->sr_no_hc;
            $gc_hc = GcHighCourt::where('sr_no_hc', $temp_sr_no_hc)->first();
            $gc_hc->update(['user_id' => NULL]);
        }

        $this->user->update();
        $this->dispatchBrowserEvent('page:reload');
    }
}
