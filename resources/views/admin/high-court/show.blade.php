@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="{{URL::asset('public/admin/plugins/summernote/summernote-bs4.css')}}">
@endsection

@section('content')
<section class="content">
    <div class="container">
        <div class="row mb-2">
            <div class="col-md-12">
                @if (permission('delete-high-court'))
                <button type="button" id="hc_permanent_delete" class="btn btn-danger float-right btn-sm m-1"><i
                        class="fas fa-remove mr-1 " aria-hidden="true"></i>Permanent Delete</button>
                @endif

                <a href="{{route('high-court.index','all')}}" class="btn btn-secondary btn-sm float-right m-1"><i
                        class="fas fa-list mr-1 " aria-hidden="true"></i>High Court List</a>

                @if ($application->is_final_submitted == 1)
                @include('admin.high-court.partials.prints')
                @include('admin.high-court.partials.notes')
                @include('admin.high-court.partials.objections')
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">High Court Application</h3>
                    </div>
                    <div class="card-body">
                        @include('admin.high-court.partials.application-section')
                        @include('admin.high-court.partials.payment-section')
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script src="{{asset('public/js/app.js')}}"></script>
<script>
    $(".custom-loader").removeClass('hidden');

    jQuery(document).ready(function () {
        App.init();
    });

    $(document).ready(function() {

        $("#hc_permanent_delete").on("click", function(event){
            Swal.fire({
            title: 'Are you sure you want to permanently delete all the record of lower court application? This action cannot be changed or undone.',
            showCancelButton: true,
            icon: 'error',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes! Delete it.'
            }).then((result) => {
            if (result.value) {
            $.ajax({
            method: "GET",
            data: {
            'high_court_id': '{{$application->id}}',
            },
            url: '{{route('high-court.permanent-delete', $application->id)}}',
            beforeSend: function(){
            $(".custom-loader").removeClass('hidden');
            },
            success: function (response) {
            if (response.status == 1) {
            window.location.href = '{{route('admin.dashboard')}}';
            }
            },
            error : function (errors) {
            Swal.fire('Operation Failed!', 'Please try again later','warning')
            $(".custom-loader").addClass('hidden');
            }
            });
            }
            })
        });

        $("#hc_app_status_form").on("submit", function(event){
            event.preventDefault();
            $('span.text-success').remove();
            $('span.invalid-feedback').remove();
            $('input.is-invalid').removeClass('is-invalid');
            var application_status = $("button[type=submit]:focus").val();

            /* TOastr */
            Swal.fire({
            title: 'Are you sure you want to change status of this application?',
            showCancelButton: true,
            confirmButtonColor: '#008000',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes! Confirm it.',
            icon:'warning'
            }).then((result) => {
            console.log(result)
            if (result.value) {
            $.ajax({
            method: "POST",
            data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            'application_status': application_status,
            },
            url: '{{route('high-court.status', $application->id)}}',
            beforeSend: function(){
            $(".custom-loader").removeClass('hidden');
            },
            success: function (response) {
            if (response.status == 1) {
            window.location.reload();
            }
            },
            error : function (errors) {
            Swal.fire(errors.responseJSON.title,errors.responseJSON.message,'warning')
            $(".custom-loader").addClass('hidden');
            }
            });
            }
            })
        });

        $("#hc_update_app_status_form").on("submit", function(event){
            event.preventDefault();
            var high_court_id = '{{$application->id}}';
            var app_status = $("#app_status").val();
            var app_status_reason = $("#app_status_reason").val();
            $.ajax({
            method: "POST",
            data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            'high_court_id': high_court_id,
            'app_status': app_status,
            'app_status_reason': app_status_reason,
            },
            url: '{{route('high-court.status.update')}}',
            beforeSend: function(){
            $(".custom-loader").removeClass('hidden');
            },
            success: function (response) {
            window.location.reload();
            },
            error : function (errors) {
            Swal.fire(errors.responseJSON.title,errors.responseJSON.message,'warning')
            $(".custom-loader").addClass('hidden');
            }
            });
        });

        $("#notes_form").on("submit", function(event){
            event.preventDefault();
            var high_court_id = '{{$application->id}}';
            var notes = $("#hc_notes").val();
            $.ajax({
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'high_court_id': high_court_id,
                    'notes': notes,
                },
                url: '{{route('high-court.notes')}}',
                beforeSend: function(){
                    $(".custom-loader").removeClass('hidden');
                },
                success: function (response) {
                    $('#notes_modal').modal('toggle');
                    Swal.fire('Save Successfully!','Notes have been saved successfully.','success').then(function() {
                        $(".custom-loader").removeClass('hidden');
                        window.location.reload();
                    });
                },
                error : function (errors) {
                    $(".custom-loader").addClass('hidden');
                    $("#notes_error").remove();
                    $("#notes").append("<span class='text-danger text-capitalize' id='notes_error'>*"+errors.responseJSON.errors.notes+'</span>');
                }
            });

        });

        $('#hc_date').datetimepicker({
            size: 'large',
            format: 'DD-MM-YYYY',
        });

        $("#hc_date_form").on("submit", function(event){
            event.preventDefault();
            var hc_date = $(".hc_date").val();
            var high_court_id = '{{$application->id}}';

            $.ajax({
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'hc_date': hc_date,
                    'high_court_id': high_court_id,
                },
                url: '{{route('high-court.hc-date.update')}}',
                beforeSend: function(){
                    $(".custom-loader").removeClass('hidden');
                },
                success: function (response) {
                    window.location.reload();
                },
                error : function (errors) {
                    $(".custom-loader").addClass('hidden');
                }
            });

        });

        $("#enr_date_lc_form").on("submit", function(event){
            event.preventDefault();
            var enr_date_lc = $(".enr_date_lc").val();
            var high_court_id = '{{$application->id}}';

            $.ajax({
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'enr_date_lc': enr_date_lc,
                    'high_court_id': high_court_id,
                },
                url: '{{route('high-court.lc-date.update')}}',
                beforeSend: function(){
                    $(".custom-loader").removeClass('hidden');
                },
                success: function (response) {
                    window.location.reload();
                },
                error : function (errors) {
                    $(".custom-loader").addClass('hidden');
                }
            });

        });


        // LOWER COURT EXPIRY DATE FORM
        $('#lc_exp_date').datetimepicker({
            size: 'large',
            format: 'DD-MM-YYYY',
        });

        $("#lc_exp_date_form").on("submit", function(event){
            event.preventDefault();
            var lc_exp_date = $(".lc_exp_date").val();
            var high_court_id = '{{$application->id}}';

            $.ajax({
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'lc_exp_date': lc_exp_date,
                    'high_court_id': high_court_id,
                },
                    url: '{{route('high-court.lc-exp-date.update')}}',
                    beforeSend: function(){
                    $(".custom-loader").removeClass('hidden');
                },
                    success: function (response) {
                    window.location.reload();
                },
                error : function (errors) {
                    $(".custom-loader").addClass('hidden');
                }
            });

        });

        $('.textarea').summernote({
            height: 350,
        });

        $("#lc_update_form").on("submit", function(event){
            event.preventDefault();
            $('span.text-success').remove();
            $('span.invalid-feedback').remove();
            $('input.is-invalid').removeClass('is-invalid');
            var formData = new FormData(this);
            $.ajax({
                method: "POST",
                data: formData,
                url: '{{route('high-court.update', $application->id)}}',
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function(){
                    $(".custom-loader").removeClass('hidden');
                },
                success: function (response) {
                    swal.fire({
                        title: "Update Successfully!",
                        icon:'success',
                        text: response.message,
                        type: "success"
                    }).then(function() {
                        window.location.reload();
                    });
                },
                error : function (errors) {
                    errorsGet(errors.responseJSON.errors)
                    $(".custom-loader").addClass('hidden');
                }
            });
        });
    });

    function deletePayment(payment_id){
        confirmDialog
            .fire({
            title: 'Payment Delete Confirmation!',
            text: 'Are you sure you want to delete this payment? This action cannot be undone.',
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
            })
            .then((result) => {
            if (result.value) {
                $.ajax({
                    method: "POST",
                    url: '{{route('high-court.delete-payment')}}',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        'payment_id': payment_id,
                        'high_court_id': '{{$application->id}}',
                    },
                    beforeSend: function(){
                        $(".custom-loader").removeClass('hidden');
                    },
                    success: function (response) {
                        location.reload();
                    }
                });
            }
        });
    }

    $(".assign_member_form").on("submit", function(event){
        event.preventDefault();
        $('span.text-success').remove();
        $('span.invalid-feedback').remove();
        $('input.is-invalid').removeClass('is-invalid');
        var formData = new FormData(this);
        $.ajax({
            method: "POST",
            data: formData,
            url: '{{route('high-court.assign-member')}}',
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
            },
            success: function (response) {
               location.reload();
            },
            error : function (errors) {
                errorsGet(errors.responseJSON.errors)
                $(".custom-loader").addClass('hidden');
            }
        });
    });

    $(".edit_member_form").on("submit", function(event){
        event.preventDefault();
        $('span.text-success').remove();
        $('span.invalid-feedback').remove();
        $('input.is-invalid').removeClass('is-invalid');
        var formData = new FormData(this);
        $.ajax({
                method: "POST",
                data: formData,
                url: '{{route('high-court.assign-member.update')}}',
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
            },
            success: function (response) {
                location.reload();
            },
            error : function (errors) {
                errorsGet(errors.responseJSON.errors)
                $(".custom-loader").addClass('hidden');
            }
        });
    });

    $(".assign_code_verification_form").on("submit", function(event){
        event.preventDefault();
        $('span.text-success').remove();
        $('span.invalid-feedback').remove();
        $('input.is-invalid').removeClass('is-invalid');
        var formData = new FormData(this);
        $.ajax({
            method: "POST",
            data: formData,
            url: '{{route('high-court.assign-code-verification')}}',
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
            },
            success: function (response) {
               if (response.status == true) {
                    swal.fire({
                    title: "Code Matched!",
                    icon:'success',
                    text: response.message,
                    type: "success"
                    }).then(function() {
                        window.location.reload();
                    });
               }
               if (response.status == false) {
                $(".custom-loader").addClass('hidden');
                    Swal.fire('Not Matched!',response.message,'warning')
                }
            },
            error : function (errors) {
                errorsGet(errors.responseJSON.errors)
                $(".custom-loader").addClass('hidden');
            }
        });
    });

    $(document).ready(function() {

        $("#hcr_no_hc_form").on("submit", function(event){
            event.preventDefault();
            var hc_number = $("#hcr_no_hc").val();
            var type = 'hcr_no_hc';
            updateNumber(hc_number, type)
        });

        $("#license_no_hc_form").on("submit", function(event){
            event.preventDefault();
            var hc_number = $("#license_no_hc").val();
            var type = 'license_no_hc';
            updateNumber(hc_number, type)
        });

        $("#bf_no_hc_form").on("submit", function(event){
            event.preventDefault();
            var hc_number = $("#bf_no_hc").val();
            var type = 'bf_no_hc';
            updateNumber(hc_number, type)
        });

        $("#lc_lic_form").on("submit", function(event){
            event.preventDefault();
            var hc_number = $("#lc_lic").val();
            var type = 'lc_lic';
            updateNumber(hc_number, type)
        });

        $("#lc_ledger_form").on("submit", function(event){
            event.preventDefault();
            var hc_number = $("#lc_ledger").val();
            var type = 'lc_ledger';
            updateNumber(hc_number, type)
        });

        $("#plj_no_hc_form").on("submit", function(event){
            event.preventDefault();
            var hc_number = $("#plj_no_hc").val();
            var type = 'plj_no_hc';
            updateNumber(hc_number, type)
        });

        $("#gi_no_hc_form").on("submit", function(event){
            event.preventDefault();
            var hc_number = $("#gi_no_hc").val();
            var type = 'gi_no_hc';
            updateNumber(hc_number, type)
        });

        $("#reg_no_hc_form").on("submit", function(event){
            event.preventDefault();
            var hc_number = $("#reg_no_hc").val();
            var type = 'reg_no_hc';
            updateNumber(hc_number, type)
        });

        $("#bf_ledger_no_form").on("submit", function(event){
            event.preventDefault();
            var hc_number = $("#bf_ledger_no").val();
            var type = 'bf_ledger_no';
            updateNumber(hc_number, type)
        });

        function updateNumber(hc_number, type)
        {
            $('span.text-success').remove();
            $('span.invalid-feedback').remove();
            $('input.is-invalid').removeClass('is-invalid');

            $.ajax({
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'hc_number': hc_number,
                    'application_id': '{{$application->id}}',
                    'type': type,
                },
                url: '{{route('high-court.number.update')}}',
                beforeSend: function(){
                    $(".custom-loader").removeClass('hidden');
                },
                success: function (response) {
                    location.reload();
                },
                error : function (errors) {
                    if (errors.responseJSON.status == 0 ) {
                    Swal.fire('Error!',errors.responseJSON.message,'error');
                }
                errorsGet(errors.responseJSON.errors)
                    $(".custom-loader").addClass('hidden');
                }
            });
        }

        $(".hc_number_btn").click(function (e) {
            e.preventDefault();
            $('span.text-success').remove();
            $('span.invalid-feedback').remove();
            $('input.is-invalid').removeClass('is-invalid');
        });

        $('#rcpt_date').datetimepicker({
            size: 'large',
            format: 'DD-MM-YYYY',
        });

        $(".rcpt_current_date_form").on("submit", function(event){
            event.preventDefault();

            var url = '{{ route("high-court.rcpt-date") }}';
            var rcpt_date = $(".rcpt_date").val();
            var high_court_id = '{{$application->id}}';

            $.ajax({
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'high_court_id': high_court_id,
                    'rcpt_date': rcpt_date,
                },
                url: url,
                beforeSend: function(){
                    $(".custom-loader").removeClass('hidden');
                },
                success: function (response) {
                    window.location.reload();
                },
                error : function (errors) {
                    Swal.fire('Failed!','Operation failed to perform. Please try again later.','error');
                    $(".custom-loader").addClass('hidden');
                }
            });
        });

    });

    $("#reset-payments").on("click", function(event){
        Swal.fire({
            title: 'Are you sure you want to reset all the payments of lower court application? This action cannot be changed or undone.',
            showCancelButton: true,
            icon: 'error',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes! Reset it.'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                        method: "GET",
                        data: {
                        'high_court_id': '{{$application->id}}',
                    },
                    url: '{{route('high-court.reset-payments', $application->id)}}',
                    beforeSend: function(){
                        $(".custom-loader").removeClass('hidden');
                    },
                    success: function (response) {
                        if (response.status == 1) {
                            window.location.reload();
                        }
                    },
                    error : function (errors) {
                        Swal.fire('Operation Failed!', 'Please try again later','warning')
                        $(".custom-loader").addClass('hidden');
                    }
                });
            }
        })
    });

    $("#hc_objections").on("submit", function(event){
        event.preventDefault();
        $('span.text-success').remove();
        $('span.invalid-feedback').remove();
        $('input.is-invalid').removeClass('is-invalid');

        var formData = new FormData(this);
        var url = '{{ route("high-court.objections", ":id") }}';
        url = url.replace(':id', '{{$application->id}}');

        $.ajax({
            method: "POST",
            data: formData,
            url: url,
            processData: false,
            contentType: false,
            cache: false,
                beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
            },
            success: function (response) {
                window.location.reload();
            },
            error : function (errors) {
                errorsGet(errors.responseJSON.errors)
                $(".custom-loader").addClass('hidden');
                $("#error_message").removeClass('hidden');
            }
        });
    });

    $("#plj_br_form").on("submit", function(event){
        event.preventDefault();
        $('span.text-success').remove();
        $('span.invalid-feedback').remove();
        $('input.is-invalid').removeClass('is-invalid');

        var formData = new FormData(this);
        var url = '{{ route("high-court.plj-br", ":id") }}';
        url = url.replace(':id', '{{$application->id}}');

        $.ajax({
            method: "POST",
            data: formData,
            url: url,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
                $('button').attr('disabled', true);
                $('button').append('<span class="spinner-border spinner-border-sm ml-1" role="status" aria-hidden="true"></span>');
            },
            success: function (response) {
                window.location.reload();
            },
            error : function (errors) {
                errorsGet(errors.responseJSON.errors)
                $(".custom-loader").addClass('hidden');
                $("#error_message").removeClass('hidden');
            }
        });
    });

    $("#remove_plj_br").on("click", function(event){
        Swal.fire({
            title: 'Are you sure you want to remove PLJ Blood Relation? This action cannot be changed or undone.',
            showCancelButton: true,
            icon: 'error',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes! Remove it.'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    method: "GET",
                    data: {
                    'high_court_id': '{{$application->id}}',
                    },
                    url: '{{route('high-court.plj-br', $application->id)}}',
                    beforeSend: function(){
                        $(".custom-loader").removeClass('hidden');
                    },
                    success: function (response) {
                    if (response.status == 1) {
                        window.location.reload();
                    }
                    },
                    error : function (errors) {
                        Swal.fire('Operation Failed!', 'Please try again later','warning')
                        $(".custom-loader").addClass('hidden');
                    }
                });
            }
        })
    });
</script>

<script src="{{URL::asset('public/admin/plugins/summernote/summernote-bs4.min.js')}}"></script>

<script>
    $( window ).on( "load", function() {
        $(".custom-loader").addClass('hidden');
    });
</script>
@endsection