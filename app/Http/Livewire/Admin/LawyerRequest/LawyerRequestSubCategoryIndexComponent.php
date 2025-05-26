<?php

namespace App\Http\Livewire\Admin\LawyerRequest;

use Livewire\Component;
use App\LawyerRequestSubCategory;
use Livewire\WithPagination;

class LawyerRequestSubCategoryIndexComponent extends Component
{
    use WithPagination;

    public $updateMode = false;
    protected $queryString = ['search'];
    protected $paginationTheme = 'bootstrap';
    public $search;

    public function render()
    {
        $records = LawyerRequestSubCategory::where('name', 'like', '%' . $this->search . '%')->paginate(10);

        return view('livewire.admin.lawyer-request.lawyer-request-sub-category-index-component', [
            'records' => $records
        ]);
    }

    public function changeStatus($id, $status)
    {
        LawyerRequestSubCategory::find($id)->update(['status' => $status]);
    }
}
