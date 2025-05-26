<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>High Court License Report - {{ date('F d, Y - H:i A')}}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        /* Create two equal columns that floats next to each other */
        .column {
            float: left;
            width: 50%;
            padding: 15px;
            height: 300px;
        }

        /* Clear floats after the columns */
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

        .badge-dark {
            background: gray;
            border-radius: 2px;
            color: white;
            font-size: 12px;
            padding-top: 2px;
        }
    </style>

</head>

<body>
    @if ($filter['application_address'] == 'no')
    <div style="text-align: center">
        <h3>PBC - HIGH COURT LICENSE REPORT</h3>
        <h5 style="text-transform: uppercase; ">
            @isset($filter['division_name']) Division: {{ $filter['division_name'] }} @endisset
            @isset($filter['bar_name']) Voter Member: {{ $filter['bar_name'] }} @endisset
            @isset($filter['application_date'])
            @if ($filter['application_date'] == 1) Today
            @elseif ($filter['application_date'] == 2) Yesterday
            @elseif ($filter['application_date'] == 3) Last 7 Days
            @elseif ($filter['application_date'] == 4) Last 30 Days
            @elseif ($filter['application_date'] == 5) Date: {{$filter['application_custom_date']}}
            @elseif ($filter['application_date'] == 6) Date To - From:{{$filter['application_date_range']}}
            @endif @endisset
            @isset($filter['application_rcpt_date']) RCPT Date: {{ $filter['application_rcpt_date'] }} @endisset
            @isset($filter['application_status']) Application Status: {{getStatus($filter['application_status']) }}
            @endisset
            @isset($filter['payment_status']) Payment Status: {{ $filter['payment_status'] }} @endisset
        </h5>
        <h4>Total Applications: {{$applications->count()}}</h4>
    </div>

    <table>
        <thead>
            <tr style="background-color: rgb(77, 77, 77); color:white">
                <th>SR NO.</th>
                <th>APP NO.</th>
                <th>LICENSE NO.</th>
                <th>LAWYER/FATHER NAME</th>
                <th>ENR DATE</th>
                <th>VOTER MEMBER</th>
                <th>MOBILE</th>
                <th>CNIC</th>
                <th>BF NO</th>
            </tr>
        </thead>
        <tbody style="text-align: center">
            @foreach($applications->chunk(100) as $chunk)
            @foreach($chunk as $key => $application)
            <tr>
                <td>{{++$key}}</td>
                <td>{{ $application->id }}</td>
                <td>{{ $application->license_no_hc ?? '-' }}</td>
                <td>
                    {{ $application->u_name}} / {{$application->u_father}}
                </td>
                <td>{{ getDateFormat($application->enr_date_hc)}} </td>
                <td>{{ str_replace('BAR ASSOCIATION', '', getBarName($application->voter_member_hc)) ?? '-' }}</td>
                <td>{{ $application->mobile_no ? $application->mobile_no : '-' }} </td>
                <td>{{$application->cnic_no ?? '-'}}</td>
                <td>{{ $application->bf_no_lc ?? '-' }}</td>
                @endforeach
                @endforeach
        </tbody>
    </table>
    @else
    <div class="row" style="font-size:17px">
        @foreach($applications as $key => $application)
        <div class="column" style="text-align: center; border:1px solid black">
            <div>
                <h4>{{ $application->u_name}} SDW/o {{ $application->u_father}}</h4>
                <strong>Address:</strong> {{ getHcPostalAddress($application->id) }}, {{ $application->mobile_no}}
            </div>
            <div>
                <strong>License No:</strong>{{ $application->license_no_hc ?? 'N/A' }},
                <strong>Legder No:</strong>{{ $application->reg_no_hc ?? 'N/A' }}, <br>
                <strong>BF No:</strong>{{ $application->bf_no_hc ?? 'N/A' }},<br>
                <strong>RCPT No:</strong>{{ $application->rcpt_no_hc ?? 'N/A' }}
            </div>
        </div>

        @if ($loop->iteration % 8 == 0)
        <div>.</div>
        <div class="page-break"></div>
        @endif

        @endforeach
    </div>
    @endif
</body>

</html>