<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Intimation Application - {{$application->application_token_no}}</title>

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

        /* body {
            background-image: url('{{asset('public/admin/images/watermark.png')}}');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            z-index: -999;
        } */

        footer {
            position: fixed;
            bottom: -20px;
            left: 0px;
            right: 0px;
            height: 50px;
            /* text-align: center; */
            line-height: 35px;
        }

        .page-break {
            page-break-after: always;
        }

        td {
            line-height: 50px;
            font-size: 20px;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td colspan="2" style="height: 287px;width: 100%; margin-bottom: 50px"> </td>
        </tr>
        <tr>
            <td colspan="2" style="width: 100%; padding-left: 220px;">{{ $application->advocates_name }}</td>
        </tr>
        <tr>
            <td colspan="2" style="width: 100%; padding-left: 300px;">{{ $application->so_of }}
            </td>
        </tr>
        <tr>
            <td style="width: 100%;;padding-left: 180px">{{ date('jS',strtotime($application->date_of_enrollment_lc)) ??
                date('jS',strtotime($application->date_of_enrollment_hc)) }}</td>
            <td style="width: 100%;;padding-left: 270px">{{ date('F',strtotime($application->date_of_enrollment_lc)) ??
                date('F',strtotime($application->date_of_enrollment_hc)) }}</td>
        </tr>
        <tr>
            <td colspan="2" style="height: 270px;width: 100%; margin-bottom: 50px"> </td>
        </tr>
        <tr>
            <td style="width: 100%;;padding-left: 180px">{{ date('jS',strtotime($application->date_of_enrollment_lc)) ??
                date('jS',strtotime($application->date_of_enrollment_hc)) }}</td>
            <td style="width: 100%;;padding-left: 270px">{{ date('F',strtotime($application->date_of_enrollment_lc)) ??
                date('F',strtotime($application->date_of_enrollment_hc)) }}</td>
        </tr>
    </table>
</body>

</html>
