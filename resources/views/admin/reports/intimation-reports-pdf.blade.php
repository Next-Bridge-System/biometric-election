<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Intimation Reports - PDF</title>

    <style>
        body {
            color: black;
            font-family: Arial, sans-serif;
        }

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
            font-size: 10px;
            text-transform: uppercase;
            padding-bottom: 10px;
            padding-top: 10px;
            padding-left: 3px
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
    <div style="text-align: center">
        <h3>INTIMATION REPORT</h3>
        <h5 style="text-transform: uppercase; ">

            @if ($filter['division_name'])
            Division: {{ $filter['division_name'] }}
            @endif

            @if ($filter['bar_name'])
            Voter Member: {{ $filter['bar_name'] }} <br>
            @endif

            @if ($filter['application_status'])
            Application Status: {{$filter['application_status']}}
            @endif

            @if ($filter['payment_status'])
            Payment Status: {{ $filter['payment_status'] }} <br>
            @endif

            @if ($filter['application_date'] == "today") Today
            @elseif ($filter['application_date'] == "yesterday") Yesterday
            @elseif ($filter['application_date'] == "last_7_days") Last 7 Days
            @elseif ($filter['application_date'] == "last_30_days") Last 30 Days
            @elseif ($filter['application_date'] == "date_range")
            {{clean($filter['application_date_type'])}}: {{$filter['custom_date_range_input']}}
            @endif
        </h5>
        <h4>Total Applications: {{$applications->count()}}</h4>
    </div>

    <div>
        <table>
            <thead>
                <tr style="background-color: rgb(77, 77, 77); color:white">
                    <th>SR NO.</th>
                    <th>APP NO.</th>
                    <th>BAR NAME</th>
                    <th>LAWYER NAME</th>
                    <th>CNIC</th>
                    <th>DATE</th>
                    <th>MOBILE</th>
                    <th>STATUS</th>
                    <th>RCPT</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($applications as $application)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{ $application->app_token_no }}</td>
                    <td>{{ $application->bar_name }}</td>
                    <td>{{ $application->user_name }} <br> {{ $application->app_father_husband}}</td>
                    <td>{{ $application->user_cnic }}</td>
                    <td>{{ $application->intimation_start_date }}</td>
                    <td>{{ $application->user_phone }}</td>
                    <td>{{ $application->status }}</td>
                    <td>{{ $application->app_rcpt_date }}, {{ $application->app_rcpt_no }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>