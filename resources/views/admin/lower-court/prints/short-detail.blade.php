@extends('layouts.pdf')
@section('title') Lower Court - Report - {{$data['app_no']}} @endsection
@section('styles')
<style>
    #lc_short_detail_table table,
    #lc_short_detail_table th,
    #lc_short_detail_table td {
        padding: 5px !important;
        font-size: 14px !important;
        border: 1px solid black !important;
        text-align: left !important;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    .custom-image-preview {
        width: 50%;
        height: 100px
    }

    #lc_short_detail_table td {
        text-transform: uppercase
    }

    #lc_short_detail_table th {
        width: 18%
    }
</style>
@endsection
@section('content')
<div class="table-responsive" style="margin-top:5px">
    <table style="margin-bottom: 10px">
        <tr>
            <td style="text-align:center">
                <span style="font-size:20px"><b>PUNJAB BAR COUNCIL</b></span> <br>
                <span style="font-size:16px"><b>Advocate Lower Court Report</b></span>
            </td>
        </tr>
    </table>
    <table id="lc_short_detail_table">
        <tr>
            <th>App No:</th>
            <td>{{$data['app_no']}}</td>
            <th>Ledger No:</th>
            <th>{{$data['leg_no']}}</th>
            <td rowspan="3" colspan="2" style="text-align:center !important">
                <img src="{{$data['img_url']}}" class="custom-image-preview" alt="">
            </td>
        </tr>
        <tr>
            <th>License No:</th>
            <th>{{$data['lic_no']}}</th>

            <th>BF No:</th>
            <th>{{$data['bf_no']}}</th>
        </tr>
        <tr>
            <th>PLJ No:</th>
            <th>{{$data['plj_no']}}</th>

            <th>GI No:</th>
            <th>{{$data['gi_no']}}</th>
        </tr>
        <tr>
            <th>Lawyer Name:</th>
            <th colspan="5">{{$data['lawyer_name']}}</th>
        </tr>
        <tr>
            <th>S/D/W:</th>
            <td colspan="5">{{$data['sdw_name']}}</td>
        </tr>
        <tr>
            <th>CNIC No:</th>
            <td colspan="3">{{$data['cnic_no']}}</td>

            <th>Gender:</th>
            <td>{{$data['gender']}}</td>
        </tr>
        <tr>
            <th>Address:</th>
            <td colspan="5">{{$data['address']}}</td>
        </tr>
        <tr>
            <th>Contact No:</th>
            <td>{{$data['contact']}}</td>

            <th>Email:</th>
            <td colspan="3">{{$data['email']}}</td>
        </tr>
        <tr>
            <th>Enr Date:</th>
            <th colspan="2">{{$data['enr_date']}}</th>

            <th>Date of Birth:</th>
            <td colspan="2">{{$data['dob']}}</td>
        </tr>
        <tr>
            <th>Status:</th>
            <td colspan="3">
                <table>
                    <tr>
                        <td>
                            {!!$data['app_status']!!}
                        </td>
                        <td>
                            {{$data['payment_status'] ?? '-'}}
                        </td>
                    </tr>

                    @if ($data['other_status'])
                    <tr>
                        <td colspan="2">
                            {!!$data['other_status']!!}
                        </td>
                    </tr>
                    @endif
                </table>
            </td>
            <th>Voter Member:</th>
            <td>{{$data['voter_member']}}</td>
        </tr>
        <tr>
            <th>Status Reason:</th>
            <td colspan="5">
                {!!$data['status_reason']!!}
            </td>
        </tr>
    </table>
    <div style="margin-top: 5px">
        <div style="height: 30px;width:200px">{!!$data['bar_code']!!}</div>
        <span style="float:right">Printed on: {{date("F d, Y | h:i A")}}</span>
    </div>
</div>
@endsection