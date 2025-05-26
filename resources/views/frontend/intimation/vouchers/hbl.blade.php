<?php
$res = explode(".",$application->age);
if (isset($res[0]) && isset($res[1])) {
    if ($res[1] == 0) {
        $age = $res[0].'Y';
    } else {
        $age = $res[0].'Y'.' '.$res[1].'M';
    }
} else {
    $age = $application->age.'Y';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HBL-Online-Voucher-{{$application->application_token_no}}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        /* Create two equal columns that floats next to each other */
        .column {
            float: left;
            width: 25%;
            padding: 0px;
            height: 300px;
            /* Should be removed. Only for demonstration */
        }

        /* Clear floats after the columns */
        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .text-lg {
            font-size: 14px;
            font: bold;
            padding-left: 1px;
            padding-right: 1px;
        }

        table,
        th,
        td {
            border: 1px solid rgb(46, 45, 45);
            padding: 2px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
    </style>
</head>

<body>

    <div class="row">

        <?php for ($i=1; $i <= 4; $i++) { ?>

        <div class="column">

            @if (isset($payment) && $payment->payment_status == 1)
            <div style="left:10%; top:51.50%; position:absolute;">
                <img src="{{asset('public/admin/images/paid.png')}}" style="width:150px">
            </div>
            @endif

            <div style="text-align: center;">
                <img src="{{asset('public/admin/images/hbl.png')}}" alt="" style="width: 120px; padding-right:70px">
                <img src="{{asset('public/admin/images/logo.png')}}" alt="" style="width: 50px">
            </div>
            <div style="text-align: center;">
                <span style="font-size: 16px; font:bold">Punjab Bar Council</span> <br>
                <span style="font-size: 16px; font:bold;">Habib Bank Limited</span> <br>
                <span style="font-size: 12px">Challan-Cash & Local Instruments Only</span> <br> <br>
                <span>
                    @php
                    if ($i == 1) {
                    echo "Bank's Copy";
                    }
                    if ($i == 2) {
                    echo "Punjab Bar Council's Copy";
                    }
                    if ($i == 3) {
                    echo "Depositor's Copy 01";
                    }
                    if ($i == 4) {
                    echo "Depositor's Copy 02";
                    }
                    @endphp
                </span>
            </div>

            <table class="table">
                <tr>
                    <td style="padding: 15px">HBL Voucher</td>
                    <td style=" padding: 15px"> <span style="font-size:11px;"><b>
                                {{$payment->voucher_no}}</b></span></td>
                </tr>
                <tr>
                    <td style="padding: 15px">1 Link</td>
                    <td style=" padding: 15px"> <span style="font-size:11px;"><b>
                                1001145142{{$payment->voucher_no}}</b></span></td>
                </tr>

                <tr>
                    <td colspan="2" style="text-align: center; padding:14px;font-size:16px">Habib Bank Limited (HBL)
                        <br> A/c 0042-79000543-03
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center">Payable at any branch in Pakistan</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center"><b>Transactions to be processed via HBL App</b></td>
                </tr>
                <tr>
                    <td>Name of Depositor:</td>
                    <td>{{getLawyerName($application->id)}}</td>
                </tr>
                <tr>
                    <td>Father/Husband Name:</td>
                    <td>{{$application->so_of}}</td>
                </tr>
                <tr>
                    <td>CNIC No:</td>
                    <td>{{$application->cnic_no}} <small><b>(Age:{{$age}})</b></small></td>
                </tr>
                <tr>
                    <td>Contact No:</td>
                    <td>+92{{$application->active_mobile_no}}</td>
                </tr>
            </table>

            <table>
                <tr style="text-align: center">
                    <td>Nature of fee:</td>
                    <td>Amount</td>
                </tr>

                @if ($payment->enr_fee_pbc > 0)
                <tr style="text-align: center">
                    <td style="padding-bottom:5px"> <b>Intimation </b> </td>
                    <td style="padding-bottom:5px"> <b>{{ $payment->enr_fee_pbc}}/-</b> </td>
                </tr>
                @endif

                @if ($payment->degree_fee > 0)
                <tr style="text-align: center">
                    <td style="padding-bottom:5px"> <b>Degree Fee</b> </td>
                    <td style="padding-bottom:5px"> <b>{{ $payment->degree_fee }}/-</b> </td>
                </tr>
                @endif

            </table>

            <table>
                <tr style="text-align: center">
                    <td style="padding-bottom:32px">Total Amount (Words)</td>
                    <td style="padding-bottom:32px; text-transform: uppercase;">
                        @php
                        $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                        echo $digit->format($payment->amount);
                        @endphp RUPEES ONLY.
                    </td>
                    <td style="padding-bottom:32px">Total Amount (Digits)</td>
                    <td style="padding-bottom:32px"> {{$payment->amount}}/- </td>
                </tr>
            </table>

            @if (isset($payment->paid_date))
            <span style="font-size: 12px">Paid Date: {{$payment->paid_date}} </span> <br>
            @endif

            <span style="font-size: 12px">Printed On: {{$payment->created_at}} </span>
        </div>

        <?php } ?>


    </div>
</body>

</html>