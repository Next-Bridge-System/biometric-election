<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Application;
use App\Admin;
use App\VPP;
use Auth;

class VPPReturnImport implements ToModel, WithHeadingRow
{
    protected $type;

    function __construct($type) {
        $this->excel_import_app_type = $type;
    }

    public function model(array $row)
    {
        $application= Application::where('reg_no_lc', $row['legder'])->first();
        if (isset($application)) {
            $find = ['application_id'=>$application->id];
            $vpp = VPP::updateOrCreate($find,[
                'admin_id' => Auth::guard('admin')->user()->id,
                'application_id' => $application->id,
                'vpp_returned' => TRUE,
            ]);
        }
    }
}
