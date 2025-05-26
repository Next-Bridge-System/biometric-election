<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Application;
use App\Bar;
use App\GcHighCourt;
use App\GcLowerCourt;
use App\HighCourt;
use App\LowerCourt;
use Illuminate\Support\Facades\DB;

class VPApplicationExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    protected $app_type, $selected_records;

    function __construct($app_type, $selected_records)
    {
        $this->app_type = $app_type;
        $this->selected_records = $selected_records;
    }

    public function headings(): array
    {
        if ($this->app_type == 'lower_court') {
            $cols = [
                "bf_no",
                "ledger_no",
                "license_no",
                "name",
                "father_name",
                "dob",
                "enr_date",
                "voter_member",
                "cnic_no",
                "contact_no",
                "address_1",
                "address_2",
                "image",
                "rfid",
                "voter_member_id",
            ];
        }

        if ($this->app_type == 'high_court') {
            $cols = [
                "bf_no",
                "license_no",
                "lawyer_name",
                "father_name",
                "hcr_no",
                "enr_date_lc",
                "enr_date_hc",
                "voter_member_lc",
                "voter_member_hc",
                "cnic_no",
                "contact_no",
                "address_1",
                "address_2",
                "image",
            ];
        }

        return $cols;
    }

    public function collection()
    {
        if ($this->app_type == 'lower_court') {
            $gc_lc = GcLowerCourt::orderBy('gc_lower_courts.date_of_enrollment_lc', 'asc');
            $gc_lc->leftJoin('users as u', 'u.id', 'gc_lower_courts.user_id');
            $gc_lc->leftJoin('bars as b', 'b.id', '=', 'gc_lower_courts.voter_member_lc');
            $gc_lc->select(
                DB::raw('CASE WHEN gc_lower_courts.bf_no_lc THEN gc_lower_courts.bf_no_lc ELSE "N/A" END  as bf_no_lc'),
                'gc_lower_courts.reg_no_lc',
                'gc_lower_courts.license_no_lc',
                'gc_lower_courts.lawyer_name as lawyer_name_full',
                'gc_lower_courts.father_name',
                DB::raw("DATE_FORMAT(gc_lower_courts.date_of_birth, '%d-%m-%Y') as date_of_birth"),
                DB::raw("DATE_FORMAT(gc_lower_courts.date_of_enrollment_lc, '%d-%m-%Y') as date_of_enrollment_lc"),
                DB::raw('REPLACE(b.name, " Bar Association", "") as voter_member_name'),
                'gc_lower_courts.cnic_no as cnic_no_v2',
                DB::raw('CASE WHEN gc_lower_courts.contact_no THEN CONCAT("0", SUBSTRING(gc_lower_courts.contact_no, 1, 3), "-", SUBSTRING(gc_lower_courts.contact_no, 4)) ELSE "N/A" END  as contact_no'),
                'gc_lower_courts.address_1',
                'gc_lower_courts.address_2',
                DB::raw('CONCAT(gc_lower_courts.sr_no_lc,".gif","") as image'),
                'gc_lower_courts.rf_id',
                'gc_lower_courts.voter_member_lc as voter_member_id',
            );

            $gc_lc->whereIn('gc_lower_courts.id', $this->selected_records);
            $gc_lc->where('gc_lower_courts.app_status', 1);
            $gc_lc_records = $gc_lc->get()->toArray();

            $lc = LowerCourt::query()
                ->join('users as u', 'u.id', 'lower_courts.user_id')
                ->join('bars as b', 'b.id', 'lower_courts.voter_member_lc')
                ->leftJoin('lawyer_addresses as la', 'lower_courts.id', '=', 'la.lower_court_id')
                ->orderBy('lower_courts.date_of_enrollment_lc', 'asc')
                ->select(
                    DB::raw('CASE WHEN lower_courts.bf_no_lc THEN lower_courts.bf_no_lc ELSE "N/A" END  as bf_no_lc'),
                    'lower_courts.reg_no_lc',
                    'lower_courts.license_no_lc',
                    'u.name as lawyer_name_full',
                    'lower_courts.father_name',
                    DB::raw("lower_courts.date_of_birth as date_of_birth"),
                    DB::raw("DATE_FORMAT(lower_courts.lc_date, '%d-%m-%Y') as date_of_enrollment_lc"),
                    DB::raw('REPLACE(b.name, " Bar Association", "") as voter_member_name'),
                    'u.cnic_no as cnic_no_v2',
                    DB::raw('CONCAT("0", SUBSTRING(u.phone, 1, 3), "-", SUBSTRING(u.phone, 4)) as phone'),
                    DB::raw('CONCAT(
                                    la.ha_house_no,
                                    ", ",
                                    la.ha_str_address,
                                    ", ",
                                    la.ha_town,
                                    ", ",
                                    la.ha_city
                                ) as address_1'),
                    DB::raw('CONCAT("") as address_2'),
                    DB::raw('CONCAT(u.cnic_no,"") as image'),
                    'lower_courts.rf_id',
                    'lower_courts.voter_member_lc',
                );

            $lc->whereIn('lower_courts.id', $this->selected_records);
            $lc->where('lower_courts.app_status', 1);
            $lc_records = $lc->get()->toArray();

            $results = collect(array_merge($gc_lc_records, $lc_records));
            return $results;
        }

        if ($this->app_type == 'high_court') {
            $gchc = GcHighCourt::query()
                ->leftJoin('users as u', 'u.id', 'gc_high_courts.user_id')
                ->leftJoin('bars as b2', 'b2.id', 'gc_high_courts.voter_member_hc')
                ->orderBy('gc_high_courts.id', 'asc');

            $gchc->select(
                "gc_high_courts.bf_no_hc",
                "gc_high_courts.license_no_hc",
                "u.name as lawyer_name",
                "gc_high_courts.father_name as father_name",
                "gc_high_courts.hcr_no_hc",
                DB::raw("DATE_FORMAT(gc_high_courts.enr_date_lc, '%d-%m-%Y') as enr_date_lc"),
                DB::raw("DATE_FORMAT(gc_high_courts.enr_date_hc, '%d-%m-%Y') as enr_date_hc"),
                DB::raw('"" as voter_member_lc'),
                "b2.name as voter_member_hc",
                "u.cnic_no",
                DB::raw('CONCAT("0", SUBSTRING(u.phone, 1, 3), "-", SUBSTRING(u.phone, 4)) as phone'),
                "gc_high_courts.address_1",
                "gc_high_courts.address_2",
                "u.cnic_no as image",
            );

            $gchc->whereIn('gc_high_courts.id', $this->selected_records);
            $gchc_records =  $gchc->get()->toArray();

            $hc = HighCourt::query()
                ->join('users as u', 'u.id', 'high_courts.user_id')
                ->join('bars as b2', 'b2.id', 'high_courts.voter_member_hc')
                ->leftJoin('lawyer_addresses as la', 'high_courts.id', '=', 'la.high_court_id')
                ->orderBy('high_courts.id', 'asc');

            $hc->select(
                "high_courts.bf_no_hc",
                "high_courts.license_no_hc",
                "u.name as lawyer_name",
                "u.father_name as father_name",
                "high_courts.hcr_no_hc",
                DB::raw("DATE_FORMAT(high_courts.enr_date_lc, '%d-%m-%Y') as enr_date_lc"),
                DB::raw("DATE_FORMAT(high_courts.enr_date_hc, '%d-%m-%Y') as enr_date_hc"),
                DB::raw('"" as voter_member_lc'),
                "b2.name as voter_member_hc",
                "u.cnic_no",
                DB::raw('CONCAT("0", SUBSTRING(u.phone, 1, 3), "-", SUBSTRING(u.phone, 4)) as phone'),
                DB::raw('CONCAT(la.ha_house_no,", ",la.ha_city) as address_1'),
                DB::raw('CONCAT("-","") as address_2'),
                "u.cnic_no as image",
            );

            $hc->whereIn('high_courts.id', $this->selected_records);
            $hc_records =  $hc->get()->toArray();

            $results = collect(array_merge($gchc_records, $hc_records));

            return $results;
        }
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

    // public function map($query): array
    // {
    //     $formatted_mobile = NULL;
    //     if (isset($query->contact_no) && !empty($query->contact_no)) {
    //         $mobile = '0' . str_replace('-', '', $query->contact_no);
    //         $formatted_mobile = substr($mobile, 0, 4) . '-' . substr($mobile, 4, 13);
    //     }

    //     $bar = Bar::find($query->voter_member_lc);
    //     $voter_member_lc = NULL;
    //     $voter_member_id = NULL;
    //     if (isset($bar)) {
    //         $voter_member_lc = str_replace('BAR ASSOCIATION', '', $bar->name);
    //         $voter_member_id = $bar->id;
    //     }

    //     if ($this->app_type == 'lower-court') {
    //         return [
    //             $query->bf_no_lc,
    //             $query->reg_no_lc,
    //             $query->license_no_lc,
    //             $query->lawyer_name,
    //             $query->father_name,
    //             $query->date_of_birth,
    //             $query->date_of_enrollment_lc,
    //             $voter_member_lc,
    //             $query->cnic_no,
    //             $formatted_mobile,
    //             $query->postal_address,
    //             $query->address_2,
    //             $query->profile_image_name,
    //             $query->rf_id,
    //             $voter_member_id,
    //         ];
    //     }

    //     if ($this->app_type == 'high-court') {
    //         return [
    //             $query->bf_no_hc,
    //             $query->license_no_hc,
    //             $query->lawyer_name,
    //             $query->father_name,
    //             $query->hcr_no_hc,
    //             $query->enr_date_lc,
    //             $query->enr_date_hc,
    //             $query->cnic_no,
    //             $formatted_mobile,
    //             $query->postal_address,
    //             $query->address_2,
    //             $query->profile_image_name,
    //             $voter_member_id,
    //         ];
    //     }
    // }
}
