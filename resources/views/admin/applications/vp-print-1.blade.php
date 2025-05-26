<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>VP LETTER - {{date('d-m-Y h:i A', strtotime(Carbon\Carbon::now()))}}</title>
    <style>
        .whatever {
            page-break-after: always;
        }

        table {
            width: 100%;
        }

        .first,
        .second {
            width: 50px;
        }

        .second_lawyer_name {
            text-align: right;
            padding-right: 190px
        }
    </style>
</head>

<body>
    @foreach($applications as $application)
    <table>
        <tr>
            <td style="height: 440px; margin-bottom: 50px"> </td>
        </tr>
        <tr>
            <td style="padding-left: 140px;text-transform:uppercase">
                <b>{{$application['lawyer_name_full']}}</b>
            </td>
        </tr>
        <tr>
            <td style="height: 248px;width: 100%; margin-bottom: 50px"> </td>
        </tr>
        <tr>
            <td class="second_lawyer_name" style="text-transform:uppercase">
                <b>{{$application['lawyer_name_full']}}</b>
            </td>
        </tr>
        <tr>
            <td style="height: 55px;width: 100%; margin-bottom: 50px"></td>
        </tr>
        <tr>
            <td style="width: 100%;padding-left: 70px;text-transform:uppercase">
                <b>{{$application['reg_no_lc']}}</b>
            </td>
        </tr>
    </table>

    @if(!$loop->last)
    <div class="whatever"></div>
    @endif

    @endforeach
</body>

</html>