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
                <h1>Manage Existing Lawyers</h1>
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
                            Unapproved Lawyers List (Total Unapproved Lawyers : {{$count}})
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="{{route('lawyers.unapproved')}}" method="POST" id="search_application_form"> @csrf
                            <div class="row align-items-center mb-2">
                                <div class="col-md-4 form-group">
                                    <label><strong>Application Type :</strong></label>
                                    <select class="form-control custom-select" id="application_type">
                                        <option value="">--Select Type--</option>
                                        <option value="1">Lower Court</option>
                                        <option value="2">High Court</option>
                                    </select>
                                </div>
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
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table id="applications_table" class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Application Token #</th>
                                        <th>Advocate's Name</th>
                                        <th>CNIC No.</th>
                                        <th>Mobile No.</th>
                                        <th>Application Type</th>
                                        <th>Application Status</th>
                                        <th>Card Status</th>
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
        table = $('#applications_table').DataTable({
            processing: true,
            serverSide: true,
            "responsive": true,
            "autoWidth": false,
            "searching": true,
            ajax: {
                url: "{{ route('lawyers.unapproved') }}",
                data: function (d) {
                    d.application_type = $('#application_type').val(),
                    d.application_date = $('#application_date').val(),
                    d.application_date_range = $('#custom_date_range_input').val(),
                    d.search = $('input[type="search"]').val()
                }
            },
            order:[[1,"desc"]],
            columns: [
                {data: 'application_token_no', name: 'application_token_no'},
                {data: 'advocates_name', name: 'advocates_name'},
                {data: 'cnic_no', name: 'cnic_no'},
                {data: 'active_mobile_no', name: 'active_mobile_no'},
                {data: 'application_type', name: 'application_type',orderable: false, searchable: false},
                {data: 'application_status', name: 'application_status',orderable: false, searchable: false},
                {data: 'card_status', name: 'card_status',orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('#application_type').change(function(){
            table.draw();
            $("#app_type").val($(this).val());
        });

        $('#application_date').change(function(){
            table.draw();
            $("#app_date").val($(this).val());
        });

        $('#custom_date_range_input').change(function(){
            table.draw();
            $("#app_date_range").val($(this).val());
        });
    });
</script>

@endsection
