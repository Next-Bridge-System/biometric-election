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
                        <h3 class="card-title">Add University</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="#" id="store_university_form" method="POST"> @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Type<span class="text-danger">*</span>:</label>
                                    <select name="type" id="type" class="form-control custom-select" required>
                                        <option value="" selected>--Select Type--</option>
                                        <option value="1">University</option>
                                        <option value="2">Affliated With</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12 university_select hidden">
                                    <label>University<span class="required-star">*</span></label>
                                    <select name="aff_university_id" id="aff_university_id"
                                        class="form-control custom-select">
                                        <option value="" selected>--Select University--</option>
                                        @foreach ($universities as $uni)
                                        <option value="{{$uni->id}}">{{$uni->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row hidden university_section">
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Name <span class="required-star">*</span></label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Phone No.<span class="required-star">*</span></label>
                                    <input type="tel" class="form-control " name="university_phone">
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Email <span class="required-star">*</span></label>
                                    <input type="email" class="form-control " name="email" required>
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Country<span class="required-star">*</span></label>
                                    <select name="country_id" id="country_id" class="form-control custom-select">
                                        @foreach ($countries as $country)
                                        <option {{$country->name == 'Pakistan' ? 'selected' : ''}}
                                            value="{{$country->id}}" >{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12 pakistan_section">
                                    <label>Province<span class="required-star">*</span></label>
                                    <select name="province_id" id="province_id" class="form-control custom-select">
                                        <option value="">--Select Province--</option>
                                        @foreach ($provinces as $key => $province)
                                        <option value="{{$province->id}}">{{$province->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12 pakistan_section">
                                    <label>District<span class="required-star">*</span></label>
                                    <select name="district_id" id="district_id" class="form-control custom-select">
                                        <option value="">--Select District--</option>
                                        @foreach ($districts as $key => $district)
                                        <option value="{{$district->id}}">{{$district->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>City<span class="required-star">*</span></label>
                                    <input type="text" class="form-control " name="city">
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Address<span class="required-star">*</span></label>
                                    <input type="text" class="form-control " name="address">
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <label>Postal Code<span class="required-star">*</span></label>
                                    <input type="number" class="form-control " name="postal_code">
                                </div>
                            </div>
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
      $("#store_university_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
            method: "POST",
            data: formData,
            url: '{{route('universities.store')}}',
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
            $(".pakistan_section").show();
            $('#province_id').prop('required',true);
            $('#district_id').prop('required',true);
        } else {
            $(".pakistan_section").hide();
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
