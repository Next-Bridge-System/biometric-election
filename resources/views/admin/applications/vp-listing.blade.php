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
                    <h1>{{__('Print Application Data')}}</h1>
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
                            <div class="card-tools">
                                <a href="javascript:void(0)" onclick="exportBulk()" class="btn btn-sm bg-gradient-gray-dark">Export PDF</a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form action="{{route('applications.index')}}" method="POST" id="search_application_form"> @csrf
                                {{--<div class="row align-items-center mb-2">
                                    <div class="col-md-4 form-group">
                                        <label><strong>Application Type :</strong></label>
                                        <select class="form-control custom-select" id="application_type">
                                            <option value="" selected>--Select Type--</option>
                                            <option value="1">Lower Court</option>
                                            <option value="2">High Court</option>
                                            <option value="3">Renewal High Court</option>
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
                                        <label><strong>Operators/Managers :</strong></label>
                                        <select class="form-control custom-select" id="application_operator">
                                            <option value="">--Select Operator--</option>
                                            @foreach ($operators as $operator)
                                                <option value="{{$operator->id}}">{{$operator->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>--}}
                            </form>
                            <div class="table-responsive">
                                <table id="applications_table" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Application Token #</th>
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
                "dom": 'l<"toolbar">frtip',
                processing: true,
                serverSide: true,
                "responsive": true,
                "autoWidth": false,
                "searching": true,
                ajax: {
                    url: "{{ route('applications.vpIndex') }}",
                    data: function (d) {
                        d.application_type = $('#application_type').val(),
                            d.application_date = $('#application_date').val(),
                            d.application_date_range = $('#custom_date_range_input').val(),
                            d.search = $('input[type="search"]').val(),
                            d.application_operator = $('#application_operator').val()
                    }
                },
                order:[[1,"desc"]],
                columns: [
                    {
                        'defaultContent': '<input type="checkbox" class="application_checked" name="application_token_no[]" value=""/>',
                        'data': 'checkbox',
                        'name': 'checkbox',
                        'orderable': false,
                        'searchable': false,
                        'exportable': false,
                        'printable': true,
                        'width': '10px',
                    },
                    {data: 'application_id', name: 'application_id'},
                    {data: 'submitted_by', name: 'submitted_by',orderable: false, searchable: false},
                    {data: 'printed_at', name: 'printed_at',orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                drawCallback: function (response) {
                    getSelected();
                }
            });



            var htmlss = '<form action="{{ route('applications.removeQueue') }}" method="POST">@csrf<textarea name="selectedApplication" hidden class="hidden" id="selectedApplication" cols="30" rows="10"></textarea><button type="submit" id="pushSelected" class="btn btn-default btn-sm bg-gradient-maroon float-right ml-1" disabled>Remove From Queue</button></form>';
            $("div.toolbar").html(htmlss);
        });

        function exportBulk(){
            $.ajax({
                method: "POST",
                url: '{{ route('applications.exportBulk') }}',
                datatype: 'json',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                beforeSend: function(){
                    $(".custom-loader").removeClass('hidden');
                },
                success: function (response) {
                    console.log(response);
                    if(response.status == 1){
                        window.open(response.excel+'?app_type=4&ids='+response.ids);
                        window.open(response.pdf+'?ids='+response.ids, '_blank');
                        window.open(response.print+'?ids='+response.ids, '_blank');
                        $(".custom-loader").addClass('hidden');
                        setTimeout(function (){
                            table.draw();
                        },1000)
                    }else{
                        $(".custom-loader").addClass('hidden');
                        notifyToast('error','No Record Found');
                    }
                },
                error : function (errors) {
                    $(".custom-loader").addClass('hidden');
                    notifyToast('error','Something went wrong');
                }
            });


        }




        var tableRow = [];
        $(document).on('change','.application_checked',function(){
            console.log(tableRow);
            let row =  $(this).parent().parent()
            let id =   row.children('td:nth-child(1)').children('input').val();
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
            if(tableRow.length> 0){
                $('#pushSelected').prop('disabled',false);
            }else{
                $('#pushSelected').prop('disabled',true);
            }
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

    </script>

@endsection
