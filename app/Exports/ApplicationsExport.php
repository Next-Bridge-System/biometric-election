<?php

namespace App\Exports;

use App\Application;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;
use App\Bar;

class ApplicationsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithMapping
{
    protected $type, $date, $range;

    function __construct($type, $date, $range) {
        $this->app_type = $type;
        $this->app_date = $date;
        $this->app_date_range = $range;
    }

    public function headings(): array
    {
        if ($this->app_type == 1) {
            $cols = [
                "License #",
                "Name",
                "F name",
                "Enr. Date",
                "Voter Member",
                "CNIC No.",
                "Contact No.",
                "Address 1",
                "Address 2",
                "Image",
                "RFID",
                // "Print Date",
                // "Card Status",
            ];
        }

        if ($this->app_type == 2) {
            $cols = [
                "License #",
                "B.F #",
                "SR.#",
                "Name",
                "F name",
                "Enr. Date LC",
                "Enr. Date HC",
                "Voter Member LC",
                "Voter Member HC",
                "CNIC No.",
                "Contact No.",
                "Address 1",
                "Address 2",
                "Image",
                // "Print Date",
                // "Card Status",
            ];
        }

        if ($this->app_type == 3) {
            $cols = [
                "B.F #",
                "License #",
                "Name",
                "F name",
                "HCR #",
                "Enr. Date LC",
                "Enr. Date HC",
                "Voter Member LC",
                "Voter Member HC",
                "CNIC No.",
                "Contact No.",
                "Address 1",
                "Address 2",
                "Image",
                // "Print Date",
                // "Card Status",
            ];
        }

        if ($this->app_type == 4) {
            $cols = [
                "BF #",
                "Ledger #",
                "License #",
                "Name",
                "F name",
                "Date of birth",
                "Enr. Date",
                "Voter Member",
                "CNIC No.",
                "Contact No.",
                "Address 1",
                "Address 2",
                "Image",
                "RFID",
                "Voter Member ID",
                // "Print Date",
                // "Card Status",
            ];
        }

        return $cols;
    }

    public function collection()
    {
        $query = Application::orderBy('id','asc');

        if ($this->app_type == 1 || $this->app_type == 2 || $this->app_type == 3  || $this->app_type == 4) {

            if ($this->app_type == 1) {
                $query->select(
                    'license_no_lc',
                    'advocates_name',
                    'so_of',
                    'date_of_enrollment_lc',
                    'voter_member_lc',
                    'cnic_no',
                    'active_mobile_no',
                    'postal_address',
                    'address_2',
                    'profile_image_name',
                    'rf_id',
                    // 'print_date',
                    // 'card_status',
                );
            }

            if ($this->app_type == 2) {
                $query->select(
                    'license_no_hc',
                    'bf_no_hc',
                    'sr_no_hc',
                    'advocates_name',
                    'so_of',
                    'date_of_enrollment_lc',
                    'date_of_enrollment_hc',
                    'voter_member_lc',
                    'voter_member_hc',
                    'cnic_no',
                    'active_mobile_no',
                    'postal_address',
                    'address_2',
                    'profile_image_name',
                    // 'print_date',
                    // 'card_status',
                );
            }

            if ($this->app_type == 3) {
                $query->select(
                    'bf_no_hc',
                    'license_no_hc',
                    'advocates_name',
                    'so_of',
                    'hcr_no',
                    'date_of_enrollment_lc',
                    'date_of_enrollment_hc',
                    'voter_member_lc',
                    'voter_member_hc',
                    'cnic_no',
                    'active_mobile_no',
                    'postal_address',
                    'address_2',
                    'profile_image_name',
                    // 'print_date',
                    // 'card_status',
                );
            }

            if ($this->app_type == 4) {
                $query->select(
                    "bf_no_lc",
                    "reg_no_lc",
                    'license_no_lc',
                    'advocates_name',
                    'so_of',
                    "date_of_birth",
                    'date_of_enrollment_lc',
                    'voter_member_lc',
                    'cnic_no',
                    'active_mobile_no',
                    'postal_address',
                    'address_2',
                    'profile_image_name',
                    'rf_id',
                    // 'print_date',
                    // 'card_status',
                );
            }

            $query->where('application_type', $this->app_type);
        }

        return $query->get();
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:W1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            },
        ];
    }

    public function map($query): array
    {
        $formatted_mobile = NULL;
        if (isset($query->active_mobile_no) && !empty($query->active_mobile_no)) {
            $mobile = '0'.str_replace('-', '', $query->active_mobile_no);
            $formatted_mobile = substr($mobile, 0, 4) .'-'. substr($mobile, 4, 13);
        }

        $bar = Bar::find($query->voter_member_lc);
        $voter_member_lc = NULL;
        $voter_member_id = NULL;
        if (isset($bar)) {
            $voter_member_lc = str_replace('BAR ASSOCIATION', '', $bar->name);
            $voter_member_id = $bar->id;
        }

        if ($this->app_type == 4) {
            return [
                $query->bf_no_lc,
                $query->reg_no_lc,
                $query->license_no_lc,
                $query->advocates_name,
                $query-> so_of,
                $query->date_of_birth,
                $query->date_of_enrollment_lc,
                $voter_member_lc,
                $query->cnic_no,
                $formatted_mobile,
                $query-> postal_address,
                $query->address_2,
                $query->profile_image_name,
                $query->rf_id,
                $voter_member_id,
            ];
        }
    }
}
