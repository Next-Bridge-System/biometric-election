<div>
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Data Verification</h3>
                        </div>
                        <div class="card-body">
                            @if ($submit_data)
                            <section>
                                <div class="col-md-8 offset-md-2">
                                    <span class="alert alert-success">The data have been submitted successfully.</span>
                                </div>
                            </section>
                            @else
                            <section>
                                @if ($otp)
                                <section>
                                    <div class="form-group col-md-4 offset-md-4">
                                        <label>OTP<span class="required-star">*</span></label>
                                        <input wire:model="code" type="number" class="form-control">
                                        @error('code') <span class="text-danger">{{ $message }}</span>@enderror
                                        <button wire:click='verify_otp' class="btn btn-primary mt-2">Verify</button>
                                    </div>
                                </section>
                                @else
                                <section>
                                    <div class="row">
                                        <div class="col-md-4 offset-md-4">
                                            <div class="form-group">
                                                <label>Lawyer Type<span class="required-star">*</span></label>
                                                <select wire:model="lawyer_type" wire:change='changeLawyerType'
                                                    class="form-control custom-select">
                                                    <option value="" selected>--Select Type--</option>
                                                    <option value="1">Lower Court</option>
                                                    <option value="2">High Court</option>
                                                </select>
                                                @error('lawyer_type') <span
                                                    class="text-danger">{{ $message }}</span>@enderror
                                            </div>

                                            @if ($lawyer_type == 1)
                                            <div class="form-group">
                                                <label>LEDGER NO. <span class="required-star">*</span></label>
                                                <input wire:model="reg_no_lc" type="text" class="form-control" required>
                                                @error('reg_no_lc') <span
                                                    class="text-danger">{{ $message }}</span>@enderror
                                            </div>
                                            @endif

                                            @if ($lawyer_type == 2)
                                            <div class="form-group">
                                                <label>HCR NO.<span class="required-star">*</span></label>
                                                <input wire:model="hcr_no_hc" type="text" class="form-control" required>
                                                @error('hcr_no_hc') <span
                                                    class="text-danger">{{ $message }}</span>@enderror
                                            </div>
                                            @endif

                                            @if ($lawyer_type)
                                            <div class="col-md-12">
                                                <button wire:click='search'
                                                    class="btn btn-primary float-right">Search</button>
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row">

                                        @if ($search_data)

                                        <div class="col-md-12">
                                            <hr>
                                        </div>

                                        <div class="form-group col-12 col-md-6">
                                            <label>Name <span class="required-star">*</span></label>
                                            <input type="text" class="form-control" value="{{$lawyer_name}}" readonly>
                                        </div>

                                        <div class="form-group col-12 col-md-6">
                                            <label>Father Name <span class="required-star">*</span></label>
                                            <input type="text" class="form-control" value="{{$father_name}}" readonly>
                                        </div>

                                        <div class="form-group col-12 col-md-3">
                                            <label>CNIC <span class="text-danger">*</span>:</label>
                                            <input wire:model="cnic_no" type="text" class="form-control" required>
                                            @error('cnic_no') <span class="text-danger">{{ $message }}</span>@enderror
                                        </div>

                                        <div class="form-group col-12 col-md-3">
                                            <label>Mobile<span class="required-star">*</span></label>
                                            <input wire:model="mobile_no" type="number" class="form-control">
                                            @error('mobile_no') <span class="text-danger">{{ $message }}</span>@enderror
                                        </div>

                                        <div class="form-group col-12 col-md-3">
                                            <label>CNIC Front Image <span class="required-star">*</span></label>
                                            <input type="file" wire:model="cnic_front_image">
                                            @if ($cnic_front_image)
                                            <img src="{{ $cnic_front_image->temporaryUrl() }}"
                                                class="custom-image-preview">
                                            @endif
                                            @error('cnic_front_image') <span class="error">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-12 col-md-3">
                                            <label>CNIC Back Image <span class="required-star">*</span></label>
                                            <input type="file" wire:model.defer="cnic_back_image">
                                            @if ($cnic_back_image)
                                            <img src="{{ $cnic_back_image->temporaryUrl() }}"
                                                class="custom-image-preview">
                                            @endif
                                            @error('cnic_back_image') <span class="error">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        @endif
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            @if ($search_data)
                                            <button wire:click="store" type="button"
                                                class="btn btn-success float-right">Save &
                                                Submit</button>
                                            @endif
                                        </div>
                                    </div>
                                </section>
                                @endif
                            </section>
                            @endif
                        </div>
                        <div class="card-footer">
                            <div class="float-left">
                                Note: Donot submit any type of fake data. Otherwise PBC will take strick action against
                                you.
                            </div>
                            <div class="float-right">
                                <a href="{{route('frontend.index')}}" type="button" class="btn btn-secondary">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('livewire.loader')
</div>