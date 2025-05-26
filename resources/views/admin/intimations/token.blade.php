<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Intimation Application - {{$application->application_token_no}}</title>
</head>

<body>
    <table id="section-to-print">
        <tr style="text-align: center; font-size:14px">
            <td>
                <br>
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
            <td>
                <h4>INTIMATION APPLICATION NO.</h4>
                <h1>{{$application->application_token_no}}</h1>
            </td>
        </tr>
        <tr style="text-align: center">
            <td style="font: 16px; text-transform:uppercase">
                <b>NAME:</b> <span>{{getLawyerName($application->id)}}</span> <br>
                <b>CNIC:</b> <span>{{$application->cnic_no}}</span> <br>
                <b>STATUS:</b> <span>{!!appStatus($application->application_status,$application->app_type)!!}</span>
            </td>
        </tr>
        <tr>
            <td>
                <div style="margin-bottom: 40px"></div>
            </td>
        </tr>
        <tr style="text-align: center">
            <td style="font:12px">For tracking detail visist: <br> <a
                    href="https://admin.pbbarcouncil.com/applications/search"
                    style="font: 12px">https://admin.pbbarcouncil.com/applications/search</a></td>
        </tr>
        <tr>
            <td>
                <div style="margin-bottom: 20px"></div>
                <div>------------------------------------------------------------</div>
            </td>
        </tr>
    </table>

</body>

</html>