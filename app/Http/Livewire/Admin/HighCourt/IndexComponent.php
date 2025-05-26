<?php

namespace App\Http\Livewire\Admin\HighCourt;

use App\GcHighCourt;
use App\HighCourt;
use Livewire\Component;
use App\Bar;
use Livewire\WithPagination;
use App\Traits\ResetsPagination;
use Carbon\Carbon;

class IndexComponent extends Component
{
    use WithPagination, ResetsPagination;
    protected $paginationTheme = 'bootstrap';
    public $search_voter_member,
        $search_by = 'name',
        $search_data,
        $search_data_type = 'text',
        $search_by_02 = 'father_name',
        $search_data_02_type = 'text',
        $search_data_02;

    public $bars, $short_detail, $slug, $open_row_id, $rcpt_no, $rcpt_date;

    public function mount()
    {
        $this->bars = Bar::orderBy('name', 'asc')->get();
    }

    public function render()
    {
        $filter = [
            'search_by' => $this->search_by,
            'search_data' => $this->search_data,
            'search_by_02' => $this->search_by_02,
            'search_data_02' => $this->search_data_02,
            'search_voter_member' => $this->search_voter_member,
            'slug' => $this->slug,
        ];

        $hc = HighCourt::highCourtrecord($filter);

        if (in_array($this->slug, ['submit', 'partial'])) {
            $records = $hc->orderBy('created_at', 'desc')->paginate(10);
        } else if ($this->slug == 'gc') {
            $gc = GcHighCourt::gcHighCourtrecord($filter);
            $records = $gc->orderBy('created_at', 'desc')->paginate(10);
        } else if ($this->slug == 'all') {
            $gc = GcHighCourt::gcHighCourtrecord($filter);
            $records = $hc->union($gc)->orderBy('created_at', 'desc')->paginate(10);
        } else {
            abort(403);
        }

        return view('livewire.admin.high-court.index-component', [
            'records' => $records
        ]);
    }

    public function detail($id, $type)
    {
        $this->open_row_id = $id;
        $this->short_detail = highCourtShortDetail($id, $type);
    }

    public function openRcpt($id)
    {
        $this->open_row_id = $id;
    }

    public function updateRcpt($id)
    {
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

    public function close()
    {
        $this->open_row_id = null;
        $this->rcpt_no = null;
        $this->rcpt_date = null;
    }

    public function changeFilter()
    {   
        $this->search_data_type = 'text';
        if (in_array($this->search_by, ['dob', 'enr_date'])) {
            $this->search_data_type = 'date';
        }
    }
}
