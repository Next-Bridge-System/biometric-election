@extends('layouts.admin')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Lower Court Applications</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        @if (permission('add-lower-court'))
                        <a href="{{route('lower-court.initial-step')}}" class="btn btn-success float-right m-1"><i
                                class="fas fa-plus mr-1" aria-hidden="true"></i>Add Lower Court</a>
                        @endif
                        @include('admin.lower-court.partials.quick-create')
                    </li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Lower Court Applications List (Total Lower Court Applications :
                            <span id="countTotal">0</span>)
                        </h3>

                        @include('admin.lower-court.partials.excel-import')

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @include('admin.lower-court.partials.search-filters')

                        <div class="table-responsive">
                            <table id="applications_table"
                                class="table table-bordered table-striped table-sm text-center text-uppercase">
                                <thead class="text-sm">
                                    <tr class="bg-success">
                                        <th>App No</th>
                                        <th>Photo</th>
                                        {{-- <th>Ledger No</th> --}}
                                        {{-- <th>License No</th> --}}
                                        <th>Name</th>
                                        <th>Father/Husband</th>
                                        <th class="px-5">CNIC</th>
                                        <th class="px-5">DOB</th>
                                        {{-- <th class="px-4">Enr Date</th> --}}
                                        {{-- <th class="px-4">BF No</th> --}}
                                        <th class="px-5">RCPT</th>
                                        <th>Mobile</th>
                                        <th>User ID</th>
                                        <th>Created By</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>

@endsection

@section('scripts')

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{asset('public/js/app.js')}}"></script>

<script>
    $(document).ready(function () {
        var url = '{{ route("lower-court.index", ":slug") }}';
                url = url.replace(':slug', "{{$slug}}");

        table = $('#applications_table').DataTable({
            processing: true,
            serverSide: true,
            "responsive": true,
            "autoWidth": false,
            "searching": true,
            ajax: {
                url: url,
                data: function (d) {
                    d.application_date = $('#application_date').val(),
                    d.application_date_range = $('#custom_date_range_input').val(),
                    d.application_submitted_by = $('#application_submitted_by').val(),
                    d.payment_status = $('#payment_status').val(),
                    d.payment_type = $('#payment_type').val(),
                    d.bar_id = $('#bar_id').val(),
                    d.app_system_status = $('#app_system_status').val(),
                    d.search = $('input[type="search"]').val(),
                    d.find_by = $('#find_by').val(),
                    d.age_from = $('#age_from').val(),
                    d.age_to = $('#age_to').val(),
                    d.university = $('#university').val(),
                    d.find_data = $('#find_data').val()
                }
            },
            order:[[0,"desc"]],
            columns: [
                {data: 'id', name: 'id', orderable: false, searchable: false},
                {data: 'photo', name: 'photo', orderable: false, searchable: false},
                // {data: 'reg_no_lc', name: 'reg_no_lc', orderable: false, searchable: false},
                // {data: 'license_no_lc', name: 'license_no_lc', orderable: false, searchable: false},
                {data: 'lawyer_name', name: 'lawyer_name', orderable: false, searchable: false},
                {data: 'father_name', name: 'father_name', orderable: false, searchable: false},
                {data: 'cnic_no', name: 'cnic_no', orderable: false, searchable: false},
                {data: 'dob', name: 'dob', orderable: false, searchable: false},
                // {data: 'enr_date_lc', name: 'enr_date_lc', orderable: false, searchable: false},
                // {data: 'bf_no_lc', name: 'bf_no_lc', orderable: false, searchable: false},
                {data: 'rcpt', name: 'rcpt',orderable: false, searchable: false},
                {data: 'mobile_no', name: 'mobile_no', orderable: false, searchable: false},
                {data: 'user_id', name: 'user_id', orderable: false, searchable: false},
                {data: 'created_by', name: 'created_by',orderable: false, searchable: false},
                {data: 'app_status', name: 'app_status',orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            drawCallback: function (response) {
                $('#countTotal').empty();
                $('#countTotal').append(response['json'].recordsTotal);
            }
        });
    });
</script>

<script>
    jQuery(document).ready(function () {
        App.init();
    });

    $(function() {
      $('#custom_date_range_input').daterangepicker({
        opens: 'left'
      }, function(start, end, label) {
        console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
      });
    });

    $("#application_date").change(function (e) {
        e.preventDefault();
        var option = $(this).val();
        if (option == 5) {
            $("#custom_date_range").removeClass('hidden');
            $("#custom_date_range_input").attr('disabled', false);
        } else {
            $("#custom_date_range").addClass('hidden');
            $("#custom_date_range_input").attr('disabled', true);
        }
    });

    var table;
    $(document).ready(function () {
        $('#application_date').change(function(){
            table.draw();
            $("#app_date").val($(this).val());
        });

        $('#custom_date_range_input').change(function(){
            table.draw();
            $("#app_date_range").val($(this).val());
        });

        $('#application_submitted_by').change(function(){
            table.draw();
            $("#app_type").val($(this).val());
        });

        $('#payment_status').change(function(){
            table.draw();
            $("#app_type").val($(this).val());
        });

        $('#payment_type').change(function(){
            table.draw();
            $("#app_type").val($(this).val());
        });

        $('#bar_id').change(function(){
            table.draw();
            $("#app_type").val($(this).val());
        });

        $('#app_system_status').change(function(){
            table.draw();
            $("#app_type").val($(this).val());
        });

        $('#find_by').change(function(){
            table.draw();
            $("#app_date").val($(this).val());
        });

        $('#find_data').keyup(function(){
            table.draw();
            $("#app_date").val($(this).val());
        });

        $('#find_data').change(function(){
            table.draw();
            $("#app_date").val($(this).val());
        });

        $('#university').change(function(){
            table.draw();
            $("#app_type").val($(this).val());
        });

        $('#age_from,#age_to').keyup(function () {
                table.draw();
        });
    });

    $('#userRegistrationForm').on('submit',function (event){
        event.preventDefault();
        console.log(event.target.dataset.target);
        url = event.target.dataset.target;
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
            beforeSend: function(){
                $(".save_btn").hide();
                $(".save_btn_loader").show();
            },
            success: function (response) {
                $('#addIntimationApplication').modal('hide');
                $(".custom-loader").removeClass('hidden');
                setTimeout(function (){
                    location.href = response.url
                },800)
            },
            error : function (errors) {
                $(".save_btn").show();
                $(".save_btn_loader").hide();
                $(".custom-loader").addClass('hidden');
                errorsGet(errors.responseJSON.errors)
            }
        });
    })

    function reports() {
        $("#report_application_type").val($("#application_type").val());
        $("#report_application_date").val($("#application_date").val());
        $("#report_application_date_range").val($("#application_date_range").val());
        $("#application_reports_pdf_form").submit();
    }

    $("#lc_excel_import_form").on("submit", function(event){
        event.preventDefault();
        $('span.text-success').remove();
        $('span.invalid-feedback').remove();
        $('input.is-invalid').removeClass('is-invalid');
        var formData = new FormData(this);
        $.ajax({
            method: "POST",
            data: formData,
            url: '{{route('lower-court.excel.import')}}',
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function(){
                $(".save_btn").hide();
                $(".loading_btn").removeClass('hidden');
            },
            success: function (response) {
                if (response.status == 1) {
                    location.reload();
                }
            },
            error : function (errors) {
                Swal.fire('INVALID EXCEL IMPORT FORMAT',errors.responseJSON.message,'error')
                $(".save_btn").show();
                $(".loading_btn").addClass('hidden');
            }
        });
    });

    $("#lc_quick_create_form").on("submit", function(event){
        event.preventDefault();
        $('span.text-success').remove();
        $('span.invalid-feedback').remove();
        $('input.is-invalid').removeClass('is-invalid');
        var formData = new FormData(this);
        $.ajax({
            method: "POST",
            data: formData,
            url: '{{route('lower-court.quick-create')}}',
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
            },
            success: function (response) {
                swal.fire({
                title: "Record Created Successfully!",
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

    function rcptDate(event, token){
        Swal.fire({
            title: 'Lower Court Application: ' + token,
            icon: 'warning',
            text: "Note: Please confirm before proceeding because this action can't be revertable!",
            showCancelButton: true,
            confirmButtonColor: 'green',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Confirm it!'
        }).then((result) => {
        if (result.value) {
                $.get(event.dataset.action, function (data, status) {
                $('#applications_table').DataTable().ajax.reload(null, false)
            });
        }
        })
    }
</script>

<script>
    $('#find_by').change(function (e) {
        var find_by = $(this).val();
        if (find_by == 'dob' || find_by == 'enr_date') {
            $("#find_data").attr('type', 'date');
        } else {
            $("#find_data").attr('type', 'text');
        }
    });
</script>
@endsection
