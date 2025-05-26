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
                        <h3 class="card-title">Edit District</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="#" method="POST" id="update_district_form"> @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Division <span class="required-star">*</span></label>
                                    <select name="division_id" id="division_id" class="form-control custom-select" required>
                                        <option value="" selected>--Select Division--</option>
                                        @foreach ($divisions as $division)
                                        <option {{$division->id == $district->division_id ? 'selected' : ''}} value="{{$division->id}}">{{$division->name}} - {{$division->code}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>District Name <span class="required-star">*</span></label>
                                    <input type="text" class="form-control" name="district_name"
                                        value="{{$district->name}}" required>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>District Code <span class="required-star">*</span></label>
                                    <input type="number" class="form-control" name="district_code"
                                        value="{{$district->code}}" required>
                                </div>
                            </div>
                            <fieldset class="border p-4">
                                <legend class="w-auto">Tehsils of District</legend>
                                <div class="row">
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addtehsilModal">
                                        <i class="fas fa-plus mr-1" aria-hidden="true"></i>Add Tehsil
                                    </button>
                                </div>
                                <div class="row">
                                    <table class="table table-bordered table-striped mt-4">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>District</th>
                                                <th>Tehsil Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($district->tehsils as $tehsil)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$district->name}} - {{$district->code}}</td>
                                                <td>{{$tehsil->name}} - {{$tehsil->code}}</td>
                                                <td>
                                                    <a href="#" data-toggle="modal" data-target="#editTehsilModal{{$tehsil->id}}">
                                                        <span class="badge badge-primary"><i class="far fa-edit mr-1"
                                                                aria-hidden="true"></i>Edit</span>
                                                    </a>

                                                    <a href="javascript:void(0)" data-action="{{route('districts.tehsils.destroy',$tehsil->id)}}" onclick="deleteTehsil(this)">
                                                        <span class="badge badge-danger">
                                                            <i class="far fa-trash-alt mr-1"
                                                                aria-hidden="true"></i>Delete</span>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success float-right">Save & Update</button>
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


<div class="modal fade" id="addtehsilModal" tabindex="-1" aria-labelledby="addtehsilModalLabel" aria-hidden="true">
    <form action="#" method="POST" id="add_tehsil_form"> @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="addtehsilModalLabel">Add Tehsil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-8">
                        <label>Tehsil Name <span class="required-star">*</span></label>
                        <input type="text" class="form-control" name="tehsil_name" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Tehsil Code <span class="required-star">*</span></label>
                        <input type="number" class="form-control" name="tehsil_code" required>
                    </div>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save & Submit</button>
                </div>
            </div>
        </div>
    </form>
</div>


@foreach ($district->tehsils as $tehsil)
<div class="modal fade" id="editTehsilModal{{$tehsil->id}}" tabindex="-1" aria-labelledby="editTehsilModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="#" class="update_tehsil_form" method="POST"> @csrf
            <input type="hidden" name="tehsil_id" value="{{$tehsil->id}}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTehsilModalLabel">Edit Tehsil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-8">
                            <label>Tehsil Name <span class="required-star">*</span></label>
                            <input type="text" class="form-control" name="tehsil_name" value="{{$tehsil->name}}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>Tehsil Code <span class="required-star">*</span></label>
                            <input type="number" class="form-control" name="tehsil_code" value="{{$tehsil->code}}" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success save_btn">Save &
                        Update</button>
                    <button class="btn btn-success hidden loading_btn" type="button" disabled><span
                            class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                        Loading...</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach


@endsection

@section('scripts')
<script src="{{asset('public/js/app.js')}}"></script>

<script>
    jQuery(document).ready(function () {
        App.init();
    });
    $(document).ready(function(){
      $("#update_district_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var submitButtonType = $("button[type=submit]:focus").val();
          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('districts.update',$district->id)}}',
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

      $("#add_tehsil_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var submitButtonType = $("button[type=submit]:focus").val();
          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('districts.tehsils.store',$district->id)}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
              },
              success: function (response) {
                if (response.status == 1) {
                    window.location.href = '{{route('districts.edit', $district->id)}}';
                }
              },
              error : function (errors) {
                errorsGet(errors.responseJSON.errors)
                $(".custom-loader").addClass('hidden');
              }
          });
      });

      $(".update_tehsil_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
            method: "POST",
            data: formData,
            url: '{{route('districts.tehsils.update',$district->id)}}',
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
            }
          });
      });

    });

    function deleteTehsil(event){
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

</script>
@endsection
