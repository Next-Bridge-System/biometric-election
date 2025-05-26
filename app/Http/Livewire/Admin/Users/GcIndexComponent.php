<?php

namespace App\Http\Livewire\Admin\Users;

use App\User;
use Livewire\Component;
use Livewire\WithPagination;

class GcIndexComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search_name, $search_user_id, $search_gc_status;

    public function render()
    {
        $query = User::query();
        $query->whereIn('register_as', ['gc_lc', 'gc_hc']);

        if ($this->search_user_id) {
            $query->where('id', $this->search_user_id);
        }

        if ($this->search_name) {
            $query->where('name', 'like', '%' . $this->search_name . '%');
        }

        if ($this->search_gc_status) {
            $query->where('gc_status', $this->search_gc_status);
        }

        $records = $query->orderBy('id', 'desc')->paginate();

        return view('livewire.admin.users.gc-index-component', [
            'records' => $records
        ]);
    }
}
