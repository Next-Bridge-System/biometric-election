<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PRINT ADDRESS</title>

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
    <table>
        @foreach ($address as $item)
        <tr style="text-align: center;">
            <th style="padding: 25px;font-size: 18px;">{{$loop->iteration}}</th>
            <td style="padding: 25px;font-size: 18px;">
                {{ getLawyerName($item->application_id) }} <br>
                ADVOCATE <br>
                {{ $item->address }} <br>
                {{ getLawyerMobileNo($item->application_id) }} <br>
            </td>
        </tr>
        @endforeach
    </table>
</body>

</html>
