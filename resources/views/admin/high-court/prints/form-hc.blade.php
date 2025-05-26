@extends('layouts.pdf')

@section('title') Form E - {{$application->id}} @endsection

@section('styles')
<style>
    .underline {
        text-decoration: underline;
        font-size: 16px;
        font-style: bold;
        margin-left: 3px;
        margin-right: 3px;
    }

    body {
        font-size: 15px
    }

    .uppercase {
        text-transform: uppercase;
    }
</style>
@endsection

@section('content')

<h3 style="text-align: center">
    FORM E <br>
    APPLICATION FOR ADMISSION AS AN <br>
    ADVOCATE OF THE HIGH COURT <br>
    (See Rule 5.1)
</h3>

<div style="position: absolute;top: 100px;right: 0;border: 2px solid">
    @if (isset($application->uploads->profile_image))
    <img src="{{asset('storage/app/public/'.$application->uploads->profile_image)}}"
        style="width:125px; border-radius:1px; padding-top:2px;height:140px">
    @endif
</div>

<div>
    <ol class="ol-spacing">
        <li>
            Name of the Applicant:<span class="underline uppercase">{{ $application->lawyer_name }}</span>
        </li>
        <li>Father's/Husband's Name:<span class="underline uppercase">{{ $application->user->father_name }}</span>
        </li>
        <li>Date of Birth:<span class="underline">{{ $application->user->date_of_birth }}</span> Station
            <span class="underline">{{ getBarName($application->voter_member_hc) ?? '' }} </span>
        </li>
        <li class="table-bordered-no-width">Nationality of applicant
            <span class="underline">{{ 'Pakistan' }}</span>
            CNIC #<span class="underline">{{$application->user->cnic_no}}</span>
            <br>(Attach Photo Copy of National Identity Card)
        </li>
        <li>Home Address <span class="underline">{{getHcHomeAddress($application->id)}}</span>
        </li>
        <li>Postal Address <span class="underline">{{getHcPostalAddress($application->id)}}</span>
        </li>
        <li>E-mail Address <span class="underline">{{ $application->user->email }}</span>
            Contact <span class="underline">{{$application->mobile_no }}</span>
        </li>
        <li>Qualification & Duration of practice as an Advocate Lower Court</li>
        <li>
            Whether He/She filled:
            <ul>
                <li>Three photographs in professional dress</li>
                <li>Fitness certificate from two lawyers on their letter pads eligible to practice in the H.C
                </li>
                <li>Filed photocopy of renewal license of current year</li>
                <li>Affidavit regarding no criminal proceeding or proceeding of professional misconduct are pending in any
                    court of law</li>
                <li>A list of 20 cases, conducted by the applicant in the lower courts.</li>
                <li>
                    Deposit slip of any branch of HBL only
                    <ul>
                        <li>
                            Rs. 335 in the Pakistan Bar Council Account as enrolment fee. <br>
                            (Pakistan B.C. A/C 0042-79918974-03)
                        </li>
                        <li>
                            Rs. 665/- in Punjab Bar Council Account as Enrollment Fee. <br>
                            (Punjab B.C. A/C 0042-79000543-03)
                        </li>
                        <li>Rs. 15,200/- in Punjab Bar Council Account as General Fund <br>
                            (Punjab B.C. A/C 0042-79000543-03)</li>
                        <li>
                            Rs. 2,000/- deposited as Lawyers Group Benefit Scheme (Punjab B.C. A/C 0042-79000544-03)
                        </li>
                        <li>
                            Rs. 500- deposited as PLJ Law Site, <br>
                            (PLJ Law Site A/C 0042-79000554-03)
                        </li>
                        <li>
                            Rs. 2,000/- deposited as Lawyers Welfare Fund, <br> (A/C 0042-79922451-03)
                        </li>
                    </ul>

                </li>
            </ul>
        </li>
        <li>Whether he/she has filled an affidavit as to the duration of practice?</li>
        <li>Whether the applicant claims exemption from conditions of two years practice in the lower courts? If so,
            reasons there of to be supported by documentary evidence? <br>
            <b>[Holders of bar at law degree or having one year practical experience certificate possessing five years
                legal/judicial experience may seek exemption] fee for exemption from two years practice in the lower
                court RS 30,000/-</b>
        </li>
        <li>
            Whether the applicant has paid up to date renewal fees and arrears if any? 
            <strong>{{$application->paid_upto_date_renewal ?? '' }}</strong>
        </li>
        <li>
            Whether the applicant is/was engaged in any business, service, profession or vocation in Pakistan? If so.
            the nature there of and the place at which it is carried out?
            <strong>{{$application->is_engaged_in_business ?? '' }}</strong>
        </li>
        <li>
            Whether the applicant proposes to practice generally within the jurisdiction of the Punjab Bar Council?
            State place of practice?
            <strong>{{$application->is_practice_in_pbc ?? '' }}</strong>
        </li>
        <li>
            Whether the applicant has been declared insolvent?
            <strong>{{$application->is_declared_insolvent ?? '' }}</strong>
        </li>
        <li>
            Whether the applicant has been dismissed/removed from service of Government or of a Public Statutory
            Corporation, if so the reasons there of?
            <strong>{{$application->is_dismissed_from_public_service ?? '' }}</strong>
        </li>
        <li>
            Whether the applicant is enrolled as an advocate on the Roll of any other Bar Council?
            <strong>{{$application->is_enrolled_as_advocate ?? '' }}</strong>
        </li>
        <li>
            Whether the applicant has been convicted of any offense? If so, date and particulars there of?
            <strong>{{$application->is_any_misconduct ?? '' }}</strong>
        </li>
        <li>
            Whether the application of the applicant of enrolment has previously been rejected?
            <strong>{{$application->is_prev_rejected ?? '' }}</strong>
        </li>

        <footer>
            <span style="background-color: rgba(0, 0, 0, 0.100);">
                App No: {{$application->id}}
            </span>
            <span style="background-color: rgba(0, 0, 0, 0.100);">
                {!!appStatus($application->app_status,$application->app_type)!!}
            </span>
            @php $generator = new Picqer\Barcode\BarcodeGeneratorHTML(); @endphp
            {!! $generator->getBarcode($application->cnic_no, $generator::TYPE_CODE_128) !!}
        </footer>

        <div class="page-break"></div>

        <li>
            Description of Fees Deposited any <strong>Branch of HBL</strong>:
            <table style="width: 100%">
                <tr>
                    <td>(a)</td>
                    <td>Enrolment Fee: </td>
                    <td>(i) Pb. B.C.</td>
                    <td>Rs. <span style="font-weight: bold">
                            {{ $application->payments()->where('voucher_type',2)->first()->amount ?? '______' }}
                        </span>
                    </td>
                    <td>Bank Slip #<span style="font-weight: bold">
                            {{$application->payments()->where('voucher_type',2)->first()->voucher_no ??
                            '_______________'}}
                            @if (getHcTransactionNo($application->id, 2) != NULL)
                            / {{ getHcTransactionNo($application->id, 2) }}
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
                            @if (getHcTransactionNo($application->id, 1) != NULL)
                            / {{ getHcTransactionNo($application->id, 1) }}
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
                            @if (getHcTransactionNo($application->id, 2) != NULL)
                            / {{ getHcTransactionNo($application->id, 2) }}
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>(c)</td>
                    <td>Group Insurance</td>
                    <td></td>
                    <td>Rs. <span style="font-weight: bold">{{
                            $application->payments()->where('voucher_type',3)->first()->amount ?? '______' }}</span>
                    </td>
                    <td>Bank Slip #<span style="font-weight: bold">
                            {{$application->payments()->where('voucher_type',3)->first()->voucher_no ??
                            '_______________'}}
                            @if (getHcTransactionNo($application->id, 3) != NULL)
                            / {{ getHcTransactionNo($application->id, 3) }}
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>(d)</td>
                    <td>PLJ Subscription</td>
                    <td></td>
                    <td>Rs. <span style="font-weight: bold">{{
                            $application->payments()->where('voucher_type',5)->first()->amount ?? '______' }}</span>
                    </td>
                    <td>Bank Slip #<span style="font-weight: bold">
                            {{$application->payments()->where('voucher_type',5)->first()->voucher_no ??
                            '_______________'}}
                            @if (getHcTransactionNo($application->id, 5) != NULL)
                            / {{ getHcTransactionNo($application->id, 5) }}
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>(e)</td>
                    <td>Exemption Fee (if any)</td>
                    <td></td>
                    <td>Rs. <span style="font-weight: bold"></span>______</td>
                    <td>Bank Slip #<span style="font-weight: bold"></span>_______________</td>
                </tr>
                <tr>
                    <td>(e)</td>
                    <td>Lawyer Welfare</td>
                    <td></td>
                    <td>Rs. <span style="font-weight: bold"></span>______</td>
                    <td>Bank Slip #<span style="font-weight: bold"></span>_______________</td>
                </tr>
            </table>
        </li>
    </ol>
    <div style="float: right;margin: 20px 0px">
        SIGNATURE : __________________
    </div>

   
    <div style="padding-top:60px">
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
    <span style="background-color: rgba(0, 0, 0, 0.100);">
        Application - {{$application->id}}
    </span>
    <span style="background-color: rgba(0, 0, 0, 0.100);">
        {!!appStatus($application->app_status,$application->app_type)!!}
    </span>
    @php $generator = new Picqer\Barcode\BarcodeGeneratorHTML(); @endphp
    {!! $generator->getBarcode($application->cnic_no, $generator::TYPE_CODE_128) !!}
</footer>
@endsection