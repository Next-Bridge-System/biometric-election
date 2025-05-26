@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<style>
    select option {
        text-transform: capitalize
    }
</style>
@endsection

@section('content')

<section class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Intimations</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <div class="">
                            <span>
                                Application No: {{$application->application_token_no}}
                            </span> <br>
                            <span>
                                Candidate: {{getLawyerName($application->id)}}
                            </span> <br>
                            <a href="{{route('intimations.show',$application->id)}}" class="btn btn-primary btn-xs m-1">
                                <i class="fas fa-copy mr-1" aria-hidden="true"></i>View Detail</a>
                        </div>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Add Intimation Application</h3>
                    </div>
                    @include('admin.intimations.partials.steps')

                    <!-- THIS PORTION => RENDERED -->
                    <div id="step-render">
                        <form class="steps-form" action="#" method="POST"
                            data-action="{{ route('intimations.create-step-1',$application->id) }}"
                            enctype="multipart/form-data"> @csrf
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
                                            @if(!Auth::guard('admin')->user()->is_super)
                                            <input type="text" class="form-control"
                                                value="{{ Auth::guard('admin')->user()->bar->name }}" readonly>
                                            @else
                                            <select name="bar_association" id="bar_association"
                                                class="form-control custom-select" required>
                                                <option value="">--Select Bar--</option>
                                                @foreach ($bars as $bar)
                                                <option {{ isset($application->barAssociation->id) && $bar->id ==
                                                    $application->barAssociation->id ? 'selected': ''}}
                                                    value="{{$bar->id}}">{{$bar->name}}</option>
                                                @endforeach
                                            </select>
                                            @endif
                                        </div>
                                    </div>
                                </fieldset>

                                <fieldset class="border p-4 mb-4">
                                    <legend class="w-auto">Personal Information</legend>
                                    <div class="row" id="application_form">
                                        <div class="form-group col-md-3">
                                            <label>First Name: <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="first_name"
                                                value="{{isset($application->advocates_name) ? $application->advocates_name : $user->fname}}"
                                                required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Last Name: <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="last_name"
                                                value="{{isset($application->last_name) ? $application->last_name : $user->lname}}"
                                                required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Father's / Husband's Name: <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="father_name"
                                                value="{{isset($application->so_of) ? $application->so_of : ''}}"
                                                required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Gender: <span class="text-danger">*</span></label>
                                            <select class="form-control custom-select" name="gender" id="gender"
                                                required>
                                                <option disabled="">Select Gender</option>
                                                <option {{$application->gender == "Male" ? 'selected' : ''}}
                                                    value="Male">Male
                                                </option>
                                                <option {{$application->gender == "Female" ? 'selected' : ''}}
                                                    value="Female">Female
                                                </option>
                                                <option {{$application->gender == "Other" ? 'selected' : ''}}
                                                    value="Other">Other
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Date of Birth (As per CNIC): <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group date" id="dateOfBirth" data-target-input="nearest">
                                                <input type="text"
                                                    value="{{isset($application->date_of_birth) ? $application->date_of_birth : ''}}"
                                                    class="form-control datetimepicker-input" data-target="#dateOfBirth"
                                                    name="date_of_birth" required autocomplete="off"
                                                    data-toggle="datetimepicker" />
                                                <div class="input-group-append" data-target="#dateOfBirth"
                                                    data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>


                                        </div>
                                        {{--<div class="form-group col-md-6">
                                            <label>Profile Picture: <span class="text-danger">*</span></label>
                                            @if (isset($application->uploads->profile_image) &&
                                            $application->uploads->profile_image != NULL)
                                            <img src="{{asset('storage/app/public/'.$application->uploads->profile_image)}}"
                                        alt="" class="col-md-12">
                                        <a href="javascript:void(0)"
                                            data-action="{{route('intimations.destroy.profile-image')}}"
                                            class="btn btn-danger btn-sm float-right col-md-12 mt-2"
                                            onclick="removeImage(this,1)">Remove</a>
                                        @else
                                        <input type="file" id="profile_image" name="profile_image"
                                            accept="image/jpg,image/jpeg,image/png" required>
                                        @endif
                                    </div>--}}
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
                                        <input type="email" class="form-control" name="email" value="{{$user->email}}">
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
                                                value="{{$user->phone}}">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success float-right" value="save">Save & Next
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<!-- /.content -->

@endsection

@section('scripts')
<script src="{{asset('public/js/app.js')}}"></script>

<script>
    jQuery(document).ready(function () {
            App.init();
        });

        $('#dateOfBirth').datetimepicker({
            size: 'large',
            format: 'DD-MM-YYYY',
        });

        $('#rcard_date').datetimepicker({
            size: 'large',
            format: 'DD-MM-YYYY',
        });

        $(document).on("submit", '.steps-form', function (event) {
            event.preventDefault();
            url = event.target.dataset.action;
            console.log(event.target.dataset.action);
            $('span.text-success').remove();
            $('span.invalid-feedback').remove();
            $('input.is-invalid').removeClass('is-invalid');
            var formData = new FormData(this);
            $.ajax({
                method: "POST",
                data: formData,
                url: url,
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function () {
                    $(".custom-loader").removeClass('hidden');
                },
                success: function (response) {
                    if (response.status === 1) {
                        $('#step-render').html(response.nextStep)
                        $('.steps-holder:nth-child(' + response.step + ') .steps-content').addClass("active");
                        switch (response.step) {
                            case 2:
                                stepTwo();
                                break;
                            case 3:
                                stepThree();
                                break;
                            case 4:
                                stepFour();
                                break;
                            case 5:
                                stepFive();
                                break;
                            case 6:
                                stepSix();
                                break;
                            case 7:
                                stepSeven();
                                break;
                            case 8:
                                $('#exampleModalFinal').modal('hide');
                                $('.modal-backdrop').remove();
                                if(response.print_voucher_url != undefined){
                                    window.open(response.print_voucher_url,'_blank')
                                }
                                break;
                            case 9:
                                $('#exampleModalFinal').modal('hide');
                                setTimeout(function(){
                                    location.href = response.nextStep;
                                },800)
                                break;
                        }

                    } else if (response.status === 2) {
                        goToStep('{{ route('intimations.create-step-4',$application->id) }}', 4)
                    }
                    if(response.step != undefined && response.step === 9){

                    }else{
                        $(".custom-loader").addClass('hidden');
                    }
                },
                error: function (errors) {
                    errorsGet(errors.responseJSON.errors)
                    $(".custom-loader").addClass('hidden');
                }
            });
        });

        $(document).on("click", '#saveAndNext', function (event) {
            event.preventDefault();
            var target = event.target.dataset.target;
            if (target === '#exampleModal') {
                $('#exampleModal').modal('show');
            } else {
                $(".custom-loader").removeClass('hidden');
                $.get("{{ route('intimations.create-step-5',$application->id) }}", function (data, status) {
                    $('#step-render').html(data);
                    $('.steps-holder:nth-child(5) .steps-content').addClass("active");
                    stepFive();
                    $(".custom-loader").addClass('hidden');
                });
            }
        });

        function removeImage(event, step) {
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
                    console.log(event.dataset.action)
                    $(".custom-loader").removeClass('hidden');
                    $.get(event.dataset.action, function (data, status) {
                        console.log(data);
                        if (step === 1) {
                            location.reload();
                        } else {
                            var action = $('form').data('action');
                            goToStep(action, step)
                        }
                    });
                }
            })
            /*var result = window.confirm('Are you sure you want to delete it. This action cannot be revert.');
            if(result){

            }*/
        }

        function removeDocument(event) {
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
                    console.log(event.dataset.action)
                    $(".custom-loader").removeClass('hidden');
                    $.get(event.dataset.action, function (data, status) {
                        var action = $('form').data('action');
                        console.log(action);
                        goToStep(action, 4)
                    });
                }
            })
            /*var result = window.confirm('Are you sure you want to delete it. This action cannot be revert.');
            if(result){
                console.log(event.dataset.action)
                $(".custom-loader").removeClass('hidden');
                $.get(event.dataset.action, function(data, status){
                    console.log(data);
                    var action = $('form').data('action');
                    goToStep(action,4)
                });
            }*/
        }

        function goToStep(action, step) {
            $(".custom-loader").removeClass('hidden');
            $.get(action, function (data, status) {
                $('#step-render').html(data);
                $('.steps-content').removeClass("active");
                switch (step) {
                    case 2:
                        stepTwo();
                        break;
                    case 3:
                        stepThree();
                        break;
                    case 4:
                        stepFour();
                        break;
                    case 5:
                        stepFive();
                        break;
                    case 6:
                        stepSix();
                        break;
                    case 7:
                        break;
                    case 8:
                        $('#exampleModalFinal').modal('hide');
                        $('.modal-backdrop').remove();
                        break;
                }
                for (var i = 1; i <= step; i++) {
                    $('.steps-holder:nth-child(' + i + ') .steps-content').addClass("active");
                }
                $(".custom-loader").addClass('hidden');
            });
        }

        function stepTwo() {



            $('#cnic_expiry_date').datetimepicker({
                size: 'large',
                format: 'DD-MM-YYYY',
            });
        }

        // Step 3
        $(document).on('change', '#same_address_btn', function (e) {
            e.preventDefault();
            var value = $(this).val();
            if (this.checked) {
                $("#postal_address_section").addClass('hidden');
                // $('.postal_address').prop('required',false);
            } else {
                $("#postal_address_section").removeClass('hidden');
                // $('.postal_address').prop('required',true);
            }
        });

        $(document).on('change', "#ha_country_id", function (e) {
            e.preventDefault();
            var country_id = $(this).val();

            if (country_id == 166) {
                $(".ha_pakistan_section").show();
                // $('#ha_province_id').prop('required',true);
                // $('#ha_district_id').prop('required',true);
                // $('#ha_tehsil_id').prop('required',true);
            } else {
                $(".ha_pakistan_section").hide();
                // $('#ha_province_id').prop('required',false);
                // $('#ha_district_id').prop('required',false);
                // $('#ha_tehsil_id').prop('required',false);
            }
        });

        $(document).on('change', "#pa_country_id", function (e) {
            e.preventDefault();
            var country_id = $(this).val();

            if (country_id == 166) {
                $(".pa_pakistan_section").show();
                // $('#pa_province_id').prop('required',true);
                // $('#pa_district_id').prop('required',true);
                // $('#pa_tehsil_id').prop('required',true);
            } else {
                $(".pa_pakistan_section").hide();
                // $('#pa_province_id').prop('required',false);
                // $('#pa_district_id').prop('required',false);
                // $('#pa_tehsil_id').prop('required',false);
            }
        });

        (function ($) {
            $(document).ready(function () {

            });
        })(jQuery);

        function stepThree() {
            var ha_country_id = $("#ha_country_id").val();
            if (ha_country_id == 166) {
                $(".ha_pakistan_section").show();
                // $('#ha_province_id').prop('required',true);
                // $('#ha_district_id').prop('required',true);
                // $('#ha_tehsil_id').prop('required',true);
            } else {
                $(".ha_pakistan_section").hide();
                // $('#ha_province_id').prop('required',false);
                // $('#ha_district_id').prop('required',false);
                // $('#ha_tehsil_id').prop('required',false);
            }

            var pa_country_id = $("#pa_country_id").val();
            if (pa_country_id == 166) {
                $(".pa_pakistan_section").show();
                // $('#pa_province_id').prop('required',true);
                // $('#pa_district_id').prop('required',true);
                // $('#pa_tehsil_id').prop('required',true);
            } else {
                $(".pa_pakistan_section").hide();
                // $('#pa_province_id').prop('required',false);
                // $('#pa_district_id').prop('required',false);
                // $('#pa_tehsil_id').prop('required',false);
            }
        }

        $(document).on('change', '#ha_district_id', 'change', function () {
            var district_id = $('#ha_district_id').find(":selected").val();
            var option = '';
            $.ajax({
                method: "POST",
                url: '{{route('getTehsilsByDistrict')}}',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'district_id': district_id
                },
                success: function (response) {
                    $('#ha_tehsil_id').empty();
                    $('#ha_tehsil_id').append(' <option value="" selected>--Select Tehsil--</option>');
                    response.tehsils.forEach(function (item, index) {
                        option = "<option value='" + item.id + "'>" + item.name + ' - ' + item.code + "</option>"
                        $('#ha_tehsil_id').append(option);
                    });
                }
            });
        });

        $(document).on('change', '#pa_district_id', 'change', function () {
            var district_id = $('#pa_district_id').find(":selected").val();
            var option = '';
            $.ajax({
                method: "POST",
                url: '{{route('getTehsilsByDistrict')}}',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'district_id': district_id
                },
                success: function (response) {
                    $('#pa_tehsil_id').empty();
                    $('#pa_tehsil_id').append(' <option value="" selected>--Select Tehsil--</option>');
                    response.tehsils.forEach(function (item, index) {
                        option = "<option value='" + item.id + "'>" + item.name + ' - ' + item.code + "</option>"
                        $('#pa_tehsil_id').append(option);
                    });
                }
            });
        });

        // Step 4
        function stepFour() {
            let startYear = 1900;
            let endYear = new Date().getFullYear();
            for (i = endYear; i > startYear; i--) {
                $('#passing_year').append($('<option />').val(i).html(i));
            }
        }

        $(document).on('change', '#qualification', function (e) {
            e.preventDefault();
            var qualification = $(this).val();
            if (qualification == 1 ||
                qualification == 2 ||
                qualification == 3 ||
                qualification == 9) {
                $("#university_id").parent().addClass('hidden');
                $("#institute").parent().removeClass('hidden');
            } else {
                $("#university_id").parent().removeClass('hidden');
                $("#institute").parent().addClass('hidden');
            }

            if (qualification == 1 || qualification == 2) {
                $("#sub_qualification").attr('disabled', false);
            } else {
                $("#sub_qualification").attr('disabled', true);
            }

            if (qualification == 1) {
                $(".matric").removeClass('hidden');
            } else {
                $(".matric").addClass('hidden');
            }

            if (qualification == 2) {
                $(".inter").removeClass('hidden');
            } else {
                $(".inter").addClass('hidden');
            }

        });

        // Step 5

        function stepFive() {
            $('#srl_enr_date').datetimepicker({
                size: 'large',
                format: 'DD-MM-YYYY',
            });
            $('#srl_joining_date').datetimepicker({
                size: 'large',
                format: 'DD-MM-YYYY',
            });
        }

        // Step 6

        function stepSix() {
            var profileImage = FilePond.create(document.querySelector('input[id="profile_image"]'), {
                acceptedFileTypes: ['image/png', 'image/jpeg', 'image/jpg'],
                required: false,
                allowMultiple: false,
                allowFileSizeValidation: true,
                maxFileSize: '1MB',
                allowRevert: false,
                fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
                    resolve(type);
                })
            });
            profileImage.setOptions({
                server: {
                    url: '{{route('intimations.uploads.profile-image')}}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }
            });

            var cnicFront = FilePond.create(document.querySelector('input[id="cnic_front"]'), {
                acceptedFileTypes: ['image/png', 'image/jpeg', 'image/jpg'],
                required: false,
                allowMultiple: false,
                allowFileSizeValidation: true,
                maxFileSize: '1MB',
                allowRevert: false,
                fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
                    resolve(type);
                })
            });
            cnicFront.setOptions({
                server: {
                    url: '{{route('intimations.uploads.cnic-front')}}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }
            });

            var cnicBack = FilePond.create(document.querySelector('input[id="cnic_back"]'), {
                acceptedFileTypes: ['image/png', 'image/jpeg', 'image/jpg'],
                required: false,
                allowMultiple: false,
                allowFileSizeValidation: true,
                maxFileSize: '1MB',
                allowRevert: false,
                fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
                    resolve(type);
                })
            });
            cnicBack.setOptions({
                server: {
                    url: '{{route('intimations.uploads.cnic-back')}}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }
            });

            var srlCnicFront = FilePond.create(document.querySelector('input[id="srl_cnic_front"]'), {
                acceptedFileTypes: ['image/png', 'image/jpeg', 'image/jpg'],
                required: false,
                allowMultiple: false,
                allowFileSizeValidation: true,
                maxFileSize: '1MB',
                allowRevert: false,
                fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
                    resolve(type);
                })
            });
            srlCnicFront.setOptions({
                server: {
                    url: '{{route('intimations.uploads.srl-cnic-front')}}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }
            });

            var srlCnicBack = FilePond.create(document.querySelector('input[id="srl_cnic_back"]'), {
                acceptedFileTypes: ['image/png', 'image/jpeg', 'image/jpg'],
                required: false,
                allowMultiple: false,
                allowFileSizeValidation: true,
                maxFileSize: '1MB',
                allowRevert: false,
                fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
                    resolve(type);
                })
            });
            srlCnicBack.setOptions({
                server: {
                    url: '{{route('intimations.uploads.srl-cnic-back')}}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }
            });

            var srlLicenseFront = FilePond.create(document.querySelector('input[id="srl_license_front"]'), {
                acceptedFileTypes: ['image/png', 'image/jpeg', 'image/jpg'],
                required: false,
                allowMultiple: false,
                allowFileSizeValidation: true,
                maxFileSize: '1MB',
                allowRevert: false,
                fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
                    resolve(type);
                })
            });
            srlLicenseFront.setOptions({
                server: {
                    url: '{{route('intimations.uploads.srl-license-front')}}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }
            });

            var srlLicenseBack = FilePond.create(document.querySelector('input[id="srl_license_back"]'), {
                acceptedFileTypes: ['image/png', 'image/jpeg', 'image/jpg'],
                required: false,
                allowMultiple: false,
                allowFileSizeValidation: true,
                maxFileSize: '1MB',
                allowRevert: false,
                fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
                    resolve(type);
                })
            });
            srlLicenseBack.setOptions({
                server: {
                    url: '{{route('intimations.uploads.srl-license-back')}}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }
            });
        }

        // Step 7

        $(document).on('click','#is_otp',function(){
            console.log($(this).is(':checked'));
            if($(this).is(':checked')){
                $('.otp-section').removeClass('hidden');
                $('.otp-section input').prop('disabled',false)
            }else{
                $('.otp-section').addClass('hidden');
                $('.otp-section input').prop('disabled',true)
                $.get("{{ route('intimations.sendOTP',$application->id) }}", function (data, status) {
                    notifyBlackToast('OTP has sent successfully')
                });
            }
        })

        /*$(document).on('show.bs.modal','#exampleModalFinal',function (){

        })*/

        $(document).on('click','#resendPIN',function (){
            $.get("{{ route('intimations.sendOTP',$application->id) }}", function (data, status) {
                notifyBlackToast('OTP has sent successfully')
            });
        })

        // Step 7

    function stepSeven() {
        $('.submit_btn').removeClass('hidden');
        $('.loading_btn').addClass('hidden');

        $('#intimation_start_date').datetimepicker({
            size: 'large',
            format: 'DD-MM-YYYY',
        });

        $('.submit_btn').on('click', function () {
            $('.submit_btn').addClass('hidden');
            $('.loading_btn').removeClass('hidden');
        });
    }


</script>

@endsection