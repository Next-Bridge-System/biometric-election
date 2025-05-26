@extends('layouts.frontend')

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')

<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
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
                                    <div class="form-group col-md-3">
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
                                    <div class="form-group col-md-3">
                                        <label>Result Card Date <span class="text-danger">*</span></label>
                                        <div class="input-group date" id="rcard_date" data-target-input="nearest">
                                            <input type="text" value="{{getDateFormat($application->rcard_date)}}"
                                                class="form-control datetimepicker-input" data-target="#rcard_date"
                                                name="rcard_date" required autocomplete="off"
                                                data-toggle="datetimepicker" />
                                            <div class="input-group-append" data-target="#rcard_date"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Bar Association <span class="text-danger">*</span></label>
                                        <select name="bar_association" id="bar_association"
                                            class="form-control custom-select" required>
                                            <option value="">--Select Bar--</option>
                                            @foreach ($bars as $bar)
                                            <option @if (isset($application->barAssociation->id)) {{$bar->id ==
                                                $application->barAssociation->id ?
                                                'selected'
                                                : ''}}
                                                @endif
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
                                            <option {{$application->gender == "Male" ? 'selected' : ''}} value="Male">
                                                Male</option>
                                            <option {{$application->gender == "Female" ? 'selected' : ''}}
                                                value="Female">Female</option>
                                            <option {{$application->gender == "Other" ? 'selected' : ''}} value="Other">
                                                Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Date of Birth (As per CNIC): <span class="text-danger">*</span></label>
                                        <div class="input-group date" id="dateOfBirth" data-target-input="nearest">
                                            <input type="text"
                                                value="{{isset($application->date_of_birth) ? \Carbon\Carbon::parse($application->date_of_birth)->format('Y-m-d') : ''}}"
                                                class="form-control datetimepicker-input" data-target="#dateOfBirth"
                                                name="date_of_birth" required autocomplete="off"
                                                data-toggle="datetimepicker" />
                                            <div class="input-group-append" data-target="#dateOfBirth"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
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
                                        
                                        <b class="text-danger">Important Note: The profile picture must be passport size (600x600 pixels) with
                                            a white
                                            background. The lawyer should wear a uniform with a maroon tie. If the profile image does not
                                            meet these conditions, the application will be rejected during processing.</b>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Blood Group</label>
                                        <select name="blood_group" id="blood_group" class="form-control custom-select">
                                            <option value="">--Select Blood Group--</option>
                                            <option {{$application->blood == "A+" ? 'selected' :''}} value="A+">A+
                                            </option>
                                            <option {{$application->blood == "A-" ? 'selected' :''}} value="A-">A-
                                            </option>
                                            <option {{$application->blood == "B+" ? 'selected' :''}} value="B+">B+
                                            </option>
                                            <option {{$application->blood == "B-" ? 'selected' :''}} value="B-">B-
                                            </option>
                                            <option {{$application->blood == "O+" ? 'selected' :''}} value="O+">O+
                                            </option>
                                            <option {{$application->blood == "O-" ? 'selected' :''}} value="O-">O-
                                            </option>
                                            <option {{$application->blood == "AB+" ? 'selected' :''}} value="AB+">AB+
                                            </option>
                                            <option {{$application->blood == "AB-" ? 'selected' :''}} value="AB-">AB-
                                            </option>
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


<div class="modal fade" id="instructionModal" tabindex="-1" aria-labelledby="instructionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="instructionModalLabel">Intimation Application</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <fieldset class="border p-4 mb-4">
                    <legend class="w-auto">Instructions & Guidlines</legend>
                    <div class="row">
                        <h6><b>Note:</b></h6>
                        <ol>
                            <li>Personal Information</li>
                            <li>Lawyer Identification</li>
                            <li>Postal & Home Address</li>
                            <li>Academic Records</li>
                            <li>Senior Lawyer Information</li>
                            <li>User HBL voucher</li>
                            <li>User payment slip</li>
                            <li>Application with user and senior lawyer signatures</li>
                            <li>User all documents copies</li>
                            <li>User CNIC copy</li>
                            <li>Senior lawyer CNIC copy and licence copy submit to the relevant bar by user</li>
                        </ol>
                    </div>
                    <input type="checkbox" id="instructions_checkbox" name="instructions_checkbox">
                    <label for="instructions_checkbox" style="display: inline;">I have read and accept the instructions
                        & guidelines.</label><br>
                </fieldset>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

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

    $(window).on('load', function() {
        if (localStorage.getItem("instruction_value") == null) {
            $('#instructionModal').modal('show');
        }
    })

    $("#instructions_checkbox").change(function (e) {
        e.preventDefault();
        if ($('#instructions_checkbox').is(":checked")) {
            localStorage.setItem("instruction_value", "TRUE")
        } else {
            localStorage.removeItem("instruction_value", "TRUE")
        }
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
        allowRevert: true,
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

    $('#dateOfBirth').datetimepicker({
        size: 'large',
        date: '{{ $application->date_of_birth ?? null }}',
        format: 'DD-MM-YYYY',
    });

    $('#rcard_date').datetimepicker({
        size: 'large',
        format: 'DD-MM-YYYY',
    });
</script>

@endsection