<div class="row">
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <td class="bg-success text-bold" colspan="6">High Court Information</td>
            </tr>
            <tr>
                <th>User ID:</th>
                <td>
                    {{$application->user_id ?? '-'}}
                </td>
                <th>GC Verification</th>
                <td>
                    @if (isset($user->gc_status) && in_array($user->gc_status,['pending','approved','disapproved']))
                    <span class="badge badge-light text-capitalize">{{$user->gc_status}}</span>
                    @else
                    <span class="badge badge-danger">The data verification request is not send by the Lawyer.</span>
                    @endif
                </td>

                <td rowspan="4" colspan="2" class="text-center">
                    <label for="">Old Profile Image</label><br>
                    {{-- <img src="https://portal.pbbarcouncil.com/storage/app/public/applications/profile-images/HC/{{$application->image}}"
                        class="custom-image-preview" alt="no-image-found"> --}}

                        <br>
                        @if ($user != null)
                            @component('components.image-upload-gc')
                            @slot('is_detail_profile') true @endslot
                            @slot('user_id',$user->id)
                            @slot('label') Profile Image @endslot
                            @slot('name') profile_image @endslot
                            @slot('type','hc')
                            @if(isset($user->getFirstMedia('gc_profile_image')->id))
                                @slot('condition') {{$user->getFirstMedia('gc_profile_image')->id . '/' . $user->getFirstMedia('gc_profile_image')->file_name ?? null}} @endslot
                            @else
                                @slot('condition') {{null}} @endslot
                            @endif
                            @endcomponent
                        @endif

                </td>
            </tr>

            <tr>
                <th>HC Serial No:</th>
                <td>
                    {{$application->sr_no_hc ?? '-'}}
                </td>

                <th>HC License No:</th>
                <td>
                    <input type="text" name="license_no_hc" id="license_no_hc" class="form-control"
                        value="{{$application->license_no_hc}}">
                </td>
            </tr>

            <tr>
                <th>HCR No:</th>
                <td>
                    <input type="text" name="hcr_no_hc" id="hcr_no_hc" class="form-control"
                        value="{{$application->hcr_no_hc}}">
                </td>

                <th>BF No:</th>
                <td>
                    <input type="text" name="bf_no_hc" id="bf_no_hc" class="form-control"
                        value="{{$application->bf_no_hc}}">
                </td>
            </tr>

            <tr>
                <th>HC Enr Date:</th>
                <td>
                    <div class="input-group date" id="enr_date_hc" data-target-input="nearest">
                        <input type="text" value="{{getDateFormat($application->enr_date_hc)}}"
                            class="form-control datetimepicker-input" data-target="#enr_date_hc" name="enr_date_hc"
                            required autocomplete="off" data-toggle="datetimepicker" />
                        <div class="input-group-append" data-target="#enr_date_hc" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                </td>

                <th>Voter Member:</th>
                <td>
                    <select name="voter_member_hc" id="voter_member_hc" class="form-control custom-select">
                        @foreach ($bars as $bar)
                        <option value="{{$bar->id}}" {{$bar->id == $application->voter_member_hc ? 'selected': ''}}>
                            {{$bar->name}}
                        </option>
                        @endforeach
                    </select>
                </td>
            </tr>

            <tr>
                <th>Application Status:</th>
                <td>
                    <select class="form-control custom-select" name="app_status" id="app_status">
                        @foreach ($app_status as $status)
                        <option value="{{$status->key}}" {{$application->app_status == $status->key ? 'selected' :''}}>
                            {{$status->value}}
                        </option>
                        @endforeach
                    </select>
                </td>
                <th>Application Type:</th>
                <td>
                    <select class="form-control custom-select" name="app_type" id="app_type">
                        <option value="{{$application->app_type}}">Same as Current</option>
                        @foreach ($app_types as $type)
                        <option value="{{$type->key}}" {{$application->app_type == $type->key ? 'selected' :''}}>
                            {{$type->value}}
                        </option>
                        @endforeach
                    </select>
                </td>
            </tr>

            <tr>
                <td class="bg-success text-bold" colspan="5">Lawyer Information</td>
            </tr>
            <tr>
                <th>Name:</th>
                <td>
                    <input type="text" name="lawyer_name" id="lawyer_name" class="form-control"
                        value="{{$application->lawyer_name}}">
                </td>

                <th>
                    @if ($application->lc_sdw == 1) Son of
                    @elseif($application->lc_sdw == 2) Daughter of
                    @elseif($application->lc_sdw == 3) Wife of
                    @endif
                </th>
                <td>
                    <input type="text" name="father_name" id="father_name" class="form-control"
                        value="{{$application->father_name}}">
                </td>
            </tr>
            <tr>
                <th>CNIC No:</th>
                <td>
                    <input type="text" name="cnic_no" id="cnic_no" class="form-control"
                        value="{{$application->cnic_no}}">
                </td>

                <th>Date of Birth:</th>
                <td>
                    <div class="input-group date" id="date_of_birth" data-target-input="nearest">
                        <input type="text" value="{{getDateFormat($application->date_of_birth)}}"
                            class="form-control datetimepicker-input" data-target="#date_of_birth" name="date_of_birth"
                            required autocomplete="off" data-toggle="datetimepicker" />
                        <div class="input-group-append" data-target="#date_of_birth" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>Contact No:</th>
                <td>
                    <input type="text" name="contact_no" id="contact_no" class="form-control"
                        value="{{$application->contact_no}}">
                </td>

                <th>Email:</th>
                <td>
                    <input type="text" name="email" id="email" class="form-control" value="{{$application->email}}">
                </td>
            </tr>
            <tr>
                <th>Gender: <br> S/D/W:</th>
                <td>
                    <select name="gender" id="gender" class="form-control custom-select">
                        <option value="" selected>--Select Gender--</option>
                        <option value="M" {{$application->gender == 'M' ? 'selected': ''}}>Male</option>
                        <option value="F" {{$application->gender == 'F' ? 'selected': ''}}>Female</option>
                    </select>

                    <select name="lc_sdw" id="lc_sdw" class="form-control custom-select mt-2">
                        <option value="" selected>--Select--</option>
                        <option value="1" {{$application->lc_sdw == 1 ? 'selected': ''}}>Son of</option>
                        <option value="2" {{$application->lc_sdw == 2 ? 'selected': ''}}>Daughter of</option>
                        <option value="3" {{$application->lc_sdw == 3 ? 'selected': ''}}>Wife of</option>
                    </select>
                </td>
                <th>Religion</th>
                <td>
                    <select name="religion" id="religion" class="form-control custom-select">
                        <option value="" selected>--Select Religion--</option>
                        <option value="1" {{$application->religion == 1 ? 'selected': ''}}>Muslim</option>
                        <option value="2" {{$application->religion == 2 ? 'selected': ''}}>Non-Muslim</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Address:</th>
                <td colspan="3">
                    <input type="text" name="address_1" id="address_1" class="form-control"
                        value="{{$application->address_1}}">
                    <input type="text" name="address_2" id="address_2" class="form-control mt-1"
                        value="{{$application->address_2}}">
                </td>
            </tr>

            <tr>
                <td class="bg-success text-bold" colspan="6">Lower court Information</td>
            </tr>
            <tr>
                <th>LC Ledger No:</th>
                <td>
                    <input type="text" name="lc_ledger" id="lc_ledger" class="form-control mt-1"
                        value="{{$application->lc_ledger}}">
                </td>

                <th>Enr Date LC:</th>
                <td>
                    <div class="input-group date" id="enr_date_lc" data-target-input="nearest">
                        <input type="text" value="{{getDateFormat($application->enr_date_lc) ?? ''}}"
                            class="form-control datetimepicker-input" data-target="#enr_date_lc" name="enr_date_lc"
                            required autocomplete="off" data-toggle="datetimepicker" />
                        <div class="input-group-append" data-target="#enr_date_lc" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>LC License No:</th>
                <td>
                    <input type="text" name="lc_lic" id="lc_lic" class="form-control mt-1"
                        value="{{$application->lc_lic}}">
                </td>
            </tr>

            <tr>
                <td class="bg-success text-bold" colspan="6">Additional Information</td>
            </tr>


            <tr>
                <th>Enr Status Reason:</th>
                <td colspan="3">
                    <textarea name="enr_status_reason" id="enr_status_reason" cols="30" rows="5"
                        class="form-control">{{$application->enr_status_reason}}</textarea>
                </td>
            </tr>

            <tr>
                <th>GC Created By:</th>
                <td>
                    {{$application->gc_created_by}}
                </td>

                <th>GC Updated By:</th>
                <td>
                    {{$application->gc_updated_by}}
                </td>
            </tr>
        </table>
    </div>
</div>
