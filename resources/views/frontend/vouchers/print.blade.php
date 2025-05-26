<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Voucher - 00{{$voucher->id}}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        .column {
            float: left;
            width: 32%;
            padding: 0px;
            height: 300px;
        }

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
            border-collapse: collapse;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="row">

        @foreach ($voucher->payments as $payment)

        <?php for ($i=1; $i <= 3; $i++) { ?>
        <div class="column">

            @if ($payment->vch_payment_status == 1)
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
                    echo "Depositor's Copy";
                    }
                    @endphp
                </span>
            </div>

            <table class="table">
                <tr>
                    <td style="padding: 15px">Date: </td>
                    <td style=" padding: 15px">VOUCHER # <span style="font-size:18px;"><b>
                                {{$payment->vch_no}}</b></span></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center; padding:14px;font-size:16px">Habib Bank Limited (HBL)
                        <br> {{getBankAccount($payment->vch_type)}}
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
                    <td>{{$voucher->name}}</td>
                </tr>
                <tr>
                    <td>Father/Husband Name:</td>
                    <td>{{$voucher->father_name}}</td>
                </tr>
                <tr>
                    <td>CNIC No:</td>
                    <td>{{$voucher->cnic_no}} <small><b>(Age:{{$voucher->age}})</b></small></td>
                </tr>
                <tr>
                    <td>Contact No:</td>
                    <td>+92{{$voucher->contact}}</td>
                </tr>
            </table>

            <table>
                <tr style="text-align: center">
                    <td>Nature of fee:</td>
                    <td>Amount</td>
                </tr>
                <tr style="text-align: center">
                    <td style="padding-bottom:20px">
                        <b>{{$payment->vch_name}}</b>
                    </td>
                    <td style="padding-bottom:20px"> <b>{{$payment->vch_amount}}/-</b> </td>
                </tr>
            </table>

            <table>
                <tr style="text-align: center">
                    <td style="padding-bottom:32px">Total Amount (Words)</td>
                    <td style="padding-bottom:32px; text-transform: uppercase;">
                        @php
                        $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                        echo $digit->format($payment->vch_amount);
                        @endphp RUPEES ONLY.
                    </td>
                    <td style="padding-bottom:32px">Total Amount (Digits)</td>
                    <td style="padding-bottom:32px"> {{$payment->vch_amount}}/- </td>
                </tr>
            </table>

            @if (isset($payment->paid_date))
            <span style="font-size: 12px">Paid Date: - </span> <br>
            @endif

            <span style="font-size: 12px">Printed On: {{$voucher->created_at}} </span>
        </div>
        <?php } ?>

        <div class="page-break"></div>
        @endforeach
    </div>
</body>

</html>
