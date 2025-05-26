@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Universities</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('universities.index')}}" class="btn btn-dark">Back</a>
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
                        <h3 class="card-title">Edit University</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="#" id="update_university_form" method="POST"> @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Type<span class="text-danger">*</span>:</label>
                                    <select name="type" id="type" class="form-control custom-select" required>
                                        <option value="" selected>--Select Type--</option>
                                        <option value="1" {{$university->type == 1 ? 'selected' : ''}}>University
                                        </option>
                                        <option value="2" {{$university->type == 2 ? 'selected' : ''}}>Affliated With
                                        </option>
                                    </select>
                                </div>
                                <div
                                    class="form-group col-md-6 col-sm-6 col-xs-12 university_select {{$university->type == 1 ? 'hidden' : ''}}">
                                    <label>University<span class="required-star">*</span></label>
                                    <select name="aff_university_id" id="aff_university_id"
                                        class="form-control custom-select">
                                        <option value="" selected>--Select University--</option>
                                        @foreach ($universities as $uni)
                                        <option {{$uni->id == $university->aff_university_id ? 'selected' : ''}}
                                            value="{{$uni->id}}">{{$uni->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Name <span class="required-star">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{$university->name}}"
                                        required>
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Phone No.<span class="required-star">*</span></label>
                                    <input type="tel" class="form-control " name="university_phone"
                                        value="{{$university->phone}}">
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Email <span class="required-star">*</span></label>
                                    <input type="email" class="form-control " name="email"
                                        value="{{$university->email}}" required>
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Country<span class="required-star">*</span></label>
                                    <select name="country_id" id="country_id" class="form-control custom-select">
                                        @foreach ($countries as $country)
                                        <option {{$country->id == $university->country_id ? 'selected' : ''}}
                                            value="{{$country->id}}" >{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12 pakistan_section
                                    {{$university->country_id == '166' ? '' : 'hidden'}}">
                                    <label>Province<span class="required-star">*</span></label>
                                    <select name="province_id" id="province_id" class="form-control custom-select">
                                        <option value="">--Select Province--</option>
                                        @foreach ($provinces as $key => $province)
                                        <option {{$province->id == $university->province_id ? 'selected' : ''}}
                                            value="{{$province->id}}">{{$province->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12 pakistan_section
                                    {{$university->country_id == '166' ? '' : 'hidden'}}">
                                    <label>District<span class="required-star">*</span></label>
                                    <select name="district_id" id="district_id" class="form-control custom-select">
                                        <option value="">--Select District--</option>
                                        @foreach ($districts as $key => $district)
                                        <option {{$district->id == $university->district_id ? 'selected' : ''}}
                                            value="{{$district->id}}">{{$district->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>City<span class="required-star">*</span></label>
                                    <input type="text" class="form-control " name="city" value="{{$university->city}}">
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Address<span class="required-star">*</span></label>
                                    <input type="text" class="form-control " name="address"
                                        value="{{$university->address}}">
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Postal Code<span class="required-star">*</span></label>
                                    <input type="number" class="form-control " name="postal_code"
                                        value="{{$university->postal_code}}">
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>University Status<span class="required-star">*</span></label>
                                    <select name="approved" id="approved" class="form-control custom-select">
                                        <option value="1" {{$university->approved == 1 ? 'selected' : ''}}>Approved
                                        </option>
                                        <option value="0" {{$university->approved == 0 ? 'selected' : ''}}>Unapproved
                                        </option>
                                    </select>
                                </div>
                            </div>
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

@endsection

@section('scripts')
<script src="{{asset('public/js/app.js')}}"></script>
<script>
    jQuery(document).ready(function () {
        App.init();
    });

    $(document).ready(function(){
      $("#update_university_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
            method: "POST",
            data: formData,
            url: '{{route('universities.update',$university->id)}}',
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
            },
            success: function (response) {
                if (response.status == 1) {
                    window.location.href = '{{route('universities.index')}}';
                }
            },
            error : function (errors) {
                errorsGet(errors.responseJSON.errors)
                $(".custom-loader").addClass('hidden');
                $("#error_message").removeClass('hidden');
            }
          });
      });
    });

    $("#country_id").change(function (e) {
        e.preventDefault();
        var country_id = $(this).val();
        if (country_id == 166) {
            $(".pakistan_section").removeClass('hidden');
            $('#province_id').prop('required',true);
            $('#district_id').prop('required',true);
        } else {
            $(".pakistan_section").addClass('hidden');
            $('#province_id').prop('required',false);
            $('#district_id').prop('required',false);
        }
    });

    $("#type").change(function (e) {
        e.preventDefault();
        var type = $(this).val();
        if (type == 1) {
            $(".university_section").removeClass('hidden');
            $(".university_select").addClass('hidden');
        } else if (type == 2) {
            $(".university_section").removeClass('hidden');
            $(".university_select").removeClass('hidden');
        } else {
            $(".university_section").addClass('hidden');
            $(".university_select").addClass('hidden');
        }
    });
</script>
@endsection
