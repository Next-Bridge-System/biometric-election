<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class VPPReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents,WithMapping
{
    protected $applications;

    function __construct($applications) {
        $this->applications = $applications;
    }

    public function headings(): array
    {
        $cols = [
            "App No.",
            "Legder No.",
            "Name  Father/Husband Name",
            "DOE",
            "Fee/Total Dues",
            "VPP Number",
            "VPP Delivered",
            "VPP Returned",
            "VPP Duplicate",
            "Remarks",
        ];

        return $cols;
    }

    public function collection()
    {
        return $this->applications;
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
        $vpp_number=  isset($query->vppost->vpp_number) ? $query->vppost->vpp_number: '';
        $vpp_fees_year=  isset($query->vppost->vpp_fees_year) ? $query->vppost->vpp_fees_year: '750';
        $vpp_total_dues=  isset($query->vppost->vpp_total_dues) ?    $query->vppost->vpp_total_dues : '750';
        $vpp_delivered=  isset($query->vppost->vpp_delivered) ? $query->vppost->vpp_delivered: 'No';
        $vpp_returned=  isset($query->vppost->vpp_returned) ? $query->vppost->vpp_returned :'No';
        $vpp_duplicate=  isset($query->vppost->vpp_duplicate) ? $query->vppost->vpp_duplicate: 'No';
        $vpp_remarks=  isset($query->vppost->vpp_remarks) ? $query->vppost->vpp_remarks : 'No Remarks';

        return [
            $query->application_token_no,
            $query->reg_no_lc,
            $query->so_of,
            $query->date_of_enrollment_lc,
            $vpp_fees_year .'/'.$vpp_total_dues,
            $vpp_number,
            $vpp_delivered,
            $vpp_returned,
            $vpp_duplicate,
            $vpp_remarks
        ];


    }
}
