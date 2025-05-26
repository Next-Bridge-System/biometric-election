@extends('layouts.frontend')

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
                <h1>High Court Applications</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                    </li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Add High Court Application</h3>
                    </div>
                    @include('admin.high-court.partials.steps')
                    <div id="step-render">
                        <form class="steps-form" action="#" method="POST"
                            data-action="{{ route('frontend.high-court.create-step-1',$application->id) }}"
                            enctype="multipart/form-data"> @csrf
                            <div class="card-body">
                                <fieldset class="border p-4 mb-4">
                                    <legend class="w-auto">Personal Information</legend>
                                    <div class="row" id="application_form">
                                        <div class="form-group col-md-3">
                                            <label>First Name: <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="first_name"
                                                value="{{$user->fname}}" required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Last Name: <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="last_name"
                                                value="{{$user->lname}}" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Father's / Husband's Name: <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="father_name"
                                                value="{{isset($user->father_name) ? $user->father_name : ''}}"
                                                required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Gender: <span class="text-danger">*</span></label>
                                            <select class="form-control custom-select" name="gender" id="gender"
                                                required>
                                                <option disabled="">Select Gender</option>
                                                <option {{$user->gender == "Male" ? 'selected' : ''}}
                                                    value="Male">Male
                                                </option>
                                                <option {{$user->gender == "Female" ? 'selected' : ''}}
                                                    value="Female">Female
                                                </option>
                                                <option {{$user->gender == "Other" ? 'selected' : ''}}
                                                    value="Other">Other
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Date of Birth (As per CNIC): <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group date" id="dateOfBirth" data-target-input="nearest">
                                                <input type="text" value="{{getDateFormat($user->date_of_birth)}}"
                                                    class="form-control datetimepicker-input" data-target="#dateOfBirth"
                                                    name="dob" required autocomplete="off"
                                                    data-toggle="datetimepicker" />
                                                <div class="input-group-append" data-target="#dateOfBirth"
                                                    data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Blood Group</label>
                                            <select name="blood_group" id="blood_group"
                                                class="form-control custom-select">
                                                <option value="">--Select Blood Group--</option>
                                                <option {{$user->blood == "A+" ? 'selected' :''}} value="A+">A+
                                                </option>
                                                <option {{$user->blood == "A-" ? 'selected' :''}} value="A-">A-
                                                </option>
                                                <option {{$user->blood == "B+" ? 'selected' :''}} value="B+">B+
                                                </option>
                                                <option {{$user->blood == "B-" ? 'selected' :''}} value="B-">B-
                                                </option>
                                                <option {{$user->blood == "O+" ? 'selected' :''}} value="O+">O+
                                                </option>
                                                <option {{$user->blood == "O-" ? 'selected' :''}} value="O-">O-
                                                </option>
                                                <option {{$user->blood == "AB+" ? 'selected' :''}} value="AB+">
                                                    AB+
                                                </option>
                                                <option {{$user->blood == "AB-" ? 'selected' :''}} value="AB-">
                                                    AB-
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
                                                value="{{$user->email}}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Mobile No: <span class="text-danger">*</span></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><img
                                                            src="{{asset('public/admin/images/pakistan.png')}}"
                                                            alt=""></span>
                                                    <span class="input-group-text">+92</span>
                                                </div>
                                                <input type="tel" class="form-control" name="mobile_no"
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
                        goToStep('{{ route('frontend.lower-court.create-step-4',$application->id) }}', 4)
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
            }else if(target === '#ExemptionModal') {
                $('#ExemptionModal').modal('show');
            }else {
                $(".custom-loader").removeClass('hidden');
                $.get("{{ route('frontend.lower-court.create-step-5',$application->id) }}", function (data, status) {
                    $('#step-render').html(data);
                    $('.steps-holder:nth-child(5) .steps-content').addClass("active");
                    stepFive();
                    $(".custom-loader").addClass('hidden');
                });
            }
        });

        function removeDocument(event) {
            Swal.fire({
                title: 'Are you sure you want to delete it?',
                text: "You won't be able to revert this!",
                icon:'warning',
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
                        var action = $('#lc_create_step_4_form').data('action');
                        console.log(action);
                        goToStep(action, 4)
                    });
                }
            })
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
                        // stepFive();
                        break;
                    case 5:
                        // stepSix();
                        break;
                    case 6:
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
            $('#cnic_exp_date').datetimepicker({
                size: 'large',
                format: 'DD-MM-YYYY',
            });

            $('#lc_exp_date').datetimepicker({
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
            $('#exemption_modal').modal('show');
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
                qualification == 3 ) {
                $("#university_id").parent().addClass('hidden');
                $("#institute").parent().removeClass('hidden');
                $("#sub_qualification").parent().removeClass('hidden');
                $("#gat_pass").parent().addClass('hidden');

            }else if(qualification == 9){
                $("#sub_qualification").parent().addClass('hidden');
                $("#university_id").parent().addClass('hidden');
                $("#institute").parent().removeClass('hidden');
                $("#gat_pass").parent().removeClass('hidden');
            } else {
                $("#university_id").parent().removeClass('hidden');
                $("#sub_qualification").parent().removeClass('hidden');
                $("#institute").parent().addClass('hidden');
                $("#gat_pass").parent().addClass('hidden');


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
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');

            $('input[name="is_practice_in_pbc"]').change(function(){
                console.log($(this));
                let item = $('input[name="is_practice_in_pbc"]:checked').val();
                console.log(item);
                if(item == 'Yes'){
                    $('input[name="practice_place"]').show();
                }else{
                    $('input[name="practice_place"]').hide();
                }
            })

            $('input[name="is_dismissed_from_gov"]').change(function(){
                console.log($(this));
                let item = $('input[name="is_dismissed_from_gov"]:checked').val();
                console.log(item);
                if(item == 'Yes'){
                    $('input[name="dismissed_reason"]').show();
                }else{
                    $('input[name="dismissed_reason"]').hide();
                }
            })

            $('input[name="is_offensed"]').change(function(){
                console.log($(this));
                let item = $('input[name="is_offensed"]:checked').val();
                console.log(item);
                if(item == 'Yes'){
                    $('input[name="offensed_date"]').show();
                    $('input[name="offensed_reason"]').show();
                }else{
                    $('input[name="offensed_date"]').hide();
                    $('input[name="offensed_reason"]').hide();
                }
            })

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
                $.get("{{ route('lower-court.sendOTP',$application->id) }}", function (data, status) {
                    notifyBlackToast('OTP has sent successfully')
                });
            }
        })

        /*$(document).on('show.bs.modal','#exampleModalFinal',function (){

        })*/

        $(document).on('click','#resendPIN',function (){
            $.get("{{ route('lower-court.sendOTP',$application->id) }}", function (data, status) {
                notifyBlackToast('OTP has sent successfully')
            });
        })

        // Step 7
        function stepSeven() {
            var url = '{{ route("frontend.high-court.show", ":id") }}';
            url = url.replace(':id', '{{$application->id}}');
            window.location.href = url;
        }

        $('#intimation_date').datetimepicker({
            size: 'large',
            format: 'DD-MM-YYYY',
        });

        $(document).on('submit', '.exemption_form', function (e) {
            e.preventDefault();
            var url = '{{ route("frontend.lower-court.create-step-4.exemption", ":id") }}';
            url = url.replace(':id', '{{$application->id}}');
            $('span.text-success').remove();
            $('span.invalid-feedback').remove();
            $('input.is-invalid').removeClass('is-invalid');
            var formData = new FormData(this);
            $.ajax({
                method: "POST",
                data: formData,
                url:url,
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function(){
                    $(".custom-loader").removeClass('hidden');
                },
                success: function (response) {
                    if (response.status == 1) {
                        $('#exemption_modal').modal('toggle');
                        $(".custom-loader").addClass('hidden');
                    }
                },
                error : function (errors) {
                    errorsGet(errors.responseJSON.errors)
                    $(".custom-loader").addClass('hidden');
                    $("#error_message").removeClass('hidden');
                }
            });
        });
</script>

@endsection