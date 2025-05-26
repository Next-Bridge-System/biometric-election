@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Operators/Managers</h1>
            </div>
        </div>
    </div><!-- /.container -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- jquery validation -->
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Add Operator</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{route('admins.store')}}" method="POST"> @csrf
                        <div class="card-body">
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Account Information</legend>
                                <div class="row">
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label>Name <span class="required-star">*</span></label>
                                        <input type="text" maxlength="100" class="form-control" name="name"
                                            value="{{ old('name') }}" required>
                                    </div>
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label>Email <span class="required-star">*</span></label>
                                        <input type="email" class="form-control " name="email"
                                            value="{{ old('email') }}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Mobile No. <span class="required-star">*</span></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><img
                                                        src="{{asset('public/admin/images/pakistan.png')}}"
                                                        alt=""></span>
                                                <span class="input-group-text">+92</span>
                                            </div>
                                            <input type="tel" class="form-control" name="phone"
                                                value="{{ old('phone') }}">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label for="">Operator Type</label><span class="required-star">*</span><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_super"
                                                id="inlineRadio1" value="1">
                                            <label class="form-check-label" for="inlineRadio1">Punjab Bar Operator
                                                <small>(Have access of all bars)</small></label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_super"
                                                id="inlineRadio2" value="0">
                                            <label class="form-check-label" for="inlineRadio2">Bar Operator</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label>Bar Associate Permissions <span class="required-star">*</span></label>
                                        <select class="form-control" name="bar_id" id="bar_id" required>
                                            <option value="">Select Bar Associate</option>
                                            @foreach ($barAssociates as $item)
                                            <option value="{{$item->id}}" {{ old('bar_id')==$item->id ? 'selected' :
                                                ''}}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Account Permissions</legend>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <div>
                                            <input type="checkbox" id="checkAllPermissions"> <label
                                                for="checkAllPermissions">Select All</label>
                                        </div>
                                        @foreach ($permissions as $permission)
                                        @if ($permission->type == 'parent') <br> @endif
                                        <div>
                                            <input type="checkbox" id="permissions-{{$permission->id}}"
                                                name="permissions[]" value="{{$permission->id}}">
                                            <span for="permissions"
                                                class="{{$permission->type == 'parent' ? 'text-success text-bold': ''}}">{{$permission->name}}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Account Password</legend>
                                <div class="row">
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label>Password <span class="required-star">*</span></label>
                                        <input type="password" id="password" class="form-control" name="password"
                                            required>
                                    </div>
                                    <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                        <label>Confirm Password <span class="required-star">*</span></label>
                                        <input id="password_confirm" type="password" class="form-control"
                                            name="password_confirmation" required>
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
<script>
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    $('#checkAllPermissions').on('change',function(event){
        if($(this).is(':checked')){
            $('input[name="permissions[]"]').prop('checked',true)
        }else{
            $('input[name="permissions[]"]').prop('checked',false);
        }
    })

    $('input[name="permissions[]"]').on('change',function(){
        if($('input[name="permissions[]"]:checked').length ===  $('input[name="permissions[]"]').length){
            $('#checkAllPermissions').prop('checked',true);
        }else{
            $('#checkAllPermissions').prop('checked',false);
        }
    });

    $('input[name="is_super"]').on('change',function(){
        if($('input[name="is_super"]:checked').val() == "1"){
            $('#bar_id').prop('disabled',true)
        }else{
            $('#bar_id').prop('disabled',false)
        }
    });
</script>

@endsection
