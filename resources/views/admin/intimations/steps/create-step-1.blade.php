@extends('layouts.frontend')

@section('content')

<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12" id="stepManagement">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Add Intimation Application</h3>
                    </div>

                    @include('frontend.intimation.partials.steps')

                    <form action="#" method="POST" id="create_step_1_form" enctype="multipart/form-data"> @csrf
                        <div class="card-body">

                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Bar Association & Passing Year</legend>
                                <div class="row" id="application_form">
                                    <div class="form-group col-md-6">
                                        <label>LLB Passing Year <span class="text-danger">*</span></label>
                                        <select name="llb_passing_year" id="llb_passing_year"
                                            class="form-control custom-select" required>
                                            <option value="">--Select Passing Year--</option>
                                            @foreach ($years as $year)
                                            <option value="{{$year}}" {{$application->llb_passing_year ==
                                                $year ? 'selected' : ''}}>{{$year}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Bar Association <span class="text-danger">*</span></label>
                                        <select name="bar_association" id="bar_association"
                                            class="form-control custom-select" required>
                                            <option value="">--Select Bar--</option>
                                            @foreach ($bars as $bar)
                                            <option {{$bar->id == isset($application->barAssociation->id) ? 'selected'
                                                : ''}}
                                                value="{{$bar->id}}">{{$bar->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Personal Information</legend>
                                <div class="row" id="application_form">
                                    <div class="form-group col-md-3">
                                        <label>First Name: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="first_name"
                                            value="{{isset($application->advocates_name) ? $application->advocates_name : Auth::guard('frontend')->user()->fname}}"
                                            required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Last Name: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="last_name"
                                            value="{{isset($application->last_name) ? $application->last_name : Auth::guard('frontend')->user()->lname}}"
                                            required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>S/O, D/O, W/O: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="father_name"
                                            value="{{isset($application->so_of) ? $application->so_of : ''}}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Gender: <span class="text-danger">*</span></label>
                                        <select class="form-control custom-select" name="gender" id="gender" required>
                                            <option disabled="">Select Gender</option>
                                            <option {{$application->gender == "Male" ? 'selected' : ''}}
                                                value="Male">Male</option>
                                            <option {{$application->gender == "Female" ? 'selected' : ''}}
                                                value="Female">Female</option>
                                            <option {{$application->gender == "Other" ? 'selected' : ''}}
                                                value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Date of Birth (As per CNIC): <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="date_of_birth"
                                            value="{{isset($application->date_of_birth) ? $application->date_of_birth : ''}}"
                                            required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Profile Picture: <span class="text-danger">*</span></label>
                                        @if (isset($application->uploads->profile_image) &&
                                        $application->uploads->profile_image != NULL)
                                        <img src="{{asset('storage/app/public/'.$application->uploads->profile_image)}}"
                                            alt="" class="col-md-12">
                                        <a href="{{route('frontend.intimation.destroy.profile-image')}}"
                                            class="btn btn-danger btn-sm float-right col-md-12 mt-2"
                                            onclick="return confirm('Are you sure you want to delete it. This action cannot be revert.')">Remove</a>
                                        @else
                                        <input type="file" id="profile_image" name="profile_image"
                                            accept="image/jpg,image/jpeg,image/png" required>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Blood Group</label>
                                        <select name="blood_group" id="blood_group" class="form-control custom-select">
                                            <option value="">--Select Blood Group--</option>
                                            <option {{$application->blood == "A+" ? 'selected' :''}}
                                                value="A+">A+</option>
                                            <option {{$application->blood == "A-" ? 'selected' :''}}
                                                value="A-">A-</option>
                                            <option {{$application->blood == "B+" ? 'selected' :''}}
                                                value="B+">B+</option>
                                            <option {{$application->blood == "B-" ? 'selected' :''}}
                                                value="B-">B-</option>
                                            <option {{$application->blood == "O+" ? 'selected' :''}}
                                                value="O+">O+</option>
                                            <option {{$application->blood == "O-" ? 'selected' :''}}
                                                value="O-">O-</option>
                                            <option {{$application->blood == "AB+" ? 'selected' :''}}
                                                value="AB+">AB+</option>
                                            <option {{$application->blood == "AB-" ? 'selected' :''}}
                                                value="AB-">AB-</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Contact Information</legend>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Email:</label>
                                        <input type="email" class="form-control" name="email"
                                            value="{{Auth::guard('frontend')->user()->email}}" readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Active Mobile No: <span class="text-danger">*</span></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><img
                                                        src="{{asset('public/admin/images/pakistan.png')}}"
                                                        alt=""></span>
                                                <span class="input-group-text">+92</span>
                                            </div>
                                            <input type="tel" class="form-control" name="active_mobile_no"
                                                value="{{Auth::guard('frontend')->user()->phone}}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success float-right" value="save">Save & Next</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script src="{{asset('public/js/app.js')}}"></script>

<script>
    jQuery(document).ready(function () {
        App.init();
    });

    $(document).ready(function(){
      $("#create_step_1_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('frontend.intimation.create-step-1', $application->id)}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
              },
              success: function (response) {
                if (response.status == 1) {
                    window.location.href = '{{route('frontend.intimation.create-step-2', $application->id)}}';
                }
              },
              error : function (errors) {
                errorsGet(errors.responseJSON.errors)
                $(".custom-loader").addClass('hidden');
              }
          });
      });
    });
</script>

<script>
    // PROFILE IMAGE
    var profileImage = FilePond.create(document.querySelector('input[id="profile_image"]'), {
        acceptedFileTypes: ['image/png' ,'image/jpeg','image/jpg'],
        required: false,
        allowMultiple: false,
        allowFileSizeValidation: true,
        maxFileSize:'1MB',
        allowRevert: false,
        fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
            resolve(type);
        })
    });
    profileImage.setOptions({
      server: {
          url: '{{route('frontend.intimation.uploads.profile-image')}}',
          headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
      }
    });
</script>

@endsection
