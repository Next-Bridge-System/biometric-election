<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Biometric Token</title>
</head>

<body>
    <table id="section-to-print">
        <tr style="text-align: center; font-size:14px">
            <td>
                <b>PUNJAB BAR COUNCIL, <br> 9-FANE ROAD, LAHORE</b> <br>
                <b>Phone: 042-99214245-49, <br> Fax 042-99214250</b> <br>
                <b>E-mail; info@pbbarcouncil.com,</b> <br>
                <b>URL: www.pbbarcouncil.com</b> <br>
            </td>
        </tr>
        <tr>
            <td> <br> </td>
        </tr>

        <tr style="text-align: center">
            <td>Application Token # : <br>
                <h1>{{$application->application_token_no}}</h1>
            </td>
        </tr>
        <tr style="text-align: center">
            <td>Lawyer Name: <br>
                <h4>{{getLawyerName($application->id)}}</h4>
            </td>
        </tr>
        <tr style="text-align: center">
            <td>CNIC No: <br>
                <h4>{{$application->cnic_no}}</h4>
            </td>
        </tr>
        <tr style="text-align: center">
            <td>License No: <br>
                <h4>
                    @if ($application->application_type == 1 || $application->application_type == 4 )
                    {{$application->license_no_lc}}
                    @elseif($application->application_type == 2 || $application->application_type == 3)
                    {{$application->license_no_hc}}
                    @else N/a @endif
                </h4>
            </td>
        </tr>
        <tr style="text-align: center">
            <td>Voter Member: <br>
                <h4>
                    @if ($application->application_type == 1 || $application->application_type == 4 )
                    {{getBarName($application->voter_member_lc)}}
                    @elseif($application->application_type == 2 || $application->application_type == 3)
                    {{getBarName($application->voter_member_hc)}} @else N/a @endif
                </h4>
            </td>
        </tr>
        <tr>
            <td>
                <div style="margin-bottom: 50px"></div>
                <div> -------------------------------------------- </div>
            </td>
        </tr>
    </table>
</body>

</html>
