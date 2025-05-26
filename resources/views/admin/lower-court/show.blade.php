@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="{{URL::asset('public/admin/plugins/summernote/summernote-bs4.css')}}">
@endsection

@section('content')
<section class="content">
    <div class="container">
        <div class="row mb-2">
            <div class="col-md-12">

                @if (permission('delete-lower-court'))
                <button type="button" id="permanent-delete-lc" class="btn btn-danger float-right btn-sm m-1"><i
                        class="fas fa-remove mr-1 " aria-hidden="true"></i>Permament Delete</button>
                @endif

                <a href="{{route('lower-court.index','final')}}" class="btn btn-secondary btn-sm float-right m-1"><i
                        class="fas fa-list mr-1 " aria-hidden="true"></i>Lower Court Listing</a>

                @if ($application->is_excel == 1 || $application->quick_created == 1)
                @if (permission('lc_quick_edit'))
                @include('admin.lower-court.partials.edit')
                @endif
                @else
                @if (permission('edit-lower-court'))
                <a href="{{route('lower-court.create-step-1',$application->id)}}"
                    class="btn btn-warning btn-sm float-right m-1">
                    <i class="fas fa-edit mr-1" aria-hidden="true"></i>Edit</a>
                @endif
                @endif

                @if ($application->is_final_submitted == 1)

                @if (permission('lower-court-activity-log'))
                <a href="{{route('activity-log.lower-court-index', $application->user_id)}}"
                    class="btn btn-info btn-sm float-right m-1">
                    <i class="fas fa-folder mr-1" aria-hidden="true"></i>Log</a>
                @endif

                @endif

            </div>

            <div class="col-md-12">
                @if ($application->is_final_submitted == 1)
                <section>
                    @include('admin.lower-court.partials.assign-member')
                    @include('admin.lower-court.partials.send-messages')
                    @include('admin.lower-court.partials.additional-notes')
                    @include('admin.lower-court.partials.objections')
                    @include('admin.lower-court.partials.plj-blood-relation')
                    @include('admin.lower-court.partials.prints')
                </section>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Lower Court Application</h3>
                    </div>
                    <div class="card-body">
                        @include('admin.lower-court.partials.application-section')
                        @include('admin.lower-court.partials.interview-section')
                        @include('admin.lower-court.partials.payment-section')
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    $(".custom-loader").removeClass('hidden');
</script>
<script src="{{asset('public/js/app.js')}}"></script>
<script>
    jQuery(document).ready(function () {
        App.init();
    });

    $("#permanent-delete-lc").on("click", function(event){
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
                    'lc_id': '{{$application->id}}',
                },
                url: '{{route('lower-court.permanent-delete', $application->id)}}',
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

    $("#lc_exemption_btn").on("click", function(event){
        Swal.fire({
            title: 'Are you sure you want to apply exemption for high court application?',
            showCancelButton: true,
            icon: 'warning',
            confirmButtonColor: 'green',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes! Apply it.'
        }).then((result) => {
        if (result.value) {
            $.ajax({
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'lc_id': '{{$application->id}}',
                },
                url: '{{route('lower-court.exemption')}}',
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

    $(document).ready(function() {

        $("#lc_status_form").on("submit", function(event){
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
                        url: '{{route('lower-court.status', $application->id)}}',
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

        $("#update_status_form").on("submit", function(event){
            event.preventDefault();
            var lower_court_id = '{{$application->id}}';
            var app_status = $("#app_status").val();
            var app_status_reason = $("#app_status_reason").val();
            $.ajax({
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'lower_court_id': lower_court_id,
                    'app_status': app_status,
                    'app_status_reason': app_status_reason,
                },
                url: '{{route('lower-court.status.update')}}',
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

        $("#lc_notes_form").on("submit", function(event){
            event.preventDefault();
            var lower_court_id = '{{$application->id}}';
            var notes = $("#lc_notes").val();
            $.ajax({
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'lower_court_id': lower_court_id,
                    'notes': notes,
                },
                url: '{{route('lower-court.notes')}}',
                beforeSend: function(){
                    $(".custom-loader").removeClass('hidden');
                },
                success: function (response) {
                    $('#lcNotesModal').modal('toggle');
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

        $('#lc_date').datetimepicker({
            size: 'large',
            format: 'DD-MM-YYYY',
        });

        $("#lc_date_form").on("submit", function(event){
            event.preventDefault();
            var lc_date = $(".lc_date").val();
            var lc_id = '{{$application->id}}';

            $.ajax({
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'lc_date': lc_date,
                    'lc_id': lc_id,
                },
                url: '{{route('lower-court.lc-date.update')}}',
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
                url: '{{route('lower-court.update', $application->id)}}',
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

        $(".update_voter_member_lc").on("submit", function(event){
        event.preventDefault();
        $('span.text-success').remove();
        $('span.invalid-feedback').remove();
        $('input.is-invalid').removeClass('is-invalid');

        var formData = new FormData(this);
        var url = '{{ route("lower-court.voter-member.update") }}';

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
                    url: '{{route('lower-court.delete-payment')}}',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        'payment_id': payment_id,
                        'lower_court_id': '{{$application->id}}',
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
            url: '{{route('lower-court.assign-member')}}',
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
                url: '{{route('lower-court.assign-member.update')}}',
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
            url: '{{route('lower-court.assign-code-verification')}}',
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

        $("#license_no_lc_form").on("submit", function(event){
            event.preventDefault();
            var lc_number = $("#license_no_lc").val();
            var type = 'license_no_lc';
            updateNumber(lc_number, type)
        });

        $("#bf_no_lc_form").on("submit", function(event){
            event.preventDefault();
            var lc_number = $("#bf_no_lc").val();
            var type = 'bf_no_lc';
            updateNumber(lc_number, type)
        });

        $("#plj_no_lc_form").on("submit", function(event){
            event.preventDefault();
            var lc_number = $("#plj_no_lc").val();
            var type = 'plj_no_lc';
            updateNumber(lc_number, type)
        });

        $("#gi_no_lc_form").on("submit", function(event){
            event.preventDefault();
            var lc_number = $("#gi_no_lc").val();
            var type = 'gi_no_lc';
            updateNumber(lc_number, type)
        });

        $("#reg_no_lc_form").on("submit", function(event){
            event.preventDefault();
            var lc_number = $("#reg_no_lc").val();
            var type = 'reg_no_lc';
            updateNumber(lc_number, type)
        });

        $("#bf_ledger_no_form").on("submit", function(event){
            event.preventDefault();
            var lc_number = $("#bf_ledger_no").val();
            var type = 'bf_ledger_no';
            updateNumber(lc_number, type)
        });

        function updateNumber(lc_number, type)
        {
            $('span.text-success').remove();
            $('span.invalid-feedback').remove();
            $('input.is-invalid').removeClass('is-invalid');

            $.ajax({
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'lc_number': lc_number,
                    'application_id': '{{$application->id}}',
                    'type': type,
                },
                url: '{{route('lower-court.number.update')}}',
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

        $(".lc_number_btn").click(function (e) {
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

            var url = '{{ route("lower-court.rcpt-date") }}';
            var rcpt_date = $(".rcpt_date").val();
            var lc_id = '{{$application->id}}';

            $.ajax({
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'lc_id': lc_id,
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
                        'lc_id': '{{$application->id}}',
                    },
                    url: '{{route('lower-court.reset-payments', $application->id)}}',
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

    $("#lc_objections").on("submit", function(event){
        event.preventDefault();
        $('span.text-success').remove();
        $('span.invalid-feedback').remove();
        $('input.is-invalid').removeClass('is-invalid');

        var formData = new FormData(this);
        var url = '{{ route("lower-court.objections", ":id") }}';
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
        var url = '{{ route("lower-court.plj-br", ":id") }}';
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
                    'lc_id': '{{$application->id}}',
                    },
                    url: '{{route('lower-court.plj-br', $application->id)}}',
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
    var url = '{{ route("lower-court.upload.file", ":slug") }}';
        url = url.replace(':slug', 'lc_plj_br_cnic');
    var lc_plj_br_cnic = FilePond.create(document.querySelector('input[id="lc_plj_br_cnic"]'), {
        acceptedFileTypes: ['image/png' ,'image/jpeg','image/jpg'],
        required: true,
        allowMultiple: false,
        allowFileSizeValidation: true,
        maxFileSize:'1MB',
        allowRevert: true,
        fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
            resolve(type);
        })
    });
    lc_plj_br_cnic.setOptions({
      server: {
          url: url,
          headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
      }
    });

    var url = '{{ route("lower-court.upload.file", ":slug") }}';
        url = url.replace(':slug', 'lc_plj_br_license');
    var lc_plj_br_license = FilePond.create(document.querySelector('input[id="lc_plj_br_license"]'), {
        acceptedFileTypes: ['image/png' ,'image/jpeg','image/jpg'],
        required: true,
        allowMultiple: false,
        allowFileSizeValidation: true,
        maxFileSize:'1MB',
        allowRevert: true,
        fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
            resolve(type);
        })
    });
    lc_plj_br_license.setOptions({
      server: {
          url: url,
          headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
      }
    });
</script>

<script>
    $( window ).on( "load", function() {
        $(".custom-loader").addClass('hidden');
    });
</script>
@endsection
