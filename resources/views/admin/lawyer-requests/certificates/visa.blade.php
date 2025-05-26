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
            font-size: 14px;
            text-align: justify;
            line-height: 1.5;
        }

        strong {
            text-decoration: underline;
        }

        footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 50px;
            /** Extra personal styles **/
            line-height: 35px;
        }

        li {
            margin-bottom: 0.25in;
        }

        @page {
            margin: 1in 1in 0.5in 1in !important;
        }
    </style>
</head>

<body>
    <br><br><br><br><br>
    <table>
        <tr>
            <td style="width: 50%">
                To <br><br>
                The Visa Officer <br>
                {{ $lawyer_request->embassy_name }}<br>
                Lahore PAKISTAN.
                <br><br>
            </td>
            <td style="width: 50%;text-align: right"></td>
        </tr>
        <tr style="padding-top: 100px">
            <td colspan="2" style="text-align: center"><strong>REQUEST FOR GRANT OF VISA FACILITY</strong></td>
        </tr>
        <tr>
            <td colspan="2">
                <p>Dear Sir,</p>
                <ol>
                    <li>Is inform you that Mr. <strong>{{ $lawyer_request->name }}</strong> Son of
                        <strong>{{ $lawyer_request->father_name }}</strong>, Resident of {{ $lawyer_request->city }},
                        was
                        enrolled as an Advocate of the Lower Courts on
                        {{ date('d-m-Y',strtotime($lawyer_request->enr_date_lc)) }} and subsequently as an
                        advocate of the High Court on
                        <strong>{{ date('d-m-Y',strtotime($lawyer_request->enr_date_hc)) }}</strong>. His name is borne
                        on
                        the Roll of Advocates
                        maintained by the Punjab Bar Council.
                    </li>
                    <li>
                        Mr. {{ $lawyer_request->name }} is elected the Member Executive Committee,
                        {{ $lawyer_request->member->name }}, {{ $lawyer_request->member->district->name }}.
                    </li>
                    <li>
                        He is an advocate of good standing enjoys good reputation and respect amongst his
                        Colleagues and the Bench,
                    </li>
                    <li>
                        He has never been proceeded against on the basis of professional or other misconduct
                        nor any complaint of professional of other misconduct is pending against him.
                    </li>
                    <li>
                        Mr. {{ $lawyer_request->name }}, Advocate intends to visit {{ $lawyer_request->visit_country }}.
                        for
                        pleasure Trip
                    </li>
                    <li>
                        It Is strongly, requested that Visa Facility may be granted to Mr. {{ $lawyer_request->name }},
                        Advocate.
                    </li>
                </ol>
                <br>
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