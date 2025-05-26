<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{asset('public/js/app.js')}}"></script>

@if (Route::currentRouteName() == 'high-court.index')
<script>
    $(document).ready(function () {
        table = $('#applications_table').DataTable({
            processing: true,
            serverSide: true,
            "responsive": true,
            "autoWidth": false,
            "searching": true,
            ajax: {
                url: "{{ route('high-court.index') }}",
                data: function (d) {
                    d.application_date = $('#application_date').val(),
                    d.application_date_range = $('#custom_date_range_input').val(),
                    d.application_submitted_by = $('#application_submitted_by').val(),
                    d.payment_status = $('#payment_status').val(),
                    d.payment_type = $('#payment_type').val(),
                    d.search = $('input[type="search"]').val()
                }
            },
            order:[[1,"desc"]],
            columns: [
                {data: 'id', name: 'id'},
                {data: 'user_id', name: 'user_id'},
                {data: 'lawyer_name', name: 'lawyer_name'},
                {data: 'cnic_no', name: 'cnic_no'},
                {data: 'mobile_no', name: 'mobile_no'},
                {data: 'app_status', name: 'app_status',orderable: false, searchable: false},
                // {data: 'payment_status', name: 'payment_status',orderable: false, searchable: false},
                {data: 'created_by', name: 'created_by',orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            drawCallback: function (response) {
                $('#countTotal').empty();
                $('#countTotal').append(response['json'].recordsTotal);
            }
        });
    });
</script>
@endif

@if (Route::currentRouteName() == 'high-court.partial')
<script>
    $(document).ready(function () {
        table = $('#applications_table').DataTable({
            processing: true,
            serverSide: true,
            "responsive": true,
            "autoWidth": false,
            "searching": true,
            ajax: {
                url: "{{ route('high-court.partial') }}",
                data: function (d) {
                    d.application_date = $('#application_date').val(),
                    d.application_date_range = $('#custom_date_range_input').val(),
                    d.application_submitted_by = $('#application_submitted_by').val(),
                    d.payment_status = $('#payment_status').val(),
                    d.payment_type = $('#payment_type').val(),
                    d.search = $('input[type="search"]').val()
                }
            },
            order:[[1,"desc"]],
            columns: [
                {data: 'id', name: 'id'},
                {data: 'user_id', name: 'user_id'},
                {data: 'lawyer_name', name: 'lawyer_name'},
                {data: 'cnic_no', name: 'cnic_no'},
                {data: 'mobile_no', name: 'mobile_no'},
                {data: 'app_status', name: 'app_status',orderable: false, searchable: false},
                // {data: 'payment_status', name: 'payment_status',orderable: false, searchable: false},
                {data: 'created_by', name: 'created_by',orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            drawCallback: function (response) {
                $('#countTotal').empty();
                $('#countTotal').append(response['json'].recordsTotal);
            }
        });
    });
</script>
@endif

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
    });

    $('#userRegistrationForm').on('submit',function (event){
        event.preventDefault();
        console.log(event.target.dataset.target);
        url = event.target.dataset.target;
        $('span.text-success').remove();
        $('span.invalid-feedback').remove();
        $('input.is-invalid').removeClass('is-invalid');
        var formData = new FormData(this);
        $.ajax({
            method: "POST",
            data: formData,
            url: url,
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function(){
                $(".save_btn").hide();
                $(".save_btn_loader").show();
            },
            success: function (response) {
                $('#addIntimationApplication').modal('hide');
                $(".custom-loader").removeClass('hidden');
                setTimeout(function (){
                    location.href = response.url
                },800)
            },
            error : function (errors) {
                $(".save_btn").show();
                $(".save_btn_loader").hide();
                $(".custom-loader").addClass('hidden');
                errorsGet(errors.responseJSON.errors)
            }
        });
    })

    function reports() {
        $("#report_application_type").val($("#application_type").val());
        $("#report_application_date").val($("#application_date").val());
        $("#report_application_date_range").val($("#application_date_range").val());
        $("#application_reports_pdf_form").submit();
    }

    $("#lc_excel_import_form").on("submit", function(event){
        event.preventDefault();
        $('span.text-success').remove();
        $('span.invalid-feedback').remove();
        $('input.is-invalid').removeClass('is-invalid');
        var formData = new FormData(this);
        $.ajax({
            method: "POST",
            data: formData,
            url: '{{route('high-court.excel.import')}}',
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
