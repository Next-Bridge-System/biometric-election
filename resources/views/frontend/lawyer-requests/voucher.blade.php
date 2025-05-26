<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HBL-Online-Voucher-{{$lawyer_request->id}}</title>

    <style>
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
            $one_link_number = "1001145142";
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
                        <span style="font-size:14px;">{{$lawyer_request_payment->voucher_no}}</span> <br>
                        @if ($one_link_number)
                        <span>{{$one_link_number}}{{$lawyer_request_payment->voucher_no}}</span>
                        @endif
                    </th>
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
                    <td style="text-transform: uppercase">{{$lawyer_request->lawyer_name}}</td>
                </tr>
                <tr>
                    <td>Father/Husband Name:</td>
                    <td style="text-transform: uppercase">{{$lawyer_request->father_name}}</td>
                </tr>
                <tr>
                    <td>CNIC No:</td>
                    <td>{{$lawyer_request->cnic_no}}</td>
                </tr>
            </table>

            <table>
                <tr style="text-align: center">
                    <td>Nature of fee:</td>
                    <td>Amount</td>
                </tr>
                <tr style="text-align: center">
                    <td style="padding-bottom:20px"> <b>{{$lawyer_request->lawyer_request_sub_category->name}} Fee</b>
                    </td>
                    <td style="padding-bottom:20px"> <b>{{ $lawyer_request->lawyer_request_sub_category->amount }}/-</b>
                    </td>
                </tr>
            </table>

            <table>
                <tr style="text-align: center">
                    <td style="padding-bottom:32px">Total Amount (Words)</td>
                    <td style="padding-bottom:32px; text-transform: uppercase;">
                        @php
                        $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                        echo $digit->format($lawyer_request->lawyer_request_sub_category->amount);
                        @endphp RUPEES ONLY.
                    </td>
                    <td style="padding-bottom:32px">Total Amount (Digits)</td>
                    <td style="padding-bottom:32px"> {{$lawyer_request->lawyer_request_sub_category->amount}}/- </td>
                </tr>
            </table>

            <span style="font-size: 12px">Printed On: {{$lawyer_request->created_at}} </span>
        </div>

        <?php } ?>


    </div>
</body>

</html>