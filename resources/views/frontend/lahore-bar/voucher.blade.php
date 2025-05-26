<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HBL - {{$voucher_no}}</title>

    <style>
        .column {
            float: left;
            width: 31%;
            padding: 3px;
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
            padding: 8px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        body {
            margin-left: 0;
            margin-right: 0;
            padding: 0;
        }
        @page {
            margin-left: 0;
            margin-right: 0;
        }
        .content {
            margin-left: 0;
            margin-right: 0;
            padding: 0;
            /* Define other styles as needed */
        }
    </style>
</head>

<body>

    <div class="row">

        <?php for ($i=1; $i <= 3; $i++) { ?>

        <div class="column">

            <div style="text-align: center;">
                <img src="{{asset('public/admin/images/mcb-logo.png')}}" alt="" style="width: 50px; padding-right:70px">
                <img src="{{asset('public/admin/images/lba.png')}}" alt="" style="width: 50px">
            </div>
            <div style="text-align: center; padding-bottom:10px">
                <span style="font-size: 16px; font:bold">LAHORE BAR ASSOCIATION</span> <br>
                <span style="font-size: 16px; font:bold;">MCB Bank Limited</span> <br>
                <span>
                    @php
                    if ($i == 1) {
                    echo "Copy For Lahore Bar";
                    }
                    if ($i == 2) {
                    echo "Copy For Depositer";
                    }
                    if ($i == 3) {
                    echo "Copy For Bank";
                    }
                    @endphp
                </span>
            </div>

            <table class="table">
                <tr>
                    <td style="padding: 15px">VOUCHER # </td>
                    <td style=" padding: 15px"><span style="font-size:18px;"><b> {{$voucher_no}} </b></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center; padding:14px;font-size:16px">MCB Bank Limited
                        <br>LAHORE BAR ASSOCIATION <br> A/C PK82 MUCB 0085 4010 1000 3554
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center">Payable at any branch in Pakistan</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center">
                        <b>Transactions to be processed via MCB App</b>
                    </td>
                </tr>
                <tr>
                    <td>Name of Depositor:</td>
                    <td style="text-transform: uppercase">{{$user->name}}</td>
                </tr>
                <tr>
                    <td>Father's/Husband's Name:</td>
                    <td style="text-transform: uppercase">{{$user->father_name}}</td>
                </tr>
                <tr>
                    <td>CNIC No:</td>
                    <td>{{$user->cnic_no}}</td>
                </tr>
            </table>

            <table>
                <tr style="text-align: center">
                    <td>Nature of Fee:</td>
                    <td>Amount</td>
                </tr>
                <tr style="text-align: center">
                    <td style="padding-bottom:20px"> <b>Duplicate Card Fee</b>
                    </td>
                    <td style="padding-bottom:20px"> <b>1000/-</b>
                    </td>
                </tr>
            </table>

            <table>
                <tr style="text-align: center">
                    <td>Total Amount (Words)</td>
                    <td>Total Amount (Digits)</td>
                </tr>
                <tr style="text-align: center">
                    <td style="padding-bottom:20px; text-transform: capitalize">
                        <b>{{amountInWords(1000)}} only.</b>
                    </td>
                    <td style="padding-bottom:20px"> <b>1000/-</b>
                    </td>
                </tr>
            </table>

            <span style="font-size: 12px">Printed On: {{Carbon\Carbon::now()}} </span>
        </div>

        <?php } ?>


    </div>
</body>

</html>