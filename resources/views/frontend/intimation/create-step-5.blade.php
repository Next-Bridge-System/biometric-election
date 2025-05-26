@extends('layouts.frontend')


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

                    <form action="#" method="POST" id="create_step_5_form" enctype="multipart/form-data"> @csrf
                        <div class="card-body">
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Senior Lawyer Information</legend>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Sr. Lawyer Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="srl_name"
                                            value="{{isset($application->srl_name) ? $application->srl_name : ''}}"
                                            required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Advocate High Court / Subordinate Courts (Bar Name) <span
                                                class="text-danger">*</span></label>
                                        <select name="srl_bar_name" id="srl_bar_name" class="form-control custom-select"
                                            required>
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
                                                name="srl_enr_date" required autocomplete="off"
                                                data-toggle="datetimepicker" />
                                            <div class="input-group-append" data-target="#srl_enr_date"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Sr. Lawyer Joining Date <span class="text-danger">*</span> <small>This
                                                date is not editable. Please select valid date.</small></label>
                                        <div class="input-group date" id="srl_joining_date" data-target-input="nearest">
                                            <input type="text"
                                                value="{{isset($application->srl_joining_date) ? \Carbon\Carbon::parse($application->srl_joining_date)->format('d-m-Y') : ''}}"
                                                class="form-control datetimepicker-input"
                                                data-target="#srl_joining_date" name="srl_joining_date"
                                                {{isset($application->srl_joining_date) ? 'disabled' : ''}}
                                            autocomplete="off" data-toggle="datetimepicker" />
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
                                                        src="{{asset('public/admin/images/pakistan.png')}}"
                                                        alt=""></span>
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
                                            value="{{isset($application->srl_cnic_no) ? $application->srl_cnic_no : ''}}"
                                            required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Sr.Lawyer CNIC (Front):</label>
                                        @if (isset($application->uploads->srl_cnic_front) &&
                                        $application->uploads->srl_cnic_front != NULL)
                                        <img src="{{asset('storage/app/public/'.$application->uploads->srl_cnic_front)}}"
                                            alt="" class="col-md-12">
                                        <a href="javascript:void(0)"
                                            data-action="{{route('frontend.intimation.destroy.srl-cnic-front')}}"
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
                                        <img src="{{asset('storage/app/public/'.$application->uploads->srl_cnic_back)}}"
                                            alt="" class="col-md-12">
                                        <a href="javascript:void(0)"
                                            data-action="{{route('frontend.intimation.destroy.srl-cnic-back')}}"
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
                                        <img src="{{asset('storage/app/public/'.$application->uploads->srl_license_front)}}"
                                            alt="" class="col-md-12">
                                        <a href="javascript:void(0)"
                                            data-action="{{route('frontend.intimation.destroy.srl-license-front')}}"
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
                                        <img src="{{asset('storage/app/public/'.$application->uploads->srl_license_back)}}"
                                            alt="" class="col-md-12">
                                        <a href="javascript:void(0)"
                                            data-action="{{route('frontend.intimation.destroy.srl-license-back')}}"
                                            class="btn btn-danger btn-sm float-right col-md-12 mt-2"
                                            onclick="removeImage(this)">Remove</a>
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
                            <a href="{{route('frontend.intimation.create-step-4', $application->id)}}"
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
      $("#create_step_5_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('frontend.intimation.create-step-5', $application->id)}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
              },
              success: function (response) {
                if (response.status == 1) {
                    window.location.href = '{{route('frontend.intimation.create-step-6', $application->id)}}';
                }
              },
              error : function (errors) {
                errorsGet(errors.responseJSON.errors)
                if (errors.responseJSON.status == false) {
                    Swal.fire('Access Denied!',errors.responseJSON.error,'warning')
                }
                $(".custom-loader").addClass('hidden');
              }
          });
      });
    });
</script>

<script>
    var srlCnicFront = FilePond.create(document.querySelector('input[id="srl_cnic_front"]'), {
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
    srlCnicFront.setOptions({
      server: {
          url: '{{route('frontend.intimation.uploads.srl-cnic-front')}}',
          headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
      }
    });

    var srlCnicBack = FilePond.create(document.querySelector('input[id="srl_cnic_back"]'), {
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
    srlCnicBack.setOptions({
      server: {
          url: '{{route('frontend.intimation.uploads.srl-cnic-back')}}',
          headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
      }
    });

    var srlLicenseFront = FilePond.create(document.querySelector('input[id="srl_license_front"]'), {
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
    srlLicenseFront.setOptions({
      server: {
          url: '{{route('frontend.intimation.uploads.srl-license-front')}}',
          headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
      }
    });

    var srlLicenseBack = FilePond.create(document.querySelector('input[id="srl_license_back"]'), {
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
    srlLicenseBack.setOptions({
      server: {
          url: '{{route('frontend.intimation.uploads.srl-license-back')}}',
          headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
      }
    });

    $('#srl_enr_date').datetimepicker({
        size: 'large',
        date: '{{ $application->srl_enr_date ?? null }}',
        format: 'DD-MM-YYYY',
    });

    $('#srl_joining_date').datetimepicker({
        size: 'large',
        date: '{{ $application->srl_joining_date ?? null }}',
        format: 'DD-MM-YYYY',
    });

    function removeImage(event) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            console.log(result)
            if (result.value) {
                $(".custom-loader").removeClass('hidden');
                $.get(event.dataset.action, function (data, status) {
                    console.log(data);
                        location.reload();
                });
            }
        })
        /*var result = window.confirm('Are you sure you want to delete it. This action cannot be revert.');
        if(result){

        }*/
    }
</script>

@endsection
