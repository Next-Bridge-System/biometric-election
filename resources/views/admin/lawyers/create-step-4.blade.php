@extends('layouts.admin')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Manage Existing Lawyers</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('lawyers.index')}}" class="btn btn-dark">Back</a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Add Existing Lawyer</h3>
                    </div>

                    @include('admin.lawyers.partials.steps')

                    <form action="#" method="POST" id="create_step_4_form" enctype="multipart/form-data"> @csrf
                        <div class="card-body">

                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Home Address</legend>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>House #:</label>
                                        <input type="text" class="form-control" name="ha_house_no">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Street Address:</label>
                                        <input type="text" class="form-control" name="ha_str_address">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Town/Mohalla:</label>
                                        <input type="text" class="form-control" name="ha_town">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>City:</label>
                                        <input type="text" class="form-control" name="ha_city">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Postal Code:</label>
                                        <input type="number" class="form-control" name="ha_postal_code">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Country:</label>
                                        <select name="ha_country_id" class="form-control custom-select">
                                            <option value="">--Select Country--</option>
                                            @foreach ($countries as $country)
                                            <option {{$country->name == 'Pakistan' ? 'selected' : ''}}
                                                value="{{$country->id}}">{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Province/State:</label>
                                        <select name="ha_province_id" class="form-control custom-select">
                                            <option value="">--Select Province--</option>
                                            @foreach ($provinces as $province)
                                            <option value="{{$province->id}}">{{$province->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>District :</label>
                                        <select name="ha_district_id" class="form-control custom-select">
                                            <option value="">--Select District--</option>
                                            @foreach ($districts as $district)
                                            <option value="{{$district->id}}">{{$district->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Tehsil :</label>
                                        <select name="ha_tehsil_id" class="form-control custom-select">
                                            <option value="">--Select Tehsil--</option>
                                            @foreach ($tehsils as $tehsil)
                                            <option value="{{$tehsil->id}}">{{$tehsil->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Postal Address</legend>
                                <div class="row">
                                    <div class="form-group">
                                        <div class="custom-control custom-switch mb-2 ml-2">
                                            <input type="checkbox" class="custom-control-input" name="same_address_btn"
                                                id="same_address_btn">
                                            <label class="custom-control-label" for="same_address_btn">
                                                Same as Home Address
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="postal_address_section">
                                    <div class="form-group col-md-6">
                                        <label>House #:</label>
                                        <input type="text" class="form-control postal_address" name="pa_house_no">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Street Address:</label>
                                        <input type="text" class="form-control postal_address" name="pa_str_address">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Town/Mohalla:</label>
                                        <input type="text" class="form-control postal_address" name="pa_town">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>City:</label>
                                        <input type="text" class="form-control postal_address" name="pa_city">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Postal Code:</label>
                                        <input type="number" class="form-control postal_address" name="pa_postal_code">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Country:</label>
                                        <select name="pa_country_id" class="form-control custom-select">
                                            <option value="">--Select Country--</option>
                                            @foreach ($countries as $country)
                                            <option {{$country->name == 'Pakistan' ? 'selected' : ''}}
                                                value="{{$country->id}}">{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Province/State:</label>
                                        <select name="pa_province_id" class="form-control custom-select">
                                            <option value="">--Select Province--</option>
                                            @foreach ($provinces as $province)
                                            <option value="{{$province->id}}">{{$province->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>District :</label>
                                        <select name="pa_district_id" class="form-control postal_address custom-select">
                                            <option value="" selected>Select</option>
                                            @foreach ($districts as $district)
                                            <option value="{{$district->id}}">{{$district->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Tehsil :</label>
                                        <select name="pa_tehsil_id" class="form-control postal_address custom-select">
                                            <option value="" selected>Select</option>
                                            @foreach ($tehsils as $tehsil)
                                            <option value="{{$tehsil->id}}">{{$tehsil->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success float-right" value="save">Save & Next</button>
                            <a href="{{route('lawyers.create-step-3', $lawyer->id)}}"
                                class="btn btn-secondary float-right mr-1">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script src="{{asset('public/js/app.js')}}"></script>
<script>
    jQuery(document).ready(function () {
        App.init();
    });
    $(document).ready(function(){
      $("#create_step_4_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('lawyers.create-step-4', $lawyer->id)}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
              },
              success: function (response) {
                if (response.status == 1) {
                    window.location.href = '{{route('lawyers.create-step-5', $lawyer->id)}}';
                }
              },
              error : function (errors) {
                errorsGet(errors.responseJSON.errors)
                $(".custom-loader").addClass('hidden');
              }
          });
      });
    });
</script>

<script>
    $('#same_address_btn').change(function (e) {
        e.preventDefault();
        var value = $(this).val();
        if (this.checked) {

            // var ha_house_no = $('input[name="ha_house_no"]').val();
            // var ha_str_address = $('input[name="ha_str_address"]').val();
            // var ha_town = $('input[name="ha_town"]').val();
            // var ha_city = $('input[name="ha_city"]').val();
            // var ha_postal_code = $('input[name="ha_postal_code"]').val();
            // var ha_country_id = $('input[name="ha_country_id"]').val();
            // var ha_province_id = $('input[name="ha_province_id"]').val();
            // var ha_district_id = $('select[name="ha_district_id"]').val();
            // var ha_tehsil_id = $('select[name="ha_tehsil_id"]').val();

            // var pa_house_no = $('input[name="pa_house_no"]').val(ha_house_no);
            // var pa_str_address = $('input[name="pa_str_address"]').val(ha_str_address);
            // var pa_town = $('input[name="pa_town"]').val(ha_town);
            // var pa_city = $('input[name="pa_city"]').val(ha_city);
            // var pa_postal_code = $('input[name="pa_postal_code"]').val(ha_postal_code);
            // var pa_country_id = $('input[name="pa_country_id"]').val(ha_country_id);
            // var pa_province_id = $('input[name="pa_province_id"]').val(ha_province_id);
            // var pa_district_id = $('select[name="pa_district_id"]').val(ha_district_id);
            // var pa_tehsil_id = $('select[name="pa_tehsil_id"]').val(ha_tehsil_id);

            $("#postal_address_section").addClass('hidden');
            // $(".postal_address").attr('disabled', true);
        } else {
            $("#postal_address_section").removeClass('hidden');
            // $(".postal_address").attr('disabled', false);

            // var pa_house_no = $('input[name="pa_house_no"]').val('');
            // var pa_str_address = $('input[name="pa_str_address"]').val('');
            // var pa_town = $('input[name="pa_town"]').val('');
            // var pa_city = $('input[name="pa_city"]').val('');
            // var pa_postal_code = $('input[name="pa_postal_code"]').val('');
            // var pa_country_id = $('input[name="pa_country_id"]').val('');
            // var pa_province_id = $('input[name="pa_province_id"]').val('');
            // var pa_district_id = $('select[name="pa_district_id"]').val('');
            // var pa_tehsil_id = $('select[name="pa_tehsil_id"]').val('');
        }
    });
</script>
@endsection
