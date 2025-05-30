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
                    <h1>{{__('Secure Card Data')}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        @if (Auth::guard('admin')->user()->hasPermission('add-applications'))
                            <li class="breadcrumb-item">
                                <a href="{{route('applications.create')}}" class="btn btn-success">
                                    <i class="fas fa-plus mr-1" aria-hidden="true"></i> Add Application
                                </a>
                            </li>
                        @endif
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
                                Applications List (Total Applications : {{$applications->count()}})
                            </h3>

                            <form action="{{route('applications.reports.pdf')}}" method="post"
                                  id="application_reports_pdf_form"> @csrf
                                <input type="hidden" name="report_application_type" id="report_application_type">
                                <input type="hidden" name="report_application_date" id="report_application_date">
                                <input type="hidden" name="report_application_date_range"
                                       id="report_application_date_range">
                                <button type="button" class="btn btn-primary btn-sm float-right m-1"
                                        onclick="reports()">Reports PDF</button>
                            </form>


                            @if (Auth::guard('admin')->user()->hasPermission('export-applications'))
                                <form action="{{ route('applications.export') }}" method="POST" id="excel_export_form">
                                    @csrf
                                    <input type="hidden" name="app_type" id="app_type">
                                    <input type="hidden" name="app_date" id="app_date">
                                    <input type="hidden" name="app_date_range" id="app_date_range">
                                    <button type="submit" class="btn btn-warning btn-sm float-right m-1">Excel Export</button>
                                </form>
                            @endif

                            @if (Auth::guard('admin')->user()->hasPermission('import-applications'))
                                @include('admin.applications._excel-import-modal')
                            @endif

                            @if (Auth::guard('admin')->user()->hasPermission('import-applications'))
                                <a href="{{route('applications.upload-profile-images')}}" target="_blank"
                                   class="btn btn-primary btn-sm float-right m-1">Upload Images</a>
                            @endif

                            <a href="javascript:void(0)"
                               data-action="{{route('applications.print-address',['download'=>'pdf'])}}"
                               data-count="{{$printedCertificates->count()}}" id="printAddressQueue"
                               class="btn btn-primary btn-sm float-right m-1">Printing Address
                                Queue ({{$printedCertificates->count()}})</a>

                            <a href="{{ route('applications.vpIndex') }}"
                               class="btn btn-default bg-olive btn-sm float-right m-1">VP Listing
                            </a>

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form action="{{route('applications.index')}}" method="POST" id="search_application_form"> @csrf
                                <div class="row align-items-center mb-2">
                                    <div class="col-md-4 form-group">
                                        <label><strong>Application Type :</strong></label>
                                        <select class="form-control custom-select" id="application_type">
                                            <option value="" selected>--Select Type--</option>
                                            <option value="1">Lower Court</option>
                                            <option value="4">Renewal Lower Court</option>
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
                                    <div class="col-md-4 form-group">
                                        <label><strong>Admins :</strong></label>
                                        <select class="form-control custom-select" id="application_operator">
                                            <option value="">--Select Admins--</option>
                                            @foreach ($operators as $operator)
                                                <option value="{{$operator->id}}">{{$operator->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table id="applications_table" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th><input type="checkbox" id="selectAll" onclick="addAllToQueue()" class="btn btn-sm btn-default float-right bg-blue m-1"></th>
                                        <th>Application Token #</th>
                                        <th>Advocate's Name</th>
                                        <th>Father Name</th>
                                        <th>CNIC No.</th>
                                        <th>Mobile No.</th>
                                        <th>Application Type</th>
                                        <th>Application Status</th>
                                        <th>Card Status</th>
                                        <th>Address</th>
                                        <th>Submitted By</th>
                                        <th>Print Date</th>
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
                "dom": '<"toolbar">lfrtip',
                processing: true,
                serverSide: true,
                "responsive": true,
                "autoWidth": false,
                "searching": true,
                ajax: {
                    url: "{{ route('applications.indexLowerCourt') }}",
                    data: function (d) {
                        d.application_type = $('#application_type').val(),
                            d.application_date = $('#application_date').val(),
                            d.application_date_range = $('#custom_date_range_input').val(),
                            d.search = $('input[type="search"]').val(),
                            d.application_operator = $('#application_operator').val()
                    }
                },
                order:[[2,"desc"]],
                columns: [
                    {'defaultContent':'','data': 'sample','name': 'sample','orderable': false,'searchable': false,'exportable': false,'printable': true,'width': '10px',},
                    {'data': 'checkbox','name': 'checkbox','orderable': false,'searchable': false,'exportable': false,'printable': true,'width': '10px',},
                    {data: 'application_token_no', name: 'application_token_no'},
                    {data: 'advocates_name', name: 'advocates_name'},
                    {data: 'so_of', name: 'so_of'},
                    {data: 'cnic_no', name: 'cnic_no'},
                    {data: 'active_mobile_no', name: 'active_mobile_no'},
                    {data: 'application_type', name: 'application_type',orderable: false, searchable: false},
                    {data: 'application_status', name: 'application_status',orderable: false, searchable: false},
                    {data: 'card_status', name: 'card_status',orderable: false, searchable: false},
                    {data: 'address', name: 'address',orderable: false, searchable: false},
                    {data: 'submitted_by', name: 'submitted_by',orderable: false, searchable: false},
                    {data: 'print_date', name: 'print_date',orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                drawCallback: function (response) {
                    getSelected();
                    $('#selectAll').prop('checked',false);
                }
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

            $('#application_operator').change(function(){
                table.draw();
                $("#application_operator").val($(this).val());
            });

            var htmlss = '<form action="{{ route('applications.addQueue') }}" method="POST">@csrf<textarea name="selectedApplication" hidden class="hidden" id="selectedApplication" cols="30" rows="10"></textarea><button type="submit" id="pushSelected" class="btn btn-default btn-sm bg-olive float-right m-1" disabled>Add To Queue</button></form>';
            $("div.toolbar").html(htmlss);
        });

        $( "#excel_export_form" ).submit(function( event ) {
            console.log('success');
        });

        $(document).ready(function(){
            $("#excel_import_form").on("submit", function(event){
                event.preventDefault();
                $('span.text-success').remove();
                $('span.invalid-feedback').remove();
                $('input.is-invalid').removeClass('is-invalid');
                var formData = new FormData(this);
                $.ajax({
                    method: "POST",
                    data: formData,
                    url: '{{route('applications.import')}}',
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
                        errorsGet(errors.responseJSON.errors)
                        $(".save_btn").show();
                        $(".loading_btn").addClass('hidden');
                        alert('INVALID EXCEL IMPORT FORMAT.')
                    }
                });
            });
        });


        $(document).ready(function(){
            $("#images_upload_form").on("submit", function(event){
                event.preventDefault();
                $('span.text-success').remove();
                $('span.invalid-feedback').remove();
                $('input.is-invalid').removeClass('is-invalid');
                var formData = new FormData(this);
                $.ajax({
                    method: "POST",
                    data: formData,
                    url: '{{route('applications.upload-profile-images')}}',
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
                        errorsGet(errors.responseJSON.errors)
                        $(".save_btn").show();
                        $(".loading_btn").addClass('hidden');
                        alert('SOMETHING WENT WRONG. PLEASE TRY AGAIN LATER.')
                    }
                });
            });
        });

        $('#printAddressQueue').on('click',function(event){
            console.log(event.target.dataset.action);
            console.log(event.target.dataset.count);
            if($(this).data('count') > 0){
                confirmDialog
                    .fire({
                        title: "Lorem Ipsum",
                        text: "Doler Emit Ipsum",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: "#008000",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Yes",
                    })
                    .then((result) => {
                        if (result.value) {
                            window.open(event.target.dataset.action,'_blank')
                            setTimeout(function (){
                                $.get( "{{ route('applications.print-address-count') }}", function( data,status ) {
                                    $('#printAddressQueue').html('Printing Address Queue ('+ data.count +')');
                                    $('#printAddressQueue').data('count',data.count);
                                });
                            },1500);
                        }
                    });
            }else{
                notifyToast('error','Nothing to be printed');
            }
        })

        $(document).on('click','.certificatePrint',function(event){
            var e = $(this);
            $.when( window.open(e.data('action'))).then(function (){
                setTimeout(function (){
                    $.get( "{{ route('applications.print-address-count') }}", function( data,status ) {
                        e.parent().html('<a href="javascript:void(0)"><span class="badge badge-success mr-1"><i class="fas fa-check mr-1" aria-hidden="true"></i> Certificate Printed</span></a>');
                        $('#printAddressQueue').html('Printing Address Queue ('+ data.count +')');
                        $('#printAddressQueue').data('count',data.count);
                    });
                },1500);
            });
        });

        function deleteApplication(event){
            console.log(event.dataset.action);
            Swal.fire({
                title: 'Are you sure?',
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
                        console.log(data);
                        location.reload();
                    });
                }
            })
        }

        function reports() {
            $("#report_application_type").val($("#application_type").val());
            $("#report_application_date").val($("#application_date").val());
            $("#report_application_date_range").val($("#application_date_range").val());
            $( "#application_reports_pdf_form" ).submit();
        }


        var tableRow = [];
        $(document).on('change','.application_checked',function(){
            console.log(tableRow);
            let row =  $(this).parent().parent()
            let id =   row.children('td:nth-child(2)').children('input').val();
            if($(this).is(':checked')){
                tableRow.push(id);
                row.addClass('bg-dark')
                $('#selectedApplication').val(tableRow);
                console.log('checked')
            }else{
                tableRow = jQuery.grep(tableRow, function(value) {
                    return value != id;
                });
                $('#selectedApplication').val(tableRow);
                row.removeClass('bg-dark')
                console.log('not checked')
            }
            addToQueue();
        })

        function getSelected(){
            console.log(tableRow);
            tableRow.forEach(function (item,index){
                console.log(item);
                $('.application_checked').each(function(e){
                    if($(this).val() == item){
                        $(this).attr("checked", "checked");
                        $(this).parent().parent().addClass('bg-dark');
                    }
                });
            })
        }

        function addAllToQueue(){

            let  formData = new FormData;
            formData.set('_token',$('meta[name="csrf-token"]').attr('content'));
            formData.set('application_type',$("#app_type").val());
            formData.set('application_date',$("#app_date").val());
            formData.set('application_date_range',$("#app_date_range").val());
            formData.set('application_operator',$("#application_operator").val());
            formData.set('search',$('input[type="search"]').val());
            if($('#selectAll').is(':checked')){
                $.ajax({
                    method: "POST",
                    data: formData,
                    url: '{{route('applications.selectAll')}}',
                    processData: false,
                    contentType: false,
                    cache: false,
                    beforeSend: function(){
                        $(".loading_btn").removeClass('hidden');
                    },
                    success: function (response) {
                        if (response.status == 1) {
                            tableRow = response.application_id;
                            response.application_id.forEach(function(item){
                                $('.application_checked').each(function(e){
                                    if($(this).val() == item){
                                        $(this).prop("checked", true);
                                        $(this).parent().parent().addClass('bg-dark');
                                    }
                                });
                            })
                            addToQueue();
                            notifyBlackToast('All Selected')
                        }
                    },
                    error : function (errors) {
                        addToQueue();
                        errorsGet(errors.responseJSON.errors)
                        $(".loading_btn").addClass('hidden');
                        alert('SOMETHING WENT WRONG. PLEASE TRY AGAIN LATER.')
                    }
                });
            }else{
                tableRow = [];
                $('.application_checked').prop('checked',false);
                $('.application_checked').parent().parent().removeClass('bg-dark');
                notifyBlackToast('deSelected All');
                addToQueue();
            }

        }

        function addToQueue(){
            if(tableRow.length> 0){
                $('#pushSelected').prop('disabled',false);
            }else{
                $('#pushSelected').prop('disabled',true);
            }
            $('#selectedApplication').val(tableRow);

        }

    </script>

@endsection
