@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="{{URL::asset('public/admin/plugins/summernote/summernote-bs4.css')}}">
@endsection

@section('content')
<section class="content">
    <div class="container">
        <div class="row mb-2">
            <div class="col-md-12">

                @if ($application->is_intimation_completed == 0)
                @if (Auth::guard('admin')->user()->hasPermission('edit-intimations'))
                <a href="{{route('intimations.create-step-1',$application->id)}}"
                    class="btn btn-primary btn-sm float-right m-1">
                    <i class="fas fa-edit mr-1" aria-hidden="true"></i>Edit</a>
                @endif

                @if (Auth::guard('admin')->user()->hasPermission('intimation-messages'))
                @include('admin.intimations.partials.send-messages')
                @endif

                @if (Auth::guard('admin')->user()->hasPermission('intimation-objections'))
                @include('admin.intimations.partials.objections')
                @endif

                @if (Auth::guard('admin')->user()->hasPermission('manage-police-verification'))
                <a href="{{route('police-verifications.show', $application->id)}}"
                    class="btn btn-primary btn-sm float-right m-1"><i class="far fa-copy mr-1 "
                        aria-hidden="true"></i>Police Verification</a>
                @endif

                @endif


                @if (Auth::guard('admin')->user()->hasPermission('intimation-activity-log'))
                <a href="{{route('activity-log.index', $application->user_id)}}"
                    class="btn btn-primary btn-sm float-right m-1"><i class="fas fa-folder mr-1"
                        aria-hidden="true"></i>Log</a>
                @endif

                @if (Auth::guard('admin')->user()->hasPermission('intimation-notes'))
                @include('admin.intimations.partials.additional-notes')
                @endif

                <a href="{{route('intimations.token',['download'=>'pdf','application' => $application])}}"
                    class="btn btn-primary btn-sm float-right m-1" target="_blank"><i class="fas fa-print mr-1"
                        aria-hidden="true"></i>Print Token</a>

                @if ($application->is_accepted == 1)
                <a href="{{route('frontend.intimation.pdf',['download'=>'pdf','application' => $application])}}"
                    target="_blank" class="btn btn-primary btn-sm float-right m-1"><i
                        class="fas fa-print mr-1"></i>Print Intimation Application</a>
                @endif

                @if (Auth::guard('admin')->user()->hasPermission('intimation-detail-print'))
                <a href="{{route('intimations.print-detail',['download'=>'pdf','application' => $application])}}"
                    target="_blank" class="btn btn-primary btn-sm float-right m-1"><i
                        class="fas fa-print mr-1"></i>Print Detail</a>
                @endif

                @if($application->is_intimation_completed == 1)
                <a href="{{ route('intimations.export.form-b',['application_id' => $application->id]) }}"
                    target="_blank" class="btn btn-primary btn-sm float-right m-1"><i
                        class="fas fa-print mr-1"></i>Training Certificate</a>
                @endif

                @if (isset($payments))
                @include('admin.intimations.partials.payment-vch')
                @endif

                <a href="{{route('intimations.index', ['slug' => 'final'])}}"
                    class="btn btn-primary btn-sm float-right m-1"><i class="fas fa-list mr-1 "
                        aria-hidden="true"></i>Intimation Listing</a>

                @if (permission('delete-intimations'))
                <button type="button" id="permanent-delete-intimation" class="btn btn-danger float-right btn-sm m-1"><i
                        class="fas fa-remove mr-1 " aria-hidden="true"></i>Permament Delete</button>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-{{$application->objections == NULL ? 'success': 'danger'}}">
                    <div class="card-header">
                        <h3 class="card-title">Intimation Application</h3>
                    </div>
                    <div class="card-body">
                        @include('admin.intimations.partials.application-section')
                        @include('admin.intimations.partials.payment-section')
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
    jQuery(document).ready(function () {
        App.init();
    });

    $(document).ready(function() {

        $("#intimation_app_status_form").on("submit", function(event){
            event.preventDefault();
            $('span.text-success').remove();
            $('span.invalid-feedback').remove();
            $('input.is-invalid').removeClass('is-invalid');
            var application_status = $("button[type=submit]:focus").val();

            Swal.fire({
                title: 'Are you sure you want to change status of this application?',
                showCancelButton: true,
                confirmButtonColor: '#008000',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes! Change it.'
            }).then((result) => {
                console.log(result)
                if (result.value) {
                    $.ajax({
                        method: "POST",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            'application_status': application_status,
                        },
                        url: '{{route('intimations.status', $application->id)}}',
                        beforeSend: function(){
                            $(".custom-loader").removeClass('hidden');
                        },
                        success: function (response) {
                            if (response.status == 1) {
                                window.location.reload();
                            }
                        },
                        error : function (errors) {
                            Swal.fire('Payment Verification!', errors.responseJSON.error,'warning')
                            $(".custom-loader").addClass('hidden');
                        }
                    });
                }
            })
        });

        $("#update_intimation_app_status_form").on("submit", function(event){
            event.preventDefault();
            var intimation_id = '{{$application->id}}';
            var app_status = $("#app_status").val();
            var app_status_reason = $("#app_status_reason").val();
            $.ajax({
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'intimation_id': intimation_id,
                    'app_status': app_status,
                    'app_status_reason': app_status_reason,
                },
                url: '{{route('intimations.status.update')}}',
                beforeSend: function(){
                    $(".custom-loader").removeClass('hidden');
                },
                success: function (response) {
                    window.location.reload();
                },
                error : function (errors) {
                    Swal.fire('Payment Verification!', errors.responseJSON.error,'warning')
                    $(".custom-loader").addClass('hidden');
                }
            });
        });

        $("#notes_form").on("submit", function(event){
            event.preventDefault();
            var application_id = '{{$application->id}}';
            var notes = $("#intimation_notes").val();
            $.ajax({
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'application_id': application_id,
                    'notes': notes,
                },
                url: '{{route('intimations.notes')}}',
                beforeSend: function(){
                    $(".custom-loader").removeClass('hidden');
                },
                success: function (response) {
                    $('#notesModal').modal('toggle');
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

        $('#intimation_start_date').datetimepicker({
            size: 'large',
            format: 'DD-MM-YYYY',
        });

        $("#intimation_date_form").on("submit", function(event){
            event.preventDefault();
            var intimation_start_date = $(".intimation_start_date").val();
            var application_id = '{{$application->id}}';

            $.ajax({
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    'intimation_start_date': intimation_start_date,
                    'application_id': application_id,
                },
                url: '{{route('intimations.intimation-date.update')}}',
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

        $('#rcpt_date').datetimepicker({
            size: 'large',
            format: 'DD-MM-YYYY',
        });

        $(".rcpt_current_date_form").on("submit", function(event){
                event.preventDefault();

                var url = '{{ route("intimations.rcpt-date") }}';
                var rcpt_date = $(".rcpt_date").val();
                var application_id = '{{$application->id}}';

                $.ajax({
                method: "POST",
                data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                'application_id': application_id,
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

        $('.textarea').summernote({
            height: 350,
        });

        $("#permanent-delete-intimation").on("click", function(event){
            Swal.fire({
                title: 'Are you sure you want to permanently delete all the record of intimation application? This action cannot be changed or undone.',
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
                            'application_id': '{{$application->id}}',
                        },
                        url: '{{route('intimations.permanent-delete', $application->id)}}',
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
                    url: '{{route('intimations.delete-payment')}}',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        'payment_id': payment_id,
                        'application_id': '{{$application->id}}',
                    },
                    success: function (response) {
                        location.reload();
                    }
                });
            }
        });
    }

    function acctDeptPaymentStatus(payment_id, status){
        confirmDialog
            .fire({
            title: 'Account Dept. Payment Confirmation!',
            text: 'Are you sure you want to change the status of this payment?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Confirm it!",
            })
            .then((result) => {
            if (result.value) {
                $.ajax({
                    method: "POST",
                    url: '{{route('intimations.acct-dept-payment-status')}}',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        'payment_id': payment_id,
                        'status': status,
                        'application_id': '{{$application->id}}',
                    },
                    success: function (response) {
                        location.reload();
                    }
                });
            }
        });
    }


    $("#intimation_objections").on("submit", function(event){
        event.preventDefault();
        $('span.text-success').remove();
        $('span.invalid-feedback').remove();
        $('input.is-invalid').removeClass('is-invalid');

        var formData = new FormData(this);
        var url = '{{ route("intimations.objections", ":id") }}';
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

    $(".update_academic_form").on("submit", function(event){
        event.preventDefault();
        $('span.text-success').remove();
        $('span.invalid-feedback').remove();
        $('input.is-invalid').removeClass('is-invalid');

        var formData = new FormData(this);
        var url = '{{ route("intimations.acadmic-record.update") }}';

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

    $(".university_id").change(function (e) {
        e.preventDefault();
        var value = $(this).val();
        if (value == '0') {
            $(".institute").removeClass('hidden');
        } else {
            $(".institute").addClass('hidden');
        }
    });

    $("#update_senior_lawyer_form").on("submit", function(event){
        event.preventDefault();
        $('span.text-success').remove();
        $('span.invalid-feedback').remove();
        $('input.is-invalid').removeClass('is-invalid');

        var formData = new FormData(this);
        var url = '{{ route("intimations.senior-lawyer.update") }}';

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
                $('#editSeniorLawyerModal').modal('toggle');
                $("#senior_lawyer_section_data").html(response.response_view);
                setTimeout(function() {
                    $(".custom-loader").addClass('hidden');
                }, 2000);
            },
            error : function (errors) {
                errorsGet(errors.responseJSON.errors)
                $(".custom-loader").addClass('hidden');
                $("#error_message").removeClass('hidden');
            }
        });
    });
</script>

<script src="{{URL::asset('public/admin/plugins/summernote/summernote-bs4.min.js')}}"></script>

<script>
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
            title: 'Are you sure, you want to delete this file?',
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
                    location.reload();
                });
            }
        })
    }
</script>
@endsection
