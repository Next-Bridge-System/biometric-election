<?php

namespace App\Http\Livewire\Admin\Reports;

use App\Application;
use App\GcHighCourt;
use App\GcLowerCourt;
use App\HighCourt;
use App\LowerCourt;
use App\Note;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class GeneralSearchReport extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $filter, $search = false;
    public $search_cnic = '';

    public $selected_row_id, $rcpt_no, $rcpt_date, $selected_lawyer_type;
    public $application_note, $application_notes_list = [], $selected_application_type;

    protected $listeners = [
        'set-application-notes' => 'getApplicationNotes',
    ];

    public function mount()
    {
        // $this->divisions = Division::orderBy('name', 'asc')->get();

        // if($this->search_division == ''){
        //     $this->bars = Bar::orderBy('name', 'asc')->get();
        // }else{
        //     $districtIDs = District::where('division', '=', $this->search_division)->pluck('id')->toArray();
        //     $this->bars = Bar::whereIn('district_id',$districtIDs)->orderBy('name', 'asc')->get();
        // }

        // $this->search_start_date = "1900-01-01";
        // // $this->search_start_date = Carbon::parse(Carbon::now())->format('Y-m-d');
        // $this->search_end_date = Carbon::parse(Carbon::now())->format('Y-m-d');
        // $this->app_statuses = AppStatus::get();
    }

    public function render()
    {
        $this->filter = [
            'cnic_no' => $this->search_cnic,
            'clean_cnic_no' => preg_replace('/[^0-9]/', '', $this->search_cnic),
        ];

        $records = [];
        if ($this->search_cnic) {
            $records = $this->generalSearchQuery($this->filter)->paginate(10);
        }

        return view('livewire.admin.reports.general-search-report', [
            'records' => $records,
        ]);
    }

    public function export($barID, $type)
    {
        return redirect()->route('reports.lawyer-summary-report.export', ['filter' => $this->filter, 'type' => $type, 'bar_id' => $barID]);
    }

    public function search()
    {
        $this->search = true;
    }

    public function clear()
    {
        $this->search_cnic = '';
        $this->search = false;
        $this->mount();
    }

    public function generalSearchQuery($filter)
    {
        $user = User::query()
            ->select(
                'users.id as main_id',
                DB::raw('"user" as lawyer_type'),
                DB::raw('users.register_as as current_user_type'),
                'users.name as lawyer',
                'users.father_name as father',
                'users.date_of_birth as dob',
                'users.cnic_no as cnic',
                DB::raw('CONCAT("0", users.phone) as phone'),
                DB::raw('CONCAT("") as app_status'),
                DB::raw('CONCAT("") as profile_image'),

                DB::raw('CONCAT("") as rcpt_date'),
                DB::raw('CONCAT("") as rcpt_no'),
                'users.created_at as final_submitted_at',
            )
            ->when($filter['cnic_no'], function ($qry) use ($filter) {
                $qry->where("users.clean_cnic_no", $filter['clean_cnic_no']);
            });

        $intimation = Application::query()
            ->leftJoin('users', 'users.id', '=', 'applications.user_id')
            ->leftJoin('app_statuses', 'app_statuses.id', '=', 'applications.application_status')
            ->select(
                'applications.id as main_id',
                DB::raw('"intimation" as lawyer_type'),
                DB::raw('users.register_as as current_user_type'),
                'users.name as lawyer',
                'applications.so_of as father',
                'applications.date_of_birth as dob',
                'applications.cnic_no as cnic',
                DB::raw('CONCAT("0", users.phone) as phone'),
                'app_statuses.value as app_status',
                'users.profile_image as profile_image',

                'applications.rcpt_date as rcpt_date',
                'applications.rcpt_no as rcpt_no',
                'applications.final_submitted_at as final_submitted_at',
            )
            ->when($filter['cnic_no'], function ($qry) use ($filter) {
                $qry->where("applications.clean_cnic_no", $filter['clean_cnic_no']);
            });

        $lc = LowerCourt::query()
            ->leftJoin('users', 'users.id', '=', 'lower_courts.user_id')
            ->leftJoin('app_statuses', 'app_statuses.id', '=', 'lower_courts.app_status')
            ->select(
                'lower_courts.id as main_id',
                DB::raw('"lc" as lawyer_type'),
                DB::raw('users.register_as as current_user_type'),
                'users.name as lawyer',
                'lower_courts.father_name as father',
                'lower_courts.date_of_birth as dob',
                'lower_courts.cnic_no as cnic',
                DB::raw('CONCAT("0", users.phone) as phone'),
                'app_statuses.value as app_status',
                'users.profile_image as profile_image',

                'lower_courts.rcpt_date as rcpt_date',
                'lower_courts.rcpt_no_lc as rcpt_no',
                'lower_courts.final_submitted_at as final_submitted_at',
            )
            ->when($filter['cnic_no'], function ($qry) use ($filter) {
                $qry->where("lower_courts.clean_cnic_no", $filter['clean_cnic_no']);
            });

        $gc_lc = GcLowerCourt::query()
            ->leftJoin('users', 'users.id', '=', 'gc_lower_courts.user_id')
            ->leftJoin('app_statuses', 'app_statuses.id', '=', 'gc_lower_courts.app_status')
            ->select(
                'gc_lower_courts.id as main_id',
                DB::raw('"gc_lc" as lawyer_type'),
                DB::raw('users.register_as as current_user_type'),
                'gc_lower_courts.lawyer_name as lawyer',
                'gc_lower_courts.father_name as father',
                'gc_lower_courts.date_of_birth as dob',
                'gc_lower_courts.cnic_no as cnic',
                DB::raw('gc_lower_courts.contact_no as phone'),
                'app_statuses.value as app_status',
                'users.profile_image as profile_image',

                DB::raw('CONCAT("") as rcpt_date'),
                DB::raw('CONCAT("") as rcpt_no'),
                'gc_lower_courts.created_at as final_submitted_at',
            )
            ->when($filter['cnic_no'], function ($qry) use ($filter) {
                $qry->where("gc_lower_courts.clean_cnic_no", $filter['clean_cnic_no']);
            });


        $hc = HighCourt::query()
            ->leftJoin('users', 'users.id', '=', 'high_courts.user_id')
            ->leftJoin('app_statuses', 'app_statuses.id', '=', 'high_courts.app_status')
            ->select(
                'high_courts.id as main_id',
                DB::raw('"hc" as lawyer_type'),
                DB::raw('users.register_as as current_user_type'),
                'users.name as lawyer',
                'users.father_name as father',
                'users.date_of_birth as dob',
                'users.cnic_no as cnic_no',
                DB::raw('CONCAT("0", users.phone) as phone'),
                'app_statuses.value as app_status',
                'users.profile_image as profile_image',

                'high_courts.rcpt_date as rcpt_date',
                'high_courts.rcpt_no_hc as rcpt_no',
                'high_courts.final_submitted_at as final_submitted_at',
            )
            ->when($filter['cnic_no'], function ($qry) use ($filter) {
                $qry->where("users.clean_cnic_no", $filter['clean_cnic_no']);
            });

        $gc_hc = GcHighCourt::query()
            ->leftJoin('users', 'users.id', '=', 'gc_high_courts.user_id')
            ->leftJoin('app_statuses', 'app_statuses.id', '=', 'gc_high_courts.app_status')
            ->select(
                'gc_high_courts.id as main_id',
                DB::raw('"gc_hc" as lawyer_type'),
                DB::raw('users.register_as as current_user_type'),
                'gc_high_courts.lawyer_name as lawyer',
                'gc_high_courts.father_name as father',
                'gc_high_courts.date_of_birth as dob',
                'gc_high_courts.cnic_no as cnic_no',
                DB::raw('gc_high_courts.contact_no as phone'),
                'app_statuses.value as app_status',
                'users.profile_image as profile_image',

                DB::raw('CONCAT("") as rcpt_date'),
                DB::raw('CONCAT("") as rcpt_no'),
                'gc_high_courts.created_at as final_submitted_at',
            )
            ->when($filter['cnic_no'], function ($qry) use ($filter) {
                $qry->where("gc_high_courts.clean_cnic_no", $filter['clean_cnic_no']);
            });

        $records = $user->unionAll($intimation)->unionAll($lc)->unionAll($gc_lc)->unionAll($hc)->unionAll($gc_hc);

        return $records;
    }


    public function openRcpt($id, $lawyer_type)
    {
        $this->selected_row_id = $id;
        $this->selected_lawyer_type = $lawyer_type;
    }

    public function updateRcpt($id)
    {
        if ($this->selected_lawyer_type == 'intimation') {
            $rcpt_date = Carbon::parse(Carbon::now())->format('Y-m-d');
            $rcpt = Application::select('rcpt_no', 'rcpt_date')->orderBy('rcpt_no', 'desc')->whereYear('rcpt_date', Carbon::parse($rcpt_date)->format('Y'))->first();

            if ($rcpt == null) {
                $rcpt_count = 1;
            } else {
                $rcpt_count = $rcpt->rcpt_no  + 1;
            }

            $intimation = Application::updateOrCreate(['id' =>  $id], [
                'rcpt_no' => sprintf("%02d", $rcpt_count),
                'rcpt_date' => $rcpt_date,
            ]);

            $this->rcpt_no = $intimation->rcpt_no;
            $this->rcpt_date = getDateFormat($intimation->rcpt_date);
        }

        // LOWER COURT RCPT FORMAT
        if ($this->selected_lawyer_type == 'lc') {
            $rcpt_date = Carbon::parse(Carbon::now())->format('Y-m-d');
            $rcpt = LowerCourt::select('rcpt_no_lc', 'rcpt_date')->orderBy('rcpt_no_lc', 'desc')->whereYear('rcpt_date', Carbon::parse($rcpt_date)->format('Y'))->first();

            if ($rcpt == null) {
                $rcpt_count = 1;
            } else {
                $rcpt_count = $rcpt->rcpt_no_lc  + 1;
            }

            $lc = LowerCourt::updateOrCreate(['id' =>  $id], [
                'rcpt_no_lc' => sprintf("%02d", $rcpt_count),
                'rcpt_date' => $rcpt_date,
            ]);

            $this->rcpt_no = $lc->rcpt_no_lc;
            $this->rcpt_date = getDateFormat($lc->rcpt_date);
        }

        // HIGH COURT RCPT FORMAT
        if ($this->selected_lawyer_type == 'hc') {
            $rcpt_date = Carbon::parse(Carbon::now())->format('Y-m-d');
            $rcpt = HighCourt::select('rcpt_no_hc', 'rcpt_date')->orderBy('rcpt_no_hc', 'desc')->whereYear('rcpt_date', Carbon::parse($rcpt_date)->format('Y'))->first();

            if ($rcpt == null) {
                $rcpt_count = 1;
            } else {
                $rcpt_count = $rcpt->rcpt_no_hc  + 1;
            }

            $hc = HighCourt::updateOrCreate(['id' =>  $id], [
                'rcpt_no_hc' => sprintf("%02d", $rcpt_count),
                'rcpt_date' => $rcpt_date,
            ]);

            $this->rcpt_no = $hc->rcpt_no_hc;
            $this->rcpt_date = getDateFormat($hc->rcpt_date);
        }
    }

    public function close()
    {
        $this->selected_row_id = null;
        $this->selected_lawyer_type = null;
        $this->rcpt_no = null;
        $this->rcpt_date = null;
    }

    public function openNotes($id, $lawyer_type)
    {
        $this->selected_row_id = $id;
        $this->selected_application_type = Str::upper($lawyer_type);
        $this->application_notes_list = Note::where('application_id', $id)->where('application_type', $this->selected_application_type)->get();
    }

    public function getApplicationNotes($contents)
    {
        $this->application_note = $contents;
    }

    public function saveNotes()
    {
        if ($this->application_note == NULL) {
            $this->alert('Required', 'The notes felid is required.', 'error');
        } else {
            Note::create([
                'application_id' => $this->selected_row_id,
                'application_type' => $this->selected_application_type,
                'note' => $this->application_note,
                'admin_id' => Auth::guard('admin')->user()->id,
            ]);

            $this->openNotes($this->selected_row_id, $this->selected_application_type);

            $this->application_note = NULL;
            $this->emit('clearApplicationNotes');
        }
    }

    public function alert($title, $text, $type)
    {
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => $type,
            'title' => $title,
            'text' =>  $text
        ]);
    }
}
