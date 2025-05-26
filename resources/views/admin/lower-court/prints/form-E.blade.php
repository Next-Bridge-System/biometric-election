@extends('layouts.pdf')
@section('styles')
<style>
    body {
        margin: 0px 60px;
    }
</style>
@endsection
@section('content')
<h3 class="text-center">PUNJAB BAR COUNCIL</h3>
<h4 class="text-center" style="margin-bottom: 0px">
    APPLICATION FOR REGISTRATION AS CONTRIBUTOR TO <br>
    THE PUNJAB ADVOCATES BENEVOLENT FUND

</h4>
<p class="text-center" style="margin-top: 0px">
    (Note: To be filled in by new Contributors only) <br>
    Advocates who are already registered are not required to fill in this form. <br>
    They should only deposit their contribution in any Branch of Habib Bank, Ltd.

</p>
<div>
    Name <span class="dynamic-text">&nbsp;&nbsp;{{ $application->lawyer_name }}</span> <br>
    Fathers/Husband's Name <span class="dynamic-text">&nbsp;&nbsp;{{ $application->father_name ?? "N/A" }}</span> <br>
    Date of Birth <span class="dynamic-text">&nbsp;&nbsp;{{ $application->date_of_birth ?? "N/A" }}</span> <br>
    Date/Year of Enrolment as an Advocate ______________________ <br>
    Date/Year of Enrolment as an Advocate of High Court ______________________ <br>
    Ordinary place of practice <span class="dynamic-text">&nbsp;&nbsp;{{ $application->voterMemberLc->district->name ??
        'N/A' }}</span> <br>
    Name of Bar Association of which applicant is member <span class="dynamic-text">&nbsp;&nbsp;{{
        $application->voterMemberLc->name ?? 'N/A' }}</span> <br>
    Date of application <span class="dynamic-text">&nbsp;&nbsp;{{ date('d-m-Y',strtotime($application->created_at)) ??
        "N/A" }}</span> <br>

</div>
<div style="text-align: right;padding-top: 50px">
    SIGNATURE <br>
    FULL ADDRESS <span class="dynamic-text">&nbsp;&nbsp;{{ isset($application) ? getLcHomeAddress($application->id) :
        '--' }}</span><br><br>
    Your Cell No <span class="dynamic-text">&nbsp;&nbsp;{{ '+92'.$application->user->phone ?? 'N/A' }}</span><br>
</div>
<div>
    <span style="font-size: 18px"><strong>Particulars of Payment</strong></span> <br> <br>
    Amount: __________________________________<br><br>
    Where Deposit: ____________________________<br><br>
    Date of Deposit: ___________________________<br><br>
    Receipt No: _______________________________<br><br>
    Name of Bank Branch: ______________________<br><br>
</div>

<div style="margin-top: 70px">
    <table style="width:100%">
        <tr>
            <td class="text-center"><strong>ATTESTED</strong> </td>
            <td class="text-center"></td>
        </tr>
        <tr>
            <td><span class="text-underline">MEMBER PUNJAB BAR COUNCIL</span> <br> PRESIDENT BAR ASSOCIATION</td>
            <td class="text-center">SIGNATURE <br> OF THE APPLICANT/ADVOCATE</td>
        </tr>
    </table>
</div>
<p class="text-center" style="font-size: 18px">
    NOTE: Please send filled in form to <strong>Secretary Punjab Bar Council</strong><br>
    9-Fane Road, Lahore

</p>

@endsection
