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
                            {{$title}} List (Total {{$title}} : <span id="countTotal">0</span>))
                        </h3>

                        @php
                        if(Route::currentRouteName() == 'secure-card.lower-court'){
                        $url = URL::route('secure-card.queue.lower-court');
                        }elseif(Route::currentRouteName() == 'secure-card.renewal-lower-court'){
                        $url = URL::route('secure-card.queue.renewal-lower-court');
                        }elseif(Route::currentRouteName() == 'secure-card.higher-court'){
                        $url = URL::route('secure-card.queue.higher-court');
                        }elseif(Route::currentRouteName() == 'secure-card.renewal-higher-court'){
                        $url = URL::route('secure-card.queue.renewal-higher-court');
                        }
                        @endphp
                        @if(Auth::guard('admin')->user()->hasPermission('manage-queue-listing'))
                        <a href="{{ $url }}" class="btn btn-default bg-olive btn-sm float-right m-1">Printing Queue
                        </a>
                        @endif

                        @include('admin.applications.secure-card.vpp-number-import')
                        @include('admin.applications.secure-card.vpp-return-import')

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="{{route('applications.index')}}" method="POST" id="search_application_form"> @csrf
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
                                    <label><strong>Operators/Managers :</strong></label>
                                    <select class="form-control custom-select" id="application_operator">
                                        <option value="">--Select Operator--</option>
                                        @foreach ($operators as $operator)
                                        <option value="{{$operator->id}}">{{$operator->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4 form-group">
                                    <label><strong>Bars :</strong></label>
                                    <select class="form-control custom-select" id="bar">
                                        <option value="">--Select Bar--</option>
                                        @foreach ($bars as $bar)
                                        <option value="{{$bar->id}}">{{$bar->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label><strong>Card Status</strong></label>
                                    <select class="form-control custom-select" id="card_status" name="card_status">
                                        <option value="">--Select Card Status--</option>
                                        <option value="1">Pending</option>
                                        <option value="2">Printing</option>
                                        <option value="3">Dispatched</option>
                                        <option value="4">By Hand</option>
                                        <option value="5">Done</option>
                                    </select>
                                </div>
                                <div class="col-md-4 form-group ">
                                    <label><strong>Filter Specific Type</strong></label>
                                    <select class="form-control custom-select" id="preg_column" name="preg_column">
                                        <option value="">--Choose Type--</option>
                                        <option value="reg_no_lc">Ledger No.</option>
                                        @if($type == 1 || $type == 4)
                                        <option value="license_no_lc">License No</option>
                                        @endif
                                        @if($type == 2 || $type == 3)
                                        <option value="license_no_hc">License No</option>
                                        @if($type == 3) <option value="hcr_no">HCR No</option> @endif
                                        @if($type == 2) <option value="high_court_roll_no">HCR No</option> @endif
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-4 form-group preg_search_div" style="display: none">
                                    <label><strong>Specific Search</strong></label>
                                    <input type="text" class="form-control" id="preg_search" name="preg_search">
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table id="applications_table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th><input type="checkbox" id="selectAll" onclick="addAllToQueue()"
                                                class="btn btn-sm btn-default float-right bg-blue m-1"></th>
                                        <th>Application Token #</th>
                                        @if($type == 1 || $type == 4)<th>Legder #</th>@endif
                                        <th>Advocate's Name</th>
                                        <th>Father Name</th>
                                        <th>BF #</th>
                                        <th>Voter Member</th>
                                        <th>CNIC No.</th>
                                        <th>Date of Enrollment</th>
                                        <th>Mobile No.</th>
                                        <th>Date of Birth</th>
                                        <th>Application Type</th>
                                        <th>Application Status</th>
                                        <th>Card Status</th>
                                        <th>Address</th>
                                        <th>Submitted By</th>
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
                    url: "{{ URL::current() }}",
                    data: function (d) {
                        d.application_type = $('#application_type').val(),
                        d.application_date = $('#application_date').val(),
                        d.application_date_range = $('#custom_date_range_input').val(),
                        d.search = $('input[type="search"]').val(),
                        d.application_operator = $('#application_operator').val()
                        d.bar_id = $('#bar').val()
                        d.card_status = $('#card_status').val()
                        d.preg_column = $('#preg_column').val()
                        d.preg_search = $('#preg_search').val()
                    }
                },
                order:[[2,"desc"]],
                columns: [
                    {'defaultContent':'','data': 'sample','name': 'sample','orderable': false,'searchable': false,'exportable': false,'printable': true,'width': '10px',},
                    {'data': 'checkbox','name': 'checkbox','orderable': false,'searchable': false,'exportable': false,'printable': true,'width': '10px',},
                    {data: 'application_token_no', name: 'application_token_no',orderable: true, searchable: true},
                    @if($type == 1 || $type == 4) {data: 'reg_no_lc', name: 'reg_no_lc',orderable: true, searchable: true}, @endif
                    {data: 'advocates_name', name: 'advocates_name',orderable: true, searchable: true},
                    {data: 'so_of', name: 'so_of',orderable: true, searchable: true},
                    @if($type == 1 || $type == 4) {data: 'bf_no_lc', name: 'bf_no_lc',orderable: true, searchable: true}, @endif
                    @if($type == 2 || $type == 3) {data: 'bf_no_hc', name: 'bf_no_hc',orderable: true, searchable: true}, @endif
                    @if($type == 1 || $type == 4) {data: 'voter_member_lc', name: 'voter_member_lc',orderable: true, searchable: true}, @endif
                    @if($type == 2 || $type == 3) {data: 'voter_member_hc', name: 'voter_member_hc',orderable: true, searchable: true}, @endif
                    {data: 'cnic_no', name: 'cnic_no',orderable: true, searchable: true},
                    {data: 'date_of_enrollment_lc', name: 'date_of_enrollment_lc',orderable: true, searchable: true},
                    {data: 'active_mobile_no', name: 'active_mobile_no',orderable: true, searchable: true},
                    {data: 'date_of_birth', name: 'date_of_birth',orderable: true, searchable: true},
                    {data: 'application_type', name: 'application_type',orderable: false, searchable: false},
                    {data: 'application_status', name: 'application_status',orderable: false, searchable: false},
                    {data: 'card_status', name: 'card_status',orderable: false, searchable: false},
                    {data: 'address', name: 'address',orderable: false, searchable: false},
                    {data: 'submitted_by', name: 'submitted_by',orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                drawCallback: function (response) {
                    $('#countTotal').empty();
                    $('#countTotal').append(response['json'].recordsTotal);
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

            $('#bar').change(function(){
                table.draw();
                $("#bar").val($(this).val());
            });

            $('#card_status').change(function(){
                table.draw();
                $("#card_status").val($(this).val());
            });

            $('#preg_column').change(function(){
                if($(this).val() != ''){
                    $('.preg_search_div').show();
                }else{
                    $('.preg_search_div').hide();
                    $('.preg_search_div input').val('')
                    table.draw();
                }
                $("#preg_column").val($(this).val());
            });

            $('#preg_search').keyup(function(){
                table.draw();
            });

            @if(Auth::guard('admin')->user()->hasPermission('add-queue-listing'))
            var htmlss = '<form action="{{ route('applications.addQueue') }}" method="POST">@csrf<textarea name="selectedApplication" hidden class="hidden" id="selectedApplication" cols="30" rows="10"></textarea><button type="submit" id="pushSelected" class="btn btn-default btn-sm bg-olive float-right m-1" disabled>Add To Queue</button></form>';
            $("div.toolbar").html(htmlss);
            @endif
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
            formData.set('application_type',{{ $type }});
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


        $("#vpp_number_import").on("submit", function(event){
            event.preventDefault();
            $('span.text-success').remove();
            $('span.invalid-feedback').remove();
            $('input.is-invalid').removeClass('is-invalid');
            var formData = new FormData(this);
            $.ajax({
                method: "POST",
                data: formData,
                url: '{{route('secure-card.vpp-number-import')}}',
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
                    Swal.fire('INVALID FILE FORMAT','The format of the file is not valid or you have some invalid data in this\
                    file.','error')
                }
            });
        });

        $("#vpp_return_import").on("submit", function(event){
            event.preventDefault();
            $('span.text-success').remove();
            $('span.invalid-feedback').remove();
            $('input.is-invalid').removeClass('is-invalid');
            var formData = new FormData(this);
            $.ajax({
                method: "POST",
                data: formData,
                url: '{{route('secure-card.vpp-return-import')}}',
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
                    Swal.fire('INVALID FILE FORMAT','The format of the file is not valid or you have some invalid data in this\
                    file.','error')
                }
            });
        });

</script>

@endsection
