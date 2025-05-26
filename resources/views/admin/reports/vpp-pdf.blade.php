<?php
$bar = App\Bar::find($station);
$station_name = isset($bar->name) ? $bar->name : 'ALL Stations';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Intimation Reports - PDF</title>

    <style>
        * {
            box-sizing: border-box;
        }

        .column {
            float: left;
            width: 50%;
            padding: 10px;
            height: 300px;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .dynamic-text {
            /* font-size: 18px;
                    border: 1px solid;
                    padding: 4px */
            font: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .text-underline {
            text-decoration: underline;
        }

        .text-justify {
            text-align: justify;
        }

        footer {
            position: fixed;
            bottom: -20px;
            left: 0px;
            right: 0px;
            height: 50px;
            line-height: 35px;
        }

        .page-break {
            page-break-after: always;
        }

        table,
        th,
        td {
            border: 1px solid rgb(194, 189, 189);
            padding: 1px;
            font-size: 9px;
            text-transform: capitalize;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        fieldset legend {
            color: green;
            font-weight: 600;
        }

        fieldset.border {
            border: 2px solid #17af23 !important;
            border-radius: 5px !important;
        }

        .mb-4 {
            margin-bottom: 20px
        }
    </style>

</head>

<body>
    <div style="text-align: center">
        <h5 style="text-decoration: underline">
            @if ($app_type == 1) VPP Report - Lower Court
            @elseif($app_type == 2) VPP Report - High Court
            @elseif($app_type == 3) VPP Report - Renewal High Court
            @elseif($app_type == 4) VPP Report - Renewal Lower Court
            @else VPP Report
            @endif <br>
            <small>{{$station_name}} ({{isset($year) ? $year : 'ALL Years'}})</small>
        </h5>
        <h6>Total Applications: {{$applications->count()}}, Total Delivered: {{$totalDelivered}}, Total
            Returned: {{$totalReturned}}</h6>
    </div>

    <table>
        <thead>
            <tr style="background-color: rgb(77, 77, 77); color:white">
                <th>Sr.No.</th>
                <th>App No.</th>
                <th>Legder No.</th>
                <th>Name <br> Father/Husband Name</th>
                <th>DOE</th>
                <th>Fee/Total Dues</th>
                <th>VPP Number</th>
                <th>VPP Status</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody style="text-align: center">
            @foreach ($applications->chunk(100) as $chunk)
            @foreach($chunk as $key => $application)
            <tr>
                <td>{{++$key}}</td>
                <td>{{$application->application_token_no}}</td>
                <td>{{isset($application->reg_no_lc) ? $application->reg_no_lc : ''}}</td>
                <td>
                    {{isset($application->advocates_name) ? $application->advocates_name : ''}}
                    {{isset($application->last_name) ? $application->last_name : ''}} <br>
                    {{isset($application->so_of) ? $application->so_of : ''}}
                </td>
                <td>{{isset($application->date_of_enrollment_lc) ? $application->date_of_enrollment_lc : ''}}</td>
                <td>
                    <b>Year Fee:</b> {{isset($application->vppost->vpp_fees_year) ? $application->vppost->vpp_fees_year
                    : '750'}} <br>
                    <b>Total Dues:</b> {{isset($application->vppost->vpp_total_dues) ?
                    $application->vppost->vpp_total_dues : '750'}} <br>
                </td>
                <td>{{isset($application->vppost->vpp_number) ? $application->vppost->vpp_number : 'No'}}</td>
                <td>
                    <b>Delivered:</b> {{isset($application->vppost->vpp_delivered) ? $application->vppost->vpp_delivered
                    : 'No'}} <br>
                    <b>Returned:</b> {{isset($application->vppost->vpp_returned) ? $application->vppost->vpp_returned :
                    'No'}} <br>
                    <b>Duplicate:</b> {{isset($application->vppost->vpp_duplicate) ?
                    $application->vppost->vpp_duplicate: 'No'}} <br>
                </td>
                <td>{{isset($application->vppost->vpp_remarks) ? $application->vppost->vpp_remarks
                    : 'No Remarks'}}</td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>
</body>

</html>
