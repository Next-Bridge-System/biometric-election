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
            font-size: 12px;
            /* text-transform: capitalize; */
            text-transform: uppercase;
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
    <h3>PBC - GENERAL REPORT</h3>
    <h5 style="text-decoration: underline">
        @isset($filter['application_type']) Application Type : {{ getApplicationType($filter['application_type']) }} @endisset
        @isset($filter['application_date']) Application Date : {{ $filter['application_date'] }} @endisset
        @isset($filter['payment_status']) Payment Status : {{ $filter['payment_status'] == 1 ? "Paid" : "Un Paid" }} @endisset
        @isset($filter['bar_id']) Bar Name : {{ getBarName($filter['bar_id']) }} @endisset
        @isset($filter['division_id']) Division: {{ getDivisionName($filter['division_id']) }} @endisset
    </h5>
    <h4>Total Applications: {{$applications->count()}}</h4>
</div>

<table>
    <thead>
    <tr style="background-color: rgb(77, 77, 77); color:white">
        <th>Application No.</th>
        <th>Lawyer Name</th>
        <th>Father's Name</th>
        <th>Mobile No</th>
        <th>Application Type</th>
        <th>Card Status</th>
        <th>Submitted By</th>
    </tr>
    </thead>
    <tbody style="text-align: center">
    @foreach($applications->chunk(100) as $chunk)
        @foreach($chunk as $application)
            <tr>
                <td>{{ $application->application_token_no }}</td>
                <td>{{ getLawyerName($application->id) ? getLawyerName($application->id) : '--' }}</td>
                <td>{{ $application->so_of ? $application->so_of : '--' }}</td>
                <td>{{ $application->active_mobile_no ? $application->active_mobile_no : '--' }} </td>
                <td>{{ getApplicationType($application->id) }} </td>
                <td>{{ getCardStatus($application->id) }} </td>
                <td>{{ getAdminName($application->admin_id) ?? '--' }} </td>
        @endforeach
    @endforeach
    </tbody>
</table>
</body>

</html>
