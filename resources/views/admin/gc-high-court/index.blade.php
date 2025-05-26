@extends('layouts.admin')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage GC High Court Applications</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
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
                            GC High Court Applications List (Total GC High Court Applications :
                            <span id="countTotal">0</span>)
                        </h3>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="applications_table"
                                class="table table-bordered table-striped table-sm text-center text-uppercase">
                                <thead>
                                    <tr>
                                        <th>App.No</th>
                                        <th>SR.No</th>
                                        <th>Lawyer Name</th>
                                        <th>CNIC No.</th>
                                        <th>HCR.No</th>
                                        <th>License.No</th>
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
        var url = '{{ route("gc-high-court.index") }}';

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
                    d.search = $('input[type="search"]').val()
                }
            },
            order:[[1,"desc"]],
            columns: [
                {data: 'id', name: 'id'},
                {data: 'sr_no_hc', name: 'sr_no_hc'},
                {data: 'lawyer_name', name: 'lawyer_name'},
                {data: 'cnic_no', name: 'cnic_no'},
                {data: 'hcr_no_hc', name: 'hcr_no_hc'},
                {data: 'license_no_hc', name: 'license_no_hc'},
                {data: 'app_status', name: 'app_status'},
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
    });
</script>
@endsection