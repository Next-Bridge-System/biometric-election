<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Police Verification - {{ getUserName($application->id) }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .whatever {
            page-break-after: always;
        }

        table.bluetable {
            border: 1px solid #000000;
            background-color: #ffffff;
            width: 100%;
            text-align: left;
            border-collapse: collapse;
        }
        table.bluetable td,
        table.bluetable th {
            border: 1px solid #020202;
            padding: 3px 2px;
            font-size: 13px;
        }

        .first,
        .second {
            width: 50px;
        }


    </style>
</head>

<body>
<table style="width: 100%;">
    <tr>
        <td  style="text-align: center">
            <img style="height: 100px;width: auto;text-align: center" src="{{ asset('public/admin/images/logo.png') }}" alt="">
            <img style="height: 100px;width: auto;text-align: center" src="{{ asset('public/admin/images/qsbu.png') }}" alt="">
        </td>
    </tr>
</table>
<table>
    <tr>
        <th style="text-align: left">Application No : </th>
        <td>{{ $application->application_token_no }}</td>
    </tr>
    <tr>
        <th style="text-align: left">Name : </th>
        <td>{{ getUserName($application->id) }}</td>
    </tr>
    <tr>
        <th style="text-align: left">Application Type : </th>
        <td>{{ getApplicationType($application->id) }}</td>
    </tr>
    <br>
</table>
<table class="bluetable">
    <thead>
    <tr>
        <th style="width:20px">#</th>
        <th>Fir District</th>
        <th>Fir PS</th>
        <th>Fir No.</th>
        <th>Fir Year</th>
        <th>Fir Offence Date</th>
        <th>Fir Offecnce</th>
        <th>Fir Status</th>
        <th>Sus. Name</th>
        <th>Sus. Parent_name</th>
        <th>Sus. Gender</th>
        <th>Sus. Cast</th>
        <th>Sus. Address</th>
        <th>Sus. Phone</th>
        <th>Sus. Status</th>
    </tr>
    </thead>
    <tbody>
    @php
        $json = remove_utf8_bom($application->fir->data);
        $records  = json_decode($json, TRUE)

    @endphp
    @if(isset($records['message']) && $records['message'] != null)
        <tr>
            <td colspan="15">{{ $records['message'] }}</td>
        </tr>
    @else
        @foreach($records as $key => $record)
            <tr>
                <td style="width:20px">{{ $key + 1 }}</td>
                <td>{{ $record['fir_district'] }}</td>
                <td>{{ $record['fir_ps'] }}</td>
                <td>{{ $record['fir_no'] }}</td>
                <td>{{ $record['fir_year'] }}</td>
                <td>{{ $record['fir_offence_date'] }}</td>
                <td>{{ $record['fir_offecnce'] }}</td>
                <td>{{ $record['fir_status'] }}</td>
                <td>{{ $record['sus_name'] }}</td>
                <td>{{ $record['sus_parent_name'] }}</td>
                <td>{{ $record['sus_gender'] }}</td>
                <td>{{ $record['sus_cast'] }}</td>
                <td>{{ $record['sus_address'] }}</td>
                <td>{{ $record['sus_phone'] }}</td>
                <td>{{ $record['sus_status'] }}</td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
<br>
<br>
<table>
    <tr>
        <th>Verification Status</th>
        <td>@if($application->fir->verified == 1)
                <strong>Verified</strong> By {{ getAdminName($application->fir->verified_by) ?? "Unknown" }}
            @else
                Unverified
            @endif</td>
    </tr>
</table>
</body>

</html>
