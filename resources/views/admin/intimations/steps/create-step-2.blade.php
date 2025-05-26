<form class="steps-form" action="#" method="POST" data-action="{{route('intimations.create-step-2',$application->id)}}"
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
            <legend class="w-auto">Degree Information</legend>
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="">Degree Place</label>
                    <select name="degree_place" id="degree_place" class="form-control custom-select">
                        <option value="" selected>Select</option>
                        <option value="1" {{ $application->degree_place == 1 ? 'selected' :''}}>Punjab</option>
                        <option value="2" {{ $application->degree_place == 2 ? 'selected' : ''}}>Out of Punjab</option>
                        <option value="3" {{ $application->degree_place == 3 ? 'selected' : ''}}>Out of Pakistan</option>
                    </select>
                </div>
            </div>
        </fieldset>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <button type="submit" class="btn btn-success float-right" value="save">Save & Next</button>
        <a href="{{route('intimations.create-step-1', $application->id)}}"
            class="btn btn-secondary float-right mr-1">Back</a>
    </div>
</form>