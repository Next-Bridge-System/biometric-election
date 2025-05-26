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
            background-image: url('{{asset('public/admin/images/watermark.png')}}');
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
            line-height: 35px;
        }

        .page-break {
            page-break-after: always;
        }

        table,
        th,
        td {
            border: 1px solid rgb(194, 189, 189);
            padding: 3px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
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
    </style>
</head>

<body>

    <main>
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
                <h4 class="text-underline">INTIMATION APPLICATION</h4>
            </div>
    </main>

    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-success">
                        <div class="card-body">
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Application Information</legend>
                                <div class="row">
                                    <table class="table table-striped table-sm table-bordered">
                                        <tr>
                                            <th>Application Token No:</th>
                                            <th colspan="3" class="text-center text-lg">
                                                {{$application->application_token_no ?? 'N/A'}}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Application Type:</th>
                                            <td>Intimation</td>
                                            <th>Application Status:</th>
                                            <td>
                                                @if ($application->application_status == 1) Active
                                                @elseif($application->application_status == 2) Suspended
                                                @elseif($application->application_status == 3) Died
                                                @elseif($application->application_status == 4) Removed
                                                @elseif($application->application_status == 5) Transfer in
                                                @elseif($application->application_status == 6) Transfer out
                                                @elseif($application->application_status == 7) Pending
                                                @elseif($application->application_status == 0) Rejected
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Payment Type:</th>
                                            <td>{{$application->paymentVoucher->bank_name}}</td>
                                            <th>Payment Status:</th>
                                            <td>
                                                <span
                                                    class="badge badge-{{ getPaymentStatus($application->id)['badge'] }}">
                                                    {{
                                                    getPaymentStatus($application->id)['name'] }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>

                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Bar Association & Passing Year</legend>
                                <div class="row">
                                    <table class="table table-striped table-sm table-bordered">
                                        <tr>
                                            <th>LLB Passing Year:</th>
                                            <td>{{$application->llb_passing_year ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Bar Association:</th>
                                            <td>{{$application->barAssociation->name ?? 'N/A'}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>

                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Personal Information</legend>
                                <div class="row">
                                    <table class="table table-striped table-sm table-bordered">
                                        <tr>
                                            <th>Profile Picture:</th>
                                            <td>
                                                @if (isset($application->uploads->profile_image))
                                                <img src="{{asset('storage/app/public/'.$application->uploads->profile_image)}}"
                                                    class="custom-image-preview" alt=""
                                                    style="width: 180px; height: 180px"> @else N/A
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>First Name:</th>
                                            <td>{{$application->advocates_name ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Last Name:</th>
                                            <td>{{$application->last_name ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Father's / Husband's Name:</th>
                                            <td>{{$application->so_of ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Gender:</th>
                                            <td>{{$application->gender ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Date of Birth (As per CNIC):</th>
                                            <td>{{$application->date_of_birth ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Blood:</th>
                                            <td>{{$application->blood ?? 'N/A'}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                            <fieldset class="border p-4 mb-4 page-break">
                                <legend class="w-auto">Contact Information</legend>
                                <div class="row">
                                    <table class="table table-striped table-sm table-bordered">
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{$application->email ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Active Mobile No:</th>
                                            <td>{{$application->active_mobile_no ?? 'N/A'}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Legal Identification</legend>
                                <div class="row">
                                    <table class="table table-striped table-sm table-bordered">
                                        <tr>
                                            <th>C.N.I.C. No:</th>
                                            <td>{{$application->cnic_no ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Date of Expiry (CNIC):</th>
                                            <td>{{$application->cnic_expiry_date ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>CNIC (Front):</th>
                                            <th>CNIC (Back):</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if (isset($application->uploads->cnic_front))
                                                <img src="{{asset('storage/app/public/'.$application->uploads->cnic_front)}}"
                                                    style="width: 270px;height:240px"> @else N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($application->uploads->cnic_back))
                                                <img src="{{asset('storage/app/public/'.$application->uploads->cnic_back)}}"
                                                    style="width: 270px;height:240px"> @else N/A
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                            <fieldset class="border p-4 mb-4 page-break">
                                <legend class="w-auto">Address Information</legend>
                                <div class="row">
                                    <table class="table table-striped table-sm table-bordered">
                                        <tr class="bg-success text-center">
                                            <th colspan="2">Home Address</th>
                                        </tr>
                                        <tr>
                                            <th>House #:</th>
                                            <td>{{$application->address->ha_house_no ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Street Address:</th>
                                            <td>{{$application->address->ha_str_address ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Town/Mohalla:</th>
                                            <td>{{$application->address->ha_town ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>City:</th>
                                            <td>{{$application->address->ha_city ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Postal Code:</th>
                                            <td>{{$application->address->ha_postal_code ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Country:</th>
                                            <td>{{getCountryName($application->address->ha_country_id) ?? 'N/A'}}</td>
                                        </tr>

                                        @if ($application->address->ha_country_id == 166)
                                        <tr>
                                            <th>Province/State:</th>
                                            <td>{{getProvinceName($application->address->ha_province_id) ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>District:</th>
                                            <td>{{getDistrictName($application->address->ha_district_id) ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Tehsil:</th>
                                            <td>{{getTehsilName($application->address->ha_tehsil_id) ?? 'N/A'}}</td>
                                        </tr>
                                        @endif

                                        <tr class="bg-success text-center">
                                            <th colspan="2">Postal Address</th>
                                        </tr>
                                        <tr>
                                            <th>House #:</th>
                                            <td>{{$application->address->pa_house_no ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Street Address:</th>
                                            <td>{{$application->address->pa_str_address ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Town/Mohalla:</th>
                                            <td>{{$application->address->pa_town ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>City:</th>
                                            <td>{{$application->address->pa_city ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Postal Code:</th>
                                            <td>{{$application->address->pa_postal_code ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Country:</th>
                                            <td>{{getCountryName($application->address->pa_country_id) ?? 'N/A'}}</td>
                                        </tr>

                                        @if ($application->address->pa_country_id == 166)
                                        <tr>
                                            <th>Province/State:</th>
                                            <td>{{getProvinceName($application->address->pa_province_id) ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>District:</th>
                                            <td>{{getDistrictName($application->address->pa_district_id) ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Tehsil:</th>
                                            <td>{{getTehsilName($application->address->pa_tehsil_id) ?? 'N/A'}}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </fieldset>
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Academic Record</legend>
                                <div class="row">
                                    <table class="table table-sm text-center table-bordered">
                                        <tr class="bg-light">
                                            <th>#</th>
                                            <th>Qualification</th>
                                            <th>Institute</th>
                                            <th>Total Marks</th>
                                            <th>Obtained Marks</th>
                                            <th>Passing Year</th>
                                            <th>Roll No</th>
                                            <th>Certificate</th>
                                        </tr>
                                        @forelse ($application->educations as $education)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>
                                                {{getQualificationName($education->qualification)}}
                                                {{$education->sub_qualification ?? ''}}
                                            </td>
                                            <td>{{$education->university->name ?? $education->institute}}</td>
                                            <td>{{$education->total_marks ?? 'N/A'}}</td>
                                            <td>{{$education->obtained_marks ?? 'N/A'}}</td>
                                            <td>{{$education->passing_year ?? 'N/A'}}</td>
                                            <td>{{$education->roll_no ?? 'N/A'}}</td>
                                            <td>
                                                <span class="badge badge-success">Attached</span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" colspan="text-center">No Records Found.</td>
                                        </tr>
                                        @endforelse
                                    </table>
                                </div>
                            </fieldset>
                            <fieldset class="border p-4 mb-4 page-break">
                                <legend class="w-auto">Senior Lawyer Information</legend>
                                <div class="row">
                                    <table class="table table-striped table-sm table-bordered">
                                        <tr>
                                            <th>Sr. Lawyer Name :</th>
                                            <td>{{$application->srl_name ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Advocate High Court / Subordinate Courts (Bar Name) :</th>
                                            <td>{{$application->srlBar->name ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Sr. Lawyer Office Address :</th>
                                            <td>{{$application->srl_office_address ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Sr. Lawyer Enrollment Date :</th>
                                            <td>{{$application->srl_enr_date ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Sr. Lawyer Mobile Number :</th>
                                            <td>{{$application->srl_mobile_no ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Sr. Lawyer CNIC Number :</th>
                                            <td>{{$application->srl_cnic_no ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Sr. Lawyer CNIC (Front) :</th>
                                            <th>Sr. Lawyer CNIC (Back) :</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if (isset($application->uploads->srl_cnic_front))
                                                <img src="{{asset('storage/app/public/'.$application->uploads->srl_cnic_front)}}"
                                                    style="width: 270px;height:210px"> @else N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($application->uploads->srl_cnic_back))
                                                <img src="{{asset('storage/app/public/'.$application->uploads->srl_cnic_back)}}"
                                                    style="width: 270px;height:210px"> @else N/A
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Sr. Lawyer License (Front) :</th>
                                            <th>Sr. Lawyer License (Back) :</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if (isset($application->uploads->srl_license_front))
                                                <img src="{{asset('storage/app/public/'.$application->uploads->srl_license_front)}}"
                                                    style="width: 270px;height:210px"> @else N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($application->uploads->srl_license_back))
                                                <img src="{{asset('storage/app/public/'.$application->uploads->srl_license_back)}}"
                                                    style="width: 270px;height:210px"> @else N/A
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </fieldset>
                            @if (isset($payments))
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Payment Details</legend>
                                <div class="row">
                                    <table class="table table-striped table-sm table-bordered">
                                        <tr>
                                            <th>Applicant Name:</th>
                                            <td>{{getLawyerName($application->id)}}</td>
                                        </tr>
                                        <tr>
                                            <th>Father/Husband Name:</th>
                                            <td>{{$application->so_of ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>CNIC:</th>
                                            <td>{{$application->cnic_no}}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Fee:</th>
                                            <td><span class="badge badge-success">{{getVoucherAmount($application->id)}}
                                                    PKR</span></td>
                                        </tr>
                                    </table>

                                    @foreach($payments as $payment)
                                    <table class="table table-striped table-sm table-bordered">
                                        <tr class="text-center bg-success">
                                            <th colspan="2">Payment # {{$loop->iteration}}</th>
                                        </tr>
                                        <tr>
                                            <th>Payment:</th>
                                            <td>{{isset($payment->bank_name) ? $payment->bank_name : 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th>Amount</th>
                                            <td><span class="badge badge-success">{{isset($payment->amount) ?
                                                    $payment->amount : 'N/A'}} PKR</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Payment Status:</th>
                                            <td>
                                                @if (isset($payment) && $payment->payment_status == 1)
                                                <span class="badge badge-success">Paid</span>
                                                @else
                                                <span class="badge badge-danger">Unpaid</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Paid Date:</th>
                                            <td>
                                                {{date('d-m-Y', strtotime($payment->paid_date))}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Voucher No:</th>
                                            <td>{{$payment->voucher_no}}</td>
                                        </tr>
                                        <tr>
                                            <th>Transaction ID:</th>
                                            <td>{{$payment->transaction_id}}</td>
                                        </tr>
                                        <tr>
                                            <th>Payment added by:</th>
                                            <td>{{getAdminName($payment->admin_id) ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <th>Payment added at:</th>
                                            <td>
                                                {{date('d-m-Y', strtotime($payment->updated_at))}}
                                            </td>
                                        </tr>
                                    </table>
                                    @endforeach
                                </div>
                            </fieldset>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
