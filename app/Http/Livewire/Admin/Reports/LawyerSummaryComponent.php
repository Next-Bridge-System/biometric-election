<?php

namespace App\Http\Livewire\Admin\Reports;

use App\AppStatus;
use App\Bar;
use App\District;
use App\Division;
use App\GcHighCourt;
use App\GcLowerCourt;
use App\HighCourt;
use App\LowerCourt;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class LawyerSummaryComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $bars, $divisions, $filter, $search = false;
    public
        $search_user_status = 'all',
        $search_division = '',
        $search_app_status = 1,
        $search_gender = "",
        $search_voter_member = "",
        $search_start_date = "",
        $search_end_date = "",
        $search_request_date_from = "",
        $search_request_date_to = "",
        $search_profile_image = 'no',
        $show_lc_ledger = 'no';

    public $app_statuses = [];

    public function mount()
    {
        $this->divisions = Division::orderBy('name', 'asc')->get();

        if ($this->search_division == '') {
            $this->bars = Bar::orderBy('name', 'asc')->get();
        } else {
            $districtIDs = District::where('division', '=', $this->search_division)->pluck('id')->toArray();
            $this->bars = Bar::whereIn('district_id', $districtIDs)->orderBy('name', 'asc')->get();
        }

        $this->search_start_date = "1900-01-01";
        // $this->search_start_date = Carbon::parse(Carbon::now())->format('Y-m-d');
        $this->search_end_date = Carbon::parse(Carbon::now())->format('Y-m-d');
        $this->app_statuses = AppStatus::get();
    }

    public function render()
    {
        $this->filter = [
            'search_division' => $this->search_division,
            'search_app_status' => $this->search_app_status,
            'search_voter_member' => $this->search_voter_member,
        ];

        $records = lawyerSummaryReport($this->filter)->paginate(10);

        return view('livewire.admin.reports.lawyer-summary-component', [
            'records' => $records,
        ]);
    }

    public function export($barID, $type)
    {
        // dd($barID,$type);
        return redirect()->route('reports.lawyer-summary-report.export', ['filter' => $this->filter, 'type' => $type, 'bar_id' => $barID]);
    }

    public function search()
    {
        $this->search = true;
    }

    public function clear()
    {
        $this->search_division = '';
        $this->search_user_status = '';
        $this->search_app_status = 1;
        $this->search = false;

        $this->mount();
    }
}
