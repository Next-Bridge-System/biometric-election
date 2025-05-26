<form class="steps-form" action="#" method="POST" data-action="{{route('lower-court.create-step-2',$application->id)}}"
    id="create_step_2_form" enctype="multipart/form-data"> @csrf
    <div class="card-body">
        <fieldset class="border p-4 mb-4">
            <legend class="w-auto">Legal Identification</legend>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>C.N.I.C. No <span class="text-danger">*</span>:</label>
                    <input type="text" class="form-control" name="cnic_no"
                        value="{{isset($application->cnic_no) ? $application->cnic_no : $application->user->cnic_no}}"
                        required>
                </div>
                <div class="form-group col-md-6">
                    <label>Date of Expiry (CNIC) <span class="text-danger">*</span>:</label>
                    <div class="input-group date" id="cnic_expiry_date" data-target-input="nearest">
                        <input type="text"
                            value="{{isset($application->cnic_expiry_date) ? \Carbon\Carbon::parse($application->cnic_expiry_date)->format('d-m-Y') : ''}}"
                            class="form-control datetimepicker-input" data-target="#cnic_expiry_date"
                            name="cnic_expiry_date" required autocomplete="off" data-toggle="datetimepicker" />
                        <div class="input-group-append" data-target="#cnic_expiry_date" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset class="border p-4 mb-4">
            <legend class="w-auto">Exemption | Degree Information | BF Plan</legend>
            <div class="row">
                @if ($application->is_moved_from_intimation == 0)
                <div class="form-group col-md-12">
                    <input type="radio" name="exemption_reason" value="1" {{ $application->exemption_reason
                    == 1 ? 'checked' : ''}}><label for="">&nbsp;Holders of LL.M/Bar at Law degree having one
                        year practical experience certificate.</label>
                </div>
                <div class="form-group col-md-12">
                    <input type="radio" name="exemption_reason" value="2" {{ $application->exemption_reason
                    == 2 ? 'checked' : ''}}><label for="">&nbsp;Possessing four years Legal/Judicial
                        experience.</label>
                </div>
                <div class="form-group col-md-12">
                    <input type="radio" name="exemption_reason" value="0" {{ $application->exemption_reason
                    == 0 ? 'checked' : ''}}><label for="">&nbsp;None of Above.</label>
                </div>
                @endif


                <div class="form-group col-md-4">
                    <label for="">Degree Place</label>

                    @if ($application->intimation_degree_fee == 1)
                    <h5 class="text-success">The degree fee has already been paid in the intimation application.</h5>
                    @else

                    <select name="degree_place" id="degree_place" class="form-control custom-select"
                        {{$application->is_academic_record == TRUE ? 'required' : ''}}>
                        <option value="">Select Type</option>
                        <option value="1" {{ $application->degree_place == 1 ? 'selected' : ''}}>Punjab</option>
                        <option value="2" {{ $application->degree_place == 2 ? 'selected' : ''}}>Out of Punjab</option>
                        <option value="3" {{ $application->degree_place == 3 ? 'selected' : ''}}>Out of Pakistan
                        </option>
                    </select>
                    @endif
                </div>

            </div>
        </fieldset>

        <fieldset class="border p-4 mb-4">
            <legend class="w-auto">BF Plan</legend>
            <div class="row">
                <div class="form-group col-sm-4">
                    <select name="bf_plan" id="bf_plan" class="form-control custom-select">
                        <option value="1" {{ $application->bf_plan == 1 ? 'selected' : ''}}>
                            3 Lac Plan
                        </option>
                        <option value="2" {{ $application->bf_plan == 2 ? 'selected' : ''}}>
                            6 Lac Plan
                        </option>
                    </select>
                </div>
            </div>
            <div>
                <p>Note: For the 3 lac plan, the BF fee will be charged at the standard rate, while for the 6 lac
                    plan, the BF fee will be doubled.</p>
            </div>
        </fieldset>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <button type="submit" class="btn btn-success float-right" value="save">Save & Next</button>
        <a href="{{route('lower-court.create-step-1', $application->id)}}"
            class="btn btn-secondary float-right mr-1">Back</a>
    </div>
</form>