<fieldset class="border p-4 mb-4">
    <legend class="w-auto">Application Information</legend>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered" id="lc-table">
                <tr>
                    <th>Application No:</th>
                    <th class="text-center text-lg">
                        {{$application->id}}
                    </th>
                    <th>User ID:</th>
                    <th class="text-center text-lg">
                        {{$application->user_id}}
                    </th>
                </tr>
                <tr>
                    <th>Application Type:</th>
                    <td>Lower Court</td>
                    <th>Application Status:</th>
                    <td>
                        @if ($application->is_exemption == 1)
                        <span class="badge badge-warning">
                            Exemption
                         </span>
                        @endif

                        {!!appStatus($application->app_status, $application->app_type)!!}
                        @include('admin.lower-court.partials.update-application-status')
                    </td>
                </tr>

                @if (Route::currentRouteName() == 'lower-court.show')
                <tr>
                    <th>Payment Type:</th>
                    <td>Habib Bank Limited</td>
                    <th>Payment Status:</th>
                    <td>
                        <span class="badge badge-{{ getLcPaymentStatus($application->id)['badge'] }}">
                            {{ getLcPaymentStatus($application->id)['name'] }}</span>
                    </td>
                </tr>

                <tr>
                    <th>Final Intimation Date:</th>
                    <td>
                        {{isset($application->intimation_date) ? date('d-m-Y', strtotime($application->intimation_date))
                        : 'N/a'}}
                    </td>
                    <th>Final Lower Court Date:</th>
                    <td>
                        <span>{{getDateFormat($application->lc_date)}}</span>
                        @if (permission('update_lc_date'))
                        @include('admin.lower-court.partials.lc-date')
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>HC Exemption at:</th>
                    <td>
                        @if (!$application->hc_exemption_at)
                        <button type="button" id="lc_exemption_btn" class="btn btn-primary float-right btn-xs mr-1">
                            <i class="fas fa-plus mr-1 " aria-hidden="true"></i>Add
                        </button>
                        @else {{getDateFormat($application->hc_exemption_at)}}
                        @endif
                    </td>
                    <th>RCPT #:</th>
                    <td>
                        @component('admin.lower-court.components.number')
                        @slot('type') rcpt_no_lc @endslot
                        @slot('application_id') {{$application->id}} @endslot
                        @endcomponent
                    </td>
                </tr>

                <tr>
                    <th>License No:</th>
                    <td>
                        @component('admin.lower-court.components.number')
                        @slot('type') license_no_lc @endslot
                        @slot('application_id') {{$application->id}} @endslot
                        @endcomponent
                    </td>
                    <th>BF No:</th>
                    <td>
                        @component('admin.lower-court.components.number')
                        @slot('type') bf_no_lc @endslot
                        @slot('application_id') {{$application->id}} @endslot
                        @endcomponent
                    </td>
                </tr>
                <tr>
                    <th>PLJ No:</th>
                    <td>
                        @component('admin.lower-court.components.number')
                        @slot('type') plj_no_lc @endslot
                        @slot('application_id') {{$application->id}} @endslot
                        @endcomponent
                    </td>
                    <th>
                        Group Insurance No:
                    </th>
                    <td>
                        @component('admin.lower-court.components.number')
                        @slot('type') gi_no_lc @endslot
                        @slot('application_id') {{$application->id}} @endslot
                        @endcomponent

                        @foreach ($application->groupInsurances as $gi)
                        <div class="card card-shadow p-2 m-2">
                            <span>{{$gi->year}} - {{date('d-m-Y', strtotime($gi->date))}}</span>
                        </div>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th>Register/Legder No:</th>
                    <td>
                        @component('admin.lower-court.components.number')
                        @slot('type') reg_no_lc @endslot
                        @slot('application_id') {{$application->id}} @endslot
                        @endcomponent
                    </td>

                    <th>BF Legder No:</th>
                    <td>
                        @component('admin.lower-court.components.number')
                        @slot('type') bf_ledger_no @endslot
                        @slot('application_id') {{$application->id}} @endslot
                        @endcomponent
                    </td>
                </tr>

                <tr>
                    <th>Serial No:</th>
                    <td>{{$application->sr_no_lc}}</td>

                    <th>RF Id:</th>
                    <td>{{$application->rf_id ?? 'N/A'}}</td>
                </tr>

                <tr>
                    <th>Created at:</th>
                    <td>
                        <span>{{date('d-m-Y', strtotime($application->created_at))}}</span>
                    </td>
                    <th>Updated at:</th>
                    <td>
                        <span>{{date('d-m-Y', strtotime($application->updated_at))}}</span>
                    </td>
                </tr>

                <tr>
                    <th>Submission Date:</th>
                    <td>
                        <span>{{isset($application->final_submitted_at) ? date('d-m-Y',
                            strtotime($application->final_submitted_at)) : 'N/A'}}</span>
                    </td>
                </tr>
                @endif

            </table>
        </div>
    </div>
</fieldset>

<fieldset class="border p-4 mb-4">
    <legend class="w-auto">Bar Association & Passing Year</legend>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered" id="lc-table">
                <tr>
                    <th>LLB Passing Year:</th>
                    <td>{{$application->llb_passing_year ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th>Bar Association / Voter Member:</th>
                    <td>
                        {{$application->voterMemberLc->name ?? 'N/A'}}
                        @include('admin.lower-court.partials.edit-voter-member')
                    </td>
                </tr>
            </table>
        </div>
    </div>
</fieldset>

<fieldset class="border p-4 mb-4">
    <legend class="w-auto">Personal Information</legend>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered" id="lc-table">
                <tr>
                    <th>Profile Picture:</th>
                    <td>

                        @if (isset($application->uploads->profile_image))
                            <img src="{{asset('storage/app/public/'.$application->uploads->profile_image)}}" style="width:150px;height:auto"
                            class="custom-image-preview" alt="No Image Found">

                            @if (permission('edit-lower-court'))
                            <button type="button" data-toggle="modal" data-target="#addUpdateProfile" class="btn btn-primary float-right btn-xs mr-1">
                                <i class="fas fa-plus mr-1 " aria-hidden="true"></i>Update
                            </button>
                            @endif
                        @else
                             N/A
                             @if (permission('edit-lower-court'))
                                <button type="button" data-toggle="modal" data-target="#addUpdateProfile" class="btn btn-primary float-right btn-xs mr-1">
                                    <i class="fas fa-plus mr-1 " aria-hidden="true"></i>Add
                                </button>
                            @endif
                        @endif

                        @include('admin.lower-court.partials.add-update-profile-modal')
                    </td>
                </tr>
                <tr>
                    <th>Lawyer Name:</th>
                    <td>{{$application->lawyer_name ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th>Father's / Husband's Name:</th>
                    <td>{{$application->father_name ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th>Gender:</th>
                    <td>{{$application->gender ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th>Date of Birth (As per CNIC):</th>
                    <td>{{date('d-m-Y', strtotime($application->date_of_birth)) ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th>Age</th>
                    <td>{{convertLcAgeToWords($application->age)}}</td>
                </tr>
                <tr>
                    <th>Blood:</th>
                    <td>{{$application->blood ?? 'N/A'}}</td>
                </tr>
            </table>
        </div>
    </div>
</fieldset>

<fieldset class="border p-4 mb-4">
    <legend class="w-auto">Contact Information</legend>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered" id="lc-table">
                <tr>
                    <th>Email:</th>
                    <td>{{$application->email ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th>Mobile No:</th>
                    <td>{{$application->mobile_no ?? 'N/A'}}</td>
                </tr>
            </table>
        </div>
    </div>
</fieldset>

<fieldset class="border p-4 mb-4 page-break">
    <legend class="w-auto">GC Section</legend>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered" id="lc-table">
                <tr>
                    <th>Enrolment App SDW:</th>
                    <td>{{$application->enr_app_sdw ?? 'N/A'}}</td>

                    <th>Enrolment Status Reason:</th>
                    <td>{{$application->enr_status_reason ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th>Enrolment PLJ Check:</th>
                    <td>{{$application->enr_plj_check ?? 'N/A'}}</td>

                    <th>Enrolment GI Check:</th>
                    <td>{{$application->enr_gi_check ?? 'N/A'}}</td>
                </tr>
            </table>
        </div>
    </div>
</fieldset>

<fieldset class="border p-4 mb-4">
    <legend class="w-auto">Legal Identification</legend>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered" id="lc-table">
                <tr>
                    <th>C.N.I.C. No:</th>
                    <td>{{$application->cnic_no ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th>Date of Expiry (CNIC):</th>
                    <td>{{isset($application->cnic_expiry_date) ? date('d-m-Y',
                        strtotime($application->cnic_expiry_date)) : 'N/A'}}</td>
                </tr>
                <tr>
                    <th>CNIC (Front):</th>
                    <th>CNIC (Back):</th>
                </tr>
                <tr>
                    <td>
                        @if (isset($application->uploads->cnic_front))
                        <img src="{{asset('storage/app/public/'.$application->uploads->cnic_front)}}"
                            class="custom-image-preview" alt=""> @else N/A
                        @endif
                    </td>
                    <td>
                        @if (isset($application->uploads->cnic_back))
                        <img src="{{asset('storage/app/public/'.$application->uploads->cnic_back)}}"
                            class="custom-image-preview" alt=""> @else N/A
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
</fieldset>

<fieldset class="border p-4 mb-4 page-break">
    <legend class="w-auto">Address Information</legend>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered" id="lc-table">
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
                    <td>{{isset($application->address->ha_country_id) ?
                        getCountryName($application->address->ha_country_id): 'N/A'}}</td>
                </tr>

                @if (isset($application->address->ha_country_id) &&
                $application->address->ha_country_id == 166)
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
                    <td>{{isset($application->address->pa_country_id) ?
                        getCountryName($application->address->pa_country_id) : 'N/A'}}</td>
                </tr>

                @if (isset($application->address->pa_country_id) &&
                $application->address->pa_country_id == 166)
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
    </div>
</fieldset>

<fieldset class="border p-4 mb-4">
    <legend class="w-auto">Academic Record</legend>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-sm text-center table-bordered" id="lc-table">
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
                        @if (Route::currentRouteName() == 'lower-court.show')
                        <a href="{{asset('storage/app/public/'.$education->certificate)}}" download="certificate">
                            <span class="badge badge-success">Download</span>
                        </a>
                        @else Yes @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" colspan="text-center">No Records Found.</td>
                </tr>
                @endforelse
            </table>
        </div>
    </div>
</fieldset>

<fieldset class="border p-4 mb-4 page-break">
    <legend class="w-auto">Senior Lawyer Information</legend>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered" id="lc-table">
                <tr>
                    <th>Senior Lawyer Name :</th>
                    <td>{{$application->srl_name ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th>Advocate High Court / Subordinate Courts (Bar Name) :</th>
                    <td>{{$application->srlBar->name ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th>Senior Lawyer Office Address :</th>
                    <td>{{$application->srl_office_address ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th>Senior Lawyer Enrollment Date :</th>
                    <td>{{isset($application->srl_enr_date) ? date('d-m-Y',
                        strtotime($application->srl_enr_date)) : 'N/A'}}</td>
                </tr>
                <tr>
                    <th>Senior Lawyer Joining Date :</th>
                    <td>{{isset($application->srl_joining_date) ? date('d-m-Y',
                        strtotime($application->srl_joining_date)) : 'N/A'}}</td>
                </tr>
                <tr>
                    <th>Senior Lawyer Mobile Number :</th>
                    <td>{{isset($application->srl_mobile_no) ? '+92'.$application->srl_mobile_no :
                        'N/A'}}</td>
                </tr>
                <tr>
                    <th>Senior Lawyer CNIC Number :</th>
                    <td>{{$application->srl_cnic_no ?? 'N/A'}}</td>
                </tr>
                <tr>
                    <th>Senior Lawyer CNIC (Front) :</th>
                    <th>Senior Lawyer CNIC (Back) :</th>
                </tr>
                <tr>
                    <td>
                        @if (isset($application->uploads->srl_cnic_front))
                        <img src="{{asset('storage/app/public/'.$application->uploads->srl_cnic_front)}}" alt=""
                            class="custom-image-preview"> @else N/A @endif
                    </td>
                    <td>
                        @if (isset($application->uploads->srl_cnic_back))
                        <img src="{{asset('storage/app/public/'.$application->uploads->srl_cnic_back)}}" alt=""
                            class="custom-image-preview"> @else N/A @endif
                    </td>
                </tr>
                <tr>
                    <th>Senior Lawyer License (Front) :</th>
                    <th>Senior Lawyer License (Back) :</th>
                </tr>
                <tr>
                    <td>
                        @if (isset($application->uploads->srl_license_front))
                        <img src="{{asset('storage/app/public/'.$application->uploads->srl_license_front)}}" alt=""
                            class="custom-image-preview"> @else N/A @endif
                    </td>
                    <td>
                        @if (isset($application->uploads->srl_license_back))
                        <img src="{{asset('storage/app/public/'.$application->uploads->srl_license_back)}}" alt=""
                            class="custom-image-preview"> @else N/A @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
</fieldset>

<fieldset class="border p-4 mb-4">
    <legend class="w-auto">Lower Court Uploads</legend>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered" id="lc-table">
                <tr>
                    <th>Character Certificate From 1st Lawyer:</th>
                    <td>
                        @if (isset($application->uploads->certificate_lc))
                        <img src="{{asset('storage/app/public/'.$application->uploads->certificate_lc)}}" alt=""
                            class="custom-image-preview"> @else N/A @endif
                    </td>
                    <th>Character Certificate From 2nd Lawyer:</th>
                    <td>
                        @if (isset($application->uploads->certificate2_lc))
                        <img src="{{asset('storage/app/public/'.$application->uploads->certificate2_lc)}}" alt=""
                            class="custom-image-preview"> @else N/A @endif
                    </td>
                </tr>

                <tr>
                    <th>List of 20 Cases Signed By Senior:</th>
                    <td>
                        @if (isset($application->uploads->cases_lc))
                        <img src="{{asset('storage/app/public/'.$application->uploads->cases_lc)}}" alt=""
                            class="custom-image-preview">
                        @else N/A @endif
                    </td>

                    <th>Affidavit LC:</th>
                    <td>
                        @if (isset($application->uploads->affidavit_lc))
                        <img src="{{asset('storage/app/public/'.$application->uploads->affidavit_lc)}}" alt=""
                            class="custom-image-preview">
                        @else N/A @endif
                    </td>
                </tr>

                <tr>
                    <th>Undertaking From Bar Membership:</th>
                    <td>
                        @if (isset($application->uploads->undertaking_lc))
                        <img src="{{asset('storage/app/public/'.$application->uploads->undertaking_lc)}}" alt=""
                            class="custom-image-preview"> @else N/A @endif
                    </td>

                    <th>Original Provisional Certificate/Degree:</th>
                    <td>
                        @if (isset($application->uploads->org_prov_certificate_lc))
                        <img src="{{asset('storage/app/public/'.$application->uploads->org_prov_certificate_lc)}}"
                            alt="" class="custom-image-preview"> @else N/A @endif
                    </td>
                </tr>


                <tr>
                    @if(isset($application->uploads->practice_certificate) && $application->exemption_reason == 2)
                    <th>Practice Certificate LC:</th>
                    <td>
                        @if (isset($application->uploads->practice_certificate))
                        <img src="{{asset('storage/app/public/'.$application->uploads->practice_certificate)}}" alt=""
                            class="custom-image-preview">
                        @else N/A @endif
                    </td>
                    @endif

                    @if(isset($application->uploads->judge_certificate) && $application->exemption_reason == 3)
                    <th>Judge Certificate LC:</th>
                    <td colspan="3">
                        @if (isset($application->uploads->judge_certificate))
                        <img src="{{asset('storage/app/public/'.$application->uploads->judge_certificate)}}" alt=""
                            class="custom-image-preview">
                        @else N/A @endif
                    </td>
                    @endif
                </tr>

                <tr>
                    <th>BVC Certificate LC:</th>
                    <td colspan="3">
                        @if (isset($application->uploads->bvc_lc))
                        <img src="{{asset('storage/app/public/'.$application->uploads->bvc_lc)}}" alt=""
                            class="custom-image-preview">
                        @else N/A @endif
                    </td>
                </tr>

            </table>
        </div>
    </div>
</fieldset>
