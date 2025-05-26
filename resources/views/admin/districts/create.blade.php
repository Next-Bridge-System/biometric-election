@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Districts</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('districts.index')}}" class="btn btn-dark">Back</a>
                    </li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- jquery validation -->
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Add District</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="#" method="POST" id="store_district_form"> @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Division <span class="required-star">*</span></label>
                                    <select name="division_id" id="division_id" class="form-control custom-select" required>
                                        <option value="" selected>--Select Division--</option>
                                        @foreach ($divisions as $division)
                                        <option value="{{$division->id}}">{{$division->name}} - {{$division->code}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>District Name <span class="required-star">*</span></label>
                                    <input type="text" class="form-control" name="district_name" required>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>District Code <span class="required-star">*</span></label>
                                    <input type="number" class="form-control" name="district_code" required>
                                </div>
                            </div>
                            <fieldset class="border p-4">
                                <legend class="w-auto">Tehsils of District</legend>
                                <div class="row" id="add_tehsil_section">
                                    <div class="form-group col-md-4">
                                        <label>Tehsil Name <span class="required-star">*</span></label>
                                        <input type="text" class="form-control" name="tehsils[0][tehsil_name]" required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Tehsil Code <span class="required-star">*</span></label>
                                        <input type="number" class="form-control" name="tehsils[0][tehsil_code]" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <button type="button" class="btn btn-success" id="add_tehsil_btn"><i
                                                class="fas fa-plus mr-1" aria-hidden="true"></i>Add Tehsil</button>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success float-right">Save & Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
            <!-- right column -->
            <div class="col-md-6">

            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection

@section('scripts')
<script src="{{asset('public/js/app.js')}}"></script>

<script>
    jQuery(document).ready(function () {
        App.init();
    });
    $(document).ready(function(){
      $("#store_district_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var submitButtonType = $("button[type=submit]:focus").val();
          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('districts.store')}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
              },
              success: function (response) {
                if (response.status == 1) {
                    window.location.href = '{{route('districts.index')}}';
                }
              },
              error : function (errors) {
                errorsGet(errors.responseJSON.errors)
                $(".custom-loader").addClass('hidden');
              }
          });
      });
    });


    $('#add_tehsil_btn').click(function (e) {
        e.preventDefault();
        var num = $('#add_tehsil_section .row').length; ++num;
        $("#add_tehsil_section").append('\
            <div class="col-md-12" id="add_tehsil_section">\
                <div class="row">\
                    <div class="form-group col-md-4">\
                        <label>Tehsil Name*</label>\
                        <input type="text" class="form-control" name="tehsils['+num+'][tehsil_name]" required>\
                    </div>\
                    <div class="form-group col-md-2">\
                        <label>Tehsil Code*</label>\
                        <input type="number" class="form-control" name="tehsils['+num+'][tehsil_code]" required>\
                    </div>\
                    <div class="form-group col-md-6">\
                        <button type="button" class="btn btn-danger btn-sm" style="margin-top:35px" onclick="removeTehsil(this);"><i class="far fa-trash-alt mr-1" aria-hidden="true"></i>Remove</button>\
                    </div>\
                </div>\
            </div>\
        ');
    });

    function removeTehsil(element) {
        $(element).closest('#add_tehsil_section').remove();
    }

</script>
@endsection
