@extends('layouts.pdf')

@section('content')
<div style="margin: 0 auto; text-align: center">
    <h2 class="text-underline">UNDERTAKING</h2>
</div>

<p style="font-size: 20px; line-height: 50px;text-align: justify"><span style="margin-right: 100px; ">&nbsp;</span>I
    <span class="dynamic-text">&nbsp;&nbsp;{{ $application->lawyer_name ?? 'N/A' }}</span> &nbsp; S/D/W <span
        class="dynamic-text">&nbsp;&nbsp;{{ $application->father_name ?? 'N/A' }}</span> &nbsp; R/O <span
        class="dynamic-text">{{ isset($application) ? getLcHomeAddress($application->id) : '--' }}</span> do hereby
    undertake to become a member of <span class="dynamic-text">&nbsp;&nbsp;{{ $application->voterMemberLc->name ?? 'N/A'
        }}</span> Bar Association within a period of six months from the date of enrollment as an advocate.
</p>

<div style="margin: 50px auto; text-align: center">
    <h2 class="text-underline">_____________________</h2>
</div>


@endsection
