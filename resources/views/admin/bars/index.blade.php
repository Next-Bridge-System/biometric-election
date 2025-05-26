@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Bars</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        @if (Auth::guard('admin')->user()->hasPermission('add-bars'))
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addBarModel">
                            <i class="fas fa-plus mr-1" aria-hidden="true"></i>Add Bar</button>
                        <div class="modal fade" id="addBarModel" tabindex="-1" aria-labelledby="addBarModelLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="#" id="store_bar_form" method="POST"> @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addBarModelLabel">Add Bar</h5>
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
                                                <label>District<span class="required-star">*</span></label>
                                                <select name="district_id" id="district_id"
                                                    class="form-control custom-select">
                                                    <option value="">--Select District--</option>
                                                    @foreach ($districts as $key => $district)
                                                    <option value="{{$district->id}}">{{$district->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Tehsil<span class="required-star">*</span></label>
                                                <select name="tehsil_id" id="tehsil_id"
                                                    class="form-control custom-select">
                                                    <option value="">--Select Tehsil--</option>
                                                    @foreach ($tehsils as $key => $tehsil)
                                                    <option value="{{$tehsil->id}}">{{$tehsil->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary save_btn">Save &
                                                Submit</button>
                                            <button class="btn btn-primary hidden loading_btn" type="button"
                                                disabled><span class="spinner-grow spinner-grow-sm" role="status"
                                                    aria-hidden="true"></span> Loading...</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
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
                            Bars List (Total Bars : {{$bars->count()}})
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="bars_table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width:20px">#</th>
                                    <th>Bar ID</th>
                                    <th>Name</th>
                                    @if (Auth::guard('admin')->user()->hasPermission('edit-bars') ||
                                    Auth::guard('admin')->user()->hasPermission('delete-bars'))
                                    <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bars as $bar)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$bar->id}}</td>
                                    <td>{{$bar->name}}</td>
                                    @if (Auth::guard('admin')->user()->hasPermission('edit-bars') ||
                                    Auth::guard('admin')->user()->hasPermission('delete-bars')) <td>

                                        @if (Auth::guard('admin')->user()->hasPermission('edit-bars'))
                                        <a href="#" data-toggle="modal" data-target="#editBarModel{{$bar->id}}">
                                            <span class="badge badge-primary"><i class="far fa-edit mr-1"
                                                    aria-hidden="true"></i>Edit</span>
                                        </a>
                                        @endif

                                        {{-- @if (Auth::guard('admin')->user()->hasPermission('delete-bars'))
                                        <a href="javascript:void(0)" data-action="{{route('bars.destroy',$bar->id)}}"
                                            onclick="deleteBar(this)">
                                            <span class="badge badge-danger"><i class="far fa-trash-alt mr-1"
                                                    aria-hidden="true"></i>Delete</span>
                                        </a>
                                        @endif --}}
                                    </td>
                                    @endif
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


@foreach ($bars as $bar)
<div class="modal fade" id="editBarModel{{$bar->id}}" tabindex="-1" aria-labelledby="editBarModelLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <form action="#" class="update_bar_form" method="POST"> @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBarModelLabel">Edit Bar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <label>Name <span class="required-star">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{$bar->name}}" required>
                    </div>
                    <div class="form-group col-md-12">
                        <label>District<span class="required-star">*</span></label>
                        <select name="district_id" id="district_id" class="form-control custom-select">
                            <option value="">--Select District--</option>
                            @foreach ($districts as $key => $district)
                            <option {{$district->id == $bar->district_id ? 'selected' : ''}}
                                value="{{$district->id}}">{{$district->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Tehsil<span class="required-star">*</span></label>
                        <select name="tehsil_id" id="tehsil_id" class="form-control custom-select">
                            <option value="">--Select Tehsil--</option>
                            @foreach ($tehsils as $key => $tehsil)
                            <option {{$tehsil->id == $bar->tehsil_id ? 'selected' : ''}}
                                value="{{$tehsil->id}}">{{$tehsil->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary save_btn">Save &
                        update</button>
                    <button class="btn btn-primary hidden loading_btn" type="button" disabled><span
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
      $("#bars_table").DataTable({
        "responsive": true,
        "autoWidth": false,
      });
    });

    jQuery(document).ready(function () {
        App.init();
    });

    $(document).ready(function(){
      $("#store_bar_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
            method: "POST",
            data: formData,
            url: '{{route('bars.store')}}',
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

      $(".update_bar_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
            method: "POST",
            data: formData,
            url: '{{route('bars.store')}}',
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

    function deleteBar(event){
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
