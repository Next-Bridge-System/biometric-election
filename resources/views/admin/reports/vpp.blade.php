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
                <h1>{{__('VPP Report')}}</h1>
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
                            onclick="$('#exportPDF').trigger('click')">Export Report</a>
                        <a href="javascript:void(0)" class="btn btn-primary btn-sm float-right m-1"
                            onclick="$('#exportExcel').trigger('click')">Export Excel</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="{{route('reports.vpp.export')}}" method="POST" id="search_vpp_form"> @csrf
                            <div class="row align-items-center mb-2">
                                <div class="col-md-4 form-group">
                                    <label><strong>Search By Application Type :</strong></label>
                                    <select class="form-control custom-select" id="vpp_application_type"
                                        name="vpp_application_type">
                                        {{-- <option value="" selected>--Select Type--</option> --}}
                                        <option value="1">Lower Court</option>
                                        <option value="2">High Court</option>
                                        <option value="3">Renewal High Court</option>
                                        <option value="4">Renewal Lower Court</option>
                                    </select>
                                </div>
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
                                    <label><strong>Search By Stations</strong></label>
                                    <select class="form-control custom-select" id="vpp_application_station"
                                        name="vpp_application_station">
                                        <option value="">--Select Station--</option>
                                        @foreach ($bars as $bar)
                                        <option value="{{$bar->id}}">{{$bar->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4 form-group">
                                    <label><strong>Search By Year</strong></label>
                                    <select name="vpp_year" id="vpp_year" class="form-control custom-select">
                                        {{-- <option value="">--Select Year--</option> --}}
                                        @foreach (range(Carbon\Carbon::now()->year, 2000) as $year)
                                        <option value="{{$year}}">{{$year}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label><strong>Search By Delivered</strong></label>
                                    <select name="vpp_delivered" id="vpp_delivered" class="form-control custom-select">
                                        <option value="">--Select--</option>
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label><strong>Search By Returned</strong></label>
                                    <select name="vpp_returned" id="vpp_returned" class="form-control custom-select">
                                        <option value="">--Select--</option>
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label><strong>Search By Duplicated</strong></label>
                                    <select name="vpp_duplicate" id="vpp_duplicate" class="form-control custom-select">
                                        <option value="">--Select--</option>
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>

                            </div>
                            <button type="submit" class="hidden" name="export" id="exportPDF"
                                value="exportPDF">asd</button>
                            <button type="submit" class="hidden" name="export" id="exportExcel"
                                value="exportExcel">asd</button>
                        </form>
                        <div class="table-responsive">
                            <table id="applications_table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Sr. #</th>
                                        <th>Application</th>
                                        <th>Ledger</th>
                                        <th>Name</th>
                                        <th>Father Name</th>
                                        <th>DOE</th>
                                        <th>Fee of Year</th>
                                        <th>VPP Number</th>
                                        <th>VPP Delivered</th>
                                        <th>Returned</th>
                                        <th>Total Dues/Fee</th>
                                        <th>Remarks</th>
                                        <th>Duplicate</th>
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
                url: "{{ route('reports.vpp') }}",
                data: function (d) {
                    d.vpp_application_type = $('#vpp_application_type').val(),
                    d.vpp_application_station = $('#vpp_application_station').val(),
                    d.vpp_year = $('#vpp_year').val(),
                    d.vpp_delivered = $('#vpp_delivered').val(),
                    d.vpp_returned = $('#vpp_returned').val(),
                    d.vpp_duplicate = $('#vpp_duplicate').val(),
                    d.division_id = $('#division_id').val(),
                    d.search = $('input[type="search"]').val()
                }
            },
            order:[[2,"desc"]],
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                {data: 'application_token_no', name: 'application_token_no'},
                {data: 'reg_no_lc', name: 'reg_no_lc'},
                {data: 'lawyer_name', name: 'lawyer_name'},
                {data: 'so_of', name: 'so_of'},
                {data: 'date_of_enrollment_lc', name: 'date_of_enrollemnt_lc'},
                {data: 'vpp_fees_year', name: 'vpp_fees_year'},
                {data: 'vpp_number', name: 'vpp_number'},
                {data: 'vpp_delivered', name: 'vpp_delivered'},
                {data: 'vpp_returned', name: 'vpp_returned'},
                {data: 'vpp_total_dues', name: 'vpp_total_dues'},
                {data: 'vpp_remarks', name: 'vpp_remarks'},
                {data: 'vpp_duplicate', name: 'vpp_duplicate'},
            ],
            drawCallback: function (response) {
                $('#countTotal').empty();
                $('#countTotal').append(response['json'].recordsTotal);
            }
        });

        $('#vpp_application_type').change(function(){
            table.draw();
            $("#vpp_application_type").val($(this).val());
        });

        $('#vpp_application_station').change(function(){
            table.draw();
            $("#vpp_application_station").val($(this).val());
        });

        $('#vpp_year').change(function(){
            table.draw();
            $("#vpp_year").val($(this).val());
        });

        $('#vpp_delivered,#vpp_returned,#vpp_duplicate').change(function(){
            table.draw();
        });

        $('#division_id').change(function(){
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
                    $('#vpp_application_station').empty();
                    $('#vpp_application_station').append(' <option value="" selected>--Select Station --</option>');
                    response.bars.forEach(function (item, index) {
                        option = "<option value='" + item.id + "'>" + item.name + "</option>"
                        $('#vpp_application_station').append(option);
                    });
                }
            });

        })

    });

    function reports() {
        $("#report_app_type").val($("#vpp_application_type").val());
        $("#report_station").val($("#vpp_application_station").val());
        $("#report_year").val($("#vpp_year").val());
        $("#report_vpp_delivered").val($("#vpp_delivered").val());
        $("#report_vpp_returned").val($("#vpp_returned").val());
        $("#report_vpp_duplicate").val($("#vpp_duplicate").val());
        $("#report_division_id").val($("#division_id").val());
        $("#vpp_report_form" ).submit();
    }
    function reportsExcel() {
        $("#excel_report_app_type").val($("#vpp_application_type").val());
        $("#excel_report_station").val($("#vpp_application_station").val());
        $("#excel_report_year").val($("#vpp_year").val());
        $("#excel_report_vpp_delivered").val($("#vpp_delivered").val());
        $("#excel_report_vpp_returned").val($("#vpp_returned").val());
        $("#excel_report_vpp_duplicate").val($("#vpp_duplicate").val());
        $("#excel_report_division_id").val($("#division_id").val());
        $("#vpp_report_excel" ).submit();
    }

</script>

@endsection
