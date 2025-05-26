@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
@endsection

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{__('Reports')}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        {{--@if (Auth::guard('admin')->user()->hasPermission('add-applications'))
                        <li class="breadcrumb-item">
                            <a href="{{route('applications.create')}}" class="btn btn-success">
                                <i class="fas fa-plus mr-1" aria-hidden="true"></i> Add Application
                            </a>
                        </li>
                        @endif--}}
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

                            <form action="{{route('reports.export')}}" method="post" id="application_reports_pdf_form">
                                @csrf
                                <input type="hidden" name="report_application_type" id="report_application_type">
                                <input type="hidden" name="report_application_date" id="report_application_date">
                                <input type="hidden" name="report_application_date_range"
                                       id="report_application_date_range">
                                @if(Auth::guard('admin')->user()->hasPermission('manage-reports'))
                                    <a href="javascript:void(0)" class="btn btn-primary btn-sm float-right m-1"
                                       onclick="$('#exportButton').trigger('click')">Export PDF</a>
                                    <a href="javascript:void(0)" class="btn btn-primary btn-sm float-right m-1"
                                       onclick="$('#exportButtonExcel').trigger('click')">Export Excel</a>
                                @endif
                            </form>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form action="{{route('reports.export')}}" method="POST" id="search_application_form"> @csrf
                                <div class="row align-items-center mb-2">
                                    <div class="col-md-4 form-group">
                                        <label><strong>Application Type :</strong></label>
                                        <select class="form-control custom-select" id="application_type"
                                                name="application_type">
                                            <option value="" selected>--Select Type--</option>
                                            <option value="1">Lower Court</option>
                                            <option value="2">High Court</option>
                                            <option value="3">Renewal High Court</option>
                                            <option value="4">Renewal Lower Court</option>
                                            {{--<option value="5">Existing Lawyer</option>--}}
                                            {{--<option value="6">Intimation</option>--}}
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label><strong>Application Date :</strong></label>
                                        <select class="form-control custom-select" id="application_date"
                                                name="application_date">
                                            <option value="">--Select Date--</option>
                                            <option value="1">Today</option>
                                            <option value="2">Yesterday</option>
                                            <option value="3">Last 7 Days</option>
                                            <option value="4">Last 30 Days</option>
                                            <option value="5">Custom Date</option>
                                            <option value="6">Custom Date Range</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group hidden" id="custom_date_range" name="custom_date_range">
                                        <label>Custom Date Range:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                            </div>
                                            <input type="text" class="form-control float-right" id="custom_date_range_input" name="custom_date_range_input"
                                                   value="{{date('m')}}/01/{{date('Y')}} - {{date('m')}}/20/{{date('Y')}}"
                                                   disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group hidden" id="custom_date" name="custom_date">
                                        <label>Custom Date </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                            </div>
                                            <input type="text" class="form-control float-right" id="custom_date_input"
                                                   name="custom_date_input" value="{{date('m')}}/01/{{date('Y')}}" disabled>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group">
                                        <label><strong>Operators/Managers :</strong></label>
                                        <select class="form-control custom-select" id="application_operator" name="application_operator">
                                            <option value="">--Select Operator--</option>
                                            @foreach ($operators as $operator)
                                                <option value="{{$operator->id}}">{{$operator->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label><strong>Application Status</strong></label>
                                        <select class="form-control custom-select" id="application_status" name="application_status">
                                            <option value="">--Select Application Status--</option>
                                            <option value="1">Active</option>
                                            <option value="2">Suspended</option>
                                            <option value="3">Died</option>
                                            <option value="4">Removed</option>
                                            <option value="5">Transfer in</option>
                                            <option value="6">Transfer out</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label><strong>Payment Status :</strong></label>
                                        <select class="form-control custom-select" id="payment_status" name="payment_status">
                                            <option value="" selected>--Select Payment Status--</option>
                                            <option value="paid">Paid</option>
                                            <option value="unpaid">Unpaid</option>
                                        </select>
                                    </div>
                                    {{--<div class="col-md-4 form-group">
                                        <label><strong>Card Status</strong></label>
                                        <select class="form-control custom-select" id="card_status" name="card_status">
                                            <option value="">--Select Card Status--</option>
                                            <option value="1">Pending</option>
                                            <option value="2">Printing</option>
                                            <option value="3">Dispatched</option>
                                            <option value="4">By Hand</option>
                                            <option value="5">Done</option>
                                        </select>
                                    </div>--}}
                                    <div class="col-md-4 form-group">
                                        <label><strong>Division</strong></label>
                                        <select class="form-control custom-select" id="division_id" name="division_id">
                                            <option value="">--Select Division--</option>
                                            @foreach ($divisions as $division)
                                                <option value="{{$division->id}}">{{$division->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label><strong>Voter member (bars/stations)</strong></label>
                                        <select class="form-control custom-select" id="bar_id" name="bar_id">
                                            <option value="">--Select Voter member--</option>
                                            @foreach ($bars as $bar)
                                                <option value="{{$bar->id}}">{{$bar->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <button type="submit" class="hidden" name="export" id="exportButton" value="exportButtonPDF">asd</button>
                                <button type="submit" class="hidden" name="export" id="exportButtonExcel" value="exportButtonExcel">asd</button>
                            </form>
                            <div class="table-responsive">
                                <table id="applications_table" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Application Token #</th>
                                        <th>Advocate's Name</th>
                                        <th>Father Name</th>
                                        <th>Mobile No.</th>
                                        <th>Application Type</th>
                                        <th>Application Status</th>
                                        <th>Card Status</th>
                                        <th>Submitted By</th>
                                        {{--<th class="text-center">Action</th>--}}
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
            }, function (start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            });
        });

        $("#application_date").change(function (e) {
            e.preventDefault();
            var option = $(this).val();
            if (option == 5) {
                $("#custom_date_range").addClass('hidden');
                $("#custom_date_range_input").attr('disabled', true);
                $("#custom_date").removeClass('hidden');
                $("#custom_date_input").attr('disabled', false);
            } else if (option == 6) {
                $("#custom_date_range").removeClass('hidden');
                $("#custom_date_range_input").attr('disabled', false);
                $("#custom_date").addClass('hidden');
                $("#custom_date_input").attr('disabled', true);
            } else {
                $("#custom_date").addClass('hidden');
                $("#custom_date_input").attr('disabled', true);
                $("#custom_date_range").addClass('hidden');
                $("#custom_date_range_input").attr('disabled', true);
            }
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
                    url: "{{ route('reports.index') }}",
                    data: function (d) {
                        d.application_type = $('#application_type').val(),
                            d.application_date = $('#application_date').val(),
                            d.application_date_range = $('#custom_date_range_input').val(),
                            d.application_custom_date = $('#custom_date_input').val(),
                            d.search = $('input[type="search"]').val(),
                            d.application_operator = $('#application_operator').val()
                        d.application_status = $('#application_status').val()
                        d.payment_status = $('#payment_status').val()
                        /*d.card_status = $('#card_status').val()*/
                        d.bar_id = $('#bar_id').val()
                        d.division_id = $('#division_id').val()
                    }
                },
                order: [[2, "desc"]],
                columns: [
                    {data: 'application_token_no', name: 'application_token_no'},
                    {data: 'advocates_name', name: 'advocates_name'},
                    {data: 'so_of', name: 'so_of'},
                    {data: 'active_mobile_no', name: 'active_mobile_no'},
                    {data: 'application_type', name: 'application_type', orderable: false, searchable: false},
                    {data: 'application_status', name: 'application_status', orderable: false, searchable: false},
                    {data: 'card_status', name: 'card_status', orderable: false, searchable: false},
                    {data: 'submitted_by', name: 'submitted_by', orderable: false, searchable: false},
                    /*{data: 'action', name: 'action', orderable: false, searchable: false},*/
                ],
                drawCallback: function (response) {
                    $('#countTotal').empty();
                    $('#countTotal').append(response['json'].recordsTotal);
                }
            });

            $('#application_type').change(function () {
                table.draw();
                $("#app_type").val($(this).val());
            });

            $('#application_date').change(function () {
                table.draw();
                $("#app_date").val($(this).val());
            });

            $('#custom_date_range_input').change(function () {
                table.draw();
                $("#app_date_range").val($(this).val());
            });

            $('#application_operator').change(function () {
                table.draw();
                $("#application_operator").val($(this).val());
            });

            $('#application_status,#payment_status,#bar_id,#custom_date_input').change(function () {
                table.draw();
            });

        });

        function reports() {
            $("#report_application_type").val($("#application_type").val());
            $("#report_application_date").val($("#application_date").val());
            $("#report_application_date_range").val($("#application_date_range").val());
            $("#application_reports_pdf_form").submit();
        }

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


    </script>

@endsection
