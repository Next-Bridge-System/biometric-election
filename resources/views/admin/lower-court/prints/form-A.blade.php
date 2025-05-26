@extends('layouts.pdf')

@section('content')

<h3 style="text-align: center">FORM B <br>
    APPLICATION FOR ENROLMENT <br>
    (See Rule 5.2)
</h3>
<div style="position: absolute;top: 100px;right: 0;border: 2px solid">
    @if (isset($application->uploads->profile_image))
    <img src="{{asset('storage/app/public/'.$application->uploads->profile_image)}}"
        style="width:125px; border-radius:1px; padding-top:2px;height:140px">
    @else
    <img src="#" style="width:125px; border-radius:1px; padding-top:2px;height:140px">
    @endif
</div>
<div>
    <strong>Your Cell No: <div class="dynamic-text-dotted">{{ $application->mobile_no }}</div> <span><img
                style="position: absolute;width: 150px;height: auto; right: 140px;top: 175px "
                src="{{ asset('public/admin/images/form-urdu.png') }}" alt=""></span></strong>
</div>

<div>
    <ol class="ol-spacing">
        <li>Name of the applicant:<div class="dynamic-text-dotted">&nbsp;&nbsp;{{ $application->lawyer_name }}</div>
        </li>
        <li>Father's/Husband's Name:<div class="dynamic-text-dotted">&nbsp;&nbsp;{{ $application->father_name }}</div>
        </li>
        <li>Date of Birth. <div class="dynamic-text-dotted">&nbsp;&nbsp;{{ $application->date_of_birth }}</div> Station
            <div class="dynamic-text-dotted">&nbsp;&nbsp;{{ getBarName($application->voter_member_lc) ?? '--' }} </div>
        </li>
        <li class="table-bordered-no-width">Nationality of applicant
            <div class="dynamic-text-dotted">{{ 'Pakistan' }}</div>
            NIC#<span style="display: inline">
                @php
                $cnic = str_split($application->cnic_no);
                @endphp
                @foreach($cnic as $c)
                @if($c != '-')
                <span class="bordered">{{$c}}</span>
                @endif
                @endforeach

            </span>
            <br>(Attach Photo Copy of National Identity Card)
        </li>
        <li>Home Address <div class="dynamic-text-dotted">{{ isset($application) ?
                getLcHomeAddress($application->id) : '--' }}</div>
        </li>
        <li>Postal Address <div class="dynamic-text-dotted">{{ isset($application) ?
                getLcPostalAddress($application->id) : '--' }}</div>
        </li>
        <li>E-mail Address <div class="dynamic-text-dotted" style="text-transform: lowercase">{{ $application->email }}
            </div>&nbsp;&nbsp;Contact <div class="dynamic-text-dotted" style="text-transform: lowercase">{{
                $application->mobile_no }}</div>
        </li>

        <!--LAWYER EDUCATION START -->
        @if ($educations->count() > 0)
        <li class="table-bordered">
            Qualification for enrolment:- <br>
            <table>
                <thead>
                    <tr>
                        <th>Qualification</th>
                        <th>Roll No</th>
                        <th>Year</th>
                        <th>Institution</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($educations as $education)
                    <tr>
                        <td> 
                            {{ getQualificationName($education->qualification) }}
                            @if ($education->sub_qualification)
                            / {{$education->sub_qualification}}
                            @endif
                        </td>
                        <td> {{ $education->roll_no ?? '-' }}</td>
                        <td> {{ $education->passing_year ?? '-' }} </td>
                        <td> {{$education->university->name ?? $education->institute}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </li>
        @endif
        <!--LAWYER EDUCATION END -->

        <li>
            Attach two copies of the following documents, duly attested by the Gazetted Officer or Member Punjab Bar
            Council or President of the Bar Association: - <br>
            <ol class="ol-roman">
                <li>
                    <ol class="ol-alpha">
                        <li>Matriculation/O-Level</li>
                        <li>F.A./F.Sc./B.A./B.Sc. Result Cards/Degree</li>
                        <li>LL.B. Result Card Part I, IL III/LL,B, (Hon.)./LL.M./Bar-at-Law </li>
                        <li>LL.B. Degree or the Provisional Certificate in original.</li>
                        <li>Pass Result Card of Entry Test/Law Gat with minimum 50% marks.</li>
                        <li>In case of foreign degree result card of Special Equivalence Examination (SEE).</li>
                        <li>Certificate of bar vocational course issued by the Punjab Bar Council.</li>
                    </ol>
                </li>
                <li>Two character certificates from two Advocates.</li>
                <li>An undertaking that you will become member of a Bar Association (mention name of the Bar
                    Association) in Punjab, within six months after your enrolment. <div class="dynamic-text-dotted">{{
                        getBarName($application->voter_member_lc) }}</div>
                </li>
                <li>List of at least twenty cases in which you rendered assistance to your senior duly signed by you
                    and
                    the senior giving the nature of each case.</li>
                <li>Your four passport size photographs in professional dress. <strong>(with white back
                        ground)</strong>.
                </li>
                <li>An affidavit, stating truly and accurately, that no criminal proceedings or proceedings for
                    professional misconduct were ever instituted against in any Court of Law,</li>
                <li>An affidavit explaining the reasons of gap, if any, in between examinations and training after
                    passing the LL.B. Examination.</li>
            </ol>
        </li>

        <li>Whether the applicant is a person exempt from training and examination provided under Rule 5.4(2) of the
            Punjab Legal Practitioners & Bar Council Rules, 2023? <span class="bold-underline">!Holders of LL.M/Bar
                at
                Law degree having one year practical experience certificate or possessing five years Judicial
                experience may-seek exemption' [Rs. 10,000/- exemption fee from six months training & for viva-voce
                Lower
                Court Enrollment in case of holders of LL.M. or Bar-at-Law Degree]</span>.
        </li>

        <footer>
            <small>App No: {{$application->id}}</small>
            <span style="background-color: black; color:white;font-size:18px">
                {!!getLcStatuses($application->id)['res']!!}
            </span>
            @php $generator = new Picqer\Barcode\BarcodeGeneratorHTML(); @endphp
            {!! $generator->getBarcode($application->cnic_no, $generator::TYPE_CODE_128) !!}
        </footer>

        <li>
            <span class="bold-underline">Enrolment fees including Verification Fee etc.</span> (in ease of non
            availability of prescribed challan forms of Punjab Bar Council. fee can be deposit in given account
            numbers,
            directly).
            <ol class="ol-roman ">
                <?php
                    $condition1 = $application->age >= 21 && $application->age <= 26;
                    $condition2 = $application->age > 26 && $application->age <= 30;
                    $condition3 = $application->age > 30 && $application->age <= 35;
                    $condition4 = $application->age > 35 && $application->age <= 40;
                    $condition5 = $application->age > 40 && $application->age <= 50;
                    $condition6 = $application->age > 50 && $application->age <= 60;
                    $condition7 = $application->age > 60;
                ?>

                @if ($condition1)
                <li>
                    <table class="table-50-w " style="width: 100%">
                        <tr>
                            <td><strong>Upto the age of 21 to 25 years:</strong></td>
                            <td><strong>HBL</strong></td>
                        </tr>
                        <tr>
                            <td>Rs. 100/- Enrollment Pakistan Bar Council</td>
                            <td>(Pakistan B.C. A/C 0042-79918974-03)</td>
                        </tr>
                        <tr>
                            <td>Rs. 200/- Enrollment Punjab Bar Council</td>
                            <td>(Punjab B.C. A/C 0042-79000543-03)</td>
                        </tr>
                        <tr>
                            <td>Rs. 800/- Identity Card Fee </td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 500/- Degree Ver. Fee (with in Punjab) <strong>OR</strong></td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 2,000/- Degree Ver. Fee (outside Punjab) OR</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 25,000/- Degree Ver. Fee (Foreign Universities)</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 2,000/- Group Insurance </td>
                            <td>(Group Insurance A/C 0042-79000544-03)</td>
                        </tr>
                        <tr>
                            <td>Rs. 10,000/- Benevolent Fund </td>
                            <td>(Benevolent Fund A/C 0042-79000545-03)</td>
                        </tr>
                        <tr>
                            <td>Rs. 4,000/- Pakistan Law Journal Subscript ion</td>
                            <td>(PLJ A/C 0042-79000546-03)</td>
                        </tr>
                        <tr>
                            <td>Rs. 1,000/- Lawyer Welfare Fund </td>
                            <td>(LWF A/C 0042-79922451-03)</td>
                        </tr>
                    </table>
                </li>
                @endif

                @if ($condition2)
                <li>
                    <table class="table-50-w" style="width: 100%;margin-top:0px">
                        <tr>
                            <td><strong>Above 25 upto 30 years:</strong></td>
                            <td><strong>HBL</strong></td>
                        </tr>
                        <tr>
                            <td>Rs. 165/- Enrollment Pakistan Bar Council Rs.</td>
                            <td>(Pakistan B.C. A/C 0042-79918974-03) </td>
                        </tr>
                        <tr>
                            <td>335/- Enrollment Punjab Bar Council</td>
                            <td>(Punjab B.C. A/C 0042-79000543-03)</td>
                        </tr>
                        <tr>
                            <td> Rs. 800/- Identity Card Fee</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 500/- Certificate Fee</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 500/- Building Fund</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 500/- General Fund</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 500/- Degree Ver. Fee (within Punjab) <strong>OR</strong></td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 2,000/- Degree Ver. Fee (outside Punjab) <strong>OR</strong></td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 25.000/- Degree Ver. Fee (Foreign Universities)</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 2,000/- Group Insurance</td>
                            <td>(Group Insurance A/C 0042-79000544-03)</td>
                        </tr>
                        <tr>
                            <td>Rs. 15,000/- Benevolent Fund</td>
                            <td>(Benevolent Fund A/C 0042-79000545-03) </td>
                        </tr>
                        <tr>
                            <td>Rs. 4,000/- Pakistan Law Journal Subscription</td>
                            <td>(PLJ A/C 0042-790005-16-03)</td>
                        </tr>
                        <tr>
                            <td>Rs. 1,000/- Lawyer Welfare Fund </td>
                            <td>(LWF A/C 0042-79922451-03)</td>
                        </tr>
                    </table>
                </li>
                @endif

                @if ($condition3)
                <li>
                    <table class="table-50-w" style="width: 100%">
                        <tr>
                            <td><strong>Above 30 upto 35 years:</strong></td>
                            <td><strong>HBL</strong></td>
                        </tr>
                        <tr>
                            <td>Rs. 165/- Enrollment Pakistan Bar Council </td>
                            <td>(Pakistan B.C. A/C 0042-79918974-03) </td>
                        </tr>
                        <tr>
                            <td>Rs. 335/- Enrollment Punjab Bar Council </td>
                            <td>(Punjab B.C. A/C 0042-79000543-03)</td>
                        </tr>
                        <tr>
                            <td>Rs. 800/- Identity Card Fee</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 500/- Certificate Fee</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 500/- Building Fund</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 750/- General Fund</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 500/- Degree Ver. Fee (within Punjab) <strong>OR</strong> </td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 2,000/- Degree Vet Fee (outside Punjab) <strong>OR</strong></td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 25,000/- Degree Ver. Fee (Foreign Universities) </td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 2,000/- Group Insurance</td>
                            <td>(Group Insurance A/C 0042-79000544-03) </td>
                        </tr>
                        <tr>
                            <td>Rs. 20.000/- Benevolent Fund</td>
                            <td>(Benevolent Fund A/C 0042-79000545-03) </td>
                        </tr>
                        <tr>
                            <td>Rs. 4,000/- Pakistan Law Journal Subscription</td>
                            <td>(PLJ A/C 0042-79000546-03)</td>
                        </tr>
                        <tr>
                            <td>Rs. 1,000/- Lawyer Welfare Fund </td>
                            <td>(LWF A/C 0042-79922451-03)</td>
                        </tr>
                    </table>
                </li>
                @endif

                @if ($condition4)
                <li>
                    <table class="table-50-w" style="width: 100%">
                        <tr>
                            <td><strong>Above 35 upto 40 years:</strong></td>
                            <td><strong>HBL</strong></td>
                        </tr>
                        <tr>
                            <td>Rs. 165/- Enrollment Pakistan Bar Council </td>
                            <td>(Pakistan B.C. A/C 0042-79918974-03) </td>
                        </tr>
                        <tr>
                            <td>Rs. 335/- Enrollment Punjab Bar Council </td>
                            <td>(Punjab B.C. A/C 0042-79000543-03)</td>
                        </tr>
                        <tr>
                            <td>Rs. 800/- Identity Card Fee</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 500/- Certificate Fee</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 2,000/- Building Fund</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 20,000/- General Fund</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 500/- Degree Ver. Fee (within Punjab) <strong>OR</strong> </td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 2,000/- Degree Vet Fee (outside Punjab) <strong>OR</strong></td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 25,000/- Degree Ver. Fee (Foreign Universities) </td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 2,000/- Group Insurance</td>
                            <td>(Group Insurance A/C 0042-79000544-03) </td>
                        </tr>
                        <tr>
                            <td>Rs. 4,000/- Pakistan Law Journal Subscription</td>
                            <td>(PLJ A/C 0042-79000546-03)</td>
                        </tr>
                        <tr>
                            <td>Rs. 1,000/- Lawyer Welfare Fund </td>
                            <td>(LWF A/C 0042-79922451-03)</td>
                        </tr>
                    </table>
                </li>
                @endif

                @if ($condition5)
                <li>
                    <table class="table-50-w" style="width: 100%">
                        <tr>
                            <td><strong>Above 40 upto 50 years:</strong></td>
                            <td><strong>HBL</strong></td>
                        </tr>
                        <tr>
                            <td>Rs. 165/- Enrollment Pakistan Bar Council </td>
                            <td>(Pakistan B.C. A/C 0042-799189744)3) </td>
                        </tr>
                        <tr>
                            <td>Rs. 335/- Enrollment Punjab Bar Council </td>
                            <td>(Punjab B.C. A/C 0042-79000543-03)</td>
                        </tr>
                        <tr>
                            <td>Rs. 800/- Identity Card Fee</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 500/- Certificate Fee</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 2,000/- Building Fund</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 40,000/- General Fund</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 500/- Degree Ver. Fee (within Punjab) <strong>OR</strong> </td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 2,000/- Degree Ver. Fee (outside Punjab) <strong>OR</strong></td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 25,000/- Degree Ver. Fee (Foreign Universities)</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td> Rs. 2,000/- Group Insurance</td>
                            <td>(Group Insurance A/C 0042-79000544-03)</td>
                        </tr>
                        <tr>
                            <td>Rs. 4,000/- Pakistan Law Journal Subscription</td>
                            <td>(PLJ A/C 0042-79000546-03)</td>
                        </tr>
                        <tr>
                            <td>Rs. 1,000/- Lawyer Welfare Fund </td>
                            <td>(LWF A/C 0042-79922451-03)</td>
                        </tr>
                    </table>
                </li>
                @endif

                @if ($condition6)
                <li>
                    <table class="table-50-w" style="width: 100%" style="margin: 0 auto">
                        <tr>
                            <td><strong>Above 50 upto 60 years:</strong></td>
                            <td><strong>HBL</strong></td>
                        </tr>
                        <tr>
                            <td>Rs. 165/- Enrollment Pakistan Bar Council </td>
                            <td>(Pakistan B.C. A/C 0042-79918974-03) </td>
                        </tr>
                        <tr>
                            <td>Rs. 335/- Enrollment Punjab Bar Council </td>
                            <td>(Punjab B.C. A/C 0042-79000543-03</td>
                        </tr>
                        <tr>
                            <td>Rs. 800/- Identity Card Fee</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 500/- Certificate Fee</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 2,000/- Building Fund</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 175,000/- General Fund</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 500/- Degree Ver. Fee (within Punjab) <strong>OR</strong> </td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 2,000/- Degree Ver. Fee (outside Punjab) <strong>OR</strong></td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 25,000/- Degree Ver. Fee (Foreign Universities) </td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 2,000/- Group Insurance</td>
                            <td>(Group Insurance A/C 0042-79000544-03)</td>
                        </tr>
                        <tr>
                            <td>Rs. 4,000/- Pakistan Law Journal Subscription</td>
                            <td>(PLJ A/C 0042-79000546-03)</td>
                        </tr>
                        <tr>
                            <td>Rs. 1,000/- Lawyer Welfare Fund </td>
                            <td>(LWF A/C 0042-79922451-03)</td>
                        </tr>
                    </table>
                </li>
                @endif

                @if ($condition7)
                <li>
                    <table class="table-50-w" style="width: 100%">
                        <tr>
                            <td><strong>Above 60 years:</strong></td>
                            <td><strong>HBL</strong></td>
                        </tr>
                        <tr>
                            <td>Rs. 165/- Enrollment Pakistan Bar Council </td>
                            <td>(Pakistan B.C. A/C 0042-79918974-03) </td>
                        </tr>
                        <tr>
                            <td>Rs. 335/- Enrollment Punjab Bar Council </td>
                            <td>(Punjab B.C. A/C 0042-79000543-03)</td>
                        </tr>
                        <tr>
                            <td>Rs. 800/- Identity Card Fee</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 500/- Certificate Fee</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 2,000/- Building Fund</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 275,000/- General Fund</td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 500/- Degree Vet Fee (within Punjab) <strong>OR</strong> </td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 2,000/- Degree Ver. Fee (outside Punjab) <strong>OR</strong></td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 25,000/- Degree Ver. Fee (Foreign Universities) </td>
                            <td class="text-center">-do-</td>
                        </tr>
                        <tr>
                            <td>Rs. 2,000/- Group Insurance</td>
                            <td>Group Insurance A/C 0042-79000544-03)</td>
                        </tr>
                        <tr>
                            <td>Rs. 4,000/- Pakistan Law Journal Subscription </td>
                            <td> (PLJ A/C 0042-79000546-03)</td>
                        </tr>
                        <tr>
                            <td>Rs. 1,000/- Lawyer Welfare Fund </td>
                            <td>(LWF A/C 0042-79922451-03)</td>
                        </tr>
                    </table>
                </li>
                @endif

                <li class="table-width-100">
                    Benevolent Fund Contribution (optional scheme of Rs. Six Lac (6,00,000/-)) if the applicant
                    desires
                    to join optional scheme he/she will have to deposit double contribution of Benevolent Fund
                    Scheme
                    Rupees Three Lac:--
                    <strong>
                        <table>
                            @if ($condition1)
                            <tr>
                                <td>21 YEARS UPTO 25 YEARS</td>
                                <td>Rs. 20,000/-</td>
                            </tr>
                            @endif

                            @if ($condition2)
                            <tr>
                                <td>25 YEARS UPTO 30 YEARS</td>
                                <td>Rs. 30,000/-</td>
                            </tr>
                            @endif

                            @if ($condition3)
                            <tr>
                                <td>30 YEARS UPTO 35 YEARS</td>
                                <td>Rs. 40,000/-</td>
                            </tr>
                            @endif
                        </table>
                    </strong>
                </li>

            </ol>
        <li>
            Whether the applicant is/was engaged in any business, service, profession or vocation in Pakistan? If
            so.
            the nature there of and the place at which it is carried out? <strong>{{
                $application->is_engaged_in_business
                ?? '' }}</strong>
        </li>
        <li>
            Whether the applicant proposes to practice generally within the jurisdiction of the Punjab Bar Council?
            State place of practice? <strong>{{ $application->is_practice_in_pbc . ($application->is_practice_in_pbc
                ==
                'Yes' ? ', '.$application->practice_place : '') }}</strong>
        </li>
        <li>
            Whether the applicant has been declared insolvent? <strong>{{ $application->is_declared_insolvent ?? ''
                }}</strong>
        </li>
        <li>
            Whether the applicant has been dismissed/removed from service of Government or of a Public Statutory
            Corporation, if so the reasons thereof? <strong>{{ $application->is_dismissed_from_gov .
                ($application->is_dismissed_from_gov == 'Yes' ? ', '.$application->dismissed_reason : '')
                }}</strong>
        </li>
        <li>
            Whether the applicant is enrolled as an advocate on the Roll of any other Bar Council? <strong>{{
                $application->is_enrolled_as_adv ?? '' }}</strong>
        </li>
        <li>
            Whether the applicant has been convicted of any offense? If so, date and particulars thereof? <strong>{{
                $application->is_offensed . ($application->is_offensed == 'Yes' ? ',
                '.$application->offensed_date.',
                '.$application->offensed_reason : '') }}</strong>
        </li>
        <li>
            Whether the application of the applicant of enrolment has previously been rejected? <strong>{{
                $application->is_prev_rejected ?? '' }}</strong>
        </li>
        <li>
            Description of Fees Deposited any <strong>Branch of HBL</strong>:
            <table style="width: 100%">
                <tr>
                    <td>(a)</td>
                    <td>Enrolment Fee: </td>
                    <td>(i) Pb.B.L.</td>
                    <td>Rs. <span style="font-weight: bold">
                            {{ $application->payments()->where('voucher_type',2)->first()->amount ?? '______' }}
                        </span>
                    </td>
                    <td>Bank Slip #<span style="font-weight: bold">
                            {{$application->payments()->where('voucher_type',2)->first()->voucher_no ??
                            '_______________'}}
                            @if (getLcTransactionNo($application->id, 2) != NULL)
                            / {{ getLcTransactionNo($application->id, 2) }}
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>(ii) Pak. B.C.</td>
                    <td>Rs. <span style="font-weight: bold">{{
                            $application->payments()->where('voucher_type',1)->first()->amount ?? '______' }}</span>
                    </td>
                    <td>Bank Slip #<span style="font-weight: bold">
                            {{ $application->payments()->where('voucher_type',1)->first()->voucher_no ??
                            '_______________'}}
                            @if (getLcTransactionNo($application->id, 1) != NULL)
                            / {{ getLcTransactionNo($application->id, 1) }}
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>(b)</td>
                    <td>General Fund</td>
                    <td></td>
                    <td>Rs. <span style="font-weight: bold">{{ '1000' }}</span></td>
                    <td>Bank Slip #<span style="font-weight: bold">
                            {{ $application->payments()->where('voucher_type',2)->first()->voucher_no ??
                            '_______________'}}
                            @if (getLcTransactionNo($application->id, 2) != NULL)
                            / {{ getLcTransactionNo($application->id, 2) }}
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>(c)</td>
                    <td>B.F. Contribution</td>
                    <td></td>
                    <td>Rs. <span style="font-weight: bold">{{
                            $application->payments()->where('voucher_type',4)->first()->amount ?? '______' }}</span>
                    </td>
                    <td>Bank Slip #<span style="font-weight: bold">
                            {{ $application->payments()->where('voucher_type',4)->first()->voucher_no ??
                            '_______________' }}
                            @if (getLcTransactionNo($application->id, 4) != NULL)
                            / {{ getLcTransactionNo($application->id, 4) }}
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>(d)</td>
                    <td>Group Insurance</td>
                    <td></td>
                    <td>Rs. <span style="font-weight: bold">{{
                            $application->payments()->where('voucher_type',3)->first()->amount ?? '______' }}</span>
                    </td>
                    <td>Bank Slip #<span style="font-weight: bold">
                            {{$application->payments()->where('voucher_type',3)->first()->voucher_no ??
                            '_______________'}}
                            @if (getLcTransactionNo($application->id, 3) != NULL)
                            / {{ getLcTransactionNo($application->id, 3) }}
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>(e)</td>
                    <td>PLJ Subscription</td>
                    <td></td>
                    <td>Rs. <span style="font-weight: bold">{{
                            $application->payments()->where('voucher_type',5)->first()->amount ?? '______' }}</span>
                    </td>
                    <td>Bank Slip #<span style="font-weight: bold">
                            {{$application->payments()->where('voucher_type',5)->first()->voucher_no ??
                            '_______________'}}
                            @if (getLcTransactionNo($application->id, 5) != NULL)
                            / {{ getLcTransactionNo($application->id, 5) }}
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>(f)</td>
                    <td>Exemption Fee (if any)</td>
                    <td></td>
                    <td>Rs. <span style="font-weight: bold"></span>______</td>
                    <td>Bank Slip #<span style="font-weight: bold"></span>_______________</td>
                </tr>
            </table>
        </li>
        </li>
    </ol>

    <div style="float: right;margin: 20px 0px">
        SIGNATURE : __________________
    </div>
    {{-- <div class="page-break"></div> --}}
    <div>
        <h4 class="bold-underline text-center">AFFIDAVIT</h4>
    </div>
    <div class="field-margin">
        Affidavit of Mr./Ms. <span class="dynamic-text">{{ $application->lawyer_name }}</span>
    </div>
    <div class="field-margin">
        s/o d/o w/o <span class="dynamic-text">{{ $application->father_name }}</span> r/o <span class="dynamic-text">{{
            isset($application) ? getLcHomeAddress($application->id) : '--' }}</span>
    </div>
    <div>

        <ol>
            <span>I, the above named deponent, do hereby solemnly affirm and declare as under:—</span>
            <li>That the deponent is neither engaged in any Business/Service/Profession/Vocation anywhere at present nor
                was so engaged during the period of apprenticeship. <br></li>
            <li>That the replies to clauses Nos. 9 to 15, above, are correct.</li>
            <li>That after enrolment as an Advocate, if the deponent joins Business/Service/Vocation, he shall forthwith
                intimate the Bar Council for suspension or cancellation of the license.</li>
        </ol>
    </div>
    <div style="float: right;margin: 20px 0px">
        DEPONENT
    </div>
    <br><br><br>
    <div class="bold-underline">
        VERIFICATION:
    </div>
    <div>
        <p>
            Verified on oath this <span class="dynamic-text">__________________</span> day of <span
                class="dynamic-text">____________________</span> that the contents of the above
            affidavit are true and correct to the best of deponent's knowledge and belief and that nothing has been
            concealed therefrom.
        </p>

    </div>

    <div style="float: right;margin: 20px 0px">
        DEPONENT
    </div>


    <div class="page-break"></div>
    <div>
        <div>
            <img src="{{ asset('public/admin/images/affidavit-muslim.png') }}" alt="" style="width: 760px">
        </div>
        <div class="bold-underline">
            VERIFICATION:
        </div>
        <div>
            <p>
                Verified on oath this <span class="dynamic-text">__________________</span> day of <span
                    class="dynamic-text">____________________</span> that the contents of the above
                affidavit are true and correct to the best of deponent's knowledge and belief and that nothing has been
                concealed therefrom.
            </p>
        </div>
        <div style="float: right;margin: 20px 0px">
            DEPONENT
        </div>
    </div>

</div>
<footer>
    <small>Application No: {{$application->id}}</small>
    <span style="background-color: black; color:white; font-size:18px">
        {!!getLcStatuses($application->id)['res']!!}
    </span>
    @php $generator = new Picqer\Barcode\BarcodeGeneratorHTML(); @endphp
    {!! $generator->getBarcode($application->cnic_no, $generator::TYPE_CODE_128) !!}
</footer>
@endsection