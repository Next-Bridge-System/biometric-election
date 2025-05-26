<?php

namespace App\Exports;

use App\Application;
use App\Bar;
use App\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class IntimationReportExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithMapping
{
    protected $filters;

    function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function headings(): array
    {
        $cols = [
            "APP NO.",
            "BAR",
            "LAWYER NAME",
            "FATHER NAME",
            "INTIMATION.",
            "MOBILE",
            "STATUS",
            "RCPT DATE",
            "RCPT NO",
        ];

        return $cols;
    }

    public function collection()
    {
        $applications = Application::intimationReport($this->filters)->get();
        return $applications;
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
        return [
            $query->app_token_no,
            $query->bar_name,
            $query->user_name,
            $query->app_father_husband,
            $query->intimation_start_date,
            $query->user_phone,
            $query->status,
            $query->app_rcpt_date,
            $query->app_rcpt_no,
        ];
    }
}
