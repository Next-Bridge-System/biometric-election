<fieldset class="border p-4 mb-4">
    <legend class="w-auto">Application Information</legend>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered">
                <tr>
                    <th>Application Token No:</th>
                    <th colspan="3" class="text-center text-lg">
                        {{$application->application_token_no ?? 'N/A'}}
                    </th>
                </tr>
                <tr>
                    <th>Application Status:</th>
                    <td>Intimation</td>
                    <th>Application Status:</th>
                    <td>
                        {!!appStatus($application->application_status,$application->app_type)!!}
                        @include('admin.intimations.partials.application-status')
                    </td>
                </tr>

                @if (Route::currentRouteName() == 'intimations.show')
                <tr>
                    <th>Payment Type:</th>
                    <td>{{ $application->paymentVoucher->bank_name ?? '- -' }}</td>
                    <th>Payment Status:</th>
                    <td>
                        <span class="badge badge-{{ getPaymentStatus($application->id)['badge'] }}">
                            {{ getPaymentStatus($application->id)['name'] }}</span>
                    </td>
                </tr>
                <tr>
                    <th>RCPT #:</th>
                    <td>
                        @component('admin.intimations.components.rcpt')
                        @slot('type') rcpt_no @endslot
                        @slot('application_id') {{$application->id}} @endslot
                        @endcomponent
                    </td>
                </tr>
                <tr>
                    <th>Final Intimation Date</th>
                    <td>
                        <span>{{$application->intimation_start_date ?? 'N/A'}}</span>
                        @if (permission('intimation-start-date') && $application->is_intimation_completed == 0)
                        @include('admin.intimations.partials.intimation-date')
                        @endif
                    </td>
                </tr>
                @endif

                @if (Route::currentRouteName() == 'home' && isset($application->intimation_start_date))
                <th>Final Intimation Date</th>
                <td>
                    <span>{{$application->intimation_start_date ?? 'N/A'}}</span>
                </td>
                @endif


                @isset($application->objections)
                <tr>
                    @php
                    $objections = json_decode($application->objections, TRUE)
                    @endphp
                    @isset($objections)
                    <th>Objections</th>
                    <td colspan="3">

                        @foreach ($objections as $item)
                        <span class="badge badge-danger">{{getObjections($item)}}</span>
                        @endforeach
                    </td>
                    @endisset
                </tr>
                @endisset
            </table>
        </div>
    </div>
</fieldset>
<fieldset class="border p-4 mb-4">
    <legend class="w-auto">Bar Association & Passing Year</legend>
    <div class="row">
        <table class="table table-striped table-sm table-bordered">
            <tr>
                <th>LLB Passing Year:</th>
                <td>{{$application->llb_passing_year ?? 'N/A'}}</td>
                <th>Result Card Date:</th>
                <td>
                    <span>{{getDateFormat($application->rcard_date)}}</span>
                </td>
            </tr>
            <tr>
                <th>Bar Association:</th>
                <td colspan="3">{{$application->barAssociation->name ?? 'N/A'}}</td>
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
                            <img src="{{asset('storage/app/public/'.$application->uploads->profile_image)}}" style="width:150px;height:auto"
                            class="custom-image-preview" alt="No Image Found">

                            @if (permission('edit-intimations'))
                            <button type="button" data-toggle="modal" data-target="#addUpdateProfile" class="btn btn-primary float-right btn-xs mr-1">
                                <i class="fas fa-plus mr-1 " aria-hidden="true"></i>Update
                            </button>
                            @endif
                        @else
                             N/A
                             @if (permission('edit-intimations'))
                                <button type="button" data-toggle="modal" data-target="#addUpdateProfile" class="btn btn-primary float-right btn-xs mr-1">
                                    <i class="fas fa-plus mr-1 " aria-hidden="true"></i>Add
                                </button>
                            @endif
                        @endif

                        @include('admin.intimations.partials.add-update-profile-modal')
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
                <td>{{date('d-m-Y', strtotime($application->date_of_birth)) ?? 'N/A'}}</td>
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
                <td>+92{{$application->active_mobile_no ?? 'N/A'}}</td>
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
</fieldset>
<fieldset class="border p-4 mb-4">
    <legend class="w-auto">Academic Record</legend>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-sm text-center table-striped table-bordered">
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
                        @if (Route::currentRouteName() != 'intimations.print-detail')
                        <a href="{{asset('storage/app/public/'.$education->certificate)}}" target="_blank">
                            <span class="badge badge-primary"><i class="fas fa-print mr-1"></i>Preview</span>
                        </a>
                        <a href="{{asset('storage/app/public/'.$education->certificate)}}" download="certificate">
                            <span class="badge badge-success"><i class="fas fa-file-download mr-1"></i>Download</span>
                        </a>

                        @else - @endif
                    </td>
                    @if (Route::currentRouteName() == 'intimations.show' &&
                    permission('intimation-edit-academic-record') && $application->is_intimation_completed == 0)
                    <td>
                        @include('admin.intimations.partials.edit-academic')
                    </td>
                    @endif
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
    @if (Route::currentRouteName() == 'intimations.show'
    && permission('intimation-edit-senior-lawyer-information') && $application->is_intimation_completed == 0)
    @include('admin.intimations.partials.edit-senior-lawyer')
    @endif
    <div id="senior_lawyer_section_data">
        @include('admin.intimations.partials.senior-lawyer-section')
    </div>
</fieldset>

@if (Route::currentRouteName() == 'intimations.show')
<fieldset class="border p-4 mb-4">
    <legend class="w-auto">Additional Information</legend>
    <div class="row">
        <div class="table-responsive">
            <table class="table table-striped table-sm table-bordered">
                <tr>
                    <th>Created Date:</th>
                    <td>
                        <span>{{date('d-m-Y', strtotime($application->created_at))}}</span>
                    </td>
                    <th>Updated Date:</th>
                    <td>
                        <span>{{date('d-m-Y', strtotime($application->updated_at))}}</span>
                    </td>
                </tr>
                <tr>
                    <th>Submission Date:</th>
                    <td colspan="3">
                        <span>{{isset($application->final_submitted_at) ? date('d-m-Y',
                            strtotime($application->final_submitted_at)) : 'N/A'}}</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</fieldset>
@endif
