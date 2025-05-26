<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Application;
use App\PrintSecureCard;
use Illuminate\Support\Facades\Auth;

class ApplicationImport implements ToModel, WithHeadingRow
{
    protected $type, $excel_import_app_type;

    function __construct($type)
    {
        $this->excel_import_app_type = $type;
    }

    public function model(array $row)
    {
        // COLUMNS IMPORT FORMATS
        $contact = null;
        if (!empty($row['contact_no']) || $row['contact_no'] != '' || $row['contact_no'] != null) {
            $r1 = str_replace('-', ' ', $row['contact_no']);
            $r2 = ltrim($r1, '0');
            $contact = preg_replace('/\s+/', '', $r2);
        }

        $formatted_cnic = null;
        if (!empty($row['cnic_no']) || $row['cnic_no'] != '' || $row['cnic_no'] != null) {
            $cnic = str_replace('-', '', $row['cnic_no']);
            $formatted_cnic = substr($cnic, 0, 5) . '-' . substr($cnic, 5, 7) . '-' . substr($cnic, 12);
        }

        $address = str_replace('_x000D_', '', $row['address_1']);

        // APPLICATION QUERIES
        $admin = Auth::guard('admin')->user();
        // $application = Application::where('cnic_no', $formatted_cnic)->first();
        $application = Application::where('reg_no_lc', $row['ledger'])->first();
        // $application = Application::where('license_no_hc', $row['license'])->first();

        // LOWER COURT - APPLICATION
        if ($this->excel_import_app_type == 1) {
            $data = [
                'license_no_lc' => $row['license'], // License #
                'advocates_name' => $row['name'], // Name
                'so_of' => $row['f_name'], // F name
                'date_of_enrollment_lc' => $row['enr_date'], // Enr.Date
                'voter_member_lc' => $row['voter_member'], // Voter Member
                'cnic_no' => $row['cnic_no'], // CNIC No.
                'active_mobile_no' => $contact, // Contact No.
                'postal_address' => $row['address_1'], // Address 1
                'address_2' => $row['address_2'], // Address 2
                'profile_image_name' => $row['image'], // Image
                'profile_image_url' => 'applications/profile-images/' . $row['cnic_no'] . '.png',
                'rf_id' => $row['rfid'], // RFID
                'print_date' => $row['print_date'], // Print Date
                'card_status' => $row['card_status'], // Card Status

                // Application Data
                'submitted_by' => $admin->id,
                'application_type' => $this->excel_import_app_type,
                'application_status' => '1', // Active
                'is_excel_import' => TRUE,
            ];

            if ($application == NULL) {
                $application = Application::create($data);
                $application->update([
                    'application_token_no' => $application->id + 1000,
                ]);
            } else {
                $application->update($data);
            }
        }

        // HIGH COURT - APPLICATION
        if ($this->excel_import_app_type == 2) {
            $data = [
                'license_no_hc' => $row['license'], // License #
                'bf_no_hc' => $row['bf'], // BF #
                'sr_no_hc' => $row['sr'], // SR #
                'advocates_name' => $row['name'], // Name
                'so_of' => $row['f_name'], // F name
                'date_of_enrollment_lc' => $row['enr_date_lc'], // Enr.Date LC
                'date_of_enrollment_hc' => $row['enr_date_hc'], // Enr Date HC
                'voter_member_lc' => $row['voter_member_lc'], // Voter Member LC
                'voter_member_hc' => $row['voter_member_hc'], // Voter Member HC
                'cnic_no' => $row['cnic_no'], // CNIC No
                'active_mobile_no' => $contact, // Contact No
                'postal_address' => $row['address_1'], // Address 1
                'address_2' => $row['address_2'], // Address 2
                'profile_image_name' => $row['image'], // Image
                'profile_image_url' => 'applications/profile-images/' . $row['cnic_no'] . '.png',
                'print_date' => $row['print_date'], // Print Date
                'card_status' => $row['card_status'], // Card Status

                // Application Data
                'submitted_by' => $admin->id,
                'application_type' => $this->excel_import_app_type,
                'application_status' => '1', // Active
                'is_excel_import' => TRUE,
            ];

            if ($application == NULL) {
                $application = Application::create($data);
                $application->update([
                    'application_token_no' => $application->id + 1000,
                ]);
            } else {
                $application->update($data);
            }
        }

        // RENEWAL HIGH COURT - APPLICATION
        if ($this->excel_import_app_type == 3) {
            $data = [
                'bf_no_hc' => $row['bf'], // BF #
                'license_no_hc' => $row['license'], // License #
                'advocates_name' => $row['name'], // Name
                'so_of' => $row['f_name'], // F name
                'hcr_no' => $row['hcr'], // HCR #
                'date_of_enrollment_lc' => transformDate($row['enr_date_lc']), // Enr.Date LC
                'date_of_enrollment_hc' => transformDate($row['enr_date_hc']), // Enr. Date HC
                'voter_member_lc' => $row['voter_member_lc'], // Voter Member LC
                'voter_member_hc' => $row['voter_member_hc'], // Voter Member HC
                'cnic_no' => $row['cnic_no'], // CNIC No.
                'active_mobile_no' => $contact, // Contact No.
                'postal_address' => $row['address_1'], // Address 1
                'address_2' => $row['address_2'], // Address 2
                'profile_image_name' => $row['image'], // Image
                // 'profile_image_url' => 'applications/profile-images/' . $row['cnic_no'] . '.png',
                'profile_image_url' => 'applications/profile-images/HC/' . $row['image'],
                // 'print_date' => $row['print_date'], // Print Date
                // 'card_status' => $row['card_status'], // Card Status

                // Application Data
                'submitted_by' => $admin->id,
                'application_type' => $this->excel_import_app_type,
                'application_status' => '1', // Active
                'is_excel_import' => TRUE,
            ];

            if ($application == NULL) {
                $application = Application::create($data);
                $application->update([
                    'application_token_no' => $application->id + 1000,
                ]);
            } else {
                $application->update($data);
            }
        }

        // RENEWAL LOWER COURT - APPLICATION
        if ($this->excel_import_app_type == 4) {
            $data = [
                'bf_no_lc' => isset($row['bf']) ? $row['bf'] : null, // BF #
                'reg_no_lc' => isset($row['ledger']) ?  $row['ledger'] : null, // Ledger #
                'license_no_lc' => $row['license'], // License #
                'advocates_name' => $row['name'], // Name
                'so_of' => $row['f_name'], // F Name
                'date_of_birth' => transformDate($row['date_of_birth']), // Date of birth
                'date_of_enrollment_lc' => transformDate($row['enr_date']), // Enr Date
                'voter_member_lc' => $row['voter_member_id'], // Voter Member
                'cnic_no' => $formatted_cnic, // CNIC No.
                'active_mobile_no' => $contact, // Contact No.
                'postal_address' => $address, // Address 1
                'address_2' => $row['address_2'], // Address 2
                'profile_image_name' => $row['image'], // Image
                'rf_id' => $row['rfid'], // RFID
                'profile_image_url' => 'applications/profile-images/LC/' . $row['image'],
                'print_date' => isset($row['print_date']) ? $row['print_date'] : 'N/A', // Print Date
                // 'card_status' => isset($row['card_status']) ? $row['card_status'] : '1', // Card Status

                // Application Data
                'submitted_by' => $admin->id,
                'application_type' => $this->excel_import_app_type,
                'application_status' => '1', // Active
                'is_excel_import' => TRUE,
            ];

            if ($application == NULL) {
                $application = Application::create($data);
                $application->update([
                    'application_token_no' => $application->id + 1000,
                ]);
            } else {
                $application->update($data);
            }
        }

        if (isset($row['rfid']) && !empty($row['rfid']) && isset($application)) {
            $application->update(['card_status' => 5]);
        } else {
            $application->update(['card_status' => 2]);
        }

        PrintSecureCard::create([
            'application_id' => $application->id,
            'admin_id' => Auth::guard('admin')->user()->id,
            'card_status' => $application->card_status
        ]);

        return $application;
    }
}
