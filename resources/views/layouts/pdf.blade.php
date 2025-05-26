<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> @yield('title') </title>

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
            font: bolder;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .flex-item {
            display: inline-flex;
        }

        .dynamic-text-dotted {
            /* font-size: 18px;
            border: 1px solid;
            padding: 4px */
            display: inline;
            font-weight: bold;
            width: 100%;
            text-transform: uppercase;
            border-bottom: dotted 2px black;
        }

        .text-underline {
            text-decoration: underline;
        }

        .text-justify {
            text-align: justify;
        }

        .field-margin {
            margin: 10px auto
        }

        .table-50-w td {
            width: 50%;
            font-size: 14px;
        }

        .table-bordered table,
        .table-width-100 table {
            width: 100%;
            margin: 10px 0px;
        }

        .bordered {
            margin-right: -5px;
            width: 15px;
            padding: 5px 5px;
            border: 1px solid;
        }

        .table-bordered table,
        .table-bordered th,
        .table-bordered td {
            border: 1px solid rgba(0, 0, 0, 1);
            padding: 1px;
            border-collapse: collapse;
        }

        .text-center {
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: -20px;
            left: 0px;
            right: 0px;
            height: 50px;
            /* text-align: center; */
            line-height: 35px;
        }

        .bold-underline {
            text-decoration: underline;
            font-weight: bolder;
        }

        .ol-spacing li,
        .table-bordered td,
        .p-text {
            margin: 6px 0px;
        }

        .p-text {
            line-height: 35px;
        }

        .p-justify {
            text-justify: inherit;
        }

        .ur-text {
            font-family: DejaVu Sans, sans-serif;
            direction: rtl !important;
            text-align: right;
        }

        .ol-alpha {
            list-style-type: lower-alpha;
        }

        /*.ol-alpha li::before{*/
        /*    counter-increment: list;*/
        /*    content: "("counter(list, lower-alpha) ") ";*/
        /*    position: absolute;*/
        /*    left: -1.4em;*/
        /*}*/



        .ol-roman {
            list-style-type: lower-roman;
        }

        /*.ol-roman li::before {*/
        /*    counter-increment: list;*/
        /*    content: "("counter(list, lower-alpha) ") ";*/
        /*    !*position: absolute;*!*/
        /*    left: -1.4em;*/
        /*}*/

        .ol-roman li {
            margin-bottom: 5px;
        }

        /*.ol-alpha > li {
            list-style: none;
            position: relative;
        }

        .ol-roman > li {
            list-style: none;
            position: relative;
        }*/

        .page-break {
            page-break-after: always;
        }
    </style>


    @if (Route::currentRouteName() != 'lower-court.prints.short-detail')
    <style>
        body {
            background-image: url('{{asset('public/admin/images/watermark.png')}}');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            z-index: -999;
            font-size: 14px;
        }
    </style>
    @endif


    @yield('styles')
</head>

<body>
    <table style="border:none !important; width: 100%;">
        <tr>
            <td style="text-align: center">
                <img style="height: 70px;width: auto;" src="{{ asset('public/admin/images/pbc_qsb.png') }}" alt="">
            </td>
        </tr>
    </table>
    <main>
        @yield('content')
    </main>
</body>

</html>