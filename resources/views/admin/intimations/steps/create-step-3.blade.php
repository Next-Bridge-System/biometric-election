<form class="steps-form" action="#" data-action="{{route('intimations.create-step-3',$application->id)}}" method="POST" id="create_step_3_form" enctype="multipart/form-data"> @csrf
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
                                    value="{{$province->id}}">{{$province->name}}</option>
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
        <a href="javascript:void" onclick="goToStep('{{route('intimations.create-step-2', $application->id)}}',2)"
           class="btn btn-secondary float-right mr-1">Back</a>
    </div>
</form>


