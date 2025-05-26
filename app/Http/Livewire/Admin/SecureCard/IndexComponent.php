<?php

namespace App\Http\Livewire\Admin\SecureCard;

use App\Exports\VPApplicationExport;
use App\GcHighCourt;
use App\Imports\QueueImport;
use App\PrintSecureCard;
use App\Traits\ResetsPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class IndexComponent extends Component
{
    use WithFileUploads, WithPagination, ResetsPagination;
    protected $paginationTheme = 'bootstrap';

    public $app_type, $file, $selected_records;

    public function render()
    {
        $query = PrintSecureCard::orderBy('id', 'desc');
        $records = $query->paginate(5000);

        $this->selected_records = $query->get()->pluck('application_id')->toArray();

        return view('livewire.admin.secure-card.index-component', [
            'records' => $records
        ]);
    }

    public function close()
    {
        // 
    }

    public function import()
    {
        try {
            $this->validate([
                'file' => 'required',
                'app_type' => 'required',
            ]);

            PrintSecureCard::truncate();
            Excel::import(new QueueImport($this->app_type), $this->file);

            $this->alert('success', 'Import completed!', 'The import has been completed successfully.');
        } catch (\Throwable $th) {
            $this->alert('error', 'Import Failed!', $th->getMessage());
        }
    }

    public function export()
    {
        try {
            $this->validate([
                'app_type' => 'required',
            ]);

            return Excel::download(new VPApplicationExport($this->app_type, $this->selected_records), 'export.xlsx');
        } catch (\Throwable $th) {
            // throw $th;
            $this->alert('error', 'Export Failed!', $th->getMessage());
        }
    }

    public function exportImages()
    {
        $selected_records = implode(',', $this->selected_records);
        $final_string = $this->app_type . '|' . $selected_records;
        $encoded_data = base64_encode($final_string);
        return redirect()->route('secure-card.export-images', $encoded_data);
    }

    public function exportLetters()
    {
        try {
            $this->validate([
                'app_type' => 'required',
            ]);

            // $selected_records = implode(',', $this->selected_records);
            $final_string = $this->app_type . '|' . 1;
            $encoded_data = base64_encode($final_string);
            return redirect()->route('secure-card.export-letters-envelops', $encoded_data);
        } catch (\Throwable $th) {
            // throw $th;
            $this->alert('error', 'Export Failed!', $th->getMessage());
        }
    }

    public function exportEnvelops()
    {
        try {
            $this->validate([
                'app_type' => 'required',
            ]);

            $selected_records = implode(',', $this->selected_records);
            $final_string = $this->app_type . '|' . 2;
            $encoded_data = base64_encode($final_string);
            return redirect()->route('secure-card.export-letters-envelops', $encoded_data);
        } catch (\Throwable $th) {
            // throw $th;
            $this->alert('error', 'Export Failed!', $th->getMessage());
        }
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
