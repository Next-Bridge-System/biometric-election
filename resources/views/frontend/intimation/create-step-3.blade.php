@extends('layouts.frontend')

<style>
    select option {
        text-transform: capitalize
    }
</style>


@section('content')

<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Add Intimation Application</h3>
                    </div>

                    @include('frontend.intimation.partials.steps')

                    <form action="#" method="POST" id="create_step_3_form" enctype="multipart/form-data"> @csrf
                        <div class="card-body">

                            <fieldset class="border p-4 mb-4">
                                <legend class="w-auto">Home Address</legend>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>House #: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="ha_house_no"
                                            value="{{isset($application->address->ha_house_no) ? $application->address->ha_house_no : '' }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Street Address: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="ha_str_address"
                                            value="{{isset($application->address->ha_str_address) ? $application->address->ha_str_address : '' }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Town/Mohalla:</label>
                                        <input type="text" class="form-control" name="ha_town"
                                            value="{{isset($application->address->ha_town) ? $application->address->ha_town : '' }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>City: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="ha_city"
                                            value="{{isset($application->address->ha_city) ? $application->address->ha_city : '' }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Postal Code:</label>
                                        <input type="number" class="form-control postal_code" name="ha_postal_code"
                                            value="{{isset($application->address->ha_postal_code) ? $application->address->ha_postal_code : '' }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Country: <span class="text-danger">*</span></label>
                                        <select name="ha_country_id" class="form-control custom-select"
                                            id="ha_country_id">
                                            <option value="">--Select Country--</option>
                                            @foreach ($countries as $country)
                                            <option @if (isset($application->address->ha_country_id))
                                                {{$country->id == $application->address->ha_country_id ? 'selected' :
                                                ''}}
                                                @else
                                                {{$country->id == 166 ? 'selected' : ''}}
                                                @endif
                                                value="{{$country->id}}">{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6 ha_pakistan_section">
                                        <label>Province/State: <span class="text-danger">*</span></label>
                                        <select name="ha_province_id" class="form-control custom-select"
                                            id="ha_province_id">
                                            <option value="">--Select Province--</option>
                                            @foreach ($provinces as $province)
                                            <option @if (isset($application->address->ha_province_id))
                                                {{$application->address->ha_province_id == $province->id ?
                                                'selected' : ''}}
                                                @endif
                                                value="{{$province->id}}" >{{$province->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6 ha_pakistan_section">
                                        <label>District : <span class="text-danger">*</span></label>
                                        <select name="ha_district_id" class="form-control custom-select"
                                            id="ha_district_id">
                                            <option value="">--Select District--</option>
                                            @foreach ($districts as $district)
                                            <option @if (isset($application->address->ha_district_id)) {{$district->id
                                                == $application->address->ha_district_id ?
                                                'selected' : ''}}
                                                @endif value="{{$district->id}}">{{$district->name}} - {{$district->code}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6 ha_pakistan_section">
                                        <label>Tehsil : <span class="text-danger">*</span></label>
                                        <select name="ha_tehsil_id" class="form-control custom-select"
                                            id="ha_tehsil_id">
                                            <option value="">--Select Tehsil--</option>
                                            @if (isset($application->address->ha_tehsil_id))
                                            @foreach ($tehsils->where('id',$application->address->ha_tehsil_id) as
                                            $tehsil)
                                            <option value="{{$tehsil->id}}" {{$tehsil->id ==
                                                $application->address->ha_tehsil_id ? 'selected' :
                                                ''}}>{{$tehsil->name}} - {{$tehsil->code}}</option>
                                            @endforeach
                                            @endif
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
                                                id="same_address_btn" @if (isset($application->address))
                                            {{$application->address->same_as == 1 ? 'checked' : ''}} @else checked
                                            @endif >
                                            <label class="custom-control-label" for="same_address_btn">
                                                Same as Home Address
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row  @if (isset($application->address))
                                    {{$application->address->same_as == 1 ? 'hidden' : ''}} @else hidden
                                    @endif" id="postal_address_section">
                                    <div class="form-group col-md-6">
                                        <label>House #: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control postal_address" name="pa_house_no"
                                            value="{{isset($application->address->pa_house_no) ? $application->address->pa_house_no : '' }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Street Address: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control postal_address" name="pa_str_address"
                                            value="{{isset($application->address->pa_str_address) ? $application->address->pa_str_address : '' }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Town/Mohalla:</label>
                                        <input type="text" class="form-control postal_address" name="pa_town"
                                            value="{{isset($application->address->pa_town) ? $application->address->pa_town : '' }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>City: <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control postal_address" name="pa_city"
                                            value="{{isset($application->address->pa_city) ? $application->address->pa_city : '' }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Postal Code:</label>
                                        <input type="number" class="form-control postal_address" name="pa_postal_code"
                                            value="{{isset($application->address->pa_postal_code) ? $application->address->pa_postal_code : '' }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Country: <span class="text-danger">*</span></label>
                                        <select name="pa_country_id" class="form-control custom-select postal_address"
                                            id="pa_country_id">
                                            <option value="">--Select Country--</option>
                                            @foreach ($countries as $country)
                                            <option @if (isset($application->address->pa_country_id))
                                                {{$country->id == $application->address->pa_country_id ? 'selected' :
                                                ''}}
                                                @else
                                                {{$country->id == 166 ? 'selected' : ''}}
                                                @endif
                                                value="{{$country->id}}">{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6 pa_pakistan_section">
                                        <label>Province/State: <span class="text-danger">*</span></label>
                                        <select name="pa_province_id" class="form-control custom-select postal_address"
                                            id="pa_province_id">
                                            <option value="">--Select Province--</option>
                                            @foreach ($provinces as $province)
                                            <option @if (isset($application->address->pa_province_id)) {{$province->id
                                                == $application->address->pa_province_id ?
                                                'selected' : ''}}
                                                @endif value="{{$province->id}}">{{$province->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6 pa_pakistan_section">
                                        <label>District : <span class="text-danger">*</span></label>
                                        <select name="pa_district_id" class="form-control postal_address custom-select"
                                            id="pa_district_id">
                                            <option value="" selected>Select</option>
                                            @foreach ($districts as $district)
                                            <option @if (isset($application->address->pa_district_id )) {{$district->id
                                                == $application->address->pa_district_id ?
                                                'selected' : ''}}
                                                @endif value="{{$district->id}}">{{$district->name}} - {{$district->code}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6 pa_pakistan_section">
                                        <label>Tehsil : <span class="text-danger">*</span></label>
                                        <select name="pa_tehsil_id" class="form-control postal_address custom-select"
                                            id="pa_tehsil_id">
                                            <option value="" selected>Select</option>
                                            <option value="">--Select Tehsil--</option>
                                            @if (isset($application->address->pa_tehsil_id))
                                            @foreach ($tehsils->where('id',$application->address->pa_tehsil_id) as
                                            $tehsil)
                                            <option value="{{$tehsil->id}}" {{$tehsil->id ==
                                                $application->address->pa_tehsil_id ? 'selected' :
                                                ''}}>{{$tehsil->name}} - {{$tehsil->code}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </fieldset>

                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success float-right" value="save">Save & Next</button>
                            <a href="{{route('frontend.intimation.create-step-2', $application->id)}}"
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
      $("#create_step_3_form").on("submit", function(event){
          event.preventDefault();
          $('span.text-success').remove();
          $('span.invalid-feedback').remove();
          $('input.is-invalid').removeClass('is-invalid');
          var formData = new FormData(this);
          $.ajax({
              method: "POST",
              data: formData,
              url: '{{route('frontend.intimation.create-step-3', $application->id)}}',
              processData: false,
              contentType: false,
              cache: false,
              beforeSend: function(){
                $(".custom-loader").removeClass('hidden');
              },
              success: function (response) {
                if (response.status == 1) {
                    window.location.href = '{{route('frontend.intimation.create-step-4', $application->id)}}';
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
            $("#postal_address_section").addClass('hidden');
            // $('.postal_address').prop('required',false);
        } else {
            $("#postal_address_section").removeClass('hidden');
            // $('.postal_address').prop('required',true);
        }
    });

    $("#ha_country_id").change(function (e) {
        e.preventDefault();
        var country_id = $(this).val();

        if (country_id == 166) {
            $(".ha_pakistan_section").show();
            // $('#ha_province_id').prop('required',true);
            // $('#ha_district_id').prop('required',true);
            // $('#ha_tehsil_id').prop('required',true);
        } else {
            $(".ha_pakistan_section").hide();
            // $('#ha_province_id').prop('required',false);
            // $('#ha_district_id').prop('required',false);
            // $('#ha_tehsil_id').prop('required',false);
        }
    });

    $("#pa_country_id").change(function (e) {
        e.preventDefault();
        var country_id = $(this).val();

        if (country_id == 166) {
            $(".pa_pakistan_section").show();
            // $('#pa_province_id').prop('required',true);
            // $('#pa_district_id').prop('required',true);
            // $('#pa_tehsil_id').prop('required',true);
        } else {
            $(".pa_pakistan_section").hide();
            // $('#pa_province_id').prop('required',false);
            // $('#pa_district_id').prop('required',false);
            // $('#pa_tehsil_id').prop('required',false);
        }
    });
</script>

<script>
    (function($) {
        $(document).ready(function() {
            var ha_country_id = $("#ha_country_id").val();
            if (ha_country_id == 166) {
                $(".ha_pakistan_section").show();
                // $('#ha_province_id').prop('required',true);
                // $('#ha_district_id').prop('required',true);
                // $('#ha_tehsil_id').prop('required',true);
            } else {
                $(".ha_pakistan_section").hide();
                // $('#ha_province_id').prop('required',false);
                // $('#ha_district_id').prop('required',false);
                // $('#ha_tehsil_id').prop('required',false);
            }

            var pa_country_id = $("#pa_country_id").val();
            if (pa_country_id == 166) {
                $(".pa_pakistan_section").show();
                // $('#pa_province_id').prop('required',true);
                // $('#pa_district_id').prop('required',true);
                // $('#pa_tehsil_id').prop('required',true);
            } else {
                $(".pa_pakistan_section").hide();
                // $('#pa_province_id').prop('required',false);
                // $('#pa_district_id').prop('required',false);
                // $('#pa_tehsil_id').prop('required',false);
            }
        });
    })(jQuery);
</script>

<script>
    $('#ha_district_id').on('change', function () {
        var district_id = $('#ha_district_id').find(":selected").val();
        var option = '';
        $.ajax({
            method: "POST",
            url: '{{route('getTehsilsByDistrict')}}',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                'district_id': district_id
            },
            success: function (response) {
                $('#ha_tehsil_id').empty();
                $('#ha_tehsil_id').append(' <option value="" selected>--Select Tehsil--</option>');
                response.tehsils.forEach(function (item, index) {
                    option = "<option value='" + item.id + "'>" + item.name + ' - ' + item.code + "</option>"
                    $('#ha_tehsil_id').append(option);
                });
            }
        });
    });

    $('#pa_district_id').on('change', function () {
        var district_id = $('#pa_district_id').find(":selected").val();
        var option = '';
        $.ajax({
            method: "POST",
            url: '{{route('getTehsilsByDistrict')}}',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                'district_id': district_id
            },
            success: function (response) {
                $('#pa_tehsil_id').empty();
                $('#pa_tehsil_id').append(' <option value="" selected>--Select Tehsil--</option>');
                response.tehsils.forEach(function (item, index) {
                    option = "<option value='" + item.id + "'>" + item.name + ' - ' + item.code + "</option>"
                    $('#pa_tehsil_id').append(option);
                });
            }
        });
    });
</script>
@endsection
