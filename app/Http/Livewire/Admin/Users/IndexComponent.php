<?php

namespace App\Http\Livewire\Admin\Users;

use App\AppStatus;
use Livewire\Component;
use Livewire\WithPagination;
use App\User;
use Illuminate\Support\Facades\DB;

class IndexComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search_name,
        $search_user_id,
        $search_gc_status,
        $search_cnic_no,
        $search_phone_no,
        $search_request_from,
        $search_request_to,
        $search_register_date,
        $search_user_type,
        $search_app_status;

    public $slug;
    public $app_statuses = [];

    public function mount()
    {
        $this->app_statuses = AppStatus::get();
    }

    public function render()
    {
        $query = User::select('users.*', DB::raw("CONCAT('0', users.phone) AS formatted_phone"))
            ->when($this->slug == 'gc', function ($qry) {
                return $qry->whereIn('users.register_as', ['gc_lc', 'gc_hc']);
            })
            ->when($this->search_user_type && $this->search_user_type == 'intimation', function ($qry) {
                return $qry->where('users.register_as', 'intimation');
            })
            ->when($this->search_user_type && $this->search_user_type == 'lc', function ($qry) {
                return $qry->whereIn('users.register_as', ['lc', 'gc_lc']);
            })
            ->when($this->search_user_type && $this->search_user_type == 'hc', function ($qry) {
                return $qry->whereIn('users.register_as', ['hc', 'gc_hc']);
            })
            ->when($this->search_user_id, function ($qry) {
                return $qry->where('users.id', $this->search_user_id);
            })
            ->when($this->search_name, function ($qry) {
                return $qry->where('users.name', 'like', '%' . $this->search_name . '%');
            })
            ->when($this->search_cnic_no, function ($qry) {
                return $qry->where('users.cnic_no', 'like', '%' . $this->search_cnic_no . '%');
            })
            ->when($this->search_phone_no, function ($qry) {
                return $qry->where('users.phone', 'like', '%' . $this->search_phone_no . '%');
            })
            ->when($this->search_gc_status, function ($qry) {
                return $qry->where('users.gc_status', $this->search_gc_status);
            })
            ->when($this->search_request_from && $this->search_request_to, function ($qry) {
                $qry->where(function ($q) {
                    $q->where('users.gc_requested_at', '>=', $this->search_request_from);
                    $q->where('users.gc_requested_at', '<=', $this->search_request_to);
                });
            })
            ->when($this->search_register_date, function ($qry) {
                return $qry->whereDate('users.created_at', $this->search_register_date);
            });

        $records = $query->orderBy('users.id', 'desc')->paginate();

        return view('livewire.admin.users.index-component', [
            'records' => $records
        ]);
    }
}
