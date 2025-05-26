<?php

namespace App\Imports;

use App\LawyerAddress;
use App\LawyerUpload;
use App\LowerCourt;
use App\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;

class LowerCourtImport implements ToModel, WithHeadingRow
{
    protected $type;

    function __construct($type)
    {
        $this->excel_import_app_type = $type;
    }

    public function model(array $row)
    {
        $user = User::where('sr_no_lc', $row['sr_no'])->where('register_as', 'lc')->first();

        $user_data = [
            'name' => $row['name'],
            'fname' => $row['name'],
            'email' => $row['email'],
            'password' => '1234567890',
            'cnic_no' => $row['cnic_no'],
            'phone' => $row['contact_no'],
            'sr_no_lc' => $row['sr_no'],
            'register_as' => 'lc',
            'is_excel' => 1,
        ];

        if ($user == NULL) {
            $user = User::create($user_data);
        } else {
            $user->update($user_data);
        }

        $lc = LowerCourt::where('sr_no_lc', $row['sr_no'])->first();

        $lc_data = [
            'user_id' => $user->id,
            'is_excel' => 1,
            'is_final_submitted' => 1,
            'final_submitted_at' => Carbon::now(),
            'created_by' => auth()->guard('admin')->user()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'sr_no_lc' => $row['sr_no'],
            // 'value' => $row['name'],
            'father_name' => $row['father_name'],
            'gender' => $row['gender'],
            'date_of_birth' => $row['date_of_birth'],
            'cnic_no' => $row['cnic_no'],
            'reg_no_lc' => $row['ledger_no'],
            'license_no_lc' => $row['license_no'],
            'bf_no_lc' => $row['bf_no'],
            'date_of_enrollment_lc' => $row['enr_date'],
            'voter_member_lc' => $row['voter_member'],
            // 'value' => $row['image'],
            'rf_id' => $row['rf_id'],
            'enr_app_sdw' => $row['enr_app_sdw'],
            'enr_status_reason' => $row['enr_status_reason'],
            'enr_plj_check' => $row['enr_plj_check'],
            'enr_gi_check' => $row['enr_gi_check'],
            'app_status' => $row['app_status'],
            // 'value' => $row['email'],
            // 'value' => $row['contact_no'],
        ];

        if ($lc == NULL) {
            $lc = LowerCourt::create($lc_data);
        } else {
            $lc->update($lc_data);
        }


        $lc_upload = LawyerUpload::where('lower_court_id', $lc->id)->first();

        $lc_upload_data = [
            'lower_court_id' => $lc->id,
            'profile_image' => 'lower-court/import-images/' . $row['image'],
        ];

        if ($lc_upload == NULL) {
            $lc_upload = LawyerUpload::create($lc_upload_data);
        } else {
            $lc_upload->update($lc_upload_data);
        }


        $lc_address = LawyerAddress::where('lower_court_id', $lc->id)->first();

        $lc_address_data = [
            'lower_court_id' => $lc->id,
            'ha_str_address' => $row['address_1'],
            'ha_town' => $row['address_2'],
        ];

        if ($lc_address == NULL) {
            $lc_address = LawyerAddress::create($lc_address_data);
        } else {
            $lc_address->update($lc_address_data);
        }

        return $lc;
    }
}
