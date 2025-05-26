<?php

namespace App\Http\Livewire\Admin\Reports;

use App\AppStatus;
use App\Bar;
use App\GcHighCourt;
use App\GcLowerCourt;
use App\HighCourt;
use App\LowerCourt;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class VoterMemberComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $bars, $filter, $search = false;
    public
        $search_user_status = 'all',
        $search_user_type = 'lc',
        $search_app_status = 1,
        $search_gender = "",
        $search_voter_member = "",
        $search_start_date = "",
        $search_end_date = "",
        $search_request_date_from = "",
        $search_request_date_to = "";

    public $extra_cols = [];
    public $extra_cols_list = [];

    public $app_statuses = [];

    public function mount()
    {
        $this->bars = Bar::orderBy('name', 'asc')->get();
        $this->search_start_date = "1900-01-01";

        $app_env = config('app.env');
        if ($app_env == 'local') {
            $this->search_start_date = Carbon::parse(Carbon::now())->format('Y-m-d');
        }

        $this->search_end_date = Carbon::parse(Carbon::now())->format('Y-m-d');
        $this->app_statuses = AppStatus::get();

        $this->extra_cols_list = [
            'image' => 'Profile Image',
            'dob' => 'Date of Birth',
            'phone' => 'Mobile No',
            'cnic_no' => 'CNIC No',
            'lc_ledger' => 'LC Ledger No',

            'lc_license' => 'LC License No',
            'hc_hcr' => 'HC HCR No',
            'hc_license' => 'HC License No',
        ];

    }

    public function render()
    {
        $this->filter = [
            'search_user_type' => $this->search_user_type,
            'search_user_status' => $this->search_user_status,
            'search_app_status' => $this->search_app_status,
            'search_gender' => $this->search_gender,
            'search_voter_member' => $this->search_voter_member,
            'search_start_date' => $this->search_start_date,
            'search_end_date' => $this->search_end_date,
            'search_request_date_from' => $this->search_request_date_from,
            'search_request_date_to' => $this->search_request_date_to,
            'extra_cols' => $this->extra_cols,
        ];

        $records = voterMemberReport($this->filter)->paginate(25);
        
        return view('livewire.admin.reports.voter-member-component', [
            'records' => $records
        ]);
    }

    public function export()
    {
        return redirect()->route('reports.voter-member.export', ['filter' => $this->filter]);
    }

    public function search()
    {
        $this->search = true;
    }

    public function clear()
    {
        $this->search_user_type = 'lc';
        $this->search_user_status = '';
        $this->search_app_status = 1;
        $this->search_voter_member = '';
        $this->search_request_date_from = '';
        $this->search_request_date_to = '';
        $this->search = false;

        $this->mount();
    }


    function setColsForReport()
    {
        $array_count = count($this->extra_cols);
        if ($array_count > 4) {
            $this->extra_cols = [];
            $this->alert('warning', 'Warning', 'A maximum of 4 columns can be selected from the list.');
        }
        $this->emit('hide_modal');
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
