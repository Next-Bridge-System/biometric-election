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
                @if ($filter['search_user_type'] == 'lc') Advocates Lower Court List @endif
                @if ($filter['search_user_type'] == 'hc') Advocates High Court List @endif
            </span>
        </div>
        <div style="float: left;">
            @isset($filter['search_voter_member'])
            <u>{{ getVoterMemberName($filter['search_voter_member']) }}</u>
            @endisset
        </div>

        @if ($filter['search_start_date'] && $filter['search_end_date'])
        <div style="float: right;">
            From: <u>{{ getDateFormat($filter['search_start_date']) }}</u>
            To: <u>{{ getDateFormat($filter['search_end_date']) }}</u>
        </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 1px">SR #</th>
                <th style="width: 155px">Lawyer Name</th>

                @if (isset($filter['extra_cols']) && in_array('dob', $filter['extra_cols']))
                <th style="width: 60px">DOB</th>
                @endif

                @if (isset($filter['extra_cols']) && in_array('cnic_no', $filter['extra_cols']))
                <th style="width: 60px">CNIC NO</th>
                @endif

                <th style="width: 60px">LC Date</th>

                @if (isset($filter['extra_cols']) && in_array('lc_ledger', $filter['extra_cols']))
                <th style="width: 60px">LC Ledger</th>
                @endif

                @if ($filter['search_user_type'] == 'lc' && isset($filter['extra_cols']) && in_array('lc_license', $filter['extra_cols']))
                <th style="width: 60px">LC License #</th>
                @endif

                @if ($filter['search_user_type'] == 'hc')
                <th style="width: 60px">HC Date</th>
                @endif

                @if ($filter['search_user_type'] == 'hc' && isset($filter['extra_cols']) && in_array('hc_hcr', $filter['extra_cols']))
                <th style="width: 60px">HCR #</th>
                @endif

                @if ($filter['search_user_type'] == 'hc' && isset($filter['extra_cols']) && in_array('hc_license', $filter['extra_cols']))
                <th style="width: 60px">HC License #</th>
                @endif

                <th style="width: 200px">Address</th>

                @if (isset($filter['extra_cols']) && in_array('phone', $filter['extra_cols']))
                <th style="width: 30px">Phone</th>
                @endif

                @if (isset($filter['extra_cols']) && in_array('image', $filter['extra_cols']))
                <th style="width: 20px">Image</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($records as $key => $record)
            <tr class="record {{$record->status == 'approved' ? 'bg-light-green': ''}}">
                <td style="text-align: center">{{++$key}}</td>
                <td>{{$record->lawyer}} <br>S/D/W {{$record->father}}</td>

                @if (isset($filter['extra_cols']) && in_array('dob', $filter['extra_cols']))
                <td style="text-align: center">{{$record->dob}}</td>
                @endif

                @if (isset($filter['extra_cols']) && in_array('cnic_no', $filter['extra_cols']))
                <td style="text-align: center">{{$record->cnic_no}}</td>
                @endif

                <td style="text-align: center">{{getDateFormat($record->enr_date_lc)}}</td>

                @if (isset($filter['extra_cols']) && in_array('lc_ledger', $filter['extra_cols']))
                <td style="text-align: center">{{$record->lc_ledger}}</td>
                @endif

                @if ($filter['search_user_type'] == 'lc' && isset($filter['extra_cols']) && in_array('lc_license', $filter['extra_cols']))
                <td style="text-align: center">{{$record->lc_license}}</td>
                @endif

                @if ($filter['search_user_type'] == 'hc')
                <td style="text-align: center">{{getDateFormat($record->enr_date_hc)}}</td>
                @endif

                @if ($filter['search_user_type'] == 'hc' && isset($filter['extra_cols']) && in_array('hc_hcr', $filter['extra_cols']))
                <td style="text-align: center">{{$record->hc_hcr}}</td>
                @endif

                @if ($filter['search_user_type'] == 'hc' && isset($filter['extra_cols']) && in_array('hc_license', $filter['extra_cols']))
                <td style="text-align: center">{{$record->hc_license}}</td>
                @endif

                <td>{{$record->address}}</td>

                @if (isset($filter['extra_cols']) && in_array('phone', $filter['extra_cols']))
                <td>{{$record->phone}}</td>
                @endif

                @if (isset($filter['extra_cols']) && in_array('image', $filter['extra_cols']))
                <td>
                    <img style="width:45px;height:40px" src="{{asset('storage/app/public/'.$record->profile_image)}}">
                </td>
                @endif

            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>