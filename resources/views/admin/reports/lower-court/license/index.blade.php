@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Lower Court - License Report</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                </ol>
            </div>
        </div>
    </div>
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

                        <form action="{{route('reports.lower-court.license.export')}}" method="post"
                            id="application_reports_pdf_form">
                            @csrf
                            {{-- <input type="hidden" name="report_application_type" id="report_application_type">
                            <input type="hidden" name="report_application_date" id="report_application_date">
                            <input type="hidden" name="report_application_date_range"
                                id="report_application_date_range"> --}}
                            <a href="javascript:void(0)" class="btn btn-danger btn-sm float-right m-1"
                                onclick="$('#exportButton').trigger('click')">PDF Export</a>
                        </form>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="{{route('reports.lower-court.license.export')}}" method="POST"
                            id="search_application_form"> @csrf
                            <div class="row">
                                <div class="col-md-1 form-group">
                                    <label><strong>Sort By:</strong></label>
                                    <select class="form-control custom-select" id="sort_by" name="sort_by">
                                        <option value="1">Name</option>
                                        <option value="2">License</option>
                                    </select>
                                </div>
                                <div class="col-md-1 form-group">
                                    <label><strong>License No:</strong></label>
                                    <select class="form-control custom-select" id="license_no" name="license_no">
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                                <div class="col-md-1 form-group">
                                    <label><strong>Export Address:</strong></label>
                                    <select class="form-control custom-select" id="application_address"
                                        name="application_address">
                                        <option value="yes">Yes</option>
                                        <option value="no" selected>No</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-2 form-group">
                                    <label><strong>Application Date :</strong></label>
                                    <select class="form-control custom-select" id="application_date"
                                        name="application_date">
                                        <option value="">--Select Date--</option>
                                        <option value="1">Today</option>
                                        <option value="2">Yesterday</option>
                                        <option value="3">Last 7 Days</option>
                                        <option value="4">Last 30 Days</option>
                                        <option value="5">Enr Date</option>
                                        <option value="6">Enr Date Range</option>
                                        <option value="7">Rcpt Date</option>
                                    </select>
                                </div>
                                <div class="col-md-2 form-group hidden" id="custom_date" name="custom_date">
                                    <label>Custom Date:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control float-right" id="custom_date_input"
                                            name="application_custom_date" value="{{date('m')}}/01/{{date('Y')}}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-md-2 form-group hidden" id="custom_date_range" name="custom_date_range">
                                    <label>Custom Date Range:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control float-right" id="custom_date_range_input"
                                            name="application_date_range"
                                            value="{{date('m')}}/01/{{date('Y')}} - {{date('m')}}/20/{{date('Y')}}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-md-2 form-group hidden" id="rcpt_date" name="rcpt_date">
                                    <label>Rcpt Date:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control float-right" id="rcpt_date_input"
                                            name="application_rcpt_date" value="{{date('m')}}/01/{{date('Y')}}"
                                            disabled>
                                    </div>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label><strong>Application Status</strong></label>
                                    <select class="form-control custom-select" id="application_status"
                                        name="application_status">
                                        <option value="">--Select Status--</option>
                                        @foreach ($app_statuses as $status)
                                        <option value="{{$status->key}}">{{$status->value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label><strong>Division</strong></label>
                                    <select class="form-control custom-select" id="division_id" name="division_id">
                                        <option value="">--Select Division--</option>
                                        @foreach ($divisions as $division)
                                        <option value="{{$division->id}}">{{$division->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label><strong>Voter Memebr</strong></label>
                                    <select class="form-control custom-select" id="bar_id" name="bar_id">
                                        <option value="">--Select Voter--</option>
                                        @foreach ($bars as $bar)
                                        <option value="{{$bar->id}}">{{$bar->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 form-group">
                                    <div class="row">
                                        <div class="col"><label><strong>Age From:</strong></label>
                                            <input type="text" name="age_from" id="age_from" class="form-control"
                                                placeholder="From">
                                        </div>
                                        <div class="col"><label><strong>Age To:</strong></label>
                                            <input type="text" name="age_to" id="age_to" class="form-control"
                                                placeholder="To">
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-2 form-group">
                                    <label><strong>University :</strong></label>
                                    <select class="form-control custom-select" id="university" name="university">
                                        <option value="" selected>--Select University--</option>
                                        @foreach ($universities as $university)
                                        <option value="{{$university->id}}">{{$university->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="hidden" id="exportButton" value="exportPDF"
                                name="export">asd</button>
                            <button type="submit" class="hidden" id="exportEXCEL" value="exportEXCEL"
                                name="export">asd</button>
                        </form>
                        <div class="table-responsive">
                            <div>
                                <table id="applications_table"
                                    class="table table-bordered table-sm table-striped text-uppercase">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>App No</th>
                                            <th>License No</th>
                                            <th>Lawyer/Father Name</th>
                                            <th>Enr Date</th>
                                            <th>Voter Member</th>
                                            <th>Mobile</th>
                                            <th>CNIC</th>
                                            <th>PLJ No</th>
                                            <th>BF No</th>
                                            <th>GI No</th>
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
<script src="{{asset('public/vendor/datatables/buttons.server-side.js')}}"></script>

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

            if (option == 5) {
                $("#custom_date").removeClass('hidden');
                $("#custom_date_input").attr('disabled', false);
            }

            if(option == 6){
                $("#custom_date_range").removeClass('hidden');
                $("#custom_date_range_input").attr('disabled', false);
            }

            if(option == 7){
                $("#rcpt_date").removeClass('hidden');
                $("#rcpt_date_input").attr('disabled', false);
            }
        });

        var table;
        $(document).ready(function () {
            table = $('#applications_table').DataTable({
                dom: 'lfrtip',
                processing: true,
                serverSide: true,
                "responsive": true,
                "autoWidth": false,
                "searching": true,
                ajax: {
                    url: "{{ route('reports.lower-court.license.index') }}",
                    data: function (d) {
                        d.application_status = $('#application_status').val(),
                        d.application_date = $('#application_date').val(),
                        d.application_date_range = $('#custom_date_range_input').val(),
                        d.application_custom_date = $('#custom_date_input').val(),
                        d.application_rcpt_date = $('#rcpt_date_input').val(),
                        d.payment_status = $('#payment_status').val(),
                        d.bar_id = $('#bar_id').val(),
                        d.division_id = $('#division_id').val(),
                        d.license_no = $('#license_no').val(),
                        d.sort_by = $('#sort_by').val(),
                        d.age_from = $('#age_from').val(),
                        d.age_to = $('#age_to').val(),
                        d.university = $('#university').val()
                    }
                },
                order: [[2, "asc"]],
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'id', name: 'id'},
                    {data: 'license_no_lc', name: 'license_no_lc'},
                    {data: 'lawyer_name', name: 'lawyer_name'},
                    {data: 'lc_date', name: 'lc_date'},
                    {data: 'voter_member_lc', name: 'voter_member_lc'},
                    {data: 'mobile_no', name: 'mobile_no'},
                    {data: 'cnic_no', name: 'cnic_no'},
                    {data: 'plj_no_lc', name: 'plj_no_lc'},
                    {data: 'bf_no_lc', name: 'bf_no_lc'},
                    {data: 'gi_no_lc', name: 'gi_no_lc'},
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

            $('#application_status, #payment_status, #card_status, #bar_id, #custom_date_input, #rcpt_date_input, #license_no, #sort_by,#university').change(function () {
                table.draw();
            });
            $('#age_from,#age_to').keyup(function () {
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
        $("#report_application_address").val($("#application_address").val());
        $("#report_application_type").val($("#application_type").val());
        $("#report_application_date").val($("#application_date").val());
        $("#report_application_date_range").val($("#application_date_range").val());
        $("#application_reports_pdf_form").submit();
    }

</script>
@endsection