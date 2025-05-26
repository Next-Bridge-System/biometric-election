@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{__('Printing Queue - Selected Records')}}</h1>
            </div>
            <div class="col-sm-6">

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
                            {{$title}} List (Total {{$title}} : <span id="countTotal">0</span>)
                        </h3>
                        <div class="card-tools d-flex">
                            @if(Auth::guard('admin')->user()->hasPermission('export-queue-listing'))
                                <div class="form-inline">
                                    <div class="form-check mr-2">
                                        <input class="form-check-input" type="checkbox" checked id="exportVpLetter">
                                        <label class="form-check-label text-capitalize" for="exportVpLetter">
                                            export vp letter
                                        </label>
                                    </div>
                                    <div class="form-check mr-2">
                                        <input class="form-check-input" type="checkbox" checked id="exportVpEnvelop">
                                        <label class="form-check-label text-capitalize" for="exportVpEnvelop">
                                            export vp envelop
                                        </label>
                                    </div>
                                    <div class="form-check mr-2">
                                        <input class="form-check-input" type="checkbox" checked id="exportVpExcel">
                                        <label class="form-check-label text-capitalize" for="exportVpExcel">
                                            export vp excel
                                        </label>
                                    </div>
                                </div>
                                <a href="javascript:void(0)" onclick="exportBulk()"
                                   class="btn btn-md btn-success">EXPORT</a>
                            @endif

                            @include('admin.applications.secure-card.queue-import')
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="applications_table" class="table table-bordered table-striped text-uppercase">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>APPLICATION #</th>
                                        <th>LEDGER #</th>
                                        <th>ADVOCATE</th>
                                        <th>CNIC</th>
                                        <th>OPERATOR</th>
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
<script src="{{asset('public/js/app.js')}}"></script>

<script>
    jQuery(document).ready(function () {
        App.init();
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
                url: "{{ URL::current() }}",
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
                {data: 'reg_no_lc', name: 'reg_no_lc'}, // Registration/Legder No
                {data: 'advocates_name', name: 'advocates_name'},
                {data: 'cnic_no', name: 'cnic_no'},
                {data: 'submitted_by', name: 'submitted_by',orderable: false, searchable: false},
                {data: 'status', name: 'status', orderable: false, searchable: false},
            ],
            drawCallback: function (response) {
                $('#countTotal').empty();
                $('#countTotal').append(response['json'].recordsTotal);
                getSelected();
            }
        });



        var htmlss = '<form action="{{ route('applications.removeQueue') }}" method="POST">@csrf<textarea name="selectedApplication" hidden class="hidden" id="selectedApplication" cols="30" rows="10"></textarea><button type="submit" id="pushSelected" class="btn btn-sm btn-danger float-right ml-1" disabled>Remove From Queue</button></form>';
        $("div.toolbar").html(htmlss);
    });

    function exportBulk(){
        $.ajax({
            method: "POST",
            url: '{{ route('secure-card.export.bulk') }}',
            datatype: 'json',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                type: {{ $type }}
            },
            beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
            },
            success: function (response) {
                console.log(response);
                if(response.status == 1){
                    if($('#exportVpExcel').is(':checked')){
                        window.open(response.excel+'?app_type={{$type}}&ids='+response.ids);
                    }
                    if($('#exportVpLetter').is(':checked')){
                        window.open(response.pdf+'?ids='+response.ids, '_blank');
                    }
                    if($('#exportVpEnvelop').is(':checked')){
                        window.open(response.print+'?ids='+response.ids, '_blank');
                    }
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

    $("#queue_import_form").on("submit", function(event){
        event.preventDefault();
        $('span.text-success').remove();
        $('span.invalid-feedback').remove();
        $('input.is-invalid').removeClass('is-invalid');
        var formData = new FormData(this);
        $.ajax({
            method: "POST",
            data: formData,
            url: '{{route('secure-card.queue-import')}}',
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
                Swal.fire('INVALID FILE FORMAT','The format of the file is not valid or you have some invalid data in this file.','error')
            }
        });
    });

</script>

@endsection
