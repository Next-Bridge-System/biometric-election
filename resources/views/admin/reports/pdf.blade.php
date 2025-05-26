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

        body {
            background-image: url('{{asset('public/admin/images/watermark.png')}}');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            z-index: -999;
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
            padding: 3px;
            font-size: 12px;
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
        <p>
            <img src="{{asset('public/admin/images/logo.png')}}" alt="" width="85px;"> <br>
            <span style="font: bold;font-size: 20px ">PUNJAB BAR COUNCIL</span><br>
            9-FANE ROAD, LAHORE, Phone:042-99214245-49, Fax 042-99214250 <br>
            E-mail:info@pbbarcouncil.com, URL:www.pbbarcouncil.com <br>
            Facebook: /Pbbarcouncil, Instagram: /Pbbarcouncil,Youtube: /Pbbarcouncil
        </p>
    </div>

    <div style="text-align: center">
        <h3 style="text-decoration: underline">
            @if ($application_type == 1) Lower Court Report
            @elseif($application_type == 2) High Court Report
            @elseif($application_type == 3) Renewal High Court Report
            @elseif($application_type == 4) Renewal Lower Court Report
            @elseif($application_type == 5) Existing Lawyers Report
            @elseif($application_type == 6) Intimation Report
            @endif
        </h3>
    </div>

    <table>
        <thead>
            <tr style="background-color: grey">
                <th>Sr.No.</th>
                <th>Diary No</th>
                <th>Recieved Date</th>
                <th>Name <br> Father/Husband Name</th>
                <th>Senior Advocate Name <br> Joining Date</th>
                <th>Address/Voter Member</th>
                <th>Mobile</th>
                <th>DOB</th>
            </tr>
        </thead>
        <tbody style="text-align: center">
            @forelse ($applications as $application)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$application->application_token_no}}</td>
                <td>{{$application->intimation_start_date ?? '-'}}</td>
                <td>{{getLawyerName($application->id)}} <br> {{$application->so_of}}</td>
                <td>{{$application->srl_name}} <br> {{$application->srl_enr_date}} </td>
                <td>{{isset($application->address->ha_city) ? $application->address->ha_city : '' }} {{
                    isset($application->barAssociation->name) ? '/'.$application->barAssociation->name : ''}}
                </td>
                <td>{{isset($application->active_mobile_no) ? '+92'.$application->active_mobile_no : ''}}</td>
                <td>{{$application->date_of_birth}}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center"><b>No Record Found.</b></td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
