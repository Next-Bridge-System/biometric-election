@extends('layouts.frontend')

@section('content')
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">LOWER/HIGH COURT VOUCHERS</h3>
                    </div>
                    <form action="#" id="store_voucher_form" method="POST"> @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-12 col-md-6">
                                    <label>Application Type <span class="required-star">*</span></label>
                                    <select name="application_type" id="application_type"
                                        class="form-control custom-select" required>
                                        <option value="">--Select Application Type--</option>
                                        <option value="1">LOWER COURT</option>
                                        <option value="2">HIGH COURT</option>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label>Name <span class="required-star">*</span></label>
                                    <input type="text" maxlength="100" class="form-control" name="name" required>
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label>Father Name <span class="required-star">*</span></label>
                                    <input type="text" maxlength="100" class="form-control" name="father_name" required>
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label>Date of Birth <span class="required-star">*</span></label>
                                    <div class="input-group date" id="dateOfBirth" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input"
                                            data-target="#dateOfBirth" name="date_of_birth" required autocomplete="off"
                                            data-toggle="datetimepicker" />
                                        <div class="input-group-append" data-target="#dateOfBirth"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label>Station <span class="required-star">*</span></label>
                                    <select name="station" id="station" class="form-control custom-select">
                                        <option value="" selected>--Select Station--</option>
                                        @foreach ($stations as $station)
                                        <option value="{{$station->id}}">{{$station->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label>Nationality</label>
                                    <input type="text" maxlength="100" class="form-control" name="nationality">
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label>CNIC No<span class="text-danger">*</span>:</label>
                                    <input type="text" class="form-control" name="cnic_no" required>
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label>Home Address</label>
                                    <input type="text" maxlength="100" class="form-control" name="home_address">
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label>Postal Address</label>
                                    <input type="text" maxlength="100" class="form-control" name="postal_address">
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label>Email Address</label>
                                    <input type="text" maxlength="100" class="form-control" name="email">
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label>Contact <span class="required-star">*</span></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><img
                                                    src="{{asset('public/admin/images/pakistan.png')}}" alt=""></span>
                                            <span class="input-group-text">+92</span>
                                        </div>
                                        <input type="tel" class="form-control" name="contact">
                                    </div>
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label>Degree Type <span class="required-star">*</span></label>
                                    <select name="degree_type" id="degree_type" class="form-control custom-select"
                                        required>
                                        <option value="">--Select Degree Type--</option>
                                        <option value="WITH IN PUNJAB">WITH IN PUNJAB</option>
                                        <option value="OUTSIDE PUNJAB">OUTSIDE PUNJAB</option>
                                        <option value="FOREIGN UNIVERSITY">FOREIGN UNIVERSITY</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success float-right">Save & Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@include('frontend.vouchers.includes.otp')

@endsection

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('scripts')
<script src="{{asset('public/js/app.js')}}"></script>

<script>
    jQuery(document).ready(function () {
        App.init();
    });

    $(document).ready(function(){
        $("#store_voucher_form").on("submit", function(event){
            event.preventDefault();
            $('span.text-success').remove();
            $('span.invalid-feedback').remove();
            $('input.is-invalid').removeClass('is-invalid');
            var formData = new FormData(this);
            $.ajax({
                method: "POST",
                data: formData,
                url: '{{route('frontend.vouchers.store')}}',
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function(){
                    $(".custom-loader").removeClass('hidden');
                },
                success: function (response) {
                    if (response.status == 1) {
                        var url = '{{ route("frontend.vouchers.show", ":id") }}';
                        url = url.replace(':id', response.voucher_id);
                        window.location.href = url;
                        // $('#voucher_otp_modal').modal('toggle');
                        // $.ajax({
                        //     method: "POST",
                        //     url: '{{route('frontend.vouchers.otp')}}',
                        //     data: {
                        //         _token: $('meta[name="csrf-token"]').attr('content'),
                        //     },
                        //     success: function (response) {
                        //         // alert('success');
                        //     }
                        // });
                    }
                },
                error : function (errors) {
                    errorsGet(errors.responseJSON.errors)
                    $(".custom-loader").addClass('hidden');
                    $("#error_message").removeClass('hidden');
                }
            });
        });

        // $("#otp_verify_form").on("submit", function(event){
        //     event.preventDefault();
        //     $('span.text-success').remove();
        //     $('span.invalid-feedback').remove();
        //     $('input.is-invalid').removeClass('is-invalid');
        //     var formData = new FormData(this);
        //     $.ajax({
        //         method: "POST",
        //         data: formData,
        //         url: '{{route('frontend.vouchers.verify')}}',
        //         processData: false,
        //         contentType: false,
        //         cache: false,
        //         beforeSend: function(){
        //             $(".custom-loader").removeClass('hidden');
        //         },
        //         success: function (response) {
        //             if (response.status == 1) {
        //                 var url = '{{ route("frontend.vouchers.show", ":id") }}';
        //                 url = url.replace(':id', response.voucher.id);
        //                 window.location.href = url;
        //             } else {
        //                 notifyDialogBox('warning','Warning',response.message);
        //             }
        //         },
        //         error : function (errors) {
        //             errorsGet(errors.responseJSON.errors)
        //             $(".custom-loader").addClass('hidden');
        //             $("#error_message").removeClass('hidden');
        //         }
        //     });
        // });
    });

    $('#dateOfBirth').datetimepicker({
        size: 'large',
        format: 'DD-MM-YYYY',
    });
</script>

@endsection
