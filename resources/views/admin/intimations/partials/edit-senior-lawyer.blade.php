<div class="row">
    <div class="col-md-12 mb-2">
        <button type="button" class="btn btn-primary btn-xs float-right" data-toggle="modal"
            data-target="#editSeniorLawyerModal">
            <i class="fas fa-edit mr-1" aria-hidden="true"></i> Edit
        </button>
    </div>
</div>

<div class="modal fade" id="editSeniorLawyerModal" tabindex="-1" aria-labelledby="editSrLawyerLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="#" method="POST" id="update_senior_lawyer_form"> @csrf
            <input type="hidden" name="application_id" id="application_id" value="{{$application->id}}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSrLawyerLabel">
                        Edit - Senior Lawyer Information
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Sr. Lawyer Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="srl_name"
                                value="{{isset($application->srl_name) ? $application->srl_name : ''}}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Advocate High Court / Subordinate Courts<span class="text-danger">*</span></label>
                            <select name="srl_bar_name" id="srl_bar_name" class="form-control custom-select" required>
                                <option value="">--Select Bar--</option>
                                @foreach ($bars as $bar)
                                <option @if (isset($application->srlBar->name))
                                    {{$application->srlBar->id == $bar->id ? 'selected' : ''}}
                                    @endif
                                    value="{{$bar->id}}">{{$bar->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Sr. Lawyer Office Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="srl_office_address"
                                value="{{isset($application->srl_office_address) ? $application->srl_office_address : ''}}"
                                required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Sr. Lawyer Enrollment Date <span class="text-danger">*</span></label>
                            <div class="input-group date" id="srl_enr_date" data-target-input="nearest">
                                <input type="text"
                                    value="{{isset($application->srl_enr_date) ? \Carbon\Carbon::parse($application->srl_enr_date)->format('Y-m-d') : ''}}"
                                    class="form-control datetimepicker-input" data-target="#srl_enr_date"
                                    name="srl_enr_date" required autocomplete="off" data-toggle="datetimepicker" />
                                <div class="input-group-append" data-target="#srl_enr_date"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Sr. Lawyer Joining Date <span class="text-danger">*</span></label>
                            <div class="input-group date" id="srl_joining_date" data-target-input="nearest">
                                <input type="text"
                                    value="{{isset($application->srl_joining_date) ? \Carbon\Carbon::parse($application->srl_joining_date)->format('d-m-Y') : ''}}"
                                    class="form-control datetimepicker-input" data-target="#srl_joining_date"
                                    name="srl_joining_date" autocomplete="off" data-toggle="datetimepicker" />
                                <div class="input-group-append" data-target="#srl_joining_date"
                                    data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Sr. Lawyer Mobile Number <span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><img
                                            src="{{asset('public/admin/images/pakistan.png')}}" alt=""></span>
                                    <span class="input-group-text">+92</span>
                                </div>
                                <input type="tel" class="form-control" name="srl_mobile_no"
                                    value="{{isset($application->srl_mobile_no) ? $application->srl_mobile_no : ''}}"
                                    required>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Sr. Lawyer CNIC Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="srl_cnic_no"
                                value="{{isset($application->srl_cnic_no) ? $application->srl_cnic_no : ''}}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Sr.Lawyer CNIC (Front):</label>
                            @if (isset($application->uploads->srl_cnic_front) &&
                            $application->uploads->srl_cnic_front != NULL)
                            <img src="{{asset('storage/app/public/'.$application->uploads->srl_cnic_front)}}" alt=""
                                class="col-md-12">
                            <a href="javascript:void(0)" data-action="{{route('intimations.destroy.srl-cnic-front')}}"
                                class="btn btn-danger btn-sm float-right col-md-12 mt-2"
                                onclick="removeImage(this)">Remove</a>
                            @else
                            <input type="file" name="srl_cnic_front" id="srl_cnic_front"
                                accept="image/jpg,image/jpeg,image/png">
                            @endif
                            <div class="errors" data-id="srl_cnic_front"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Sr.Lawyer CNIC (Back):</label>
                            @if (isset($application->uploads->srl_cnic_back) &&
                            $application->uploads->srl_cnic_back != NULL)
                            <img src="{{asset('storage/app/public/'.$application->uploads->srl_cnic_back)}}" alt=""
                                class="col-md-12">
                            <a href="javascript:void(0)" data-action="{{route('intimations.destroy.srl-cnic-back')}}"
                                class="btn btn-danger btn-sm float-right col-md-12 mt-2"
                                onclick="removeImage(this)">Remove</a>
                            @else
                            <input type="file" id="srl_cnic_back" name="srl_cnic_back"
                                accept="image/jpg,image/jpeg,image/png">
                            @endif
                            <div class="errors" data-id="srl_cnic_back"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Sr.Lawyer Punjab Bar License (Front):</label>
                            @if (isset($application->uploads->srl_license_front) &&
                            $application->uploads->srl_license_front != NULL)
                            <img src="{{asset('storage/app/public/'.$application->uploads->srl_license_front)}}" alt=""
                                class="col-md-12">
                            <a href="javascript:void(0)"
                                data-action="{{route('intimations.destroy.srl-license-front')}}"
                                class="btn btn-danger btn-sm float-right col-md-12 mt-2"
                                onclick="removeImage(this)">Remove</a>
                            @else
                            <input type="file" name="srl_license_front" id="srl_license_front"
                                accept="image/jpg,image/jpeg,image/png">
                            @endif
                            <div class="errors" data-id="srl_license_front"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Sr.Lawyer Punjab Bar Council (Back):</label>
                            @if (isset($application->uploads->srl_license_back) &&
                            $application->uploads->srl_license_back != NULL)
                            <img src="{{asset('storage/app/public/'.$application->uploads->srl_license_back)}}" alt=""
                                class="col-md-12">
                            <a href="javascript:void(0)" data-action="{{route('intimations.destroy.srl-license-back')}}"
                                class="btn btn-danger btn-sm float-right col-md-12 mt-2"
                                onclick="removeImage(this)">Remove</a>
                            @else
                            <input type="file" id="srl_license_back" name="srl_license_back"
                                accept="image/jpg,image/jpeg,image/png">
                            @endif
                            <div class="errors" data-id="srl_license_back"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-primary">Save & Update</button>
                </div>
        </form>
    </div>
</div>
</div>
