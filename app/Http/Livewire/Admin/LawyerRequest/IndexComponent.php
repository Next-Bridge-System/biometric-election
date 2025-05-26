<?php

namespace App\Http\Livewire\Admin\LawyerRequest;

use Livewire\Component;
use App\LawyerRequest;
use App\LawyerRequestSubCategory;
use App\Payment;
use Livewire\WithPagination;

class IndexComponent extends Component
{
    use WithPagination;

    public $updateMode = false;
    protected $paginationTheme = 'bootstrap';
    public $search_id, $search_lawyer_name, $search_lawyer_cnic, $search_sub_category_id,
        $sub_categories, $search_approved, $search_payment_status, $search_voucher_file;

    protected $listeners = [
        'delete-lawyer-request' => 'delete',
    ];

    public function mount()
    {
        $this->sub_categories = LawyerRequestSubCategory::orderBy('name', 'asc')->get();
    }

    public function render()
    {
        $query = LawyerRequest::query();

        if ($this->search_id) {
            $query->where('id', $this->search_id);
        }

        if ($this->search_lawyer_name) {
            $query->where('lawyer_name', 'like', '%' . $this->search_lawyer_name . '%');
        }

        if ($this->search_lawyer_cnic) {
            $query->where('cnic_no', 'like', '%' . $this->search_lawyer_cnic . '%');
        }

        if ($this->search_sub_category_id) {
            $query->where('lawyer_request_sub_category_id', $this->search_sub_category_id);
        }

        if ($this->search_approved) {
            $query->where('approved', $this->search_approved);
        }

        if ($this->search_payment_status) {
            $query->where('payment_status', $this->search_payment_status);
        }

        if ($this->search_voucher_file) {
            $query->where('voucher_file', $this->search_voucher_file);
        }

        $records = $query->orderBy('id', 'desc')->paginate(10);

        return view('livewire.admin.lawyer-request.index-component', [
            'records' => $records
        ]);
    }

    public function delete($id)
    {
        $vehicle = LawyerRequest::findOrFail($id);
        $vehicle->delete();

        $this->alert('Record Deleted!', 'The record have been deleted successfully.');
    }

    public function alert($title, $text)
    {
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => $title,
            'text' =>  $text
        ]);
    }
}
