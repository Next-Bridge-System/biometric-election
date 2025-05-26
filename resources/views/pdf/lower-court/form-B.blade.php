@extends('layouts.pdf')
@section('styles')
<style>
    body {
        margin: 20px 60px;
    }
</style>
@endsection
@section('content')
<h3 class="text-center">FORM C <br>
    <span class="bold-underline">CERTIFICATE OF TRAINING</span> <br>
    See Rule 5.3 (e)
</h3>
<p class="p-justify p-text" style="margin-bottom: 35px">I, Mr. <span class="dynamic-text">{{ $application->srl_name
        }}</span> Advocate of Pakistan, do hereby
    certify that Mr./Miss/Mrs.<span class="dynamic-text">{{ $application->lawyer_name}}</span> son/daughter/wife of
    <span class="dynamic-text">{{ $application->father_name }}</span> has training with me for a period of six
    months (From <span class="dynamic-text">{{ getDateFormat($application->intimation_date) ?? 'N/a' }}</span> to <span
        class="dynamic-text">
        @if (isset($application->final_submitted_at))
        {{Carbon\Carbon::parse($application->final_submitted_at)->format('d-m-Y')}}
        @endif </span> ) in
    accordance with Rule 5.3(e)
    of the Punjab Legal Practitioners & Bar Council Rules, 2023.
</p>
<p>That at the time when I took him/her as a pupil, I had been entitled to practice as a pleader/or as an Advocate for a
    period of not less than ten years.</p>
<p>That I did not have more than five pupils during the time of his pupillage.</p>
<p>That although I had more than five pupils during the whole of portion of his pupillage he was for the whole of for
    that portion of the period of his pupillage one out of the first five pupils considered in the order in which they
    were taken as pupils.</p>
<p>That I was practicing at the Bar during the whole period of pupillage and that a written intimation as to his/her
    having joined me as a pupil, signed by both of us, had been sent to the Secretary Bar Council within one month of
    the commencement of pupillage.</p>
<div style="margin-top: 80px">
    <table style="width:100%">
        <tr>
            <td>Dated : <span class="dynamic-text">{{
                    Carbon\Carbon::parse(Carbon\Carbon::now())->format('d-m-Y')}}</span>
            </td>
            <td class="text-center">SENIOR ADVOCATE SIGNATURE</td>
        </tr>
        <tr>
            <td></td>
            <td class="text-center">
                <div class="dynamic-text"></div>
            </td>
        </tr>
    </table>
</div>
<div style="margin-top: 80px">
    <p><strong>N.B.</strong></p>
    <p style="margin-left: 100px">The advocate shall specify overleaf at least twenty cases on which he/she has the
        assistance of the person who was in his pupillage.</p>
</div>
@endsection