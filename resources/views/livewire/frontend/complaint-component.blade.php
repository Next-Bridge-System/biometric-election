<div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card p-4">
                    <div class="col-md-12">
                        <h4 class="text-center text-bold">Complaints</h4>
                    </div>

                    @if ($form_step == 1)
                    <fieldset class="border p-4 mb-4">
                        <legend class="w-auto">Complainant Section</legend>
                        <div class="row align-items-end">
                            <div class="form-group col-md-3">
                                <label for="">CNIC NO: <span class="text-danger">*</span></label>
                                <input type="text" wire:model.defer="inputs.complainant_cnic" class="form-control"
                                    placeholder="xxxxx-xxxxxxx-x">
                            </div>
                            <div class="form-group col-md-3">
                                <button class="btn btn-success" wire:click="searchRecord('complainant')">Search</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                @error('inputs.complainant_cnic')
                                <span class="text-danger">{{ $message}}</span>
                                @enderror
                            </div>
                        </div>

                        @if ($complainant_record_found == 1)
                        <b>Lawyer Name:</b> {{$inputs['complainant_name']}} <br>
                        <b>Father/Husband:</b> {{$inputs['complainant_father']}} <br>
                        <b>CNIC:</b> {{$inputs['complainant_cnic']}} <br>
                        <b>Phone:</b> {{$inputs['complainant_phone']}} <br>
                        @endif

                        @if ($complainant_record_found == 2)
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <span class="text-danger">The record for complainant not found. Please enter the
                                    data manually.</span>
                            </div>
                        </div>
                        @endif

                        <div class="card p-4 mt-3">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="">Complainant Name <span class="text-danger">*</span></label>
                                    <input type="text" wire:model.defer="inputs.complainant_name" class="form-control">
                                    @error('inputs.complainant_name') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="">Father Name <span class="text-danger">*</span></label>
                                    <input type="text" wire:model.defer="inputs.complainant_father"
                                        class="form-control">
                                    @error('inputs.complainant_father') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="">Phone <span class="text-danger">*</span></label>
                                    <input type="text" wire:model.defer="inputs.complainant_phone" class="form-control"
                                        placeholder="xxxx-xxxxxxx">
                                    @error('inputs.complainant_phone') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="">Profile Image <span class="text-danger">*</span></label>
                                    <input type="file" wire:model.defer="complainant_profile_url"
                                        class="form-control custom-image-upload">

                                    @if ($complainant_profile_url)
                                    <img src="{{ $complainant_profile_url->temporaryUrl() }}"
                                        class="custom-image-preview mt-2">
                                    @endif

                                    @error('complainant_profile_url') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="">CNIC Front <span class="text-danger">*</span></label>
                                    <input type="file" wire:model.defer="complainant_cnic_front_url"
                                        class="form-control custom-image-upload">

                                    @if ($complainant_cnic_front_url)
                                    <img src="{{ $complainant_cnic_front_url->temporaryUrl() }}"
                                        class="custom-image-preview mt-2">
                                    @endif

                                    @error('complainant_cnic_front_url') <span class="text-danger">{{ $message}}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="">CNIC Back <span class="text-danger">*</span></label>
                                    <input type="file" wire:model.defer="complainant_cnic_back_url"
                                        class="form-control custom-image-upload">

                                    @if ($complainant_cnic_back_url)
                                    <img src="{{ $complainant_cnic_back_url->temporaryUrl() }}"
                                        class="custom-image-preview mt-2">
                                    @endif

                                    @error('complainant_cnic_back_url') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="">Affidavit <span class="text-danger">*</span></label>
                                    <input type="file" wire:model.defer="complainant_affidavit_url"
                                        class="form-control custom-image-upload">

                                    @if ($complainant_affidavit_url)
                                    <img src="{{ $complainant_affidavit_url->temporaryUrl() }}"
                                        class="custom-image-preview mt-2">
                                    @endif

                                    @error('complainant_affidavit_url') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="">Application PDF</label>
                                    <input type="file" wire:model.defer="complainant_application_url"
                                        class="form-control custom-image-upload">
                                    @error('complainant_application_url') <span class="text-danger">{{ $message
                                        }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border p-4 mb-4">
                        <legend class="w-auto">Defendant Section</legend>
                        <div class="row align-items-end">
                            <div class="form-group col-md-3">
                                <label for="">CNIC NO:</label>
                                <input type="text" wire:model.defer="inputs.defendant_cnic" class="form-control"
                                    placeholder="xxxxx-xxxxxxx-x">
                            </div>
                            <div class="form-group col-md-3">
                                <button class="btn btn-success" wire:click="searchRecord('defendant')">Search</button>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                @error('inputs.defendant_cnic')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        @if ($defendant_record_found == 2)
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <span class="text-danger">The record for defendant not found. Please enter the
                                    data manually.</span>
                            </div>
                        </div>
                        @endif

                        {{-- @if ($defendant_record_found == 1)
                        <b>Lawyer Name:</b> {{$inputs['defendant_name']}} <br>
                        <b>Father/Husband:</b> {{$inputs['defendant_father']}} <br>
                        <b>CNIC:</b> {{$inputs['defendant_cnic']}} <br>
                        <b>Phone:</b> {{$inputs['defendant_phone']}} <br>
                        @endif --}}

                        <div class="mt-3 card p-3 bordered">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="">Defendant Name <span class="text-danger">*</span></label>
                                    <input type="text" wire:model.defer="inputs.defendant_name" class="form-control">
                                    @error('inputs.defendant_name') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="">Father Name</label>
                                    <input type="text" wire:model.defer="inputs.defendant_father" class="form-control">
                                    @error('inputs.defendant_father') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="">Phone</label>
                                    <input type="text" wire:model.defer="inputs.defendant_phone" class="form-control"
                                        placeholder="xxxx-xxxxxxx">
                                    @error('inputs.defendant_phone') <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-12 mt-2" wire:ignore>
                                <label for="">Additional Information <span class="text-danger">*</span></label>
                                <textarea wire:model.defer="inputs.additional_info" class="form-control"
                                    id="additional_info"></textarea>
                                @error('inputs.additional_info') <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    </fieldset>


                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-success float-right" wire:click="submit">Submit
                                Complaint</button>
                        </div>
                    </div>
                    @endif

                    @if ($form_step == 2)
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Thankyou, your complaint request has been sent successfully.</h5>
                            <div>
                                <ol>
                                    <li>Download the voucher.</li>
                                    <li>Pay the amount in bank.</li>
                                    <li>Search voucher by voucher no from main menu.</li>
                                    <li>Upload the image of your paid voucher.</li>
                                </ol>
                            </div>
                            <a href="{{route('frontend.complaint-voucher', $payment->id)}}"
                                class="btn btn-success btn-sm" target="_blank">
                                <i class="fas fa-print mr-1"></i>Print Voucher</a>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped mt-3">
                        <tbody>
                            <tr>
                                <td>Complaint ID: {{$complaint->id}}</td>
                                <td>Complainant Name: {{$complaint->complainant_name}}</td>
                                <td>Voucher No: {{$payment->voucher_no}}</td>
                                <td>Voucher Name: {{$payment->voucher_name}}</td>
                            </tr>
                        </tbody>
                    </table>
                    @endif


                </div>
            </div>
        </div>
    </div>
    @include('livewire.loader')

    <script>
        document.addEventListener('livewire:load', function () {
            $('#additional_info').summernote({
                height: 300,
                callbacks: {
                    onChange: function (contents) {
                        Livewire.emit('set-additional-info', contents);
                    }
                }
            });
        });
    </script>

</div>