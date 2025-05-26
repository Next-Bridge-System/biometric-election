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
                <h1>Manage Payments</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    {{-- @if (Auth::guard('admin')->user()->hasPermission('import-applications')) --}}
                    @include('admin.payments.partials.import')
                    {{-- @endif --}}
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
                            Payments List (Total Payments : <span id="countTotal">0</span>)
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="payments_table"
                                class="table table-bordered table-striped table-sm text-center text-uppercase">
                                <thead>
                                    <tr>
                                        <th>Sr.no</th>
                                        <th>Application No.</th>
                                        <th>Lawyer Name</th>
                                        <th>Application Type</th>
                                        <th>Voucher No.</th>
                                        <th>Amount (PKR)</th>
                                        <th>Paid Date</th>
                                        <th>Transaction Id</th>
                                        <th>Payment Status</th>
                                        <th>Bank Name</th>
                                        <th>Payment Type</th>
                                        <th>Paid By</th>
                                        <th>Approved By</th>
                                        <th>Created at</th>
                                        <th>Updated at</th>
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
            table = $('#payments_table').DataTable({
                processing: true,
                serverSide: true,
                "responsive": true,
                "autoWidth": false,
                "searching": true,
                ajax: {
                    url: "{{ route('payments.index') }}",
                },
                order:[[0,"desc"]],
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
                    {data: 'application_id', name: 'application_id'},
                    {data: 'user_id', name: 'user_id'},
                    {data: 'application_type', name: 'application_type'},
                    {data: 'voucher_no', name: 'voucher_no'},
                    {data: 'amount', name: 'amount'},
                    {data: 'paid_date', name: 'paid_date'},
                    {data: 'transaction_id', name: 'transaction_id'},
                    {data: 'payment_status', name: 'payment_status',orderable: false, searchable: false},
                    {data: 'bank_name', name: 'bank_name'},
                    {data: 'payment_type', name: 'payment_type'},
                    {data: 'admin_id', name: 'admin_id'},
                    {data: 'approved_by', name: 'approved_by'},
                    {data: 'created_at', name: 'created_at',orderable: false, searchable: false},
                    {data: 'updated_at', name: 'updated_at',orderable: false, searchable: false},
                ],
                drawCallback: function (response) {
                    $('#countTotal').empty();
                    $('#countTotal').append(response['json'].recordsTotal);
                }
            });
        });
</script>

<script>
    $("#payment_excel_import_form").on("submit", function(event){
        event.preventDefault();
        $('span.text-success').remove();
        $('span.invalid-feedback').remove();
        $('input.is-invalid').removeClass('is-invalid');
        var formData = new FormData(this);
        $.ajax({
        method: "POST",
        data: formData,
        url: '{{route('payments.import')}}',
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
</script>
@endsection
