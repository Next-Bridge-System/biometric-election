<form class="steps-form" data-action="{{ route('intimations.create-step-6',$application->id) }}" action="#"
    method="POST" id="create_step_5_form" enctype="multipart/form-data"> @csrf
    <div class="card-body">
        <fieldset class="border p-4 mb-4">
            <legend class="w-auto">Uploads</legend>

            <div class="row mb-3">
                <b class="text-danger">Important Note: The profile picture must be passport size (600x600 pixels) with
                    a white
                    background. The lawyer should wear a uniform with a maroon tie. If the profile image does not
                    meet these conditions, the application will be rejected during processing.</b>
            </div>
            <div class="row">
                <div class="form-group col-md-4">
                    <label>Profile Picture: <span class="text-danger">*</span></label>
                    @if (isset($application->uploads->profile_image) &&
                    $application->uploads->profile_image != NULL)
                    <img src="{{asset('storage/app/public/'.$application->uploads->profile_image)}}" alt=""
                        class="col-md-12">
                    <a href="javascript:void(0)" data-action="{{route('intimations.destroy.profile-image')}}"
                        class="btn btn-danger btn-sm float-right col-md-12 mt-2"
                        onclick="removeImage(this,6)">Remove</a>
                    @else
                    <input type="file" id="profile_image" name="profile_image" accept="image/jpg,image/jpeg,image/png"
                        required>
                    @endif
                    <div class="errors" data-id="profile_image"></div>
                </div>
                <div class="form-group col-md-4">
                    <label>CNIC (Front) <span class="text-danger">*</span>:</label>
                    @if (isset($application->uploads->cnic_front) &&
                    $application->uploads->cnic_front != NULL)
                    <img src="{{asset('storage/app/public/'.$application->uploads->cnic_front)}}" alt=""
                        class="col-md-12">
                    <a href="javascript:void(0)" data-action="{{route('intimations.destroy.cnic-front')}}"
                        class="btn btn-danger btn-sm float-right col-md-12 mt-2"
                        onclick="removeImage(this,6)">Remove</a>

                    @else
                    <input type="file" name="cnic_front" id="cnic_front" accept="image/jpg,image/jpeg,image/png"
                        required>
                    @endif
                    <div class="errors" data-id="cnic_front"></div>
                </div>
                <div class="form-group col-md-4">
                    <label>CNIC (Back) <span class="text-danger">*</span>:</label>
                    @if (isset($application->uploads->cnic_back) &&
                    $application->uploads->cnic_back != NULL)
                    <img src="{{asset('storage/app/public/'.$application->uploads->cnic_back)}}" alt=""
                        class="col-md-12">
                    <a href="javascript:void(0)" data-action="{{route('intimations.destroy.cnic-back')}}"
                        class="btn btn-danger btn-sm float-right col-md-12 mt-2"
                        onclick="removeImage(this,6)">Remove</a>
                    @else
                    <input type="file" id="cnic_back" name="cnic_back" accept="image/jpg,image/jpeg,image/png" required>
                    @endif
                    <div class="errors" data-id="cnic_back"></div>
                </div>
                <div class="form-group col-md-3">
                    <label>Sr.Lawyer CNIC (Front):</label>
                    @if (isset($application->uploads->srl_cnic_front) &&
                    $application->uploads->srl_cnic_front != NULL)
                    <img src="{{asset('storage/app/public/'.$application->uploads->srl_cnic_front)}}" alt=""
                        class="col-md-12">
                    <a href="javascript:void(0)" data-action="{{route('intimations.destroy.srl-cnic-front')}}"
                        class="btn btn-danger btn-sm float-right col-md-12 mt-2"
                        onclick="removeImage(this,6)">Remove</a>
                    @else
                    <input type="file" name="srl_cnic_front" id="srl_cnic_front"
                        accept="image/jpg,image/jpeg,image/png">
                    @endif
                    <div class="errors" data-id="srl_cnic_front"></div>
                </div>
                <div class="form-group col-md-3">
                    <label>Sr.Lawyer CNIC (Back):</label>
                    @if (isset($application->uploads->srl_cnic_back) &&
                    $application->uploads->srl_cnic_back != NULL)
                    <img src="{{asset('storage/app/public/'.$application->uploads->srl_cnic_back)}}" alt=""
                        class="col-md-12">
                    <a href="javascript:void(0)" data-action="{{route('intimations.destroy.srl-cnic-back')}}"
                        class="btn btn-danger btn-sm float-right col-md-12 mt-2"
                        onclick="removeImage(this,6)">Remove</a>
                    @else
                    <input type="file" id="srl_cnic_back" name="srl_cnic_back" accept="image/jpg,image/jpeg,image/png">
                    @endif
                    <div class="errors" data-id="srl_cnic_back"></div>
                </div>

                <div class="form-group col-md-3">
                    <label>Sr.Lawyer PBC License (Front):</label>
                    @if (isset($application->uploads->srl_license_front) &&
                    $application->uploads->srl_license_front != NULL)
                    <img src="{{asset('storage/app/public/'.$application->uploads->srl_license_front)}}" alt=""
                        class="col-md-12">
                    <a href="javascript:void(0)" data-action="{{route('intimations.destroy.srl-license-front')}}"
                        class="btn btn-danger btn-sm float-right col-md-12 mt-2"
                        onclick="removeImage(this,6)">Remove</a>
                    @else
                    <input type="file" name="srl_license_front" id="srl_license_front"
                        accept="image/jpg,image/jpeg,image/png">
                    @endif
                    <div class="errors" data-id="srl_license_front"></div>
                </div>
                <div class="form-group col-md-3">
                    <label>Sr.Lawyer PBC License (Back):</label>
                    @if (isset($application->uploads->srl_license_back) &&
                    $application->uploads->srl_license_back != NULL)
                    <img src="{{asset('storage/app/public/'.$application->uploads->srl_license_back)}}" alt=""
                        class="col-md-12">
                    <a href="javascript:void(0)" data-action="{{route('intimations.destroy.srl-license-back')}}"
                        class="btn btn-danger btn-sm float-right col-md-12 mt-2"
                        onclick="removeImage(this,6)">Remove</a>
                    @else
                    <input type="file" id="srl_license_back" name="srl_license_back"
                        accept="image/jpg,image/jpeg,image/png">
                    @endif
                    <div class="errors" data-id="srl_license_back"></div>
                </div>
            </div>
        </fieldset>

    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <button type="submit" class="btn btn-success float-right" value="save">Save & Next</button>
        <a href="javascript:void(0)" onclick="goToStep('{{route('intimations.create-step-5', $application->id)}}',5)"
            class="btn btn-secondary float-right mr-1">Back</a>
    </div>
</form>