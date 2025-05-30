<table class="table table-sm table-striped table-bordered">
    <tr>
        <th>Application Token #</th>
        <td colspan="2"><span class="text-lg"><b>{{$application->application_token_no}}</b></span></td>
        <td rowspan="4" class="text-center">
            @if (isset($application->profile_image_url))
            {{-- <img src="{{asset('storage/app/public/'.$application->profile_image_url)}}" alt="NO IMAGE FOUND"
                style="width: 200px;height: 200px;"> --}}
            <img src="#"
                alt="NO IMAGE FOUND" style="width: 200px;height: 200px;">
            @else
            <img src="{{asset('public/admin/images/dummy.png')}}" alt="" style="width: 200px;height: 200px;">
            @endif
        </td>
    </tr>
    <tr>
        <th>Application Type:</th>
        <td>
            @if ($application->application_type == 1) Lower Court
            @elseif($application->application_type == 2) High Court
            @elseif($application->application_type == 3) Renewal High Court
            @elseif($application->application_type == 4) Renewal Lower Court
            @endif
        </td>
    </tr>
    <tr>
        <th>Application Status:</th>
        <td>
            @if ($application->application_status == 1) Active
            @elseif($application->application_status == 2) Suspended
            @elseif($application->application_status == 3) Died
            @elseif($application->application_status == 4) Removed
            @elseif($application->application_status == 5) Transfer in
            @elseif($application->application_status == 6) Transfer out
            @endif
        </td>
    </tr>
    <tr>
        <th>Card Status:</th>
        <td>
            @if ($application->card_status == 1) <span class="badge badge-warning">Pending</span>
            @elseif($application->card_status == 2) <span class="badge badge-primary">Printing</span>
            @elseif($application->card_status == 3) <span class="badge badge-success">Dispatched</span>
            @elseif($application->card_status == 4) <span class="badge badge-success">By Hand</span>
            @elseif($application->card_status == 5) <span class="badge badge-success">Done</span>
            @endif
        </td>
    </tr>
</table>

<table class="table table-sm table-striped table-bordered">
    <tr>
        <th>Advocate’s Name:</th>
        <td>{{$application->advocates_name}}</td>
    </tr>
    <tr>
        <th>Father Name:</th>
        <td>{{$application->so_of}}</td>
    </tr>

    @if ($application->application_type == 1 || $application->application_type == 4)
    <tr>
        <th>Ledger No :</th>
        <td>{{$application->reg_no_lc}}</td>
    </tr>
    @endif

    @if ($application->application_type == 1 || $application->application_type == 4)
    <tr>
        <th>B.F No (L.C.):</th>
        <td>{{$application->bf_no_lc ?? 'N/a'}}</td>
    </tr>
    @endif

    @if ($application->application_type == 2 || $application->application_type == 3)
    <tr>
        <th>B.F No (H.C.):</th>
        <td>{{$application->bf_no_hc ?? 'N/a'}}</td>
    </tr>
    @endif

    @if ($application->application_type == 1 || $application->application_type == 4)
    <tr>
        <th>License No (L.C.):</th>
        <td>{{$application->license_no_lc}}</td>
    </tr>
    @endif

    @if ($application->application_type == 2 || $application->application_type == 3)
    <tr>
        <th>License No (H.C.):</th>
        <td>{{$application->license_no_hc}}</td>
    </tr>
    @endif

    @if ($application->application_type == 3)
    <tr>
        <th>HCR No:</th>
        <td>{{$application->hcr_no}}</td>
    </tr>
    @endif

    @if ($application->application_type == 2)
    <tr>
        <th>High Court Roll No :</th>
        <td>{{$application->high_court_roll_no ?? '-'}}</td>
    </tr>
    @endif

    <tr>
        <th>Date of Birth:</th>
        <td>{{$application->date_of_birth}}</td>
    </tr>
    <tr>
        <th>Date of Enrollment (L.C.):</th>
        <td>{{$application->date_of_enrollment_lc}}</td>
    </tr>

    @if ($application->application_type == 2 || $application->application_type == 3)
    <tr>
        <th>Date of Enrollment (H.C.):</th>
        <td>{{$application->date_of_enrollment_hc}}</td>
    </tr>
    @endif

    <tr>
        <th>C.N.I.C. No:</th>
        <td>
            <div class="mb-2">{{$application->cnic_no}}</div>
            <div>
                @if (isset($application->cnic_no))
                {!! QrCode::size(100)->generate($application->cnic_no); !!}
                @endif
            </div>
        </td>
    </tr>
    <tr>
        <th>Address:</th>
        <td>{{$application->postal_address}} {{$application->address_2}}</td>
    </tr>
    <tr>
        <th>Email:</th>
        <td>{{$application->email ?? '-'}}</td>
    </tr>
    <tr>
        <th>Phone No:</th>
        <td>+92{{$application->active_mobile_no}}</td>
    </tr>
    <tr>
        <th>Voter Member (L.C.):</th>
        <td>
            Bar Association : {{isset($application->voterMemberLc->name) ? $application->voterMemberLc->name : '--'}}
            <br>
            District : {{isset($application->voterMemberLc->district->name) ?
            $application->voterMemberLc->district->name : ''}}
            <br>
            Division : {{isset($application->voterMemberLc->district->division->name) ?
            $application->voterMemberLc->district->division->name : ''}}
        </td>
    </tr>

    @if ($application->application_type == 2 || $application->application_type == 3)
    <tr>
        <th>Voter Member (H.C.):</th>
        <td>{{isset($application->voterMemberHc->name) ? $application->voterMemberHc->name : ''}}</td>
    </tr>
    @endif

    @if ($application->application_type == 1)
    <tr>
        <th>R.F ID:</th>
        <td>{{$application->rf_id}}</td>
    </tr>
    @endif

    @if (isset($payment))
    <tr>
        <th>Challan Form No.</th>
        <td>{{$payment->voucher_no}}</td>
    </tr>
    <tr>
        <th>Challan Form Paid Date</th>
        <td>{{$payment->paid_date}}</td>
    </tr>
    <tr>
        <th>Challan Form Image</th>
        <td>
            @if (isset($payment->voucher_file))
            <a href="{{asset('storage/app/public/'.$payment->voucher_file)}}" class="badge badge-success"
                download="{{$payment->voucher_file}}">Download</a> @else N/A @endif
        </td>
    </tr>
    <tr>
        <th>Challan Form Type</th>
        <td>{{getApplicationType($application->id)}}</td>
    </tr>
    @endif

</table>

<table class="table table-sm table-bordered table-striped">

    <tr>
        <th>Upload CNIC (Front):</th>
        <td>
            @if (isset($application->uploads->cnic_front))
            <img src="{{asset('storage/app/public/'.$application->uploads->cnic_front)}}" alt=""
                class="custom-image-preview">
            @endif
        </td>

        <th>Upload CNIC (Back):</th>
        <td>
            @if (isset($application->uploads->cnic_back))
            <img src="{{asset('storage/app/public/'.$application->uploads->cnic_back)}}" alt=""
                class="custom-image-preview">
            @endif
        </td>
    </tr>

    <tr>
        <th>Punjab Bar Existing License (Front):</th>
        <td>
            @if (isset($application->uploads->card_front))
            <img src="{{asset('storage/app/public/'.$application->uploads->card_front)}}" alt=""
                class="custom-image-preview">
            @endif
        </td>

        <th>Punjab Bar Existing License (Back):</th>
        <td>
            @if (isset($application->uploads->card_back))
            <img src="{{asset('storage/app/public/'.$application->uploads->card_back)}}" alt=""
                class="custom-image-preview">
            @endif
        </td>
    </tr>

    <tr>
        <th>SUBMITTED BY:</th>
        <td>{{isset($application->submitted_by) ? getAdminName($application->submitted_by) : 'Online User'}}</td>

        <th>SUBMITTED AT:</th>
        <td>{{$application->created_at}}</td>
    </tr>
    @if (isset($application->updated_by))
    <tr>
        <th>UPDATED BY:</th>
        <td>{{getAdminName($application->updated_by)}}</td>

        <th>UPDATED AT:</th>
        <td>{{$application->updated_at}}</td>
    </tr>
    @endif
</table>
