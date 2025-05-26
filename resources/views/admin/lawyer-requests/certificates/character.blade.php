<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lawyer Request - {{$lawyer_request->id}}</title>
    <style>
        html {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            text-align: justify;
            line-height: 1.5;
        }

        strong {
            text-decoration: underline;
            text-transform: uppercase;
        }

        footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 30px;
            /** Extra personal styles **/

        }

        @page {
            margin: 1in !important;
        }
    </style>
</head>

<body>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <table>
        <tr>
            <td style="width: 50%"><b>Ref</b><strong>_______________</strong>
                <br>
                <br>
                <br>
            </td>
            <td style="width: 50%;text-align: right">
                <b>Date: </b><strong>{{ date('d-m-Y',strtotime($lawyer_request->created_at)) }}</strong>
                <br>
                <br>
                <br>
            </td>
        </tr>
        <tr style="padding-top: 100px">
            <td colspan="2" style="text-align: center"><strong>TO WHOM IT MAY CONCERN</strong></td>
        </tr>
        <tr>
            <td colspan="2">
                <p style="text-align: justify">Certified that <strong>MR. {{ $lawyer_request->name }}</strong> son of
                    <strong>{{ $lawyer_request->father_name }}</strong>, Resident of
                    {{ $lawyer_request->address }}, was enrolled as an advocate by the Punjab Bar Council of the
                    {{ $lawyer_request->character_type == 1 ? 'Lower Court' : 'Higher Court' }}
                    on
                    <strong>{{ date('d-m-Y',strtotime($lawyer_request->character_type == 1 ? $lawyer_request->enr_date_lc : $lawyer_request->enr_date_hc)) }}</strong>.His
                    name is borne on the Roll of Advocates maintained by
                    the Punjab Bar Council.
                </p>
                <p>
                    He is entitled to practice in his home jurisdiction in the Courts of Punjab
                    (Pakistan) subordinate to the High Court He bears good moral character and
                    repute among his colleagues He is fit and proper person for admission to any
                    Bar and institution
                </p>
                <p>
                    He is not in arrears of the dues of this Council
                </p>
                <p>
                    He has never been proceeded against on the basis of professional or other
                    misconduct, nor is any complaint of professional or other misconduct pending
                    against him.

                </p>
                <p>
                    His License Number is <strong>{{ $lawyer_request->license_no }}</strong>.
                </p>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <p style="text-align: right;font-weight: 800">
                    SECRETARY &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>
                    PUNJAB BAR COUNCIL, <br>
                    LAHORE. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </p>
                <br>
            </td>
        </tr>
    </table>
    <footer>
        <div style="width:180px;text-align: center">
            @php $generator = new Picqer\Barcode\BarcodeGeneratorHTML(); @endphp
            {!! $generator->getBarcode($lawyer_request->cnic_no, $generator::TYPE_CODE_128,1,40) !!}
            {{ $lawyer_request->cnic_no }}
        </div>
    </footer>
</body>

</html>