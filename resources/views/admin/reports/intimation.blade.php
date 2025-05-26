@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{__('Intimation Report')}}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            (Total List : <span id="countTotal">0</span>)
                        </h3>

                        <a href="javascript:void(0)" class="btn btn-primary btn-sm float-right m-1"
                            onclick="$('#exportButton').trigger('click')"><i class="fas fa-print mr-1"></i> Print</a>

                        <a href="javascript:void(0)" class="btn btn-success btn-sm float-right m-1"
                            onclick="$('#exportEXCEL').trigger('click')"><i class="fas fa-file-export mr-1"></i> Excel Export</a>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="{{route('reports.intimation.export')}}" method="POST"
                            id="search_application_form"> @csrf
                            <div class="row align-items-center mb-2">
                                <div class="col-md-2 form-group">
                                    <label><strong>Date Type:</strong></label>
                                    <select class="form-control custom-select" id="application_date_type"
                                        name="application_date_type">
                                        <option value="">--Select Date--</option>
                                        <option value="created_at">Created Date</option>
                                        <option value="rcpt_date">RCPT Date</option>
                                    </select>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label><strong>Date Key:</strong></label>
                                    <select class="form-control custom-select" id="application_date"
                                        name="application_date">
                                        <option value="">--Select Date--</option>
                                        <option value="today">Today</option>
                                        <option value="yesterday">Yesterday</option>
                                        <option value="last_7_days">Last 7 Days</option>
                                        <option value="last_30_days">Last 30 Days</option>
                                        <option value="date_range">Date Range</option>
                                        {{-- <option value="5">Custom Date</option> --}}
                                        {{-- <option value="7">Rcpt Date</option> --}}
                                    </select>
                                </div>
                                {{-- <div class="col-md-3 form-group hidden" id="custom_date" name="custom_date">
                                    <label>Custom Date:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control float-right" id="custom_date_input"
                                            name="custom_date_input" value="{{date('m')}}/01/{{date('Y')}}" disabled>
                                    </div>
                                </div> --}}
                                <div class="col-md-3 form-group hidden" id="custom_date_range" name="custom_date_range">
                                    <label>Date Range:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control float-right" id="custom_date_range_input"
                                            name="custom_date_range_input"
                                            value="{{date('m')}}/01/{{date('Y')}} - {{date('m')}}/20/{{date('Y')}}"
                                            disabled>
                                    </div>
                                </div>
                                {{-- <div class="col-md-2 form-group hidden" id="rcpt_date" name="rcpt_date">
                                    <label>Rcpt Date:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control float-right" id="rcpt_date_input"
                                            name="rcpt_date_input" value="{{date('m')}}/01/{{date('Y')}}" disabled>
                                    </div>
                                </div> --}}

                                {{-- <div class="col-md-2 form-group">
                                    <label><strong>Application Status</strong></label>
                                    <select class="form-control custom-select" id="application_status"
                                        name="application_status">
                                        <option value="">--Select Application Status--</option>
                                        <option value="1">Active</option>
                                        <option value="2">Suspended</option>
                                        <option value="3">Died</option>
                                        <option value="4">Removed</option>
                                        <option value="5">Transfer in</option>
                                        <option value="6">Transfer out</option>
                                    </select>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label><strong>Payment Status :</strong></label>
                                    <select class="form-control custom-select" id="payment_status"
                                        name="payment_status">
                                        <option value="" selected>--Select Payment Status--</option>
                                        <option value="paid">Paid</option>
                                        <option value="unpaid">Unpaid</option>
                                    </select>
                                </div> --}}

                                {{-- <div class="col-md-2 form-group">
                                    <label><strong>Division</strong></label>
                                    <select class="form-control custom-select" id="division_id" name="division_id">
                                        <option value="">--Select Division--</option>
                                        @foreach ($divisions as $division)
                                        <option value="{{$division->id}}">{{$division->name}}</option>
                                        @endforeach
                                    </select>
                                </div> --}}

                                <div class="col-md-2 form-group">
                                    <label><strong>Voter Member</strong></label>
                                    <select class="form-control custom-select" id="bar_id" name="bar_id">
                                        <option value="">--Select Voter member--</option>
                                        @foreach ($bars as $bar)
                                        <option value="{{$bar->id}}">{{$bar->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- <div class="col-md-2 form-group">
                                    <label><strong>University :</strong></label>
                                    <select class="form-control custom-select" id="university">
                                        <option value="" selected>--Select University--</option>
                                        @foreach ($universities as $university)
                                        <option value="{{$university->id}}">{{$university->name}}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                            </div>
                            {{-- <div class="row">
                                <div class="col-md-3 form-group">
                                    <div class="row">
                                        <div class="col"><label><strong>Age From:</strong></label>
                                            <input type="text" name="age_from" id="age_from" class="form-control"
                                                placeholder="">
                                        </div>
                                        <div class="col"><label><strong>Age To:</strong></label>
                                            <input type="text" name="age_to" id="age_to" class="form-control"
                                                placeholder="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 form-group">
                                    <label><strong>RCPT :</strong></label>
                                    <div class="row">
                                        <div class="col">
                                            <select name="application_rcpt_year" id="application_rcpt_year"
                                                class="form-control custom-select">
                                                @foreach (range(Carbon\Carbon::now()->year, 2000) as $year)
                                                <option value="{{$year}}">{{$year}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" id="application_rcpt_no_start"
                                                name="application_rcpt_no_start" placeholder="">
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" id="application_rcpt_no_end"
                                                name="application_rcpt_no_end" placeholder="">
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                            <button type="submit" class="hidden" id="exportButton" value="exportPDF"
                                name="export">asd</button>
                            <button type="submit" class="hidden" id="exportEXCEL" value="exportEXCEL"
                                name="export">asd</button>
                        </form>
                        <div class="table-responsive">
                            <table id="applications_table"
                                class="table table-bordered table-sm table-striped text-uppercase">
                                <thead>
                                    <tr>
                                        <th>SR NO.</th>
                                        <th>APP NO.</th>
                                        <th>BAR NAME</th>
                                        <th>LAWYER NAME</th>
                                        <th>FATHER NAME</th>
                                        <th>INTIMATION DATE</th>
                                        <th>MOBILE</th>
                                        <th>STATUS</th>
                                        <th>RCPT Date</th>
                                        <th>RCPT NO.</th>
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
<!-- /.content -->

@endsection

@section('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{asset('public/js/app.js')}}"></script>

<script>
    jQuery(document).ready(function () {
            App.init();
        });

        $(function () {
            $('#custom_date_range_input').daterangepicker({
                opens: 'left'
            }, function (start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            });

            $('#custom_date_input').daterangepicker({
                singleDatePicker: true,
                opens: 'left'
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            });

            $('#rcpt_date_input').daterangepicker({
                singleDatePicker: true,
                opens: 'left'
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            });
        });

        $("#application_date").change(function (e) {
            e.preventDefault();
            var option = $(this).val();

            $("#custom_date").addClass('hidden');
            $("#custom_date_input").attr('disabled', true);
            $("#custom_date_range").addClass('hidden');
            $("#custom_date_range_input").attr('disabled', true);
            $("#rcpt_date").addClass('hidden');
            $("#rcpt_date_input").attr('disabled', true);

            // if (option == 5) {
            //     $("#custom_date").removeClass('hidden');
            //     $("#custom_date_input").attr('disabled', false);
            // }

            if(option == 'date_range'){
                $("#custom_date_range").removeClass('hidden');
                $("#custom_date_range_input").attr('disabled', false);
            }

            // if(option == 7){
            //     $("#rcpt_date").removeClass('hidden');
            //     $("#rcpt_date_input").attr('disabled', false);
            // }
        });

        var table;
        $(document).ready(function () {
            table = $('#applications_table').DataTable({
                "dom": '<"toolbar">lfrtip',
                processing: true,
                serverSide: true,
                "responsive": true,
                "autoWidth": false,
                "searching": true,
                ajax: {
                    url: "{{ route('reports.intimation') }}",
                    data: function (d) {
                        d.application_status = $('#application_status').val(),
                        d.university = $('#university').val()
                        d.payment_status = $('#payment_status').val(),
                        d.bar_id = $('#bar_id').val(),
                        d.division_id = $('#division_id').val(),

                        d.application_rcpt_year = $('#application_rcpt_year').val(),
                        d.application_rcpt_no_start = $('#application_rcpt_no_start').val(),
                        d.application_rcpt_no_end = $('#application_rcpt_no_end').val(),

                        d.age_from = $('#age_from').val(),
                        d.age_to = $('#age_to').val(),
                        
                        d.application_date_type = $('#application_date_type').val(),
                        d.application_date = $('#application_date').val(),
                        d.custom_date_range_input = $('#custom_date_range_input').val()

                        // d.application_custom_date = $('#custom_date_input').val(),
                        // d.application_rcpt_date = $('#rcpt_date_input').val()
                    }
                },
                order: [[2, "desc"]],
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'app_token_no', name: 'application_token_no'},
                    {data: 'bar_name', name: 'bar_name'},
                    {data: 'user_name', name: 'user_name'},
                    {data: 'app_father_husband', name: 'app_father_husband'},
                    {data: 'intimation_start_date', name: 'intimation_start_date'},
                    {data: 'user_phone', name: 'user_phone'},
                    {data: 'status', name: 'status'},
                    {data: 'app_rcpt_date', name: 'app_rcpt_date'},
                    {data: 'app_rcpt_no', name: 'app_rcpt_no'},
                ],
                drawCallback: function (response) {
                    $('#countTotal').empty();
                    $('#countTotal').append(response['json'].recordsTotal);
                }
            });

            $('#application_date_type').change(function () {
                table.draw();
                $("#app_type").val($(this).val());
            });

            $('#application_date').change(function () {
                table.draw();
                $("#app_date").val($(this).val());
            });
            
            // $('#application_date').change(function () {
            //     table.draw();
            //     $("#app_date").val($(this).val());
            // });

            $('#custom_date_range_input').change(function () {
                table.draw();
                $("#app_date_range").val($(this).val());
            });

            // $('#application_operator').change(function () {
            //     table.draw();
            //     $("#application_operator").val($(this).val());
            // });


            // $('#application_rcpt_no_start,#application_rcpt_no_end,#age_from,#age_to').keyup(function () {
            //     table.draw();
            // });

            $('#application_status, #payment_status, #card_status, #bar_id,#university').change(function () {
                table.draw();
            });
        });

    $('#division_id').change(function () {
        table.draw();
        let division_id = $(this).find(":selected").val();
        var option = '';
        $.ajax({
            method: "POST",
            url: '{{route('getBarsByDivision')}}',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                'division_id': division_id
            },
            success: function (response) {
                $('#bar_id').empty();
                $('#bar_id').append(' <option value="" selected>--Select Station --</option>');
                response.bars.forEach(function (item, index) {
                    option = "<option value='" + item.id + "'>" + item.name + "</option>"
                    $('#bar_id').append(option);
                });
            }
        });

    })

    function reports() {
        // $("#report_application_type").val($("#application_type").val());
        // $("#report_application_date").val($("#application_date").val());
        // $("#report_application_date_range").val($("#application_date_range").val());
        // $("#report_application_rcpt_year").val($("#application_rcpt_year").val());
        // $("#report_application_rcpt_no_start").val($("#application_rcpt_no_start").val());
        // $("#report_application_rcpt_no_end").val($("#application_rcpt_no_end").val());
        // $("#application_reports_pdf_form").submit();
    }

</script>

@endsection