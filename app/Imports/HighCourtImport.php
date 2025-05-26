<?php

namespace App\Imports;

use App\HighCourt;
use App\LawyerAddress;
use App\LawyerUpload;
use App\LowerCourt;
use App\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;

class HighCourtImport implements ToModel, WithHeadingRow
{
    protected $type;

    function __construct($type)
    {
        $this->excel_import_app_type = $type;
    }

    public function model(array $row)
    {
        $user = User::where('sr_no_hc', $row['sr'])->where('register_as', 'hc')->first();

        $user_data = [
            'name' => $row['name'],
            'fname' => $row['name'], // first name
            'email' => $row['email'],
            'password' => 'hc-12345',
            'cnic_no' => $row['cnic'],
            'phone' => $row['cell'],
            'sr_no_hc' => $row['sr'],
            'register_as' => 'hc',
            'is_excel' => 1,
        ];

        if ($user == NULL) {
            $user = User::create($user_data);
        } else {
            $user->update($user_data);
        }

        $hc = HighCourt::where('sr_no_hc', $row['sr'])->first();

        $hc_data = [
            'user_id' => $user->id,
            'is_excel' => 1,
            'is_final_submitted' => 1,
            'final_submitted_at' => Carbon::now(),
            'created_by' => auth()->guard('admin')->user()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            // Excel Import
            'sr_no_hc' => $row['sr'],
            'father_name' => $row['father_name'],
            'gender' => $row['gender'],
            'dob' => $row['dob'],
            'cnic_no' => $row['cnic'],
            'hcr_no_hc' => $row['hcr'],
            'license_no_hc' => $row['hc_lic'],
            'bf_no_hc' => $row['hc_bf'],
            'enr_date_hc' => $row['hc_enr_date'],
            'enr_date_lc' => $row['lc_enr_date'],
            'voter_member_hc' => $row['voter_id'],
            'enr_status_type' => $row['enr_status_type'],
            'enr_status_reason' => $row['enr_status_reason'],
            'lc_ledger' => $row['lc_ledger'],
            'lc_sdw' => $row['lc_sdw'],
            'lc_last_status' => $row['lc_last_status'],
            'lc_lic' => $row['lc_lic'],
            'app_status' => $row['app_status'],
        ];

        if ($hc == NULL) {
            $hc = HighCourt::create($hc_data);
        } else {
            $hc->update($hc_data);
        }

        // IMAGES
        $hc_upload = LawyerUpload::where('high_court_id', $hc->id)->first();
        $hc_upload_data = [
            'high_court_id' => $hc->id,
            'profile_image' => 'high-court/import-images/' . $row['image'],
        ];

        if ($hc_upload == NULL) {
            $hc_upload = LawyerUpload::create($hc_upload_data);
        } else {
            $hc_upload->update($hc_upload_data);
        }

        // ADDRESS
        $hc_address = LawyerAddress::where('high_court_id', $hc->id)->first();
        $hc_address_data = [
            'high_court_id' => $hc->id,
            'ha_str_address' => $row['address_1'],
            'ha_town' => $row['address_2'],
        ];

        if ($hc_address == NULL) {
            $hc_address = LawyerAddress::create($hc_address_data);
        } else {
            $hc_address->update($hc_address_data);
        }

        return $hc;
    }
}
