<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>High Court Voucher - {{$application->id}}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        .column {
            float: left;
            width: 25%;
            padding: 0px;
            height: 300px;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .text-lg {
            font-size: 10px;
            font: bold;
            padding-left: 1px;
            padding-right: 1px;
        }

        table,
        th,
        td {
            border: 1px solid rgb(46, 45, 45);
            padding: 3px;
            font-size: 10px;
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
        @foreach ($payments as $payment)
        <?php for ($i=1; $i <= 4; $i++) { ?>
        <div class="column">

            @if ($payment->payment_status == 1)
            <div style="left:8%; top:51.50%; position:absolute;">
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

            @php
            $one_link_number = getHcBank1LinkNumber($payment->voucher_type);
            @endphp

            <table class="table">
                <tr>
                    <td style="padding: 15px; font-size:12px;">
                        <span>Voucher #</span> <br>
                        @if ($one_link_number)
                        <span>1 Link #</span>
                        @endif
                    </td>
                    <th style=" padding: 15px">
                        <span style="font-size:14px;">{{$payment->voucher_no}}</span> <br>
                        @if ($one_link_number)
                        <span>{{$one_link_number}}{{$payment->voucher_no}}</span>
                        @endif
                    </th>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center; padding:14px;font-size:12px">Habib Bank Limited (HBL)
                        <br> <b>{{getHcBankAccount($payment->voucher_type)}}</b>
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
                    <td style="text-transform:uppercase">{{ $application->lawyer_name }}</td>
                </tr>
                <tr>
                    <td>Father/Husband Name:</td>
                    <td style="text-transform:uppercase">{{$application->user->father_name}}</td>
                </tr>
                <tr>
                    <td>CNIC No:</td>
                    <td>{{$application->user->cnic_no}}
                    </td>
                </tr>
                <tr>
                    <td>Contact No:</td>
                    <td>+92{{$application->user->phone}}</td>
                </tr>
            </table>

            <table>
                <tr style="text-align: center">
                    <td>Nature of fee:</td>
                    <td>Amount</td>
                </tr>

                @if($payment->voucher_type == 2)
                <tr style="text-align: center">
                    <td style="padding-bottom:2px">
                        <b>{{$payment->voucher_name}}
                        </b>
                    </td>
                    <td style="padding-bottom:2px"> <b>{{ $payment->enr_fee_pbc }}/-</b> </td>
                </tr>
                <tr style="text-align: center">
                    <td style="padding-bottom:2px">
                        <b>GENERAL FUND</b>
                    </td>
                    <td style="padding-bottom:2px"> <b>{{
                            $payment->id_card_fee + $payment->certificate_fee +
                            $payment->building_fund + $payment->general_fund + $payment->degree_fee }}/-</b> </td>
                </tr>
                @if ($payment->exemption_fee > 0)
                <tr style="text-align: center">
                    <td style="padding-bottom:2px">
                        <b>EXEMPTION FEE</b>
                    </td>
                    <td style="padding-bottom:2px"> <b>{{$payment->exemption_fee}}/-</b> </td>
                </tr>
                @endif
                @else
                <tr style="text-align: center">
                    <td style="padding-bottom:2px">
                        <b>{{$payment->voucher_name}}
                        </b>
                    </td>
                    <td style="padding-bottom:2px"> <b>{{$payment->amount}}/-</b> </td>
                </tr>
                @endif

            </table>

            <table>
                <tr style="text-align: center">
                    <td style="padding-bottom:32px">Total Amount (Words)</td>
                    <td style="padding-bottom:32px; text-transform: uppercase;">
                        @php
                        try {
                        $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                        echo $digit->format($payment->amount);
                        } catch (\Throwable $th) {
                        //throw $th;
                        }
                        @endphp RUPEES ONLY.
                    </td>
                    <td style="padding-bottom:32px">Total Amount (Digits)</td>
                    <td style="padding-bottom:32px"> {{$payment->amount}}/- </td>
                </tr>
            </table>

            @if (isset($payment->paid_date))
            <span style="font-size: 12px">Paid Date: {{$payment->paid_date ?? '-'}} </span> <br>
            @endif

            <span style="font-size: 12px">Printed On: {{$payment->created_at}} </span>
        </div>
        <?php } ?>

        @if(!$loop->last)
        <div class="page-break"></div>
        @endif
        @endforeach
    </div>
</body>

</html>