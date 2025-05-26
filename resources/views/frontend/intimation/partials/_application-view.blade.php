<fieldset class="border p-4 mb-4">
    <legend class="w-auto">Application Information</legend>
    <div class="row">
        <table class="table table-striped table-sm table-bordered">
            <tr>
                <th>App No:</th>
                <th colspan="3" class="text-center text-lg">
                    {{$application->application_token_no ?? 'N/A'}}
                </th>
            </tr>
            @if ($application->application_type == 6)
            <tr>
                <th>Intimation Date:</th>
                <th colspan="3" class="text-center text-lg">
                    {{$application->intimation_start_date ?? 'N/A'}}
                </th>
            </tr>
            @endif
            <tr>
                <th>App Type:</th>
                <td>Intimation</td>
                <th>App Status:</th>
                <td>
                    @if ($application->application_status == 1)
                    <span class="text-md badge badge-success">Active</span>
                    @elseif($application->application_status == 2) Suspended
                    @elseif($application->application_status == 3) Died
                    @elseif($application->application_status == 4) Removed
                    @elseif($application->application_status == 5) Transfer in
                    @elseif($application->application_status == 6) Transfer out
                    @elseif($application->application_status == 7)
                    <span class="text-md badge badge-primary">Pending</span>
                    @elseif($application->application_status == 0)
                    <span class="text-md badge badge-danger">Rejected</span>
                    @endif
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
            <tr>
                <th>Profile Picture:</th>
                <td>
                    @if (isset($application->uploads->profile_image))
                    <img src="{{asset('storage/app/public/'.$application->uploads->profile_image)}}"
                        class="custom-image-preview" alt=""> @else N/A
                    @endif
                </td>
            </tr>
        </table>
    </div>
</fieldset>
<fieldset class="border p-4 mb-4">
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
                <td>{{date('d-m-Y', strtotime($application->cnic_expiry_date)) ?? 'N/A'}}</td>

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
<fieldset class="border p-4 mb-4">
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
    <div class="row table-responsive">
        <table class="table table-sm text-center table-bordered" id="datatable">
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
                    <a href="{{asset('storage/app/public/'.$education->certificate)}}" download="certificate">
                        <span class="badge badge-success">Download</span>
                    </a>
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
<fieldset class="border p-4 mb-4">
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
                <td>{{date('d-m-Y', strtotime($application->srl_enr_date)) ?? 'N/A'}}</td>
            </tr>
            <tr>
                <th>Sr. Lawyer Joining Date :</th>
                <td>{{date('d-m-Y', strtotime($application->srl_joining_date)) ?? 'N/A'}}</td>
            </tr>
            <tr>
                <th>Sr. Lawyer Mobile Number :</th>
                <td>+92{{$application->srl_mobile_no ?? 'N/A'}}</td>
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
                <th>Sr. Lawyer License (Front) :</th>
                <th>Sr. Lawyer License (Back) :</th>
            </tr>
            <tr>
                <td>
                    @if (isset($application->uploads->srl_license_front))
                    <img src="{{asset('storage/app/public/'.$application->uploads->srl_license_front)}}" alt=""
                        class="custom-image-preview"> @else No file attached @endif
                </td>
                <td>
                    @if (isset($application->uploads->srl_license_back))
                    <img src="{{asset('storage/app/public/'.$application->uploads->srl_license_back)}}" alt=""
                        class="custom-image-preview"> @else No file attached @endif
                </td>
            </tr>
        </table>
    </div>
</fieldset>
