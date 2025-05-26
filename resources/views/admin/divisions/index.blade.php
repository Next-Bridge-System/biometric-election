@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Divisions</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addDivisionModal">
                            <i class="fas fa-plus mr-1" aria-hidden="true"></i>Add Division</button>
                        <div class="modal fade" id="addDivisionModal" tabindex="-1" aria-labelledby="addDivisionModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="#" id="add_division_form" method="POST"> @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addDivisionModalLabel">Add Division</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group col-md-12">
                                                <label>Name <span class="required-star">*</span></label>
                                                <input type="text" class="form-control" name="name" required>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Code <span class="required-star">*</span></label>
                                                <input type="number" class="form-control" name="code" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success save_btn">Save &
                                                Submit</button>
                                            <button class="btn btn-success hidden loading_btn" type="button"
                                                disabled><span class="spinner-grow spinner-grow-sm" role="status"
                                                    aria-hidden="true"></span> Loading...</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
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
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Divisions List (Total Divisions : {{$divisions->count()}})
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width:20px">#</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($divisions as $division)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$division->name}} - {{$division->code}}</td>
                                    <td>
                                        @if (Auth::guard('admin')->user()->hasPermission('edit-districts'))
                                        <a href="#" data-toggle="modal" data-target="#editDivisionModel{{$division->id}}">
                                            <span class="badge badge-primary"><i class="far fa-edit mr-1"
                                                    aria-hidden="true"></i>Edit</span>
                                        </a>
                                        @endif

                                        @if (Auth::guard('admin')->user()->hasPermission('delete-districts'))
                                        <a href="javascript:void(0)" data-action="{{route('divisions.destroy',$division->id)}}" onclick="deleteDivision(this)">
                                            <span class="badge badge-danger"><i class="far fa-trash-alt mr-1"
                                                    aria-hidden="true"></i>Delete</span>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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

@foreach ($divisions as $division)
<div class="modal fade" id="editDivisionModel{{$division->id}}" tabindex="-1" aria-labelledby="editDivisionModel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="#" class="update_division_form" method="POST"> @csrf
            <input type="hidden" name="division_id" value="{{$division->id}}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDivisionModel">Edit Bar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <label>Name <span class="required-star">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{$division->name}}" required>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Code <span class="required-star">*</span></label>
                        <input type="number" class="form-control" name="code" value="{{$division->code}}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success save_btn">Save &
                        update</button>
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
    $(function () {
      $("#table").DataTable({
        "responsive": true,
        "autoWidth": false,
      });
    });

    jQuery(document).ready(function () {
        App.init();
    });

    $(document).ready(function(){
      $("#add_division_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
            method: "POST",
            data: formData,
            url: '{{route('divisions.store')}}',
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

      $(".update_division_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
            method: "POST",
            data: formData,
            url: '{{route('divisions.update')}}',
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

    function deleteDivision(event){
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
