<div>
    <button type="button" class="btn btn-link float-right btn-sm mr-1" data-toggle="modal"
        data-target="#edit_high_court_{{$type}}" wire:click="edit">
        <i class="far fa-edit mr-1"></i>
    </button>

    <div class="modal fade" id="edit_high_court_{{$type}}" tabindex="-1" aria-labelledby="edit_high_court_{{$type}}"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_high_court_{{$type}}">Edit {{$type}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($type == 'LAWYER')
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="">Fisrt Name</label>
                            <input type="text" class="form-control" wire:model.defer="first_name">
                            @error('first_name')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Last Name</label>
                            <input type="text" class="form-control" wire:model.defer="last_name">
                            @error('last_name')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Father Name</label>
                            <input type="text" class="form-control" wire:model.defer="father_name">
                            @error('father_name')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Gender</label>
                            <input type="text" class="form-control" wire:model.defer="gender">
                            @error('gender')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Date of Birth</label>
                            <input type="text" class="form-control" wire:model.defer="date_of_birth">
                            @error('date_of_birth')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Blood</label>
                            <input type="text" class="form-control" wire:model.defer="blood">
                            @error('blood')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Email</label>
                            <input type="email" class="form-control" wire:model.defer="email">
                            @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Mobile</label>
                            <input type="text" class="form-control" wire:model.defer="phone">
                            @error('phone')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">CNIC</label>
                            <input type="text" class="form-control" wire:model.defer="cnic_no">
                            @error('cnic_no')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">CNIC Expiry Date</label>
                            <input type="text" class="form-control" wire:model.defer="cnic_expired_at">
                            @error('cnic_expired_at')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    @endif

                    @if ($type == 'BAR')
                    <div class="row">
                        <div class="form-group">
                            <select wire:model.defer="voter_member_hc" class="form-control custom-select">
                                @foreach ($bars as $bar)
                                <option value="{{$bar->id}}">{{$bar->name}}</option>
                                @endforeach
                            </select>
                            @error('voter_member_hc')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    @endif


                    @if ($type == 'ADDRESS')
                    <fieldset class="border p-4 mb-4">
                        <legend class="w-auto">Home Address</legend>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>House Address#: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model.defer="ha_house_no">
                                @error('ha_house_no')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label>City: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model.defer="ha_city">
                                @error('ha_city')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label>Country: <span class="text-danger">*</span></label>
                                <select wire:model.defer="ha_country_id" class="form-control custom-select">
                                    @foreach ($countries as $country)
                                    <option value="{{$country->id}}">{{$country->name}}</option>
                                    @endforeach
                                </select>
                                @error('ha_country_id')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group col-md-6 ha_pakistan_section">
                                <label>Province/State: <span class="text-danger">*</span></label>
                                <select wire:model.defer="ha_province_id" class="form-control custom-select">
                                    @foreach ($provinces as $province)
                                    <option value="{{$province->id}}">{{$province->name}}</option>
                                    @endforeach
                                </select>
                                @error('ha_province_id')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group col-md-6 ha_pakistan_section">
                                <label>District : <span class="text-danger">*</span></label>
                                <select wire:model.defer="ha_district_id" class="form-control custom-select">
                                    @foreach ($districts as $district)
                                    <option value="{{$district->id}}">{{$district->name}} - {{$district->code}}
                                    </option>
                                    @endforeach
                                </select>
                                @error('ha_district_id')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group col-md-6 ha_pakistan_section">
                                <label>Tehsil : <span class="text-danger">*</span></label>
                                <select wire:model.defer="ha_tehsil_id" class="form-control custom-select">
                                    @foreach ($tehsils as $tehsil)
                                    <option value="{{$tehsil->id}}">{{$tehsil->name}} - {{$tehsil->code}}</option>
                                    @endforeach
                                </select>
                                @error('ha_tehsil_id')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border p-4 mb-4">
                        <legend class="w-auto">Postal Address</legend>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>House Address#: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model.defer="pa_house_no">
                                @error('pa_house_no')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label>City: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" wire:model.defer="pa_city">
                                @error('pa_city')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label>Country: <span class="text-danger">*</span></label>
                                <select wire:model.defer="pa_country_id" class="form-control custom-select">
                                    @foreach ($countries as $country)
                                    <option value="{{$country->id}}">{{$country->name}}</option>
                                    @endforeach
                                </select>
                                @error('pa_country_id')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group col-md-6 pa_pakistan_section">
                                <label>Province/State: <span class="text-danger">*</span></label>
                                <select wire:model.defer="pa_province_id" class="form-control custom-select">
                                    @foreach ($provinces as $province)
                                    <option value="{{$province->id}}">{{$province->name}}</option>
                                    @endforeach
                                </select>
                                @error('pa_province_id')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group col-md-6 pa_pakistan_section">
                                <label>District : <span class="text-danger">*</span></label>
                                <select wire:model.defer="pa_district_id" class="form-control custom-select">
                                    @foreach ($districts as $district)
                                    <option value="{{$district->id}}">{{$district->name}} - {{$district->code}}
                                    </option>
                                    @endforeach
                                </select>
                                @error('pa_district_id')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group col-md-6 pa_pakistan_section">
                                <label>Tehsil : <span class="text-danger">*</span></label>
                                <select wire:model.defer="pa_tehsil_id" class="form-control custom-select">
                                    @foreach ($tehsils as $tehsil)
                                    <option value="{{$tehsil->id}}">{{$tehsil->name}} - {{$tehsil->code}}</option>
                                    @endforeach
                                </select>
                                @error('pa_tehsil_id')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </fieldset>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm" wire:click="update">Update</button>
                </div>
            </div>
        </div>
    </div>

    @include('livewire.loader')

</div>