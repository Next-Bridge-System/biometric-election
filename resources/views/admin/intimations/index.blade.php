@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Intimations</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        @if (Auth::guard('admin')->user()->hasPermission('add-intimations'))
                        @include('admin.intimations.partials.register-modal')
                        @endif
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
                            Intimation List (Total Intimation : <span id="countTotal">0</span>)
                        </h3>

                        {{-- @if (Auth::guard('admin')->user()->hasPermission('intimation-reports'))
                        <form action="{{route('intimations.reports.pdf')}}" method="post"
                        id="application_reports_pdf_form"> @csrf
                        <input type="hidden" name="report_application_type" id="report_application_type">
                        <input type="hidden" name="report_application_date" id="report_application_date">
                        <input type="hidden" name="report_application_date_range" id="report_application_date_range">
                        <button type="button" class="btn btn-primary btn-sm float-right m-1" onclick="reports()">Reports
                            PDF</button>
                        </form>
                        @endif --}}

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="{{route('intimations.index', $slug)}}" method="POST" id="search_application_form">
                            @csrf
                            <div class="row align-items-center mb-2">
                                <div class="col-md-4 form-group">
                                    <label><strong>Application Date :</strong></label>
                                    <select class="form-control custom-select" id="application_date">
                                        <option value="">--Select Date--</option>
                                        <option value="1">Today</option>
                                        <option value="2">Yesterday</option>
                                        <option value="3">Last 7 Days</option>
                                        <option value="4">Last 30 Days</option>
                                        <option value="5">Custom Date Range</option>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group hidden" id="custom_date_range">
                                    <label>Custom Date Range:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control float-right" id="custom_date_range_input"
                                            value="{{date('m')}}/01/{{date('Y')}} - {{date('m')}}/20/{{date('Y')}}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label><strong>Application Submitted By :</strong></label>
                                    <select class="form-control custom-select" id="application_submitted_by">
                                        <option value="" selected>--Select Submitted By--</option>
                                        <option value="frontend">Online</option>
                                        <option value="operator">Operator</option>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label><strong>Admins :</strong></label>
                                    <select class="form-control custom-select" id="intimation_operator">
                                        <option value="">--Select Operator--</option>
                                        @foreach ($operators as $operator)
                                        <option value="{{$operator->id}}">{{$operator->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label><strong>Payment Status :</strong></label>
                                    <select class="form-control custom-select" id="payment_status">
                                        <option value="" selected>--Select Payment Status--</option>
                                        <option value="paid">Paid</option>
                                        <option value="unpaid">Unpaid</option>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label><strong>Payment Type :</strong></label>
                                    <select class="form-control custom-select" id="payment_type">
                                        <option value="" selected>--Select Type--</option>
                                        <option value="online">Online</option>
                                        <option value="operator">Operator</option>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <div class="row">
                                        <div class="col"><label><strong>Age From:</strong></label>
                                            <input type="text" name="age_from" id="age_from" class="form-control" placeholder="Age From">
                                        </div>
                                        <div class="col"><label><strong>Age To:</strong></label>
                                            <input type="text" name="age_to" id="age_to" class="form-control" placeholder="Age To">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-4 form-group">
                                    <label><strong>University :</strong></label>
                                    <select class="form-control custom-select" id="university">
                                        <option value="" selected>--Select University--</option>
                                        @foreach ($universities as $university)
                                        <option value="{{$university->id}}">{{$university->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table id="applications_table"
                                class="table table-bordered table-striped table-sm text-center">
                                <thead>
                                    <tr>
                                        <th>App No.</th>
                                        <th>User ID</th>
                                        <th>Lawyer Name</th>
                                        <th>Father Name</th>
                                        <th>CNIC</th>
                                        <th>Mobile</th>
                                        <th>App Status</th>
                                        <th>Payment</th>
                                        <th>Submitted By</th>
                                        <th>RCPT</th>
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

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{asset('public/js/app.js')}}"></script>

<script>
    $(document).ready(function () {
        var url = '{{ route("intimations.index", ":slug") }}';
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
                    d.intimation_operator = $('#intimation_operator').val(),
                    d.payment_status = $('#payment_status').val(),
                    d.payment_type = $('#payment_type').val(),
                    d.age_from = $('#age_from').val(),
                    d.age_to = $('#age_to').val(),
                    d.university = $('#university').val(),
                    d.search = $('input[type="search"]').val()
                }
            },
            order:[[1,"desc"]],
            columns: [
                {data: 'application_token_no', name: 'application_token_no'},
                {data: 'user_id', name: 'user_id'},
                {data: 'name', name: 'name'},
                {data: 'so_of', name: 'so_of'},
                {data: 'cnic_no', name: 'cnic_no'},
                {data: 'active_mobile_no', name: 'active_mobile_no'},
                {data: 'application_status', name: 'application_status',orderable: false, searchable: false},
                {data: 'payment_status', name: 'payment_status',orderable: false, searchable: false},
                {data: 'submitted_by', name: 'submitted_by',orderable: false, searchable: false},
                {data: 'rcpt', name: 'rcpt',orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            drawCallback: function (response) {
                $('#countTotal').empty();
                $('#countTotal').append(response['json'].recordsTotal);
            }
        });
    });

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

        $('#intimation_operator').change(function(){
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

        $('#university').change(function(){
            table.draw();
            $("#app_type").val($(this).val());
        });

        $('#age_from,#age_to').keyup(function () {
                table.draw();
                $("#app_type").val($(this).val());
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
                if (errors.responseJSON.message) {
                    Swal.fire('Warning!',errors.responseJSON.message,'warning')
                }
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

    // function rcptDate(event, token){
    //     Swal.fire({
    //             title: 'Application: ' + token,
    //             icon: 'warning',
    //             text: "Note: Please confirm before proceeding because this action can't be revertable!",
    //             showCancelButton: true,
    //             confirmButtonColor: 'green',
    //             cancelButtonColor: '#3085d6',
    //             confirmButtonText: 'Yes, Confirm it!'
    //         }).then((result) => {
    //             if (result.value) {
    //                 $.get(event.dataset.action, function (data, status) {
    //                 $('#applications_table').DataTable().ajax.reload(null, false)
    //             });
    //         }
    //     })
    // }

    function rcptDate(event, token){
        Swal.fire({
        title: 'Intimation Application: ' + token,
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

@endsection
