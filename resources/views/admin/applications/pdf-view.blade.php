<table id="section-to-print">
    <tr style="text-align: center; font-size:14px">
        <td>
            {{-- <img src="{{asset('public/admin/images/logo.png')}}" style="width: 100px" alt="PUNJAB BAR COUNCIL">
            --}}
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
        <td>Application Token # : <br>
            <h1>{{$application->application_token_no}}</h1>
        </td>
    </tr>
    <tr style="text-align: center">
        <td>Advocated Name: <br>
            <h4>{{$application->advocates_name}}</h4>
        </td>
    </tr>
    <tr>
        <td>
            <div style="margin-bottom: 200px"></div>
            <div>
                ------------------------------------
            </div>
        </td>
    </tr>
</table>
