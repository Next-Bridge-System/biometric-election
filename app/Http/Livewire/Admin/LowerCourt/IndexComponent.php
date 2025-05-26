<?php

namespace App\Http\Livewire\Admin\LowerCourt;

use App\Bar;
use App\GcLowerCourt;
use App\LowerCourt;
use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\ResetsPagination;

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
    public $bars, $short_detail, $slug, $open_row_id;

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

        $lc = LowerCourt::lowerCourtrecord($filter);
        $gc = GcLowerCourt::gclowerCourtrecord($filter);

        if ($this->slug == 'submit') {
            $records = $lc->orderBy('created_at', 'desc')->paginate(10);
        } else if ($this->slug == 'gc') {
            $records = $gc->orderBy('created_at', 'desc')->paginate(10);
        } else if ($this->slug == 'all') {
            $records = $lc->union($gc)->orderBy('created_at', 'desc')->paginate(10);
        } else {
            abort(403);
        }

        return view('livewire.admin.lower-court.index-component', [
            'records' => $records
        ]);
    }

    public function detail($id, $type)
    {
        $this->open_row_id = $id;
        $this->short_detail = lowerCourtShortDetail($id, $type);
    }

    public function closeDetail()
    {
        $this->open_row_id = null;
    }

    public function changeFilter()
    {   
        $this->search_data_type = 'text';
        if (in_array($this->search_by, ['dob', 'enr_date'])) {
            $this->search_data_type = 'date';
        }
    }
}
