<section class="col-md-6 offset-md-3">
    @if (!$user->register_as)
    <hr class="m-4">
    <h5 class="mb-2 text-center"><u><b>Data Verification/Existing Lawyers</b></u></h5>
    <div class="row">
        <div class="col">
            <div class="card bg-light mb-3 text-center">
                <div class="card-header">Verification/Existing Lawyers</div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Lawyer Type<span class="text-danger">*</span>
                            <small>(only for existing lawyers)</small></label>
                        <select wire:model="gc_lawyer_type" wire:change='changeLawyerType'
                            class="form-control custom-select">
                            <option value="">--Select Lawyer--</option>
                            <option value="1">Lower Court</option>
                            <option value="2">High Court</option>
                        </select>
                    </div>

                    @if ($gc_lawyer_type == 1)
                    <div class="form-group">
                        <label for="">Lower Court <br> Legder/Registration No.</label>
                        <input type="text" wire:model.defer="reg_no_lc" class="form-control"
                            placeholder="Enter your Ledger/Registration No.">
                        @error('reg_no_lc') <span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                    @endif

                    @if ($gc_lawyer_type == 2)
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="">High Court <br> HCR No.</label>
                            <input type="text" wire:model.defer="hcr_no_hc" class="form-control"
                                placeholder="Enter your HCR/Registration No.">
                            @error('hcr_no_hc') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="">High Court <br> Enrollment Date</label>
                            <input type="date" wire:model.defer="enr_date_hc" class="form-control">
                            @error('enr_date_hc') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <div class="col-md-12">
                            <h3 class="text-center">OR</h3>
                        </div>

                        <div class="col-md-12 justify-content-center">
                            <div class="form-group">
                                <label for="">High Court <br> Serial Number</label>
                                <input type="text" wire:model.defer="search_sr_no_hc" class="form-control"
                                    placeholder="Enter your serial number">
                                @error('search_sr_no_hc') <span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3">
                                <a href="https://youtu.be/8N3YjkA5VYI" target="_blank">How
                                    to get your serial number?</a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if (in_array($gc_lawyer_type,[1,2]))
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" wire:click="search({{$gc_lawyer_type}})">Find &
                            Search</button>
                    </div>
                    @endif

                    @if ($gc_record_match == 1)
                    <span class="badge badge-success mb-4 text-lg">Success, Record
                        Match.</span>

                    @if ($gc_lawyer_type == 1)
                    <div class="card p-2">
                        <table>
                            <tbody>
                                <tr>
                                    <td><b>Lawyer Name:</b>
                                        {{$gc_lower_court->lawyer_name}}
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Father Name:</b>
                                        {{$gc_lower_court->father_name}}
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>License No:</b>
                                        {{$gc_lower_court->license_no_lc}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @endif

                    @if ($gc_lawyer_type == 2)
                    <div class="card p-2">
                        <table>
                            <tbody>
                                <tr>
                                    <td><b>Lawyer Name:</b>
                                        {{$gc_high_court->lawyer_name}}
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Father Name:</b>
                                        {{$gc_high_court->father_name}}
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>License No:</b>
                                        {{$gc_high_court->license_no_hc}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12 form-group border pt-3 pb-3 pl-4">
                            <label>Profile Image<span class="required-star">*</span></label>
                            <input type="file" wire:model="profile_image">
                            @if ($profile_image)
                            <img src="{{ $profile_image->temporaryUrl() }}" class="custom-image-preview mt-2">
                            @endif
                            @error('profile_image')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group border pt-3 pb-3 pl-4">
                            <label>CNIC Front<span class="required-star">*</span></label>
                            <input type="file" wire:model="cnic_front_image">
                            @if ($cnic_front_image)
                            <img src="{{ $cnic_front_image->temporaryUrl() }}" class="custom-image-preview mt-2">
                            @endif
                            @error('cnic_front_image') <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group border pt-3 pb-3 pl-4">
                            <label>CNIC Back<span class="required-star">*</span></label>
                            <input type="file" wire:model="cnic_back_image">
                            @if ($cnic_back_image)
                            <img src="{{ $cnic_back_image->temporaryUrl() }}" class="custom-image-preview mt-2">
                            @endif
                            @error('cnic_back_image') <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group border pt-3 pb-3 pl-4">
                            <label>PBC
                                {{$gc_lawyer_type == 1 ? 'Lower Court' : 'High Court'}}
                                Licence Front<span class="required-star">*</span></label>
                            <input type="file" wire:model="license_front_image">
                            @if ($license_front_image)
                            <img src="{{ $license_front_image->temporaryUrl() }}" class="custom-image-preview mt-2">
                            @endif
                            @error('license_front_image') <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group border pt-3 pb-3 pl-4">
                            <label>PBC
                                {{$gc_lawyer_type == 1 ? 'Lower Court' : 'High Court'}}
                                Licence Back<span class="required-star">*</span></label>
                            <input type="file" wire:model="license_back_image">
                            @if ($license_back_image)
                            <img src="{{ $license_back_image->temporaryUrl() }}" class="custom-image-preview mt-2">
                            @endif
                            @error('license_back_image') <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="button" class="btn btn-block btn-success"
                                wire:click="store()">Procceed</button>
                        </div>
                    </div>
                    @endif

                    @if ($gc_record_match == 2)
                    <span class="badge badge-danger mb-4 text-lg">Failed, Record Not
                        Match. <br> <small>Contact Punjab Bar Council to verify your
                            record.</small></span>
                    @endif

                </div>
            </div>
        </div>
    </div>
    @endif
</section>