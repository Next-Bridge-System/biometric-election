@extends('layouts.pdf')
@section('title') LC CANDIDATE INTERVIEW LETTER - 0{{$application->id}} @endsection
@section('styles')
<style>
    table,
    th,
    td {
        padding: 3px !important;
        font-size: 14px !important;
    }

    #interview-table td {
        border: 1px solid rgb(194, 189, 189) !important;
        text-align: left !important;
    }

    #interview-table th {
        border: 1px solid rgb(194, 189, 189) !important;
        text-align: left !important;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        text-transform: capitalize;
    }

    fieldset legend {
        color: green;
        font-weight: 600;
    }

    fieldset.border {
        border: 2px solid #17af23 !important;
        border-radius: 5px !important;
    }

    .mb-4 {
        margin-bottom: 20px
    }

    p {
        font-size: 14px !important;
    }

    ,
    .page_2 p {
        font-size: 18px !important;
        text-align: justify
    }
</style>
@endsection
@section('content')
<h3 class="text-center text-underline">CANDIDATE INTERVIEW LETTER</h3>
<p style="margin-top:30px; margin-bottom:30px; text-transform: uppercase;">
    To, <br>
    {{$application->lawyer_name}}, <br>
    {{$application->father_name}}, <br>
    {{$application->mobile_no}}, <br>
    {{getLcPostalAddress($application->id)}}, <br>
</p>

<fieldset class="border p-4">
    <legend class="w-auto">Members Information</legend>
    <div class="row">
        <table class="table table-striped table-sm table-bordered" id="interview-table">
            @foreach ($application->assignMembers as $member)
            @if (isset($member->member))
            <tr>
                <th colspan="4" style="text-align: center !important;">Member {{$loop->iteration}}</th>
            </tr>
            <tr>
                <th>Name:</th>
                <td colspan="3">{{$member->member->name}}</td>
            </tr>
            <tr>
                <th>Committe Name:</th>
                <td>{{$member->member->committee_name}}</td>

                <th>Mobile No:</th>
                <td>+92{{$member->member->mobile_no}}</td>
            </tr>
            <tr>
                <th>Division:</th>
                <td>{{$member->member->division->name}}</td>

                <th>District:</th>
                <td>{{$member->member->district->name}}</td>
            </tr>
            <tr>
                <th>Tehsil:</th>
                <td>{{$member->member->tehsil->name}}</td>

                <th>Bar/Station:</th>
                <td>{{$member->member->bar->name}}</td>
            </tr>
            <tr>
                <th>Address:</th>
                <td colspan="3">{{$member->member->address}}</td>
            </tr>
            @endif
            @endforeach

        </table>
    </div>
</fieldset>

<div style="font-size:5px; text-align:justify">
    <p><span style="font-size:10pt"><span style="font-family:Calibri,sans-serif">Dear Candidate. You are directed to
                please
                contact

                @foreach ($application->assignMembers as $member)
                @if (isset($member->member))
                <strong><span style="text-decoration: underline">{{$member->member->name}}</span> @if($loop->last) @else
                    {{'and'}} @endif </strong>
                @endif
                @endforeach

                Learned
                Members, Examination Committee on any day. during Court hours for your Viva Voce Examination. As
                provided by
                Rule 5.2A (9) of the Punjab Legal Practitioners &amp; Bar Council Rules. 1974. the Viva Voce Examination
                will be held in the following subjects:</span></span></p>

    <ul style="list-style-type: none;">
        <li><strong><span style="font-size:9pt"><span style="font-family:Calibri,sans-serif">1. Code of Civil
                        Procedure,
                        1908.
                        &nbsp;</span></span></strong></li>
        <li><strong><span style="font-size:9pt"><span style="font-family:Calibri,sans-serif">2. Code of Criminal
                        procedure,
                        1898.</span></span></strong></li>
        <li><strong><span style="font-size:9pt"><span style="font-family:Calibri,sans-serif">3. Limitation Act, 1908
                    </span></span></strong></li>
        <li><strong><span style="font-size:9pt"><span style="font-family:Calibri,sans-serif">4. Constitution of the
                        Islamic
                        Republic of Pakistan, 1973.</span></span></strong></li>
        <li><strong><span style="font-size:9pt"><span style="font-family:Calibri,sans-serif">5. Legal Practitioners
                        &amp;
                        Bar
                        Councils Act, 1973 and the Rules made there under. </span></span></strong></li>
        <li><strong><span style="font-size:9pt"><span style="font-family:Calibri,sans-serif">6. Canons of Professional
                        Conduct
                        and Etiquette of Advocates as framed by the Pakistan Bar Council, 1976. </span></span></strong>
        </li>
        <li><strong><span style="font-size:9pt"><span style="font-family:Calibri,sans-serif">7. Court Fees Act, 1870
                        and
                        Suits
                        Valuation Act; and </span></span></strong></li>
        <li><strong><span style="font-size:9pt"><span style="font-family:Calibri,sans-serif">8. Qanun-e-Shahadat Order,
                        1984
                    </span></span></strong></li>
    </ul>

    <p><span style="font-size:10pt"><span style="font-family:Calibri,sans-serif">Please bring the following documents
                with
                you and get them compared before the time of Viva Voce Examination, failing which your case will not be
                considered for examination by the Examiners:</span></span></p>

    <ul style="list-style-type: none;">
        <li><strong><span style="font-size:9pt"><span style="font-family:Calibri,sans-serif">1. Matriculation
                        Certificate.
                    </span></span></strong></li>
        <li><strong><span style="font-size:9pt"><span style="font-family:Calibri,sans-serif">2. B.A. Degree.
                    </span></span></strong></li>
        <li><strong><span style="font-size:9pt"><span style="font-family:Calibri,sans-serif">3. LL.B. Degree, if not
                        received
                        from the University. Please bring your LL.B. Provisional Certificate. </span></span></strong>
        </li>
    </ul>

    <p><span style="font-size:9px"><span style="font-family:Calibri,sans-serif">NOTE: AFTER YOU HAD PASSED THE VIVA
                VOCE
                EXAMINATION YOUR FILE WILL BE SENT TO THE ENROLMENT COMMITTEE HEADED BY THE JUDGE OF THE HIGH COURT FOR
                APPROVAL AND ON APPROVAL BEING GRANTED THE LICENCE TO PRACTICE AS AN ADVOCATE WILL BE ISSUED TO YOU.
            </span></span></p>

    <p><span style="font-size:9px"><span style="font-family:Calibri,sans-serif">THE MALE CANDIDATES SHALL APPEAR FOR
                INTERVIEW WEARING MAROON TIE, WHERE AS THE FEMALE CANDIDATES SHALL APPEAR WEARING MAROON SCARF OR MAROON
                DOPATTA </span></span></p>

</div>


<div class="page-break"></div>

<div class="page_2">
    <p>
        APPLICATION OF MR./MISS/MRS. <span class="dynamic-text">{{$application->lawyer_name}}</span>
    </p>

    <p>
        SON/DAUGHTER/WIFE OF <span class="dynamic-text">{{$application->father_name}}</span>
    </p>

    <p>
        RESIDENT OF <span class="dynamic-text">{{getLcHomeAddress($application->id)}}</span>
    </p> <br> <br>

    <p>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        The applicant has completed the
        requisite period of six months training with Mr.
        <span class="dynamic-text">{{$application->srl_name ?? 'N/a'}}</span>
        Advocate <span class="dynamic-text">{{$application->srlBar->name ?? 'N/a'}}</span>.He may, therefore, be
        examined
        as required under Rule 5.2A of the Punjab Legal Practitioners & Bar Council Rules, 1974.
    </p> <br> <br> <br> <br>

    <p style="float: right">
        <b>SECRETARY</b>
    </p> <br> <br> <br> <br> <br> <br>

    <p>
        <b>EXAMINERS</b>
        <span>
            @foreach ($application->assignMembers as $member)
            <div style=" padding-top:50px">
            </div>
            <div style="padding-bottom: 20px"><b>{{$member->member->name}}</b></div><br>
            <div style="padding-bottom: 20px">Signature: _________________________</div> <br>
            <div style="padding-bottom: 20px">Secret code: _______________________</div><br>
            @endforeach
        </span>
    </p>
</div>

<div class="page-break"></div>

@foreach ($application->assignMembers as $member)

<h3 class="text-center text-underline">Member {{$loop->iteration}}</h3>
<p style="margin-top:50px; margin-bottom:30px; text-transform: uppercase;">
    To, <br> <br>
    {{$member->member->name}}, <br>
    +92{{$member->member->mobile_no}}, <br>
    {{$member->member->bar->name}}, <br>
    {{$member->member->address}}, <br>
</p> <br> <br>

<fieldset class="border p-4" style="margin-bottom:30px;">
    <legend class="w-auto">Candidate Information</legend>
    <div class="row">
        <table class="table table-striped table-sm table-bordered" id="interview-table">
            <tr>
                <th>Name:</th>
                <td>{{$application->lawyer_name}}</td>

                <th>Father Name:</th>
                <td>{{$application->father_name}}</td>
            </tr>
            <tr>
                <th>Cnic No:</th>
                <td>{{$application->cnic_no}}</td>
                <th>Mobile No:</th>
                <td>{{$application->mobile_no}}</td>
            </tr>
            <tr>
                <th>Secret Code:</th>
                <td colspan="3">{{$member->code}}</td>
            </tr>
        </table>
    </div>
</fieldset>


<p style="text-align: justify">The applicant has/have been informed of his/her case/s having been sent to the learned
    Committee for Viva Voce
    Examination. The degree and certificate which he/they has/have been asked to produce before the Committee and time
    of the Viva Voce Examination may be verified with the originals. The file, may please be returned to this office
    after the applicant/s has/have been examined.</p>

@if(!$loop->last) <div class="page-break"></div> @endif
@endforeach

@endsection
