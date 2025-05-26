<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Voter Member Report - {{ date('F d, Y - H:i A')}}</title>

    <style>
        body {
            color: black;
            font-family: Arial, sans-serif;
        }

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
            font-size: 12px;
            text-transform: uppercase;
            height: 30px;
            padding-top: 3px;
            padding-bottom: 10px;
            padding-left: 3px;
            border: 1px solid black
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

        @media print {
            .record {
                page-break-inside: avoid;
            }
        }

        .bg-light-green {
            background: #00b44e61 !important;
        }
    </style>

</head>

<body>
    <div style="font-weight:bold">
        <div style="text-align: center;margin-bottom:20px">
            <span style="font-size:26px;">PUNJAB BAR COUNCIL</span> <br>
            <span style="font-size:20px">
                @if ($filter['search_user_type'] == 'LC') Advocates Lower Court List @endif
                @if ($filter['search_user_type'] == 'HC') Advocates High Court List @endif
                @if ($filter['search_user_type'] == 'all') Advocates Lower Court/High Court List @endif
            </span>
        </div>
        <div style="float: left;">
            @isset($filter['search_voter_member'])
            <u>{{ getVoterMemberName($filter['search_voter_member']) }}</u>
            @endisset
        </div>


    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 1px">SR #</th>
                <th style="width: 155px">Lawyer Name</th>
                <th style="width: 60px">LC Date</th>

                @if ($filter['search_user_type'] == 'LC' || $filter['search_user_type'] == 'all')
                <th style="width: 60px">LC Ledger</th>
                @endif

                @if ($filter['search_user_type'] == 'HC' || $filter['search_user_type'] == 'all')
                <th style="width: 60px">HC Date</th>
                @endif

                <th style="width: 200px">Address</th>

                <th style="width: 20px">Image</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $key => $record)
            <tr class="record {{$record['status'] == 'approved' ? 'bg-light-green': ''}}">
                <td style="text-align: center">{{++$key}}</td>
                <td>{{$record['lawyer']}} <br>S/D/W {{$record['father']}}</td>
                <td style="text-align: center">{{getDateFormat($record['enr_date_lc'])}}</td>
                @if ($filter['search_user_type'] == 'LC' || $filter['search_user_type'] == 'all')
                <td style="text-align: center">{{$record['lc_ledger']}}</td>
                @endif
                @if ($filter['search_user_type'] == 'HC' || $filter['search_user_type'] == 'all')
                <td style="text-align: center">{{isset($record['enr_date_hc']) ? getDateFormat($record['enr_date_hc']) : ''}}</td>
                @endif
                <td>{{$record['address']}},{{$record['phone']}}</td>
                <td><img style="width:45px;height:40px" src="{{asset('storage/app/public/'.$record['profile_image'])}}">
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
