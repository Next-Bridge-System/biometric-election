<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Intimation Application - {{$application->application_token_no}}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        .column {
            float: left;
            width: 50%;
            padding: 10px;
            height: 300px;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .dynamic-text {
            /* font-size: 18px;
            border: 1px solid;
            padding: 4px */
            font: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .text-underline {
            text-decoration: underline;
        }

        .text-justify {
            text-align: justify;
        }

        body {
            background-image: url('{{asset(' public/admin/images/watermark.png')}}');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            z-index: -999;
        }

        footer {
            position: fixed;
            bottom: -20px;
            left: 0px;
            right: 0px;
            height: 50px;
            /* text-align: center; */
            line-height: 35px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <main>
        <header>
            <div style="padding-left:350px;">
                <span style="font-size:20px">
                    Application No: {{$application->application_token_no}}
                </span>
                @php $generator = new Picqer\Barcode\BarcodeGeneratorHTML(); @endphp
                {!! $generator->getBarcode($application->cnic_no, $generator::TYPE_CODE_128) !!}
            </div>
        </header>
        <div style="padding-left:50px; padding-right:50px">
            <div style="text-align: center">
                <p>
                    <img src="{{asset('public/admin/images/logo.png')}}" alt="" style="width:100px"> <br>
                    <span style="font: bold;font-size: 20px ">PUNJAB BAR COUNCIL</span><br>
                    9-FANE ROAD, LAHORE, Phone:042-99214245-49, Fax 042-99214250 <br>
                    E-mail:info@pbbarcouncil.com, URL:www.pbbarcouncil.com <br>
                    Facebook: /Pbbarcouncil, Instagram: /Pbbarcouncil,Youtube: /Pbbarcouncil
                </p>
            </div>
            <div style="text-align: center">
                <h2 class="text-underline">
                    INTIMATION APPLICATION <br>
                    <small>FORM A</small>
                </h2>
                <p>(Under Rules 5.1 of the Punjab Legal Practitioners & Bar Council Rules, 2023)</p>
            </div>
            <div style="float: right;">
                <img src="{{$profile_image}}" style="width:125px; border-radius:1px; padding-top:2px;height:140px">
            </div>
            <div>
                <p>To</p>
                <p>
                    The Secretary <br>
                    Punjab Bar Council <br>
                    Lahore
                </p>
                <p>Sir,</p>
                <p class="">
                    I Mr./Ms. <span class="dynamic-text">{{getLawyerName($application->id)}}</span> D/S/W of <span
                        class="dynamic-text">{{$application->so_of}}</span> Date of Birth <span
                        class="dynamic-text">{{date('d-m-Y', strtotime($application->date_of_birth))}}</span> having
                    <span>
                        @if ($application->gender == "Male") Mr. @endif
                        @if ($application->gender == "Female") Ms. @endif
                    </span>
                    
                    <span class="dynamic-text">{{$application->srl_name}}</span> Advocate High Court/Subordinate Courts
                    <span class="dynamic-text">{{$application->srlBar->name ?? 'N/A'}}</span> as a pupil with effect
                    from
                    <span class="dynamic-text">{{getIntimationStartDate($application)['intimation_date']}}</span> under
                    the
                    Punjab
                    Legal Practitioners & Bar Council Rules, 1974, do hereby intimate you Accordingly. I hereby enclose
                    the following documents in Support of this intimation:-
                </p>
            </div>
            <span style="margin-top: 10px"><b>1. INTIMATION</b></span>

            <div style="padding-left:20px; padding-right:20px">
                <table style="width: 100%">
                    @foreach ($policy->policyFees as $key => $fee)
                    <tr>
                        @if ($key != 4)
                        <td> {{$loop->iteration}}. Intimation Fee upto {{$fee->to}} year age</td>
                        @else
                        <td> {{$loop->iteration}}. Intimation Fee above 60 year age</td>
                        @endif
                        <th>Rs.{{number_format($fee->amount)}}/-</th>
                    </tr>
                    @endforeach
                </table>
            </div>

            <p class="text-justify">
                <b>Note:</b> Deposite Fee in the Habib Bank Limited on prescribed challan from or directly in
                <b>Habib Bank Limited (HBL) A/c 0042-79000543-03</b> throughout the Punjab.
            </p>

            <p class="text-justify">
                2. Attested photo copies of the passed Result Card of Matric, Intermediate, B.A, LL.B Examination
                Part-I,
                Part-II,Part-III,Part-IV,Part-V/Last Semester. Intimation Form can Only be submitted after
                passing the LL.B Final exam.
            </p>

            <p class="text-justify">3. Copy of Seniors CNIC & I.D Card <b>(issued by the Punjab Bar Council)</b> for
                verifcation of enrolment date.</p>

            <footer class="page-break">
                <small>
                    Application No: {{$application->application_token_no}}
                    @if (isset($application->address->tehsil->code))
                    / {{$application->address->tehsil->code}}
                    @endif
                </small>
                @php $generator = new Picqer\Barcode\BarcodeGeneratorHTML(); @endphp
                {!! $generator->getBarcode($application->cnic_no, $generator::TYPE_CODE_128) !!}
            </footer>

            <p class="text-justify">
                4. <b>Three Photographs</b> in Professional dress <b>(with white background)</b>.(The male apprentice
                shall
                wear <b>Maroon Tie</b> and Female Apprentice shall wear <b>Maroon Dopatta OR Scarfduring</b> the period
                of
                Apprenticeship).
            </p>

            <p class="text-justify">
                5. Copy Of <b>National Identity Card/CNIC.#</b> <span
                    class="dynamic-text">{{$application->cnic_no}}</span>
            </p>

            <div class="row" style="margin-top: 50px">
                <span style="text-decoration: underline;"><b>Note:</b></span>
            </div>
            <div class="row">
                <div class="column">
                    <p class="text-justify">
                        (1) In case the intimation is not received within one month of the
                        commencement of training, the training shall run from the date of
                        receipt of intimation in the Bar Council
                    </p>
                    <p class="text-justify">
                        (2) After passing LL.B,holder of LL.M. & Bar-at-Law having one year
                        practical experence certiftcate are exempted from 6 Month
                        training.Foreign degree holder must have to pass a special
                        equivalence examination for Law Graduate of Foreign Universities
                        (SEE-LAW).
                    </p>
                    <p class="text-justify">
                        (3) No person is eligible to submit intimation Form if he/she is in
                        Govt./Semi-Govt./Private Service or Business.
                    </p>
                </div>

                <div class="column">
                    <p><b>Yours obediently,</b></p>
                    <p><b>Signatures: </b> ...................................</p>
                    <p style="margin-top: 38px">
                        <b>Address: </b>
                        {{$application->address->ha_house_no ?? ''}}
                        {{$application->address->ha_str_address ?? ''}}
                        {{$application->address->ha_town ?? ''}},
                        {{$application->address->ha_city ?? ''}},
                        {{$application->address->ha_postal_code ?? ''}}
                        {{getCountryName($application->address->ha_country_id) ?? ''}}
                        {{getProvinceName($application->address->ha_province_id) ?? ''}}
                        {{getDistrictName($application->address->ha_district_id) ?? ''}}
                        {{getTehsilName($application->address->ha_tehsil_id) ?? ''}}
                    </p>
                    <p> <b>Email: </b> {{$application->email}}</p>
                    <p> <b>Candidate Cell # :</b> 0{{$application->active_mobile_no}}</p>
                </div>
            </div>

            <div class="text-justify" style="margin-top:50px">
                I, <span class="dynamic-text">{{$application->srl_name}}</span> Advocate High Court/Subordinate
                Court <span class="dynamic-text">{{$application->srlBar->name ?? 'N/A'}}</span> Enrolled as such on
                <span class="dynamic-text">{{date('d-m-Y', strtotime($application->srl_enr_date))}}</span> do hereby
                certify that I have taken

                <span>
                    @if ($application->gender == "Male") Mr. @endif
                    @if ($application->gender == "Female") Ms. @endif
                </span>

                <span class="dynamic-text">{{getLawyerName($application->id)}}</span> as
                pupil
                under Punjab Legal Practitioners & Bar Council Rules, 1974 with effect from <span
                    class="dynamic-text">{{getIntimationStartDate($application)['intimation_date']}}</span> I further
                certify
                that I
                have been entitled to practice in the High Court/Lower Court for a period of not less10 years, and I
                will
                not have more three pupils during the time of his /her pupilage.
            </div>

            <div class="row" style="margin-top: 30px">
                <div class="column">
                    <p>
                        <br> <br>
                        <b>Date: </b> <span>{{getIntimationStartDate($application)['final_submission_date']}}</span>
                    </p>
                </div>
                <div class="column">
                    <p class="text-justify">
                        <b>Signatures: </b> ...................... <br>
                        <b>Address: </b> {{$application->srl_office_address ?? 'N/A'}} <br>
                        <b>Senior Cell No: </b> 0{{$application->srl_mobile_no ?? 'N/A'}} <br>
                    </p>
                </div>
            </div>

            <div class="page-break"></div>
            <div>
                <div>
                    <img src="{{ asset('public/admin/images/affidavit-muslim.png') }}" alt="" style="width: 650px">
                </div>
                <div class="bold-underline">
                    VERIFICATION:
                </div>
                <div>
                    <p>
                        Verified on oath this <span class="dynamic-text">__________________</span> day of <span
                            class="dynamic-text">____________________</span> that the contents of the above
                        affidavit are true and correct to the best of deponent's knowledge and belief and that nothing
                        has been
                        concealed therefrom.
                    </p>
                </div>
                <div style="float: right;margin: 20px 0px">
                    DEPONENT
                </div>
            </div>
        </div>
    </main>
    <footer>
        <small>
            Application No: {{$application->application_token_no}}
            @if (isset($application->address->tehsil->code))
            / {{$application->address->tehsil->code}}
            @endif
        </small>
        @php $generator = new Picqer\Barcode\BarcodeGeneratorHTML(); @endphp
        {!! $generator->getBarcode($application->cnic_no, $generator::TYPE_CODE_128) !!}
    </footer>
</body>

</html>