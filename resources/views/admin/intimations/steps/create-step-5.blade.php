<form class="steps-form" data-action="{{ route('intimations.create-step-5',$application->id) }}" action="#"
    method="POST" id="create_step_5_form" enctype="multipart/form-data"> @csrf
    <div class="card-body">
        <fieldset class="border p-4 mb-4">
            <legend class="w-auto">Senior Lawyer Information</legend>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Sr. Lawyer Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="srl_name"
                        value="{{isset($application->srl_name) ? $application->srl_name : ''}}" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Advocate High Court / Subordinate Courts (Bar Name) <span
                            class="text-danger">*</span></label>
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
                            value="{{isset($application->srl_enr_date) ? \Carbon\Carbon::parse($application->srl_enr_date)->format('d-m-Y') : ''}}"
                            class="form-control datetimepicker-input" data-target="#srl_enr_date" name="srl_enr_date"
                            required autocomplete="off" data-toggle="datetimepicker" />
                        <div class="input-group-append" data-target="#srl_enr_date" data-toggle="datetimepicker">
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
                            name="srl_joining_date"
                            {{Auth::guard('admin')->user()->hasPermission('intimation-srl-joining-date') ? '' :
                        'disabled'}}
                        autocomplete="off"
                        data-toggle="datetimepicker" />
                        <div class="input-group-append" data-target="#srl_joining_date" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label>Sr. Lawyer Mobile Number <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><img src="{{asset('public/admin/images/pakistan.png')}}"
                                    alt=""></span>
                            <span class="input-group-text">+92</span>
                        </div>
                        <input type="tel" class="form-control" name="srl_mobile_no"
                            value="{{isset($application->srl_mobile_no) ? $application->srl_mobile_no : ''}}" required>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label>Sr. Lawyer CNIC Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="srl_cnic_no"
                        value="{{isset($application->srl_cnic_no) ? $application->srl_cnic_no : ''}}" required>
                </div>
            </div>
        </fieldset>

    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-success float-right" value="save">Save & Next</button>
        <a href="javascript:void(0)" onclick="goToStep('{{route('intimations.create-step-4', $application->id)}}',4)"
            class="btn btn-secondary float-right mr-1">Back</a>
    </div>
</form>
