@extends('layouts.pdf')

@section('content')
<div style="margin: 0 auto; text-align: center">
    <h2 class="text-underline ">AFFIDAVIT</h2>
</div>


<p style="font-size: 20px; line-height: 50px;text-align: justify"><span style="margin-right: 80px; ">&nbsp;</span>I
    <span class="dynamic-text">&nbsp;&nbsp;{{ $application->lawyer_name }}</span> &nbsp; S/D/W <span
        class="dynamic-text">&nbsp;&nbsp;{{ $application->father_name }}</span> &nbsp; R/O <span class="dynamic-text">{{
        isset($application) ? getLcHomeAddress($application->id) : '--' }}</span> do hereby solemnly affirms and declare
    and state truly and accurate that no criminal proceedings or proceeding for professional misconduct were ever
    instituted against the deponent in any court of law.
</p>

<div style="margin: 0px auto; text-align: right;padding-top: 50px;padding-bottom: 50px">
    <h3 class="text-underline ">DEPONENT</h3>
</div>


<h3 class="text-underline ">VERIFICATION</h3>

<p style="font-size: 20px; line-height: 50px;text-align: justify">Verified that contents of above affidavit are true and
    correct to the best of my knowledge and belief.</p>

<div style="margin: 50px auto; text-align: right;padding-top: 50px;">
    <h3 class="text-underline ">DEPONENT</h3>
</div>


@endsection
