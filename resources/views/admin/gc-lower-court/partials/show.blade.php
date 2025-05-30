<div class="row">
    <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th class="bg-success" colspan="6">Lower Court Information</th>
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

                <td rowspan="4" class="text-center">
                    <label for="">Old Profile Image</label><br>
                    {{-- <img
                        src="https://portal.pbbarcouncil.com/storage/app/public/applications/profile-images/LC/{{$application->image}}"
                        class="custom-image-preview" alt="no-image-found"> --}}
                    <br>
                    @if ($user != null)
                        @component('components.image-upload-gc')
                            @slot('is_detail_profile') true @endslot
                            @slot('user_id',$user->id)
                            @slot('label') Profile Image @endslot
                            @slot('name') profile_image @endslot
                            @slot('type','gc_profile_image')
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
                <th>SR No:</th>
                <td>{{$application->sr_no_lc ?? '-'}}</td>

                <th>License No:</th>
                <td>
                    <input type="text" class="form-control" name="license_no_lc" id="license_no_lc"
                        value="{{$application->license_no_lc}}">
                </td>

            </tr>
            <tr>
                <th>Ledger No:</th>
                <td>
                    <input type="text" class="form-control" name="reg_no_lc" id="reg_no_lc"
                        value="{{$application->reg_no_lc}}">
                </td>
                <th>BF No:</th>
                <td>
                    <input type="text" class="form-control" name="bf_no_lc" id="bf_no_lc"
                        value="{{$application->bf_no_lc}}">
                </td>
            </tr>

            <tr>
                <th>Enr Date:</th>
                <td>
                    <div class="input-group date" id="date_of_enrollment_lc" data-target-input="nearest">
                        <input type="text" value="{{getDateFormat($application->date_of_enrollment_lc)}}"
                            class="form-control datetimepicker-input" data-target="#date_of_enrollment_lc"
                            name="date_of_enrollment_lc" required autocomplete="off" data-toggle="datetimepicker" />
                        <div class="input-group-append" data-target="#date_of_enrollment_lc"
                            data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i>
                            </div>
                        </div>
                    </div>
                </td>

                <th>Voter Member:</th>
                <td>
                    <select name="voter_member_lc" id="voter_member_lc" class="form-control custom-select">
                        @foreach ($bars as $bar)
                        <option value="{{$bar->id}}" {{$bar->id == $application->voter_member_lc ? 'selected': ''}}>
                            {{$bar->name}}
                        </option>
                        @endforeach
                    </select>
                </td>
            </tr>

            <tr>
                <th>Status:</th>
                <td>
                    <select class="form-control custom-select" name="app_status" id="app_status">
                        @foreach ($app_status as $status)
                        <option value="{{$status->key}}" {{$application->app_status == $status->key ? 'selected' :''}}>
                            {{$status->value}}
                        </option>
                        @endforeach
                    </select>
                </td>
            </tr>

            <tr>
                <th class="bg-success" colspan="5">Lawyer Information</th>
            </tr>

            <tr>
                <th>Name:</th>
                <td>
                    <input type="text" class="form-control" name="lawyer_name" id="lawyer_name"
                        value="{{$application->lawyer_name}}">
                </td>
                <th>
                    @if ($application->enr_app_sdw == 1) Son of
                    @elseif($application->enr_app_sdw == 2) Daughter of
                    @elseif($application->enr_app_sdw == 3) Wife of
                    @endif
                </th>
                <td>
                    <input type="text" class="form-control" name="father_name" id="father_name"
                        value="{{$application->father_name}}">
                </td>
            </tr>
            <tr>
                <th>CNIC No:</th>
                <td>
                    <input type="text" class="form-control" name="cnic_no" id="cnic_no"
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
                    <input type="text" class="form-control" name="contact_no" id="contact_no"
                        value="{{$application->contact_no}}">
                </td>
                <th>Email:</th>
                <td>
                    <input type="text" class="form-control" name="email" id="email" value="{{$application->email}}">
                </td>
            </tr>
            <tr>
                <th>Gender:</th>
                <td>
                    <select name="gender" id="gender" class="form-control custom-select">
                        <option value="" selected>--Select Gender--</option>
                        <option value="M" {{$application->gender == 'M' ? 'selected': ''}}>Male</option>
                        <option value="F" {{$application->gender == 'F' ? 'selected': ''}}>Female</option>
                    </select>

                    <select name="enr_app_sdw" id="enr_app_sdw" class="form-control custom-select mt-2">
                        <option value="" selected>--Select--</option>
                        <option value="1" {{$application->enr_app_sdw == 1 ? 'selected': ''}}>Son of</option>
                        <option value="2" {{$application->enr_app_sdw == 2 ? 'selected': ''}}>Daughter of</option>
                        <option value="3" {{$application->enr_app_sdw == 3 ? 'selected': ''}}>Wife of</option>
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
                    <input type="text" class="form-control" name="address_1" id="address_1"
                        value="{{$application->address_1}}">

                    <input type="text" class="form-control mt-1" name="address_2" id="address_2"
                        value="{{$application->address_2}}">
                </td>
            </tr>

            <tr>
                <th class="bg-success" colspan="6">Additional Information</th>
            </tr>

            <tr>
                <th>Enr Status Reason:</th>
                <td colspan="3">
                    <textarea name="enr_status_reason" id="enr_status_reason" cols="30" rows="5"
                        class="form-control">{{$application->enr_status_reason}}</textarea>
                </td>
            </tr>
            <tr>
                <th>Enr PLJ Check:</th>
                <td>
                    <input type="checkbox" class="form-control form-control-sm"
                        {{$application->enr_plj_check == 'Y' ? 'checked' : ''}} disabled>
                </td>
                <th>Enr GI Check:</th>
                <td>
                    <input type="checkbox" class="form-control form-control-sm"
                        {{$application->enr_gi_check == 'Y' ? 'checked' : ''}} disabled>
                </td>
            </tr>
            <tr>
                <th>GC Created By:</th>
                <td>{{$application->gc_created_by ?? '-'}}</td>

                <th>GC Updated By:</th>
                <td>{{$application->gc_updated_by ?? '-'}}</td>
            </tr>
        </table>
    </div>
</div>
