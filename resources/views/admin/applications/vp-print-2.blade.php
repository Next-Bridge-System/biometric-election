<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>VP ENVELOP - {{date('d-m-Y h:i A', strtotime(Carbon\Carbon::now()))}}</title>
    <style>
        .whatever {
            page-break-after: always;
        }
    </style>
</head>

<body>
    @foreach($applications as $application)
    <div>
        <table>
            <tr>
                <td colspan="2" style="height: 130px; margin-bottom: 50px"> </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-left: 100px; line-height: 2.0; height:160px; text-transform:uppercase">
                    <b>
                        {{$application['lawyer_name_full']}} ADVOCATE <br>
                        {{$application['address_1']}}
                        {{$application['address_2']}} <br>
                        {{$application['contact_no']}}
                    </b>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-left: 470px; line-height: 4.0;position: fixed; text-transform:uppercase">
                    <b>{{$application['reg_no_lc']}}</b>
                </td>
            </tr>
        </table>
    </div>

    @if(!$loop->last)
    <div class="whatever"></div>
    @endif

    @endforeach
</body>

</html>