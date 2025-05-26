<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HBL-Bank-Fee-Voucher-{{$application->application_token_no}}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        /* Create two equal columns that floats next to each other */
        .column {
            float: left;
            width: 32%;
            padding: 10px;
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
            font-size: 20px;
            font: bold;
            padding-left: 1px;
            padding-right: 1px;
        }

        table,
        th,
        td {
            border: 1px solid rgb(46, 45, 45);
            padding: 3px;
            font-size: 12px;
        }

        table {
            width: 100%;
            /* border-collapse: collapse; */
        }
    </style>

</head>

<body>

    <div class="row">

        <?php for ($i=1; $i <= 3; $i++) { ?>

        <div class="column">

            @if (isset($payment) && $payment->payment_status == 1)
            <div style="left:10%; top:51.50%; position:absolute;">
                <img src="{{asset('public/admin/images/paid.png')}}" style="width:150px">
            </div>
            @endif

            <div style="text-align: center;">
                <img src="{{asset('public/admin/images/hbl.png')}}" alt="" style="width: 250px">
            </div> <br>
            <div style="text-align: center;">
                <span style="font-size: 16px; font:bold">Punjab Bar Council</span>
                <img src="{{asset('public/admin/images/logo.png')}}" alt="" style="padding-left:12px; width: 40px">
                <br>
                <span style="font-size: 16px; font:bold;">HBL Cash Management Division</span> <br>
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
                    echo "Depositor's Copy";
                    }
                    @endphp
                </span> <br>
            </div>

            <table class="table">
                <tr>
                    <td>Date: {{date('d-m-Y', strtotime($application->created_at))}}</td>
                    <td>Sr.No:</td>
                </tr>
                <tr>
                    <td style="padding-bottom:25px">Branch Name:</td>
                    <td style="padding-bottom:25px">Branch Code:</td>
                </tr>
                <tr>
                    <td>Title of Account:</td>
                    <td>PUNJAB BAR COUNCIL</td>
                </tr>
                <tr>
                    <td style="padding-bottom:25px">Account No:</td>
                    <td style="padding-bottom:25px"></td>
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
                    <td>{{$application->cnic_no}} <small><b>(Age:{{$application->age}})</b></small></td>
                </tr>
                <tr>
                    <td>Contact No:</td>
                    <td>+92{{$application->active_mobile_no}}</td>
                </tr>
            </table>

            <table>
                <tr style="text-align: center">
                    <td style="padding-bottom:25px">Nature of fee:</td>
                    <td style="padding-bottom:25px">Amount</td>
                </tr>
                <tr style="text-align: center">
                    <td style="padding-bottom:25px"> <b>Intimation Fee</b> </td>
                    <td style="padding-bottom:25px"> <b>{{getVoucherAmount($application->id)}}/-</b> </td>
                </tr>
            </table>

            <table>
                <tr style="text-align: center">
                    <td style="padding-bottom:35px">Total Amount (Words)</td>
                    <td style="padding-bottom:35px; text-transform: uppercase;">
                        @php
                        $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                        echo $digit->format(getVoucherAmount($application->id));
                        @endphp RUPEES ONLY.
                    </td>
                    <td style="padding-bottom:35px">Total Amount (Digits)</td>
                    <td style="padding-bottom:35px"> {{getVoucherAmount($application->id)}}/- </td>
                </tr>
            </table>

            @if (isset($payment->paid_date))
            <span style="font-size: 14px">Paid Date: {{$payment->paid_date}} </span> <br>
            @endif

            <span style="font-size: 14px">Printed On: {{$payment->created_at}} </span>

        </div>

        <?php } ?>


    </div>
</body>

</html>
