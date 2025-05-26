<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\PrintSecureCard;
use App\Application;
use App\GcHighCourt;
use App\GcLowerCourt;
use App\HighCourt;
use App\LowerCourt;
use Illuminate\Support\Facades\Auth;

class QueueImport implements ToModel, WithHeadingRow
{
    protected $app_type;

    function __construct($app_type)
    {
        $this->app_type = $app_type;
    }

    public function model(array $row)
    {
        if ($this->app_type == 'lower_court') {
            $gc_lc = GcLowerCourt::select('gc_lower_courts.id as lc_id')
                ->leftJoin('users as u', 'u.id', 'gc_lower_courts.user_id')
                ->where('gc_lower_courts.reg_no_lc', $row['reg_no'])
                ->first();

            if ($gc_lc) {
                PrintSecureCard::create([
                    'application_id' => $gc_lc->lc_id,
                    'application_type' => 'gc_lc',
                    'is_queued' => 1,
                    'admin_id' => Auth::guard('admin')->user()->id,
                ]);
            }

            $lc = LowerCourt::select('lower_courts.id as lc_id')
                ->join('users as u', 'u.id', 'lower_courts.user_id')
                ->where('lower_courts.reg_no_lc', $row['reg_no'])
                ->first();

            if ($lc) {
                PrintSecureCard::create([
                    'application_id' => $lc->lc_id,
                    'application_type' => 'lc',
                    'is_queued' => 1,
                    'admin_id' => Auth::guard('admin')->user()->id,
                ]);
            }
        }


        if ($this->app_type == 'high_court') {
            $gc_hc = GcHighCourt::select('gc_high_courts.id as hc_id')
                ->leftJoin('users as u', 'u.id', 'gc_high_courts.user_id')
                ->where('gc_high_courts.hcr_no_hc', $row['hcr_no'])
                ->first();

            if ($gc_hc) {
                PrintSecureCard::create([
                    'application_id' => $gc_hc->hc_id,
                    'application_type' => 'gc_hc',
                    'is_queued' => 1,
                    'admin_id' => Auth::guard('admin')->user()->id,
                ]);
            }

            $hc = HighCourt::select('high_courts.id as hc_id')
                ->join('users as u', 'u.id', 'high_courts.user_id')
                ->where('high_courts.hcr_no_hc', $row['hcr_no'])
                ->first();

            if ($hc) {
                PrintSecureCard::create([
                    'application_id' => $hc->hc_id,
                    'application_type' => 'hc',
                    'is_queued' => 1,
                    'admin_id' => Auth::guard('admin')->user()->id,
                ]);
            }
        }
    }
}
