@extends('layouts.admin')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Existing Lawyers</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('lawyers.index')}}" class="btn btn-dark">Back</a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Add Existing Lawyer</h3>
                    </div>

                    @include('admin.lawyers.partials.steps')

                    <form action="#" method="POST" id="create_step_2_form" enctype="multipart/form-data"> @csrf
                        <div class="card-body">
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Personal Information</legend>
                                <div class="row" id="application_form">
                                    <div class="form-group col-md-6">
                                        <label>First Name:</label>
                                        <input type="text" class="form-control" name="first_name">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Last Name:</label>
                                        <input type="text" class="form-control" name="last_name">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>S/O, D/O, W/O:</label>
                                        <input type="text" class="form-control" name="father_name">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Gender:</label>
                                        <input type="text" class="form-control" name="gender">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Date of Birth (As per CNIC):</label>
                                        <input type="date" class="form-control" name="date_of_birth">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Profile Picture:</label>
                                        <input type="file" id="profile_image" name="profile_image"
                                            accept="image/jpg,image/jpeg,image/png">
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Contact Information</legend>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Email:</label>
                                        <input type="email" class="form-control" name="email">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Active Mobile No:</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><img
                                                        src="{{asset('public/admin/images/pakistan.png')}}"
                                                        alt=""></span>
                                                <span class="input-group-text">+92</span>
                                            </div>
                                            <input type="tel" class="form-control" name="active_mobile_no">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success float-right" value="save">Save & Next</button>
                            <a href="{{route('lawyers.create-step-1')}}"
                                class="btn btn-secondary float-right mr-1">Back</a>
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
      $("#create_step_2_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('lawyers.create-step-2', $lawyer->id)}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
              },
              success: function (response) {
                if (response.status == 1) {
                    window.location.href = '{{route('lawyers.create-step-3', $lawyer->id)}}';
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
        allowRevert: true,
        fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
            resolve(type);
        })
    });
    profileImage.setOptions({
      server: {
          url: '{{route('lawyers.uploads.profile-image')}}',
          headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
      }
    });
</script>

@endsection
