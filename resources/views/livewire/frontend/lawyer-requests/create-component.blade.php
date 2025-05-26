<div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Lawyer Requests</h3>
                    </div>
                    <div class="card-body">
                        @if ($lawyer_request_sent)
                        <section>
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Thankyou, your certificate request have been sent successfully.</h5>
                                    <div>
                                        <ol>
                                            <li>Download the voucher.</li>
                                            <li>Pay the amount in bank.</li>
                                            <li>Search voucher by voucher no from main menu.</li>
                                            <li>Upload the image of your paid voucher.</li>
                                        </ol>
                                    </div>
                                    <a href="{{route('frontend.lawyer-requests.voucher', $lawyer_request_id)}}"
                                        class="btn btn-success btn-sm" target="_blank">
                                        <i class="fas fa-print mr-1"></i>Print Voucher</a>
                                </div>
                            </div>

                            <table class="table table-bordered table-striped mt-3">
                                <tbody>
                                    <tr>
                                        <td>Lawyer Request ID: {{$lawyer_request_id}}</td>
                                        <td>Lawyer Name: {{$lawyer_name}}</td>
                                        <td>Voucher No: {{$voucher_no}}</td>
                                        <td>Voucher Name: {{$voucher_name}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </section>
                        @else
                        <section>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Lawyer Type<span class="text-danger">*</span>:</label>
                                    <input type="text" class="form-control" value="{{$user->register_as}}" disabled>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Lawyer Request Category <span class="text-danger">*</span>:</label>
                                    <select wire:model="lawyer_request_category_id" class="form-control custom-select"
                                        required>
                                        <option value="" selected>--Select Type--</option>
                                        @foreach ($lawyer_request_categories as $category)
                                        <option value="{{$category->id}}">
                                            {{$category->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('lawyer_request_category_id')
                                    <span class="text-danger">{{ $message }}</span>@enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Lawyer Request Sub Category <span class="text-danger">*</span>:</label>
                                    <select wire:model="lawyer_request_sub_category_id"
                                        class="form-control custom-select" required>
                                        <option value="" selected>--Select Type--</option>
                                        @foreach ($lawyer_request_sub_categories as $sub_category)
                                        <option value="{{$sub_category->id}}">
                                            {{$sub_category->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('lawyer_request_sub_category_id')
                                    <span class="text-danger">{{ $message }}</span>@enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Lawyer Name <span class="required-star">*</span></label>
                                    <input type="text" class="form-control" value="{{$lawyer_name}}" disabled>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>Father Name <span class="required-star">*</span></label>
                                    <input type="text" class="form-control" value="{{$father_name}}" disabled>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>CNIC <span class="text-danger">*</span>:</label>
                                    <input type="text" class="form-control" value="{{$cnic_no}}" disabled>
                                </div>

                                <div class="form-group col-md-4">
                                    <label>License No<span class="required-star">*</span></label>
                                    <input type="number" class="form-control" value="{{$license_no}}" disabled>
                                </div>


                                @if ($user->register_as == 'gc_lc' || $user->register_as == 'gc_hc')
                                <div class="form-group col-md-4">
                                    <label>Enrollment Date LC<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{getDateFormat($enr_date_lc)}}"
                                        disabled>
                                </div>
                                @endif

                                @if ($user->register_as == 'gc_hc')
                                <div class="form-group col-md-4">
                                    <label>Enrollment Date HC <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{getDateFormat($enr_date_hc)}}"
                                        disabled>
                                </div>
                                @endif

                                <div class="form-group col-md-4">
                                    <label>Voter Member <span class="required-star">*</span></label>
                                    <select id="voter_member" class="form-control custom-select" disabled>
                                        @foreach ($bars as $bar)
                                        <option value="{{$bar->id}}" {{$voter_member == $bar->id ? 'selected': ''}}>
                                            {{$bar->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-8">
                                    <label>Address <span class="required-star">*</span></label>
                                    <input wire:model.defer="address" type="text" maxlength="100" class="form-control"
                                        name="address" readonly>
                                    @error('address') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>

                                @if ($lawyer_request_sub_category_id == 1 )
                                <div class="form-group col-md-6">
                                    <label>Embassy Name <span class="required-star">*</span></label>
                                    <input wire:model.defer="embassy_name" type="text" maxlength="100"
                                        class="form-control" required>
                                    @error('embassy_name') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Visit Country <span class="required-star">*</span></label>
                                    <input wire:model.defer="visit_country" type="text" maxlength="100"
                                        class="form-control" required>
                                    @error('visit_country') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                @endif

                                @if ($lawyer_request_sub_category_id == 8 )
                                <div class="form-group col-md-6">
                                    <label>Society Name <span class="required-star">*</span></label>
                                    <input wire:model.defer="society_name" type="text" maxlength="100"
                                        class="form-control" required>
                                    @error('society_name') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>
                                @endif

                                <div class="form-group col-md-12">
                                    <label>Reason <span class="required-star">*</span></label>
                                    <textarea wire:model.defer="reason" cols="30" rows="5"
                                        class="form-control"></textarea>
                                    @error('reason') <span class="text-danger">{{ $message }}</span>@enderror
                                </div>

                            </div>
                            <div class="row">
                                <div class="col">
                                    {{-- @if (in_array($user->register_as,['lc','gc_lc']) && ($license_no == NULL ||
                                    $enr_date_lc == NULL))
                                    <span class="text-danger text-lg">Please note that we are unable to process your
                                        request
                                        until the Lower Court
                                        License number and Enrollment date from the Punjab Bar Council are
                                        provided.</span>
                                    @elseif (in_array($user->register_as,['hc','gc_hc']) && ($license_no == NULL ||
                                    $enr_date_hc == NULL))
                                    <span class="text-danger text-lg">Please note that we are unable to process your
                                        request
                                        until the High Court
                                        License number and Enrollment date from the Punjab Bar Council are
                                        provided.</span>
                                    @else
                                    <button wire:click="store" type="button" class="btn btn-success float-right">Save &
                                        Submit</button>
                                    @endif --}}

                                    <button wire:click="store" type="button" class="btn btn-success float-right">Save &
                                        Submit</button>

                                </div>
                            </div>
                        </section>
                        @endif
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
    </div>

    @include('livewire.loader')
</div>