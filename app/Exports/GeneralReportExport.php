<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class GeneralReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents,WithMapping
{
    protected $applications;

    function __construct($applications) {

        $this->applications = $applications;
    }

    public function headings(): array
    {
        $cols = [
        "Application No.",
        "Lawyer Name",
        "Father's Name",
        "Mobile No",
        "Application Type",
        "Card Status",
        "Submitted By",
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
        $payment_status = getPaymentStatus($query->id);
        $application_status = getApplicationStatus($query->id);
        $applicationType = getApplicationType($query->id);
        $cardStatus = getCardStatus($query->id);
        $adminName = getAdminName($query->admin_id);

        return [
            $query->application_token_no,
            $query->advocates_name,
            $query->so_of,
            $query->active_mobile_no,
            $applicationType,
            $cardStatus,
            $adminName,
        ];


    }
}
