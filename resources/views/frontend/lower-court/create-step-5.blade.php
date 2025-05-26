<form class="steps-form" data-action="{{ route('frontend.lower-court.create-step-5',$application->id) }}" action="#"
    method="POST" id="create_step_5_form"> @csrf
    <div class="card-body">
        @if($application->is_exemption == 0)
        <fieldset class="border p-4 mb-4">
            <legend class="w-auto">Senior Lawyer Information</legend>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Senior Lawyer Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="srl_name"
                        value="{{isset($application->srl_name) ? $application->srl_name : ''}}"
                        {{$application->is_moved_from_intimation ? 'disabled' : ''}}>
                </div>
                <div class="form-group col-md-6">
                    <label>Advocate High Court / Subordinate Courts (Bar Name) <span
                            class="text-danger">*</span></label>
                    <select name="srl_bar_id" id="srl_bar_id" class="form-control custom-select"
                        {{$application->is_moved_from_intimation ? 'disabled' : ''}}>
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
                    <label>Senior Lawyer Office Address <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="srl_office_address"
                        value="{{isset($application->srl_office_address) ? $application->srl_office_address : ''}}"
                        {{$application->is_moved_from_intimation ? 'disabled' : ''}}>
                </div>
                <div class="form-group col-md-6">
                    <label>Senior Lawyer Enrollment Date <span class="text-danger">*</span></label>
                    <div class="input-group date" id="srl_enr_date" data-target-input="nearest">
                        <input type="text" {{$application->is_moved_from_intimation ? 'disabled' : ''}}
                        value="{{isset($application->srl_enr_date) ?
                        \Carbon\Carbon::parse($application->srl_enr_date)->format('d-m-Y') : ''}}"
                        class="form-control datetimepicker-input" data-target="#srl_enr_date" name="srl_enr_date"
                        autocomplete="off" data-toggle="datetimepicker" />
                        <div class="input-group-append" data-target="#srl_enr_date" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label>Senior Lawyer Mobile <span class="text-danger">*</span></label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><img src="{{asset('public/admin/images/pakistan.png')}}"
                                    alt=""></span>
                            <span class="input-group-text">+92</span>
                        </div>
                        <input type="tel" class="form-control" name="srl_mobile_no"
                            value="{{isset($application->srl_mobile_no) ? $application->srl_mobile_no : ''}}"
                            {{$application->is_moved_from_intimation ? 'disabled' : ''}}>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label>Senior Lawyer CNIC <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="srl_cnic_no"
                        value="{{isset($application->srl_cnic_no) ? $application->srl_cnic_no : ''}}"
                        {{$application->is_moved_from_intimation ? 'disabled' : ''}}>
                </div>
                <div class="form-group col-md-6">
                    <label>Senior Lawyer Joining Date <span class="text-danger">*</span></label>
                    <div class="input-group date" id="srl_joining_date" data-target-input="nearest">
                        <input type="text" value="{{getDateFormat($application->srl_joining_date)}}"
                            {{$application->is_moved_from_intimation ? 'disabled' : ''}}
                        class="form-control datetimepicker-input" data-target="#srl_joining_date"
                        name="srl_joining_date" autocomplete="off" data-toggle="datetimepicker" />
                        <div class="input-group-append" data-target="#srl_joining_date" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        @endif
        <fieldset class="border p-4 mb-4">
            <legend class="w-auto">Questions</legend>
            <div class="row">
                <div class="col-sm-12 form-group">
                    <label for="">Whether the applicant is/was engaged in any business, service, profession or vocation
                        in Pakistan? If so. the nature there of and the place at
                        which it is carried out?</label>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_engaged_in_business1" value="Yes"
                            name="is_engaged_in_business" {{ $application->is_engaged_in_business == "Yes" ? "checked" :
                        "" }}>
                        <label class="form-check-label" for="is_engaged_in_business1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_engaged_in_business2" value="No"
                            name="is_engaged_in_business" {{ $application->is_engaged_in_business == "No" ? "checked" :
                        "" }}>
                        <label class="form-check-label" for="is_engaged_in_business2">No</label>
                    </div>
                </div>
                <div class="col-sm-12 form-group">
                    <label for="">Whether the applicant proposes to practice generally within the jurisdiction ofthe
                        Punjab Bar Council? State place of practice?</label>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_practice_in_pbc1" value="Yes"
                            name="is_practice_in_pbc" {{ $application->is_practice_in_pbc == "Yes" ? "checked" : "" }}>
                        <label class="form-check-label" for="is_practice_in_pbc1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_practice_in_pbc2" value="No"
                            name="is_practice_in_pbc" {{ $application->is_practice_in_pbc == "No" ? "checked" : "" }}>
                        <label class="form-check-label" for="is_practice_in_pbc2">No</label>
                    </div>
                    <br>
                    <input type="text" name="practice_place" value="{{ $application->practice_place }}"
                        class="form-control w-50" placeholder="Enter Place ..."
                        style="display: {{ $application->is_practice_in_pbc == " Yes" ? "block" :"none" }}">
                </div>
                <div class="col-sm-12 form-group">
                    <label for="">Whether the applicant has been declared insolvent?</label>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_declared_insolvent1" value="Yes"
                            name="is_declared_insolvent" {{ $application->is_declared_insolvent == "Yes" ? "checked" :
                        "" }}>
                        <label class="form-check-label" for="is_declared_insolvent1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_declared_insolvent2" value="No"
                            name="is_declared_insolvent" {{ $application->is_declared_insolvent == "No" ? "checked" : ""
                        }}>
                        <label class="form-check-label" for="is_declared_insolvent2">No</label>
                    </div>
                </div>
                <div class="col-sm-12 form-group">
                    <label for="">Whether the applicant has been dismissed/removed from service of Government or of a
                        Public Statutory Corporation, if so the reasons
                        thereof?</label>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_dismissed_from_gov1" value="Yes"
                            name="is_dismissed_from_gov" {{ $application->is_dismissed_from_gov == "Yes" ? "checked" :
                        "" }}>
                        <label class="form-check-label" for="is_dismissed_from_gov1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_dismissed_from_gov2" value="No"
                            name="is_dismissed_from_gov" {{ $application->is_dismissed_from_gov == "No" ? "checked" : ""
                        }}>
                        <label class="form-check-label" for="is_dismissed_from_gov2">No</label>
                    </div>
                    <br>
                    <input type="text" name="dismissed_reason" value="{{ $application->dismissed_reason }}"
                        class="form-control w-50" placeholder="Enter Dissmissed Reason ..."
                        style="display: {{ $application->is_dismissed_from_gov == " Yes" ? "block" :"none" }}">
                </div>
                <div class="col-sm-12 form-group">
                    <label for="">Whether the applicant is enrolled as an advocate on the Roll of any other Bar
                        Council?</label>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_enrolled_as_adv1" value="Yes"
                            name="is_enrolled_as_adv" {{ $application->is_enrolled_as_adv == "Yes" ? "checked" : "" }}>
                        <label class="form-check-label" for="is_enrolled_as_adv1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_enrolled_as_adv2" value="No"
                            name="is_enrolled_as_adv" {{ $application->is_enrolled_as_adv == "No" ? "checked" : "" }}>
                        <label class="form-check-label" for="is_enrolled_as_adv2">No</label>
                    </div>
                </div>
                <div class="col-sm-12 form-group">
                    <label for="">Whether the applicant has been convicted ofany offence? Ifso, date and particulars
                        thereof?</label>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_offensed1" value="Yes" name="is_offensed" {{
                            $application->is_offensed == "Yes" ? "checked" : "" }}>
                        <label class="form-check-label" for="is_offensed1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_offensed2" value="No" name="is_offensed" {{
                            $application->is_offensed == "No" ? "checked" : "" }}>
                        <label class="form-check-label" for="is_offensed2">No</label>
                    </div>
                    <br>
                    <input type="text" name="offensed_date" value="{{ $application->offensed_date }}"
                        class="form-control w-50" placeholder="Enter Offense Date ..."
                        style="display:{{ $application->is_offensed == " Yes" ? "block" :"none" }}">
                    <br>
                    <input type="text" name="offensed_reason" value="{{ $application->offensed_reason }}"
                        class="form-control w-50" placeholder="Enter Offense Reason ..."
                        style="display:{{ $application->is_offensed == " Yes" ? "block" :"none" }}">
                </div>
                <div class="col-sm-12 form-group">
                    <label for="">Whether the application ofthe applicant of enrolment has previously been rejected?
                    </label>
                    <br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_prev_rejected1" value="Yes"
                            name="is_prev_rejected" {{$application->is_prev_rejected == 'Yes' ? 'checked' : ''}}>
                        <label class="form-check-label" for="is_prev_rejected1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="is_prev_rejected2" value="No"
                            name="is_prev_rejected" {{$application->is_prev_rejected == 'No' ? 'checked' : ''}}>
                        <label class="form-check-label" for="is_prev_rejected2">No</label>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-success float-right" value="save">Save & Next</button>
        <a href="javascript:void(0)"
            onclick="goToStep('{{route('frontend.lower-court.create-step-4', $application->id)}}',4)"
            class="btn btn-secondary float-right mr-1">Back</a>
    </div>
</form>
